<?php

namespace App\Helpers;

use App\Core\Database;

/**
 * Input Validator — validates request data against defined rules.
 *
 * Usage:
 *   $v = new Validator($data, [
 *       'title'    => 'required|string|min:5|max:255',
 *       'email'    => 'required|email|max:150',
 *       'status'   => 'required|in:draft,published,archived',
 *       'category_id' => 'nullable|exists:categories,id',
 *       'image'    => 'nullable|file|max_file:5242880|mimes:jpg,jpeg,png,webp',
 *   ]);
 *
 *   if ($v->fails()) {
 *       Response::error(400, 'VALIDATION_ERROR', 'The given data was invalid', $v->errors());
 *   }
 */
class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $files;

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->files = $_FILES;
        $this->validate();
    }

    /**
     * Run all validation rules.
     */
    private function validate(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            $isNullable = in_array('nullable', $rules, true);
            $isFile = in_array('file', $rules, true);

            // If nullable and value is empty, skip all other rules
            if ($isNullable && ($value === null || $value === '')) {
                continue;
            }

            foreach ($rules as $rule) {
                if ($rule === 'nullable') {
                    continue;
                }

                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $methodName = 'rule' . str_replace('_', '', ucwords($rule, '_'));
                if (method_exists($this, $methodName)) {
                    $this->$methodName($field, $value, $params);
                }
            }
        }
    }

    /**
     * Check if validation failed.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get all validation errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Add an error message.
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    // ──────────────────────────────────────
    // Validation Rules
    // ──────────────────────────────────────

    private function ruleRequired(string $field, mixed $value, array $params): void
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' is required');
        }
    }

    private function ruleString(string $field, mixed $value, array $params): void
    {
        if ($value !== null && !is_string($value)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a string');
        }
    }

    private function ruleInteger(string $field, mixed $value, array $params): void
    {
        if ($value !== null && !is_numeric($value)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be an integer');
        }
    }

    private function ruleNumeric(string $field, mixed $value, array $params): void
    {
        if ($value !== null && !is_numeric($value)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a number');
        }
    }

    private function ruleEmail(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a valid email');
        }
    }

    private function ruleMin(string $field, mixed $value, array $params): void
    {
        $min = (int) ($params[0] ?? 0);
        if (is_string($value) && mb_strlen($value) < $min) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters");
        } elseif (is_numeric($value) && $value < $min) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min}");
        }
    }

    private function ruleMax(string $field, mixed $value, array $params): void
    {
        $max = (int) ($params[0] ?? 0);
        if (is_string($value) && mb_strlen($value) > $max) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$max} characters");
        } elseif (is_numeric($value) && $value > $max) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$max}");
        }
    }

    private function ruleIn(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '' && !in_array($value, $params, true)) {
            $allowed = implode(', ', $params);
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be one of: {$allowed}");
        }
    }

    private function ruleExists(string $field, mixed $value, array $params): void
    {
        if ($value === null || $value === '') {
            return;
        }
        $table = $params[0] ?? '';
        $column = $params[1] ?? 'id';
        if ($table) {
            $result = Database::getInstance()->fetch(
                "SELECT COUNT(*) as cnt FROM `{$table}` WHERE `{$column}` = ?",
                [$value]
            );
            if ((int) $result->cnt === 0) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' does not exist');
            }
        }
    }

    private function ruleUnique(string $field, mixed $value, array $params): void
    {
        if ($value === null || $value === '') {
            return;
        }
        $table = $params[0] ?? '';
        $column = $params[1] ?? $field;
        $exceptId = $params[2] ?? null;

        if ($table) {
            $sql = "SELECT COUNT(*) as cnt FROM `{$table}` WHERE `{$column}` = ?";
            $bindings = [$value];

            if ($exceptId) {
                $sql .= " AND `id` != ?";
                $bindings[] = $exceptId;
            }

            $result = Database::getInstance()->fetch($sql, $bindings);
            if ((int) $result->cnt > 0) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' already exists');
            }
        }
    }

    private function ruleDate(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '') {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if (!$d || $d->format('Y-m-d') !== $value) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a valid date (YYYY-MM-DD)');
            }
        }
    }

    private function ruleTime(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '') {
            if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value)) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a valid time (HH:MM or HH:MM:SS)');
            }
        }
    }

    private function ruleUrl(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a valid URL');
        }
    }

    private function ruleBoolean(string $field, mixed $value, array $params): void
    {
        $allowed = [true, false, 0, 1, '0', '1', 'true', 'false'];
        if ($value !== null && !in_array($value, $allowed, true)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be true or false');
        }
    }

    private function rulePhone(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '') {
            // Allow Indian phone: 10 digits, optionally prefixed with +91 or 0
            $cleaned = preg_replace('/[\s\-\(\)]/', '', $value);
            if (!preg_match('/^(\+91|0)?[6-9]\d{9}$/', $cleaned) && !preg_match('/^0\d{2,4}-?\d{6,8}$/', $cleaned)) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a valid phone number');
            }
        }
    }

    private function ruleFile(string $field, mixed $value, array $params): void
    {
        // File validation — check $_FILES
        if (!isset($this->files[$field]) || $this->files[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            // File not uploaded — only an error if 'required' is also set
            return;
        }
        if ($this->files[$field]['error'] !== UPLOAD_ERR_OK) {
            $this->addError($field, 'File upload failed');
        }
    }

    private function ruleMaxfile(string $field, mixed $value, array $params): void
    {
        $maxBytes = (int) ($params[0] ?? 5242880);
        if (isset($this->files[$field]) && $this->files[$field]['error'] === UPLOAD_ERR_OK) {
            if ($this->files[$field]['size'] > $maxBytes) {
                $maxMB = round($maxBytes / 1048576, 1);
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$maxMB}MB");
            }
        }
    }

    private function ruleMimes(string $field, mixed $value, array $params): void
    {
        if (isset($this->files[$field]) && $this->files[$field]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($this->files[$field]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $params, true)) {
                $allowed = implode(', ', $params);
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be of type: {$allowed}");
            }
        }
    }

    private function ruleJson(string $field, mixed $value, array $params): void
    {
        if ($value !== null && $value !== '') {
            json_decode($value);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be valid JSON');
            }
        }
    }

    private function ruleArray(string $field, mixed $value, array $params): void
    {
        if ($value !== null && !is_array($value)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be an array');
        }
    }
}
