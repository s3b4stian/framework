<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

use BadMethodCallException;

/**
 * Router.
 *
 * Manage routes, verify every resource requested by browser and return
 * a RouteInterface Object.
 */
class Router
{
    /**
     * @var string Base path for route evaluation.
     */
    protected string $basePath = '/';

    /**
     * @var bool Use of url rewriting.
     */
    protected bool $rewriteMode = false;

    /**
     * @var string Router access point without rewrite engine.
     */
    protected string $rewriteModeOffRouter = '/index.php?';

    /**
     * @var bool Parse query string when rewrite mode is on.
     */
    protected bool $parseQueryStringOnRewriteModeOn = false;

    /**
     * @var RouteInterface Utilized for return the most recently parsed route
     */
    protected RouteInterface $route;

    /**
     * @var RouteCollection<Route> Passed from constructor, is the list of registerd routes for the app
     */
    private RouteCollection $routes;

    /**
     * @var array<string> List of regex for find parameter inside passed routes
     */
    private array $matchTypes = [
        '`\[[0-9A-Za-z._-]+\]`',
    ];

    /**
     * @var array<string> List of regex for find type of parameter inside passed routes
     */
    private array $types = [
        '[0-9A-Za-z._-]++',
    ];

    /**
     * @var array<mixed> preg_match result for route.
     */
    private array $routeMatches = [];

    /**
     * @var array<mixed> Array with parameters from query string.
     */
    private array $queryParam = [];

    /**
     * Constructor.
     * Accept as parameter a RouteCollection object and an array options.
     *
     * @param RouteCollection<Route> $routes
     * @param array<mixed>           $options
     */
    public function __construct(RouteCollection $routes, array $options = [])
    {
        [
            'basePath'             => $this->basePath,
            'rewriteMode'          => $this->rewriteMode,
            'rewriteModeOffRouter' => $this->rewriteModeOffRouter,
            'parseQueryStringOnRewriteModeOn' => $this->parseQueryStringOnRewriteModeOn
        ] = \array_replace_recursive([
            'basePath'             => $this->basePath,
            'rewriteMode'          => $this->rewriteMode,
            'rewriteModeOffRouter' => $this->rewriteModeOffRouter,
            'parseQueryStringOnRewriteModeOn' => $this->parseQueryStringOnRewriteModeOn
        ], $options);

        //set routes
        $this->routes = $routes;
    }

    /**
     * Evaluate request uri.
     *
     * @param string $requestUri
     * @param string $requestMethod
     *
     * @return bool
     */
    public function validate(string $requestUri, string $requestMethod): bool
    {
        $route = $this->findRoute($this->filterUri($requestUri), $requestMethod);

        if ($route instanceof Route) {
            $this->buildRoute($route);
            return true;
        }

        $this->route = $route;

        return false;
    }

    /**
     * Find if provided route match with one of registered routes.
     *
     * @param string $uri
     * @param string $method
     *
     * @return RouteInterface
     */
    private function findRoute(string $uri, string $method): RouteInterface
    {
        $matches = [];
        $route = new NullRoute();

        foreach ($this->routes as $value) {
            $urlMatch = \preg_match('`^'.\preg_replace($this->matchTypes, $this->types, $value->url).'/?$`', $uri, $matches);
            $methodMatch = \strpos($value->method, $method);

            if ($urlMatch && $methodMatch !== false) {
                $route = clone $value;
                $this->routeMatches = $matches;
                break;
            }
        }

        return $route;
    }

    /**
     * Build a valid route.
     *
     * @param Route $route
     *
     * @return void
     */
    private function buildRoute(Route $route): void
    {
        //add to route array the passed uri for param check when call
        $matches = $this->routeMatches;

        //route match and there is a subpattern with action
        if (\count($matches) > 1) {
            //assume that subpattern rapresent action
            $route->action = $matches[1];

            //url clean
            $route->url = \preg_replace('`\([0-9A-Za-z\|]++\)`', $matches[1], $route->url);
        }

        $queryParam = [];

        if ($this->parseQueryStringOnRewriteModeOn) {
            $queryParam = $this->queryParam;
        }

        //array union operator :)
        $route->param = $this->buildParam($route) + $queryParam;

        $this->route = $route;
    }

    /**
     * Try to find parameters in a valid route and return it.
     *
     * @param Route $route
     *
     * @return array<mixed>
     */
    private function buildParam(Route $route): array
    {
        $param = [];

        $url = \explode('/', $route->url);
        $matches = \explode('/', $this->routeMatches[0]);

        $rawParam = \array_diff($matches, $url);

        foreach ($rawParam as $key => $value) {
            $paramName = \strtr($url[$key], ['[' => '', ']' => '']);
            $param[$paramName] = $value;
        }

        return $param;
    }

    /**
     * Create an array from query string params.
     *
     * @param string $queryString
     *
     * @return void
     */
    private function buildParamFromQueryString(string $queryString): void
    {
        $param = \array_map(function ($array_value) {
            $tmp = \explode('=', $array_value);
            $array_value = [];
            $key = $tmp[0];
            $value = '';

            if (isset($tmp[1])) {
                $value = \urldecode($tmp[1]);
            }

            $array_value[$key] = $value;

            return $array_value;
        }, \explode('&', $queryString));

        $temp = [];

        foreach ($param as $value) {
            if (\is_array($value)) {
                $temp = \array_merge($temp, $value);
                continue;
            }

            $temp[] = $value;
        }

        $this->queryParam = $temp;
    }

    /**
     * Check if a route is valid and
     * return the route object else return a bad route object.
     *
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * Analize current uri, sanitize and return it.
     *
     * @param string $passedUri
     *
     * @return string
     */
    private function filterUri(string $passedUri): string
    {
        //sanitize url
        $url = \filter_var($passedUri, FILTER_SANITIZE_URL);

        //check for rewrite mode
        $url = \str_replace($this->rewriteModeOffRouter, '', $url);

        //remove basepath, if present
        if (\strpos($url, $this->basePath) === 0) {
            $url = \substr($url, \strlen($this->basePath));
        }

        //remove doubled slash
        $url = \str_replace('//', '/', $url);

        //check for query string parameters
        if (\strpos($url, '?') !== false) {
            $queryString = \strstr($url, '?');
            $queryString = \substr($queryString, 1);
            $url = \strstr($url, '?', true);
            $this->buildParamFromQueryString($queryString);
        }

        return (\substr($url, 0, 1) === '/') ? $url : '/'.$url;
    }

    /**
     * Map a route.
     *
     * @param RouteInterface $route
     *
     * @return void
     */
    public function map(RouteInterface $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Fast route mapping.
     *
     * @param string       $name
     * @param array<mixed> $arguments
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        $method = \strtoupper($name);

        if (\in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->map(
                new Route(\array_merge($arguments[2] ?? [], [
                    'method'   => $method,
                    'url'      => $arguments[0],
                    'callback' => $arguments[1]
                ]))
            );

            return;
        }

        throw new BadMethodCallException("Router->{$name}() method do not exist.");
    }
}
