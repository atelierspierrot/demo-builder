<?php

function slashDirname($dirname)
{
    return rtrim($dirname, '/ '.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
}

function getSecuredRealPath( $str, $path='.', $visible_pathes=2 )
{
    $parts = explode(DIRECTORY_SEPARATOR, realpath($path));
    for($i=0;$i<$visible_pathes; $i++) {
        array_pop($parts);
    }
    return str_replace(join(DIRECTORY_SEPARATOR, $parts), DIRECTORY_SEPARATOR.'[***]', $str);
}

function getPhpManualLink( $name, $ln='en' )
{
    return sprintf('http://php.net/%s/%s', $ln, $name);
}

function getJqueryManualLink( $name )
{
    return sprintf('http://api.jquery.com/%s/', $name);
}

function isArrayOfArrays($array)
{
    if (!is_array($array)) {
        return false;
    }
    foreach($array as $item) {
        if (!is_array($item)) return false;
    }
    return true;
}

function getSlug($string)
{
    return str_replace(array(' '), '_', strtolower($string));
}

function getRouteUrl($page, $hash = null)
{
    $demo = \DemoBuilder\Controller::getInstance();
    return $demo->getRouteUrl($page, $hash);
}

function getBuilderRouteUrl($page)
{
    $demo = \DemoBuilder\Controller::getInstance();
    return $demo->getBuilderRouteUrl($page);
}

function renderView($view_file, array $params = array())
{
    $demo = \DemoBuilder\Controller::getInstance();
    return $demo->renderView($view_file, $params);
}

function getDateTimeFromTimestamp($timestamp)
{
    $time = new \DateTime;
    $time->setTimestamp( $timestamp );
    return $time;
}

function getExtension($filename)
{
    return end( explode('.', $filename) );
}

function getHumanReadableBit($bool)
{
    return (true===$bool ? 'on' : 'off');
}

// Endfile