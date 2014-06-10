<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder\DOM;

use \DOMDocument as OriginalDOMDocument,
    \DOMElement,
    \DOMXPath;

/**
 */
class DOMDocument extends OriginalDOMDocument
{

    /**
     * @var \DOMElement
     */
    protected $html;

    /**
     * Parent overrite of `loadHTML` to load the HTML body in `$this->html`
     */
    public function loadHTML($source)
    {
        @parent::loadHTML($source);
        $this->html = $this->getElementsByTagName('body')->item(0);
    }
    
    /**
     * Parent overrite of `loadHTMLFile` to load the HTML body in `$this->html`
     */
    public function loadHTMLFile($filename)
    {
        @parent::loadHTMLFile($filename);
        $this->html = $this->getElementsByTagName('body')->item(0);
    }

    /**
     * Get the DOMDocument to work on (html or this)
     */
    public function getDOM()
    {
        return !empty($this->html) ? $this->html : $this;
    }
    
    /**
     * @return \DOMXPath
     */
    public function getXPath()
    {
        return new DOMXPath($this);
    }

    /**
     * @return string
     */
    public function getHTMLBody()
    {
        if (!empty($this->html)) {
            return strtr($this->saveHTML($this->html), array('<body>'=>'', '</body>'=>''));
        }
        return $this->saveHTML();
    }

    /**
     * @param string $node_name
     * @param bool $remove_node
     * @return object \DOMNode
     */
    public function getNextNodeByName($node_name, $remove_node = false)
    {
        foreach ($this->getDOM()->childNodes as $node) {
            if ($node->nodeName===$node_name) {
                if ($remove_node) {
                    $this->getDOM()->removeChild($node);
                }
                return $node;
            }
        }
        return null;
    }
    
    /**
     * @param string $node_name
     * @param bool $remove_node
     * @return object \DOMNode
     */
    public function getNodeById($node_id, $remove_node = false)
    {
        $node = $this->getElementById($node_id);
        if (!empty($node)) {
            if ($remove_node) {
                $this->getDOM()->removeChild($node);
            }
            return $node;
        }
        return null;
    }

    /**
     * @var array
     */    
    protected static $HTML_MENU_TAGS = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

    /**
     * @param bool $keep_depth
     * @return object \RecursiveArrayIterator
     */
    public function getDocumentMenu($keep_depth = true)
    {
        if (empty($this->html)) return null;

        $menu_items = new \RecursiveArrayIterator;
        $iterator = 1;
        $current = 0;
        foreach ($this->getDOM()->childNodes as $node) {
            if (in_array($node->nodeName, self::$HTML_MENU_TAGS)) {
                $index = array_search($node->nodeName, self::$HTML_MENU_TAGS)+1;
                if ($index < $iterator) {
                    $iterator--;
                    $current++;
                } elseif ($index > $iterator) {
                    $iterator++;
                }
                if ($keep_depth) {
                    if (empty($menu_items[$current])) {
                        $menu_items[$current] = new \RecursiveArrayIterator;
                    }
                    if (empty($menu_items[$current][$index])) {
                        $menu_items[$current][$index] = new \RecursiveArrayIterator;
                    }
                    if (empty($menu_items[$current][$index]['items'])) {
                        $menu_items[$current][$index]['items'] = new \RecursiveArrayIterator;
                    }
                    $menu_items[$current][$index]['items']->append($node);
                } else {
                    $menu_items->append($node);
                }
            }
        }
/*
        foreach ($this->getDOM()->childNodes as $node) {
            if (in_array($node->nodeName, self::$HTML_MENU_TAGS)) {
                $index = array_search($node->nodeName, self::$HTML_MENU_TAGS)+1;
                if ($index < $iterator) {
                    $iterator--;
                    $current++;
                } elseif ($index > $iterator) {
                    $iterator++;
                }
                if (empty($menu_items[$index])) {
                    $menu_items[$index] = new \RecursiveArrayIterator;
                }
                if (empty($menu_items[$index][$iterator])) {
                    $menu_items[$index][$iterator] = new \RecursiveArrayIterator;
                }
                if (empty($menu_items[$iterator][$index][$current])) {
                    $menu_items[$index][$iterator][$current] = new \RecursiveArrayIterator;
                }
                $menu_items[$index][$iterator][$current]->append($node->nodeValue);
            }
        }
echo '<pre>';
var_dump($menu_items);
exit('yo');
*/        
        return !empty($menu_items) ? $menu_items : null;
    }

    /**
     * @return object \DemoBuilder\DOMDocument
     */
    public function getExtractByTagName($tag_name, $index = 0)
    {
        $counter = 0;
        $in_tag = false;
        $extract = new DOMDocument;
        foreach ($this->getDOM()->childNodes as $node) {
            if ($node->nodeName===$tag_name) {
                if ($counter===$index) {
                    $in_tag = true;
                    $new_node = $extract->importNode($node, true);
                    $extract->appendChild($new_node);
                } else {
                    $in_tag = false;
                }
                $counter++;
            } elseif ($in_tag && !empty($node->nodeValue)) {
                $new_node = $extract->importNode($node, true);
                $extract->appendChild($new_node);
            }
        }
        return ($extract->childNodes->length!==0) ? $extract : null;
    }

}

// Endfile