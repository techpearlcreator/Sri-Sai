<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewareGroups = [];

    /**
     * Register a GET route.
     */
    public function get(string $pattern, string $controller, string $method, array $middleware = []): void
    {
        $this->addRoute('GET', $pattern, $controller, $method, $middleware);
    }

    /**
     * Register a POST route.
     */
    public function post(string $pattern, string $controller, string $method, array $middleware = []): void
    {
        $this->addRoute('POST', $pattern, $controller, $method, $middleware);
    }

    /**
     * Register a PUT route.
     */
    public function put(string $pattern, string $controller, string $method, array $middleware = []): void
    {
        $this->addRoute('PUT', $pattern, $controller, $method, $middleware);
    }

    /**
     * Register a DELETE route.
     */
    public function delete(string $pattern, string $controller, string $method, array $middleware = []): void
    {
        $this->addRoute('DELETE', $pattern, $controller, $method, $middleware);
    }

    /**
     * Add a route to the routing table.
     */
    private function addRoute(string $httpMethod, string $pattern, string $controller, string $method, array $middleware): void
    {
        $this->routes[] = [
            'httpMethod'  => $httpMethod,
            'pattern'     => $pattern,
            'controller'  => $controller,
            'method'      => $method,
            'middleware'   => $middleware,
        ];
    }

    /**
     * Dispatch the current request to the matching route.
     */
    public function dispatch(string $httpMethod, string $url): void
    {
        // Handle CORS preflight
        if ($httpMethod === 'OPTIONS') {
            $this->handleCors();
            http_response_code(204);
            exit;
        }

        // Set CORS headers for API routes
        if (str_starts_with($url, '/api/')) {
            $this->handleCors();
        }

        // Normalize URL
        $url = '/' . trim($url, '/');

        foreach ($this->routes as $route) {
            if ($route['httpMethod'] !== $httpMethod) {
                continue;
            }

            $params = $this->matchRoute($route['pattern'], $url);
            if ($params !== false) {
                // Run middleware (supports params via colon: 'RoleMiddleware:blogs,read')
                foreach ($route['middleware'] as $mw) {
                    $mwParams = [];
                    if (str_contains($mw, ':')) {
                        [$mw, $mwParamStr] = explode(':', $mw, 2);
                        $mwParams = explode(',', $mwParamStr);
                    }
                    $middlewareClass = "App\\Middleware\\{$mw}";
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        $middlewareInstance->handle(...$mwParams);
                    }
                }

                // Instantiate controller and call method
                $controllerClass = $route['controller'];
                if (!class_exists($controllerClass)) {
                    $this->sendError(500, 'CONTROLLER_NOT_FOUND', "Controller {$controllerClass} not found");
                    return;
                }

                $controller = new $controllerClass();
                $methodName = $route['method'];

                if (!method_exists($controller, $methodName)) {
                    $this->sendError(500, 'METHOD_NOT_FOUND', "Method {$methodName} not found in controller");
                    return;
                }

                // Call controller method with route parameters
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // No route matched
        $this->sendError(404, 'NOT_FOUND', 'The requested resource was not found');
    }

    /**
     * Match a route pattern against a URL.
     * Returns array of captured parameters or false if no match.
     * Supports {param} placeholders.
     */
    private function matchRoute(string $pattern, string $url): array|false
    {
        // Normalize pattern
        $pattern = '/' . trim($pattern, '/');

        // Convert {param} to regex named groups
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $url, $matches)) {
            // Extract only named parameters (not numeric keys)
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return $params;
        }

        return false;
    }

    /**
     * Set CORS headers.
     */
    private function handleCors(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * Send a JSON error response.
     */
    private function sendError(int $httpCode, string $code, string $message): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
        ]);
    }
}
