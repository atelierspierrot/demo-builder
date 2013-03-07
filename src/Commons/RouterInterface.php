<?php

namespace Commons;

interface RouterInterface
{

    /**
     * Build the possible routes
     */
    public function buildRoutes();

    /**
     * Check if a route exists
     */
    public function routeExists($route);

    /**
     * Get the current route requested
     */
    public function getRoute();

    /**
     * Build a new route URL
     */
    public function getRouteUrl($page, $hash=null);

}

// Endfile