<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

use Library\StaticConfiguration\Config;
use DemoBuilder\DOM\DemoBuilderDocument;

/**
 * DemoBuilder page full structure
 */
class DemoBuilder
{

// package infos
    protected $package;

// demo pages infos
    protected $pages_items;

// head
    protected $page_title;
    protected $page_description;

// html: header
    protected $title;
    protected $subheader;
    protected $slogan;

// html: content
    protected $content;
    protected $precontent;
    protected $postcontent;

// html: navigation
    protected $menu_items;

// html: footer
    protected $js_code;

// ------------------------
// Global cleaner/setter
// ------------------------

    public function getClean()
    {
        $this->parseContent();

        if (empty($this->title)) {
            
        }

        if (empty($this->page_title)) {
            $this->page_title = $this->title;
        }
        if (empty($this->page_description)) {
            $this->page_description = $this->subheader;
        }

        return $this;
    }

    /**
     * Parse page content to grab infos for the builder
     */
    public function parseContent()
    {
        if (empty($this->content)) return;
        $dom = new DemoBuilderDocument($this->content);

        // page title
        $title = $dom->getDocumentTitle();
        if (!empty($title)) {
            $this->title = $title;
        }

        // page slogan
        $slogan = $dom->getDocumentSlogan();
        if (!empty($slogan)) {
            $this->subheader = $slogan;
        }

        // js_code block
        $js_code = $dom->getDocumentJsCode();
        if (!empty($js_code)) {
            $this->js_code = $js_code;
        }

        // blocks dependencies
        $blocks = $dom->getDocumentComments();
        if (!empty($blocks)) {
            foreach ($blocks as $node) {

            }
        }

        // page menu
        $menu = $dom->getDocumentMenu();
        if (!empty($menu)) {
            $this->menu_items = $menu;
        }

        $this->content = $dom->getHTMLBody();
    }

    /**
     * Parse a comment block in content
     */
    public function parseComment($content)
    {

    }
    
// ------------------------
// Magic access to protected properties
// ------------------------

    /**
     * Override a protected property value
     */
    public function propertyExists($name)
    {
        return property_exists($this, $name);
    }

    /**
     * Override a protected property value
     */
    public function __set($name, $value)
    {
        if ($this->propertyExists($name)) {
            $this->{$name} = $value;
        }
    }

    /**
     * Get a protected property value
     */
    public function __get($name)
    {
        if ($this->propertyExists($name)) {
            return $this->{$name};
        }
        return null;
    }

    /**
     * Test a protected property value
     */
    public function __isset($name)
    {
        if ($this->propertyExists($name)) {
            return isset($this->{$name});
        }
    }

    /**
     * Unset a protected property value
     */
    public function __unset($name)
    {
        if ($this->propertyExists($name)) {
            unset($this->{$name});
        }
    }

}

// Endfile