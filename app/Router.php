<?php

declare(strict_types=1);

class Router
{
    private $uri;
    private $method;
    private $routes = [];

    public function __construct(string $uri, string $method)
    {
        $this->uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $this->method = strtoupper($method);
    }

    public function add(string $method, string $pattern, callable|array $handler)
    {
        $this->routes[] = [$method, "~^{$pattern}$~", $handler];
    }

    public function get(string $pattern, callable|array $handler)
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler)
    {
        $this->add('POST', $pattern, $handler);
    }

    public function dispatch()
    {
        foreach ($this->routes as [$method, $regex, $handler]) {
            if ($method !== $this->method)
                continue;

            if (preg_match($regex, $this->uri, $matches)) {
                array_shift($matches);
                if (is_array($handler)) {
                    [$controller, $action] = $handler;
                    if (!class_exists($controller)) {
                        header("HTTP/1.0 500 Internal Server Error");
                        echo "Controller {$controller} not found";
                        return;
                    }
                    
                    $c = new $controller();
                    
                    return call_user_func_array([$c, $action], $matches);
                } else {
                    return call_user_func_array($handler, $matches);
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found!!!";
    }
}