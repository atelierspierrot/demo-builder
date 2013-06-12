<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

use Library\Helper\Directory as DirectoryHelper;
use MarkdownExtended\MarkdownExtended;

/**
 * Global controller
 */
class DemoPageItem implements CachableInterface, ParserInterface
{

    protected $page_path;
    protected $cache_path;
    protected $page_content;

    protected static $markdown_parser;

// -------------------------------
// Constructor
// -------------------------------

    /**
     * @param string $page_path
     * @param string $cache_path
     *
     * @throws `InvalidArgumentException` if `$page_path` not found
     * @throws `RuntimeException` if `$cache_path` not found and can not be created
     */
    public function __construct($page_path, $cache_path)
    {
        if (file_exists($page_path)) {
            $this->page_path = $page_path;
        } else {
            throw new \InvalidArgumentException(
                sprintf('Page "%s" not found!', $page_path)
            );
        }
        
        $ok = DirectoryHelper::ensureExists($cache_path);
        if (!$ok || !file_exists($cache_path)) {
            throw new \RuntimeException(
                sprintf('Cache directory "%s" doesn\'t exist and can not be created!', $cache_path)
            );
        }
        $this->cache_path = $cache_path;
    }

// -------------------------------
// Setters / Getters
// -------------------------------

    /**
     * @return string
     */    
    public function getPagePath()
    {
        return $this->page_path;
    }

    /**
     * @return string
     */    
    public function getCacheName()
    {
        return md5($this->page_path);
    }

    /**
     * @return string
     */    
    public function getCachePath()
    {
        return DirectoryHelper::slashDirname($this->cache_path) . $this->getCacheName();
    }

    /**
     * @return string
     */    
    public function getContent()
    {
        return $this->page_content;
    }

    /**
     * @param object $parser \MarkdownExtended\MarkdownExtended
     */
    public function setMarkdownParser(MarkdownExtended $parser)
    {
        self::$markdown_parser = $parser->get('\MarkdownExtended\Parser');
        return $this;
    }

    /**
     * @return object \MarkdownExtended\MarkdownExtended
     */    
    public function getMarkdownParser()
    {
        if (empty(self::$markdown_parser)) {
            $this->setMarkdownParser(MarkdownExtended::getInstance());
        }
        return self::$markdown_parser;
    }

// -------------------------------
// CachableInterface
// -------------------------------

    public function isCached()
    {
        $cached = $this->getCachePath();
        if (file_exists($cached)) {
            $original_mtime = filemtime($this->page_path);
            $cache_mtime = filemtime($cached);
            return ($original_mtime < $cache_mtime);
        }
        return false;
    }
    
    public function getCache()
    {
        return @file_get_contents($this->getCachePath());
    }
    
    public function setCache($content)
    {
        return @file_put_contents($this->getCachePath(), $content);
    }

// -------------------------------
// Process
// -------------------------------

	/**
	 * Parse page content retrieving it from cache if so
	 */
	public function parse()
	{
	    if ($this->isCached()) {
	        $this->page_content = $this->getCache();
	    } else {
	        $this->page_content = $this->getMarkdownParser()
	            ->transform(file_get_contents($this->page_path));
	        $this->setCache($this->page_content);
	    }	    
	    return $this;
	}

}

// Endfile