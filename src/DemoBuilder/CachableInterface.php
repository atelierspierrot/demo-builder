<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

/**
 */
interface CachableInterface
{

    public function isCached();
    public function getCache();
    public function setCache($content);

}

// Endfile