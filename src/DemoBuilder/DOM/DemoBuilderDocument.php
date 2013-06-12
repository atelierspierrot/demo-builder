<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder\DOM;

use \DOMDocument as OriginalDOMDocument, \DOMNode, \DOMElement, \DOMXPath;
use DemoBuilder\DOM\DOMDocument;
use Library\StaticConfiguration\Config;

/**
 */
class DemoBuilderDocument extends DOMDocument
{

    /**
     * @param string $html_content
     */
    public function __construct($html_content = '')
    {
        parent::__construct();
        $this->loadHTML($html_content);
    }

// ------------------------
// Getters Setters
// ------------------------

    /**
     * @param bool $remove
     * @return string
     */
    public function getDocumentTitle($remove = true)
    {
        $titleNode = $this->getNextNodeByName(Config::get('title_node'), true);
        return !empty($titleNode) ? $titleNode->nodeValue : null;
    }

    /**
     * @param bool $remove
     * @return string
     */
    public function getDocumentSlogan($remove = true)
    {
        $sloganNode = $this->getNextNodeByName(Config::get('slogan_node'), true);
        return !empty($sloganNode) ? $sloganNode->nodeValue : null;
    }
    
    /**
     * @param bool $remove
     * @return string
     */
    public function getDocumentJsCode($remove = true)
    {
        $jsCodeNode = $this->getNodeById(Config::get('jscode_node'), true);
        return !empty($jsCodeNode) ? $jsCodeNode->nodeValue : null;
    }

    /**
     * @return array
     */
    public function getDocumentMenu()
    {
        $menu_iterator = parent::getDocumentMenu(false);
        $menu_items = array();
        foreach ($menu_iterator as $node) {
            $depth = $this->getMenuItemDepth($node->nodeName);
            $max_depth = $this->getMenuItemDepth(Config::get('max_menu_depth'));
            if ($depth <= $max_depth) {
                $idNode = $node->hasAttributes() ? 
                        $node->attributes->getNamedItem('id') : null;
                $menu_items[] = array(
                    'text' => $node->nodeValue,
                    'tag' => $node->nodeName,
                    'id' => !empty($idNode) ? $idNode->nodeValue : uniqid(),
                );
            }
        }
        return $menu_items;
    }
    
    /**
     * @param object $node
     * @return array
     */
    protected function parseMenuItem(DOMNode $node)
    {
        $depth = $this->getMenuItemDepth($node->nodeName);
        $max_depth = $this->getMenuItemDepth(Config::get('max_menu_depth'));
        if ($depth <= $max_depth) {
            return array(
                'text' => $node->nodeValue,
                'tag' => $node->nodeName,
                'items' => array()
            );
        }
        return null;
    }
    
    /**
     * @param string $node_name
     * @return int
     */
    protected function getMenuItemDepth($node_name)
    {
        return str_replace('h', '', $node_name);
    }
    
    /**
     * @return array
     */
    public function getDocumentComments()
    {
        $blocks = array();
        $blockNodes = $this->getXPath()->query('//comment()');
        if (!empty($blockNodes)) {
            foreach ($blockNodes as $node) {
                $blocks[] = trim($node->nodeValue);
            }
        }
        return $blocks;
    }

}

// Endfile