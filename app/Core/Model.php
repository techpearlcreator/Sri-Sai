<?php

namespace App\Core;

use PDO;

/**
 * Base Model — Query builder for all database models.
 *
 * Usage:
 *   $blog = Blog::find(1);
 *   $blogs = Blog::where('status', 'published')->orderBy('created_at', 'DESC')->paginate(1, 15);
 *   $blog = Blog::create(['title' => '...', 'content' => '...']);
 *   Blog::update(1, ['title' => 'New Title']);
 *   Blog::delete(1);
 */
abstract class Model
{
    /** Table name — override in child class */
    protected static string $table = '';

    /** Primary key column */
    protected static string $primaryKey = 'id';

    /** Columns that can be mass-assigned via create/update */
    protected static array $fillable = [];

    /** Query builder state (instance-level for chaining) */
    private array $wheres = [];
    private array $bindings = [];
    private ?string $orderByClause = null;
    private ?int $limitValue = null;
    private ?int $offsetValue = null;
    private ?string $selectClause = null;
    private array $joins = [];
    private ?string $groupByClause = null;
    private ?string $searchClause = null;
    private array $searchBindings = [];

    // ──────────────────────────────────────
    // Static convenience methods
    // ──────────────────────────────────────

    /**
     * Get the PDO database connection.
     */
    protected static function db(): Database
    {
        return Database::getInstance();
    }

