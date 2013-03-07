<?php

namespace DemoBuilder;

use \ArrayAccess,
    \Exception,
    \DateTime;

use \WebFilesystem\WebFileInfo;

use \FileSystem\ConfigFile,
    \FileSystem\PackageManifest,
    \FileSystem\DemoManifest,
    \FileSystem\DemoDirectory,
    \FileSystem\DemoPageDirectory;

use \DemoBuilder\AbstractControllerDependent,
    \DemoBuilder\DemoPageInterface;

class Page extends AbstractControllerDependent implements ArrayAccess, DemoPageInterface
{

    public static $defaults = array(
        'title'             => null,
        'description'       => null,
        'update'            => null,
        'blocks'            => array(
            'browser_info'      => false,
            'js_code'           => false,
            'manifest'          => false,
            'repository'        => false,
            'sources'           => false,
            'header_description'=> false,
        ),
        'authors'           => array(),
        'sections'          => array(),
        'menu'              => array(),
        'header_menu'       => array(),
        'header_description'=> null,
    );

    protected $file;
    protected $directory;
    public $sections_contents = array();

    public function __construct(ConfigFile $file = null, DemoPageDirectory $directory = null)
    {
        if (!is_null($file)) {
            $this->setConfigFile( $file );
        }
        if (!is_null($directory)) {
            $this->setDemoPageDirectory( $directory );
        }
        $this->init()->parse( !is_null($this->file) ? $this->file->getConfig() : array() );
    }

    protected function init()
    {
        foreach(self::$defaults as $name=>$value) {
            $this->{$name} = $value;
        }
        return $this;
    }

    /**
     * Set the configuration file entity
     */
    public function setConfigFile(ConfigFile $file)
    {
        $this->file = $file;
    }

    /**
     * Get the configuration file entity
     */
    public function getConfigFile()
    {
        return $this->file;
    }

    /**
     * Set the demo directory entity
     */
    public function setDemoPageDirectory(DemoPageDirectory $directory)
    {
        $this->directory = $directory;
    }

    /**
     * Get the demo directory entity
     */
    public function getDemoPageDirectory()
    {
        return $this->directory;
    }

    public function parse(array $data = array())
    {
        // the global controller
        $ctrl = $this->getContainer();
        
        // received data
        foreach(self::$defaults as $name=>$value) {
            if (isset($data[$name])) {
                if (is_array($value) && !is_array($data[$name])) {
                    $this->{$name} = explode(',', $data[$name]);
                } else {
                    $this->{$name} = $data[$name];
                }
            }
        }

        if (null===$ctrl->getPackage() || null===$ctrl->getDemo()) return;

/*
title
demo_title
description
author
header_menu
menu
blocks
header_description
sections
update
*/
        // blocks
        if (empty($this->blocks)) $this->blocks = array();
        $demo_blocks = $ctrl->getDemo()->blocks;
        $this->blocks = array_merge($ctrl->getDemo()->blocks, $this->blocks);

        // title
        if (empty($this->title)) {
            $this->title = $ctrl->getPackage()->name;
        }

        // demo title
        if (empty($this->demo_title)) {
            $this->demo_title = $ctrl->getDemoOrPackageValue('name');
        }

        // description
        if (empty($this->description)) {
            $this->description = $ctrl->getDemoOrPackageValue('description');
        }

        // header_description
        if (empty($this->header_description)) {
            $this->header_description = $ctrl->getDemoOrPackageValue('header_description');
        }

        // authors
        if (empty($this->authors)) {
            $this->authors = $ctrl->getDemoOrPackageValue('authors');
        }

        // update
        if (empty($this->update)) {
            $this->update = $ctrl->getPackageValue('time');
        }
        if (!empty($this->update) && !($this->update instanceof DateTime)) {
            $this->update = new DateTime( $this->update );
        }

        // menu
        if (empty($this->menu)) {
            $this->menu = $ctrl->getMenu();
        }

        // global menu
        if (empty($this->header_menu)) {
            $this->header_menu = $this->menu;
        }

        // content sections
        $this->getSectionsContents();
    }

    public function getSectionsContents()
    {
        if (!is_null($this->directory)) {
            foreach($this->directory as $path=>$item) {
                if (!$this->directory->hasChildren()) {
                
                    // the content
                    if (!empty($this->sections)) {
                        $index = array_search($item->getBasename(), $this->sections)+1;
                        $this->sections_contents[$index] = $this->_parseContent($item);
                    } else {
                        $this->sections_contents[] = $this->_parseContent($item);
                    }

                    // the modification time
                    $mtime = getDateTimeFromTimestamp( $item->getMTime() );
                    if ($this->update < $mtime) {
                        $this->update = $mtime;
                    }
                }
            }
        }
        ksort($this->sections_contents);
    }

    protected function _parseContent(WebFileInfo $item)
    {
        $file_path = $item->getRealPath();
        $file_extension = $item->getExtension();
        $_meth = '_getContent'.ucfirst(strtolower($file_extension));
        if (method_exists($this, $_meth)) {
            return $this->{$_meth}( $file_path );
        } else {
            throw new Exception(
                sprintf('Unknown extension type "%s"!', $file_extension)
            );
        }
    }

    protected function _getContentMd($path)
    {
        $ctt = @file_get_contents($path);
        require_once _SRCVENDOR.'Markdown_Extra/markdown.php';
        return Markdown($ctt);
    }

    protected function _getContentTxt($path)
    {
        return @file_get_contents($path);
    }

    protected function _getContentHtml($path)
    {
        return @file_get_contents($path);
    }

    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }
    
    public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }
    
    public function offsetSet($offset, $value)
    {
    }
    
    public function offsetUnset($offset)
    {
    }
    
}

// Endfile