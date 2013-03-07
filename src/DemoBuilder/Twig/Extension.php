<?php

class DemoBuilder_Twig_Extension extends \Twig_Extension
{

    public function getName()
    {
        return 'DemoBuilder';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('slashDirname', 'slashDirname'),
            new \Twig_SimpleFilter('getRouteUrl', 'getRouteUrl'),
            new \Twig_SimpleFilter('getBuilderRouteUrl', 'getBuilderRouteUrl'),
            new \Twig_SimpleFilter('getSecuredRealPath', 'getSecuredRealPath'),
            new \Twig_SimpleFilter('getHumanReadableBit', 'getHumanReadableBit'),
        );
    }
}

// Endfile