    /**
     * Find a record by primary key.
     */
    public static function find(int $id): ?object
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        return static::db()->fetch("SELECT * FROM `{$table}` WHERE `{$pk}` = ?", [$id]);
    }

    /**
     * Find a record by primary key or return error response.
     */
    public static function findOrFail(int $id): object
    {
        $record = static::find($id);
        if (!$record) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => ['code' => 'NOT_FOUND', 'message' => static::$table . ' not found'],
            ]);
            exit;
        }
        return $record;
    }

    /**
     * Find a single record by column value.
     */
    public static function findBy(string $column, mixed $value): ?object
    {
        $table = static::$table;
        return static::db()->fetch("SELECT * FROM `{$table}` WHERE `{$column}` = ? LIMIT 1", [$value]);
    }

    /**
     * Get all records (no conditions).
     */
    public static function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $table = static::$table;
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        return static::db()->fetchAll("SELECT * FROM `{$table}` ORDER BY `{$orderBy}` {$direction}");
    }

    /**
     * Count all records in the table.
     */
    public static function count(): int
    {
        $table = static::$table;
        $result = static::db()->fetch("SELECT COUNT(*) as cnt FROM `{$table}`");
        return (int) $result->cnt;
    }

    /**
     * Create a new record. Returns the created record.
     */
    public static function create(array $data): object
    {
        $table = static::$table;
        $fillable = static::$fillable;

        // Filter to only fillable columns
        $filtered = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable, true)) {
                $filtered[$key] = $value;
            }
        }

        if (empty($filtered)) {
            throw new \RuntimeException('No fillable data provided for ' . $table);
        }

        $columns = implode('`, `', array_keys($filtered));
        $placeholders = implode(', ', array_fill(0, count($filtered), '?'));
        $values = array_values($filtered);

        static::db()->query(
            "INSERT INTO `{$table}` (`{$columns}`) VALUES ({$placeholders})",
            $values
        );

        $id = (int) static::db()->lastInsertId();
        return static::find($id);
    }

    /**
     * Update a record by primary key. Returns the updated record.
     */
    public static function update(int $id, array $data): ?object
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $fillable = static::$fillable;

        // Filter to only fillable columns
        $filtered = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable, true)) {
                $filtered[$key] = $value;
            }
        }

        if (empty($filtered)) {
            return static::find($id);
        }

        $setClauses = [];
        $values = [];
        foreach ($filtered as $column => $value) {
            $setClauses[] = "`{$column}` = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $setString = implode(', ', $setClauses);
        static::db()->query(
            "UPDATE `{$table}` SET {$setString} WHERE `{$pk}` = ?",
            $values
        );

        return static::find($id);
    }

    /**
     * Delete a record by primary key. Returns true on success.
     */
    public static function destroy(int $id): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = static::db()->query("DELETE FROM `{$table}` WHERE `{$pk}` = ?", [$id]);
        return $stmt->rowCount() > 0;
    }

    // ──────────────────────────────────────
    // Query builder (chainable instance methods)
    // ──────────────────────────────────────

    /**
     * Start a query chain.
     */
    public static function query(): static
    {
        return new static();
    }

    /**
     * Shortcut: static where() to start chaining.
     */
    public static function where(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        $instance = new static();
        return $instance->andWhere($column, $operatorOrValue, $value);
    }

    /**
     * Add a WHERE condition.
     */
    public function andWhere(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        if ($value === null) {
            // Two args: column = value
            $this->wheres[] = "`{$column}` = ?";
            $this->bindings[] = $operatorOrValue;
        } else {
            // Three args: column operator value
            $operator = strtoupper($operatorOrValue);
            $allowed = ['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'IS', 'IS NOT'];
            if (!in_array($operator, $allowed, true)) {
                $operator = '=';
            }
            if ($operator === 'IN' || $operator === 'NOT IN') {
                if (is_array($value) && !empty($value)) {
                    $placeholders = implode(', ', array_fill(0, count($value), '?'));
                    $this->wheres[] = "`{$column}` {$operator} ({$placeholders})";
                    $this->bindings = array_merge($this->bindings, array_values($value));
                }
            } elseif ($operator === 'IS' || $operator === 'IS NOT') {
                $this->wheres[] = "`{$column}` {$operator} NULL";
            } else {
                $this->wheres[] = "`{$column}` {$operator} ?";
                $this->bindings[] = $value;
            }
        }
        return $this;
    }

    /**
     * WHERE column IS NULL.
     */
    public function whereNull(string $column): static
    {
        $this->wheres[] = "`{$column}` IS NULL";
        return $this;
    }

    /**
     * WHERE column IS NOT NULL.
     */
    public function whereNotNull(string $column): static
    {
        $this->wheres[] = "`{$column}` IS NOT NULL";
        return $this;
    }

    /**
     * Full-text search using MATCH...AGAINST.
     */
    public function search(string $term, array $columns): static
    {
        if (trim($term) === '') {
            return $this;
        }
        $cols = implode(', ', array_map(fn($c) => "`{$c}`", $columns));
        $this->searchClause = "MATCH({$cols}) AGAINST(? IN BOOLEAN MODE)";
        $this->searchBindings = [$term . '*'];
        return $this;
    }

    /**
     * Set SELECT columns.
     */
    public function select(string $columns): static
    {
        $this->selectClause = $columns;
        return $this;
    }

    /**
     * Add a JOIN.
     */
    public function join(string $table, string $condition, string $type = 'LEFT'): static
    {
        $type = strtoupper($type);
        $this->joins[] = "{$type} JOIN `{$table}` ON {$condition}";
        return $this;
    }

    /**
     * ORDER BY clause.
     */
    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orderByClause = "ORDER BY `{$column}` {$direction}";
        return $this;
    }

    /**
     * ORDER BY with raw expression (for multiple columns).
     */
    public function orderByRaw(string $raw): static
    {
        $this->orderByClause = "ORDER BY {$raw}";
        return $this;
    }

    /**
     * GROUP BY clause.
     */
    public function groupBy(string $column): static
    {
        $this->groupByClause = "GROUP BY `{$column}`";
        return $this;
    }

    /**
     * LIMIT clause.
     */
    public function limit(int $limit): static
    {
        $this->limitValue = $limit;
        return $this;
    }

    /**
     * OFFSET clause.
     */
    public function offset(int $offset): static
    {
        $this->offsetValue = $offset;
        return $this;
    }

    /**
     * Execute the query and return all matching records.
     */
    public function get(): array
    {
        [$sql, $bindings] = $this->buildQuery();
        return static::db()->fetchAll($sql, $bindings);
    }

    /**
     * Execute the query and return the first matching record.
     */
    public function first(): ?object
    {
        $this->limitValue = 1;
        [$sql, $bindings] = $this->buildQuery();
        return static::db()->fetch($sql, $bindings);
    }

    /**
     * Count matching records (respects where clauses).
     */
    public function countResults(): int
    {
        $savedSelect = $this->selectClause;
        $savedOrder = $this->orderByClause;
        $savedLimit = $this->limitValue;
        $savedOffset = $this->offsetValue;

        $this->selectClause = 'COUNT(*) as cnt';
        $this->orderByClause = null;
        $this->limitValue = null;
        $this->offsetValue = null;

        [$sql, $bindings] = $this->buildQuery();
        $result = static::db()->fetch($sql, $bindings);

        // Restore
        $this->selectClause = $savedSelect;
        $this->orderByClause = $savedOrder;
        $this->limitValue = $savedLimit;
        $this->offsetValue = $savedOffset;

        return (int) ($result->cnt ?? 0);
    }

    /**
     * Paginate results. Returns ['data' => [...], 'total' => int, 'page' => int, 'per_page' => int].
     */
    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Count total first
        $total = $this->countResults();

        // Then fetch the page
        $this->limitValue = $perPage;
        $this->offsetValue = ($page - 1) * $perPage;
        $data = $this->get();

        return [
            'data'     => $data,
            'total'    => $total,
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }

    /**
     * Build the complete SQL query from the chain.
     */
    private function buildQuery(): array
    {
        $table = static::$table;
        $select = $this->selectClause ?? '*';

        $sql = "SELECT {$select} FROM `{$table}`";

        // Joins
        foreach ($this->joins as $join) {
            $sql .= " {$join}";
        }

        // WHERE clauses
        $allWheres = $this->wheres;
        $allBindings = $this->bindings;

        if ($this->searchClause) {
            $allWheres[] = $this->searchClause;
            $allBindings = array_merge($allBindings, $this->searchBindings);
        }

        if (!empty($allWheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $allWheres);
        }

        // GROUP BY
        if ($this->groupByClause) {
            $sql .= " {$this->groupByClause}";
        }

        // ORDER BY
        if ($this->orderByClause) {
            $sql .= " {$this->orderByClause}";
        }

        // LIMIT / OFFSET
        if ($this->limitValue !== null) {
            $sql .= " LIMIT {$this->limitValue}";
        }
        if ($this->offsetValue !== null) {
            $sql .= " OFFSET {$this->offsetValue}";
        }

        return [$sql, $allBindings];
    }

    /**
     * Increment a column value.
     */
    public static function increment(int $id, string $column, int $amount = 1): void
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        static::db()->query(
            "UPDATE `{$table}` SET `{$column}` = `{$column}` + ? WHERE `{$pk}` = ?",
            [$amount, $id]
        );
    }

    /**
     * Decrement a column value.
     */
    public static function decrement(int $id, string $column, int $amount = 1): void
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        static::db()->query(
            "UPDATE `{$table}` SET `{$column}` = GREATEST(`{$column}` - ?, 0) WHERE `{$pk}` = ?",
            [$amount, $id]
        );
    }
}
