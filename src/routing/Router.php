<?php
namespace waiterphp\core\Routing;
class Router
{
    private $routeTable = [];

    public function group()
    {
        return $this;
    }

    public function set($routeTable)
    {
        $this->routeTable = $routeTable;
        return $this;
    }

    public function route($signal, $params = [])
    {
        $routeTarget = $this->target($signal);
        return $this->routeTo($routeTarget, $params);
    }

    public function target($signal)
    {
        $signal = $this->parseSignal($signal);
        return $this->searchTarget($this->routeTable, $signal);
    }

    private function searchTarget($routes, $signal)
    {
        foreach ($routes as $route) {
            assert_exception(isset($route[0]) || isset($route['url']), 'route not set');
            $pattern = isset($route[0]) ? $route[0] : $route['url']; 
            if (preg_match($this->formatPattern($pattern), $signal, $matches)) {
                return $this->generateCmd($route[1], $matches);
            }
        }
        return false;
    }

    private function formatPattern($pattern)
    {
        return "/" . str_replace('/', '\/', $pattern) . '/i';
    }

    private function generateCmd($action, $matches)
    {
        foreach ($matches as $key=>$match) {
            if ($key > 0) {
                $action = str_replace('$'.$key, $match, $action);
            }
        }
        return $action;
    }

    private function routeTo($action, $params = [])
    {
        list($class, $method) = dot_method($action);
        $object = instance($class);
        return call_user_func_array([$object, $method], $params);
    }

    private function parseSignal($signal)
    {
        if (is_callable($signal)) {
            return $signal();
        }
        return $signal;
    }
}