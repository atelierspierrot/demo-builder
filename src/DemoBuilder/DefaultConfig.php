<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

use Library\StaticConfiguration\ConfiguratorInterface;

/**
 * @author 		Piero Wbmstr <me@e-piwi.fr>
 */
class DefaultConfig implements ConfiguratorInterface
{

    /**
     * Only one method for both required ones
     *
     * @return array
     */
    public static function getConfig()
    {
        return array(
            // the app config file, can be set for each demo
            'config_file' => 'demobuilder.config',
            // the default extension of a demo file
            'pages_extension' => 'md',
            // the default extension of a block file
            'blocks_extension' => 'block.md',
            // the cache directory ('www/tmp/' by default)
            'cache_dir' => 'tmp',
            // two pages for empty demo
            'welcome_page' => 'index.md',
            'sample_page' => 'sample.md',
            // DOM nodes for pages parts
            'title_node' => 'h1',
            'slogan_node' => 'h2',
            'jscode_node' => 'js_code',
            'max_menu_depth' => 'h3',
            // these are the URL parameters that can be over-written
            'default_parameters' => array(
                'pages_dir' => 'demo',
                'page' => 'index',
                'action' => 'index',
                'merge_css' => false,
                'minify_css' => false,
                'merge_js' => false,
                'minify_js' => false,
            ),
            // the application views
            'default_views' => array(
                'layout' => 'layout.htm',
                'header' => 'header.htm',
                'nav' => 'nav.htm',
                'footer' => 'footer.htm',
                'back_menu' => 'back_menu.htm',
                'block' => 'block.htm',
            ),
        );
    }

    /**
     * Get the default configuration values
     *
     * This must define at least the requires entries defined below.
     * During the configuration object lifecycle, no other entry than these defaults
     * could be define.
     *
     * @return array
     */
    public static function getDefaults()
    {
        return self::getConfig();
    }

    /**
     * Get the required configuration entries
     *
     * @return array
     */
    public static function getRequired()
    {
        return array_keys(self::getConfig());
    }

}

// Endfile