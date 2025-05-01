<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
class ListRouteNames extends Command
{
    protected $signature = 'route:names';
    protected $description = 'List all named routes in the application';

    public function handle()
    {
        $routes = Route::getRoutes();
        $namedRoutes = [];

        foreach ($routes as $route) {

            $name = $route->getName();
            if ($name === null) {
                $name = 'N/A';
            }
            $namedRoutes[] = [
                'name' => $name,
                'uri' => $route->uri(),
                'methods' => implode(',', $route->methods()),
            ];
        }

        $this->table(['Name', 'URI', 'Methods'], $namedRoutes);
        return 0;
    }
}
