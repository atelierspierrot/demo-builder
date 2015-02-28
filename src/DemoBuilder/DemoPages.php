<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

use Library\Helper\Directory as DirectoryHelper,
    Library\Helper\Html as HtmlHelper,
    Library\Helper\Url as UrlHelper,
    Library\Helper\Text as TextHelper,
    Library\StaticConfiguration\Config;

use WebFilesystem\Finder;

/**
 * Global controller
 */
class DemoPages implements ParserInterface
{

    protected $root_path;
    protected $current_page;
    protected $cache_path;
    protected $pages_name_mask;
    protected $pages = array();

// -------------------------------
// Constructor
// -------------------------------

    /**
     * @param string $root_path
     * @param string $current_page
     * @param string $cache_path
     * @param string $pages_name_mask
     *
     * @throws `InvalidArgumentException` if `$root_path` not found
     */
    public function __construct($root_path, $current_page, $cache_path, $pages_name_mask = '*.md')
    {
        if (file_exists($root_path)) {
            $this->root_path = $root_path;
        } else {
            throw new \InvalidArgumentException(
                sprintf('Pages directory "%s" not found!', $root_path)
            );
        }

        $this->current_page = $current_page;
	    $this->cache_path = $cache_path;
        $this->pages_name_mask = $pages_name_mask;
    }

// -------------------------------
// Getters
// -------------------------------

    public function getCurrentPage()
    {
        foreach ($this->pages as $_page) {
            if (basename($_page->getPagePath())===$this->current_page) {
                return $_page;
            }
        }
        return null;
    }

// -------------------------------
// Process
// -------------------------------

	/**
	 * Parse demo pages and build menu & content
	 */
	public function parse()
	{
        $finder = Finder::create()
            ->files()
            ->in($this->root_path)
            ->name($this->pages_name_mask);
        foreach ($finder as $file) {
            $this->pages[] = new DemoPageItem(
                $file->getFilename(), $this->cache_path
            );
        }
        foreach ($this->pages as $_page) {
            $_page->parse();
        }
	}

}

// Endfile