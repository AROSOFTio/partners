<?php
class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $method = strtoupper($method);
        $pattern = trim($pattern, '/');
        $pattern = $pattern === '' ? '/' : $pattern;
        $this->routes[] = compact('method', 'pattern', 'handler');
    }

    public function dispatch(string $uri, string $method = 'GET')
    {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = '/' . trim($path, '/');
        $path = $path === '//' ? '/' : $path;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $regex = $this->patternToRegex($route['pattern']);
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['handler'], $params);
            }
        }

        http_response_code(404);
        echo 'Page not found';
        return null;
    }

    private function patternToRegex(string $pattern): string
    {
        $pattern = trim($pattern, '/');
        if ($pattern === '') {
            return '@^/$@';
        }
        $pattern = preg_replace('@\{([a-zA-Z0-9_]+)\}@', '(?P<$1>[^/]+)', $pattern);
        return '@^/' . $pattern . '$@';
    }
}
