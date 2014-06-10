<?php
/**
 * Demo Builder - PHP library to quickly build demo pages
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/demobuilder>
 */

namespace DemoBuilder;

use Patterns\Abstracts\AbstractStaticCreator;
use Library\Helper\Directory as DirectoryHelper;
use Library\Helper\Html as HtmlHelper;
use Library\Helper\Url as UrlHelper;
use Library\Helper\Text as TextHelper;
use Library\StaticConfiguration\Config;
use Assets\Loader as AssetsLoader;
use TemplateEngine\TemplateEngine;

/**
 * Global controller
 */
class Controller extends AbstractStaticCreator
{

    protected $params = array();
    protected $views = array();
    protected $demo_builder;
    protected $loader;
    protected $template_engine;
    protected $demo_pages;

// -------------------------------
// AbstractStaticCreator
// -------------------------------

    public function init()
    {
        Config::load('\DemoBuilder\DefaultConfig');
        $this->demo_builder = new DemoBuilder();
	    $this
	        ->setParams(Config::get('default_parameters'))
	        ->setViews(Config::get('default_views'))
	        ;
    }

// -------------------------------
// Setters / Getters
// -------------------------------

    /**
     * @param object $loader \Assets\Loader
     */
	public function setAssetsLoader(AssetsLoader $loader)
	{
        $this->assets_loader = $loader;
        return $this;
    }

    /**
     * @param object $engine \TemplateEngine\TemplateEngine
     */
	public function setTemplateEngine(TemplateEngine $engine)
	{
        $this->template_engine = $engine;
        return $this;
	}

    /**
     * @param object $engine \DemoBuilder\DemoPages
     */
	public function setDemoPagesParser(DemoPages $parser)
	{
        $this->demo_pages = $parser;
        return $this;
	}

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $name
     * @param misc $value
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @param misc $default
     * @return misc
     */
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    /**
     * @param array $views
     */
    public function setViews(array $views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setView($name, $value)
    {
        $this->views[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getView($name)
    {
        return isset($this->views[$name]) ? $this->views[$name] : null;
    }

    /**
     * @return string
     */
    public function getCachePath()
    {
	    return DirectoryHelper::slashDirname($this->assets_loader->getDocumentRoot()) . Config::get('cache_dir');
    }

    /**
     * @return object \DemoBuilder\DemoPageItem
     */
    public function getWelcomePage()
    {
	    $page_name = Config::get('welcome_page');
	    $page_ctt = $this->template_engine->getTemplate($page_name);
	    if ($page_ctt) {
            $page = new DemoPageItem($page_ctt, $this->getCachePath());
            $page->parse();
            return $page;
        }
        return null;
    }

    /**
     * @return object \DemoBuilder\DemoPageItem
     */
    public function getSamplePage()
    {
	    $page_name = Config::get('sample_page');
	    $page_ctt = $this->template_engine->getTemplate($page_name);
	    if ($page_ctt) {
            $page = new DemoPageItem($page_ctt, $this->getCachePath());
            $page->parse();
            return $page;
        }
        return null;
    }

// -------------------------------
// Common Process
// -------------------------------

	/**
	 * Set up the object with request arguments
	 */
	public function parseRequest()
	{
	    foreach ($this->params as $name=>$val) {
	        if (isset($_GET[$name]) && !empty($_GET[$name])) {
	            $this->setParam($name, $_GET[$name]);
	        }
	    }
	    return $this;
	}
	
	/**
	 * Parse page content and demo contents
	 */
	public function parsePage($page)
	{
	    $page_ctt = $this->template_engine->getTemplate($page);
	    if (is_null($page_ctt)) {
	        $content = $this->getWelcomePage()->getContent();
	    }
        return $content;
	}

	/**
	 * Distributes the application actions
	 */
	public function distribute()
	{
	    $page = $this->getParam('page');
	    $pages_extension = Config::get('pages_extension');
	    $pages_path = DirectoryHelper::slashDirname($this->getParam('pages_dir'));
	    $page_name = $page . '.' . $pages_extension;

	    $this->setDemoPagesParser(
	        new DemoPages(
	            DirectoryHelper::slashDirname($this->assets_loader->getRootDirectory()) . $pages_path,
	            $page_name,
	            $this->getCachePath(),
	            '*.'.$pages_extension
	        )
	    );
	    $this->demo_pages->parse();

        $page_path = $pages_path . $page_name;
	    if ($page==='sample') {
	        $this->demo_builder->content = $this->getSamplePage()->getContent();
	        $this->demo_builder->title = 'Sample page';
	    } else {
			$this->demo_builder->content = $this->parsePage($page_path);
	        $this->demo_builder->title = ucfirst(TextHelper::getHumanReadable($page));
	    }

        return $this->display();
	}

	/**
	 * Full layout rendering
	 */
	public function display() 
    {
        // request params settings
        $params = array_merge($this->getParams(), array(
            'package' => array(),
            'builder' => $this->demo_builder->getClean()
        ));

/*
        if (empty($params['title'])) {
            $params['title'] = 'Demo Builder';
        }
        if (empty($params['page_title'])) {
            $params['page_title'] = $params['title'];
        }

        $title_block = array(
            'title'=> isset($params['title']) ? $params['title'] : $title,
            'subheader'=>isset($params['subheader']) ? $params['subheader'] : "A PHP package to build HTML5 views (based on HTML5 Boilerplate layouts).",
            'slogan'=>isset($params['slogan']) ? $params['slogan'] : "<p>These pages show and demonstrate the use and functionality of the <a href=\"http://github.com/atelierspierrot/templatengine\">atelierspierrot/templatengine</a> PHP package you just downloaded.</p>"
        );
        $params['title'] = $title_block;

        if (empty($params['menu'])) {
            $params['menu'] = array(
                'Home'              => UrlHelper::url(array('page'=>'index')),
                'Simple test'       => UrlHelper::url(array('page'=>'hello')),
                'Functions doc'     => UrlHelper::url(array('page'=>'fcts')),
                'Plugins test'      => UrlHelper::url(array('page'=>'test')),
                'Typographic tests' => UrlHelper::url(array('page'=>'loremipsum')),
                'PHP Assets manager' => UrlHelper::url(array('page'=>'assets')),
            );
        }
*/
        $params['footer'] = array('right'=>'A test of footer');

//var_export($params); exit('yo');

        // this will display the layout on screen and exit
        $layout = $this->getView('layout');
		$this->template_engine->renderLayout($layout, $params, true, true);
    }

}

// Endfile