<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

function _getSecuredRealPath( $str )
{
    $parts = explode('/', realpath('.'));
    array_pop($parts);
    array_pop($parts);
    return str_replace(join('/', $parts), '/[***]', $str);
}

function getPhpClassManualLink( $class_name, $ln='en' )
{
    return sprintf('http://php.net/manual/%s/class.%s.php', $ln, strtolower($class_name));
}

// Endfile