<?php
class Router {
    private $routes = [];
    
    // Add a route to the router
    public function addRoute($method, $pattern, $handler) {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }
    
    // Route the request to the appropriate handler
    public function route($method, $uri, $data) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Convert route pattern to regex
            $pattern = '#^' . $route['pattern'] . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Call the handler
                $handlerClass = $route['handler'][0];
                $handlerMethod = $route['handler'][1];
                
                $controller = new $handlerClass();
                call_user_func_array([$controller, $handlerMethod], array_merge([$data], $matches));
                return;
            }
        }
        
        // No route found
        Response::json(['error' => 'Endpoint not found'], 404);
    }
}