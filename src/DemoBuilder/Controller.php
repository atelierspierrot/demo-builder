<?php

namespace DemoBuilder;

use \Exception,
    \InvalidArgumentException;

use \Commons\ControllerInterface,
    \Commons\ViewInterface,
    \Commons\OptionableInterface,
    \Commons\AbstractSingleton,
    \Commons\RouterInterface,
    \Commons\WebPageInterface;

use \FileSystem\ConfigFile,
    \FileSystem\PackageManifest,
    \FileSystem\DemoManifest,
    \FileSystem\DemoDirectory,
    \FileSystem\DemoPageDirectory;

use \DemoBuilder\Page,
    \DemoBuilder\DemoBuilderInterface,
    \DemoBuilder\ActionsInterface;

class Controller extends AbstractSingleton implements 
    ControllerInterface,
    ViewInterface,
    OptionableInterface,
    RouterInterface,
    WebPageInterface,
    DemoBuilderInterface,
    ActionsInterface
{

    protected static $default_options = array(
        'language'          => 'en-US',
        'charset'           => 'utf-8',
        'dir'               => null,
        'root_dir'          => null,
        'manifest'          => null,
        'demo_config'       => 'demo.config.php',
        'i'                 => 0,
        'ln'                => 'en',
        'template'          => null,
        'templates_dir'     => 'views/',
        'assets'            => 'assets/',
        'vendor_assets'     => 'assets/vendor/',
        'page'              => 'index',
        'cache'             => 'cache/',
        'debug'             => false,
        'profiling'         => true,
    );

    protected static $templates = array(
        'demo_page'          => 'template.html.twig',
        'demo_page_navbar'   => 'demo_page_navbar.html.twig',
        'profiler'           => 'demo_builder_profiler.html.twig',
        'not_found'          => 'not_found.html.twig',
        'configuration'      => 'configuration.html.twig',
        'header'             => 'header.html.twig',
        'footer'             => 'footer.html.twig',
        'metas'              => 'metas.html.twig',
/*
        'demo_page'          => 'views/template.html.php',
        'demo_page_navbar'   => 'views/demo_navbar.html.php',
        'not_found'          => 'views/not_found.html.php',
        'configuration'      => 'views/configuration.html.php',
        'header'             => 'views/header.html.php',
        'footer'             => 'views/footer.html.php',
        'metas'              => 'views/metas.html.php',
*/
    );

    protected static $options = array();

    public static $MANIFEST = 'composer.json'; // manifest of DemoBuilder package
    public static $PRESET = 'DemoBuilder_preset.php'; // DemoBuilder's DemoBuilder preset

    protected $package;
    protected $demo;
    protected $pages_directory;
    protected $page;
    protected $view_params;
    protected $args = array();
    protected $user_options = array();
    protected $routes = array();
    protected $menu = array();
    protected $metas = array();
    protected $title = '';

// -------------------------
// ControllerInterface
// -------------------------
    
    public function __construct(array $user_options = array())
    {
        if (defined('_MODE') && _MODE==='dev') {
            self::$default_options['debug'] = true;
        }
        $this->user_options = $user_options;
        session_start();
        $this->parseArguments();
    }

    public function debug($what = null)
    {
        if (!is_null($what) && property_exists($this, $what)) {
            $to_debug = $this->{$what};
        } else {
            $to_debug = $this;
        }
        echo '<pre>';
        var_dump($to_debug);
        echo '</pre>';
        exit('-- out --');
    }

    /**
     * GET, POST or SESSION arguments parser
     */
    public function parseArguments()
    {
        if (!empty($_SESSION)) {
            $this->args = $_SESSION;
        }

        if (!empty($_POST)) {
            foreach($_POST as $var=>$val) {
	  		    $cleaned_val = stripslashes( htmlentities($val, ENT_QUOTES, 'UTF-8') );
	  		    $this->args[$var] = $cleaned_val;
                if (array_key_exists($var, self::$default_options)) {
                    $_SESSION[$var] = $cleaned_val;
                }
		    }
		}

        if (!empty($_GET)) {
            foreach($_GET as $var=>$val) {
	  		    $this->args[$var] = stripslashes( htmlentities($val, ENT_QUOTES, 'UTF-8') );
		    }
        }
    }

    /**
     * Distribution of requested route
     */
    public function distribute()
    {
        // webpage
        $this->buildMenu();

        // pages
        if ($this->getBuilderRoute()) {
            $db_page = $this->getBuilderRoute();
            if ('conf'===$db_page) {
                return $this->indexAction();
            } elseif ('doc'===$db_page) {
                $preset = new ConfigFile( $this->locateFile(self::$PRESET) );
                foreach($preset->getConfig() as $var=>$val) {
                    $_SESSION[$var] = $val;
                    $this->args[$var] = $val;
                }
                $this->init();
                return $this->pageAction();
            } else {
                return $this->notFoundAction();
            }
        } elseif (!$this->isConfigured()) {
            return $this->indexAction();
        } elseif ($this->routeExists( $this->getRoute() )) {
            return $this->pageAction();
        } else {
            return $this->notFoundAction();
        }
    }

// -------------------------
// OptionableInterface
// -------------------------
    
    public static function setOptions(array $options)
    {
        self::$options = $options;
    }

    public static function setOption($var, $val=null)
    {
        self::$options[$var] = $val;
    }

    public static function getOptions()
    {
        return self::$options;
    }

    public static function getOption($var, $default=null)
    {
        return isset(self::$options[$var]) ? self::$options[$var] : $default;
    }

// -------------------------
// ViewInterface
// -------------------------
    
    /**
     * Building of a view content including a view file passing it parameters
     */
    public function render($view_file, array $params = array())
    {
        // webpage
        $this->buildTitle();
        $this->buildMetas();

        // debug if so
        if (isset($this->args['dbg']) || isset($this->args['debug'])) {
            $to_debug = null;
            if (isset($this->args['dbg']) && $this->args['dbg']!='true') {
                $to_debug = $this->args['dbg'];
            } elseif (isset($this->args['debug']) && $this->args['debug']!='true') {
                $to_debug = $this->args['debug'];
            }
            return $this->debug($to_debug);
        }

  	  	$page = $this->renderView($view_file, $params);
        if (true===$this->getOption('profiling') && false===$this->isBuilderRoute()) {
            $config = $this->getOptions();
            $page_cfgfile = $this->getPage()->getConfigFile();
            if (!empty($page_cfgfile)) {
                $config['page_config_file'] = $page_cfgfile->getRealPath();
                $config['page_config_filename'] = $page_cfgfile->getBasename();
            }
            $profiler_params = array(
                'package_manifest_url'      => $this->locateFile($this->getOption('manifest')),
                'package_manifest_path'     => realpath($this->locateFile($this->getOption('manifest'))),
                'package_manifest_name'     => $this->getPackage()->name,
                'root_demo_file_url'        => $this->locateFile($this->getOption('demo_config')),
                'root_demo_file_path'       => realpath($this->locateFile($this->getOption('demo_config'))),
                'demo_file_name'            => $this->getOption('demo_config'),
                'page_name'                 => $this->getOption('page'),
                'page_dir'                  => $this->locateDirectory($this->getOption('page')),
                'config'                    => $config
            );
            $profiler = $this->renderView(self::$templates['profiler'], array(
                'profiler'=>$profiler_params
            ));
            $page = str_replace('</body>', $profiler."\n".'</body>', $page);
      	  	echo $page;
        } else {
      	  	echo $page;
        }
        exit(0);
    }

    /**
     * Building of a view content including a view file passing it parameters
     */
    public function renderView($view_file, array $params = array())
    {
        // view file
        $_vf = $this->locateFile($view_file, false);
        if (!file_exists($_vf) && isset(self::$templates[$view_file])) {
            $_vf = $this->locateFile(self::$templates[$view_file], false);
        }

        // view parameters
        $this->view_params = array_merge($params, $this->getDefaultViewParams());

        // using the Twig engine
        $extension = getExtension($view_file);
        if ('twig'===$extension) {
            return $this->twig->render($view_file, $this->view_params);
        } else {
            // rendering
            if (file_exists($_vf)) {
                extract($this->view_params, EXTR_OVERWRITE);
                ob_start();
                include $_vf;
                $output = ob_get_contents();
                ob_end_clean();
                return $output;
            } else {
                throw new Exception(
                    sprintf('Template file "%s" not found!', $_vf)
                );
            }
        }
    }

    /**
     * Get an array of the default parameters for all views
     */
    public function getDefaultViewParams()
    {
        return array(
            'DB'                => $this,
            'rootfile'          => _ROOTFILE,
            'rootdir'           => _ROOTDIR,
            'language'          => $this->getOption('language'),
            'charset'           => $this->getOption('charset'),
            'manifest_url'      => str_replace($this->getOption('root_dir'), '', $this->getOption('manifest')),
            'package'           => $this->getPackage(),
            'demo'              => $this->getDemo(),
            'page'              => $this->getPage(),
            'assets'            => $this->getOption('assets'),
            'vendor_assets'     => $this->getOption('vendor_assets'),
            'title'             => $this->getTitle(),
            'menu'              => $this->getMenu(),
            'meta'              => $this->getMetas(),
        );
    }

    /**
     * Get a template file path (relative to `option['templates_dir']`)
     */
    public function getTemplate($name)
    {
        return (isset(self::$templates[$name]) ? self::$templates[$name] : '');
    }

// -------------------------
// Singleton
// -------------------------
    
    protected function init()
    {
        // default options & user options
        $this->setOptions( array_merge(self::$default_options, $this->user_options) );

        // user arguments
        foreach($this->args as $name=>$value) {
            if (array_key_exists($name, self::$options)) {
                $this->setOption($name, $value);
            }
        }

        // the demo page template
        if (null===$this->getOption('template')) {
            $this->setOption('template', self::$templates['demo_page']);
        }

        if ($this->isConfigured()) {
            // check directories for locators
            $this->setOption('root_dir', slashDirname( $this->locateDirectory( $this->getOption('root_dir') ) ));
            $this->setOption('dir', slashDirname( $this->locateDirectory( $this->getOption('dir') ) ));

            // demo entity
            $demo_file = $this->locateFile( $this->getOption('demo_config') );
            $this->setDemo( new DemoManifest( new ConfigFile( $demo_file ) ) );

            // manifest entity
            $manifest_file = $this->locateFile( $this->getOption('manifest') );
            $this->setPackage( new PackageManifest( new ConfigFile( $manifest_file ) ) );

            // pages entity (directory)
            $demo_pages_dir = $this->locateDirectory( $this->getOption('dir') );
            $this->setPagesDirectory( new DemoDirectory( $demo_pages_dir ) );
        
            // router
            $this->buildRoutes();

        } else {
            // manifest entity
            $manifest_file = $this->locateFile( self::$MANIFEST );
            $this->setPackage( new PackageManifest( new ConfigFile( $manifest_file ) ) );
        }

        // template engine
        $views_dir = $this->locateDirectory( $this->getOption('templates_dir') );
        $loader = new \Twig_Loader_Filesystem( $views_dir );
        $this->twig = new \Twig_Environment($loader, array(
            'cache'             => $this->locateDirectory( $this->getOption('cache') ),
            'charset'           => $this->getOption('charset'),
            'debug'             => $this->getOption('debug'),
//            'strict_variables'  => $this->getOption('debug'),
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addExtension(new \DemoBuilder_Twig_Extension());
    }

// -------------------------
// RouterInterface
// -------------------------
    
    /**
     * Build the possible routes
     */
    public function buildRoutes()
    {
        foreach($this->getPagesDirectory() as $path=>$item) {
            if ($this->getPagesDirectory()->hasChildren()) {
                $this->routes[$item->getHumanReadableFilename()] = str_replace($this->getOption('dir'), '', $path);
            }
        }
    }
    
    /**
     * Check if a route exists
     */
    public function routeExists($route)
    {
        return (array_key_exists($route, $this->routes) || in_array($route, $this->routes));
    }

    /**
     * Get the current route requested
     */
    public function getRoute()
    {
        return (isset($this->args['page']) ? $this->args['page'] : $this->getOption('page'));
    }

    /**
     * Build a new route URL
     */
    public function getRouteUrl($page, $hash=null)
    {
        $filename = str_replace($this->getOption('dir'), '', $page);
        if ($filename==='index') {
            return _ROOTFILE.(!is_null($hash) ? '#'.$hash : '');
        } else {
            return _ROOTFILE.'?page='.$filename.(!is_null($hash) ? '#'.$hash : '');
        }
    }

    /**
     * Is the request an internal page
     */
    public function isBuilderRoute()
    {
        return ('db:'===substr($this->getOption('page'), 0, 3));
    }

    /**
     * Get the current route requested
     */
    public function getBuilderRoute()
    {
        return (isset($this->args['db']) ? $this->args['db'] : null);
    }

    /**
     * Build an internal route URL
     */
    public function getBuilderRouteUrl($page)
    {
        return _ROOTFILE.'?db='.$page;
    }

// -------------------------
// WebPageInterface
// -------------------------
    
    /**
     * Build the page menu
     */
    public function buildMenu()
    {
        if (null!==$this->getPagesDirectory()) {
            foreach($this->getPagesDirectory() as $path=>$item) {
                if ($this->getPagesDirectory()->hasChildren()) {
                    $this->menu[$path] = $item;
                }
            }
        }
    }

    /**
     * Get the page menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Build the page title
     */
    public function buildTitle()
    {
    }

    /**
     * Get the page title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Build the page metas
     */
    public function buildMetas()
    {
    }

    /**
     * Get the page metas
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Get one page meta
     */
    public function getMeta($name)
    {
        return (isset($this->metas[$name]) ? $this->metas[$name] : null);
    }

// -------------------------
// DemoBuilderInterface
// -------------------------
    
    /**
     * Is the demo configured enough to build pages ?
     */
    public function isConfigured()
    {
//    return true;
//    return false;
        return !(null===$this->getOption('dir') || null===$this->getOption('root_dir') || null===$this->getOption('manifest'));
    }
    
    /**
     * Set the package entity
     */
    public function setPackage(PackageManifest $manifest)
    {
        return $this->package = $manifest;
    }

    /**
     * Get the package entity
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Get a configuration value from the package
     */
    public function getPackageValue($name)
    {
        if (null===$this->getPackage()) return null;
        return ($this->getPackage()->offsetExists($name) ? $this->getPackage()->offsetGet($name) : null);
    }

    /**
     * Set the demo entity
     */
    public function setDemo(DemoManifest $manifest)
    {
        return $this->demo = $manifest;
    }

    /**
     * Get the demo entity
     */
    public function getDemo()
    {
        return $this->demo;
    }

    /**
     * Get a configuration value from the demo
     */
    public function getDemoValue($name)
    {
        if (null===$this->getDemo()) return null;
        return ($this->getDemo()->offsetExists($name) ? $this->getDemo()->offsetGet($name) : null);
    }

    /**
     * Set the pages directory entity
     */
    public function setPagesDirectory(DemoDirectory $directory)
    {
        return $this->pages_directory = $directory;
    }

    /**
     * Get the pages directory entity
     */
    public function getPagesDirectory()
    {
        return $this->pages_directory;
    }

    /**
     * Get a configuration value from the pages directory
     */
    public function getPagesDirectoryValue($name)
    {
        return null;
    }

    /**
     * Set the current page entity
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get the current page entity
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get a configuration value from the current page
     */
    public function getPageValue($name)
    {
        if (null===$this->getPage()) return null;
        return ($this->getPage()->offsetExists($name) ? $this->getPage()->offsetGet($name) : null);
    }

    /**
     * Get a configuration value from the current page or the whole demo configuration
     */
    public function getPageOrDemoValue($name)
    {
        if (null===$this->getPage() || null===$this->getDemo()) return null;
        if ($this->getPage()->offsetExists($name)) {
            return $this->getPage()->offsetGet($name);
        } elseif ($this->getDemo()->offsetExists($name)) {
            return $this->getDemo()->offsetGet($name);
        }
        return null;
    }

    /**
     * Get a configuration value from the demo or the package
     */
    public function getDemoOrPackageValue($name)
    {
        if (null===$this->getDemo() || null===$this->getPackage()) return null;
        $demo_value = $this->getDemo()->{$name};
        if (!is_null($demo_value) && (!isset(DemoManifest::$defaults[$name]) || $demo_value!==DemoManifest::$defaults[$name])) {
            return $demo_value;
        } elseif ($this->getPackage()->offsetExists($name)) {
            return $this->getPackage()->offsetGet($name);
        }
        return null;
    }

    /**
     * Special system locators : must return the found path or throw an exception
     */
    public function locate($path, $must_exists = true)
    {
        $file = $path;
        if (file_exists($path)) {
            return $path;
        } elseif (file_exists(slashDirname(_ROOTDIR).$path)) {
            return slashDirname(_ROOTDIR).$path;
        } elseif (file_exists(_SRC.$path)) {
            return _SRC.$path;
        } else {
            $path = $this->getOption('root_dir').$file;
            if (file_exists($path)) {
                return $path;
            } else {
                $path = $this->getOption('root_dir').$this->getOption('dir').$file;
                if (file_exists($path)) {
                    return $path;
                } elseif (true===$must_exists) {
                    throw new InvalidArgumentException(
                        sprintf('File "%s" not found (searched in directory "%s" and root directory "%s")!', 
                            $file, $this->getOption('dir'), $this->getOption('root_dir'))
                    );
                }
            }
        }
        return null;
    }

    public function locateDirectory($path, $must_exists = true)
    {
        $path = $this->locate($path, $must_exists);
        if (!is_null($path)) {
            if (is_dir($path)) {
                return $path;
            } elseif (true===$must_exists) {
                throw new InvalidArgumentException(
                    sprintf('Path "%s" is not a directory!', $path)
                );
            }
        }
    }
    
    public function locateFile($path, $must_exists = true)
    {
        $path = $this->locate($path, $must_exists);
        if (!is_null($path)) {
            if (!is_file($path)) {
                throw new Exception(
                    sprintf('Path "%s" is not a file!', $path)
                );
            }
            if (!is_readable($path)) {
                throw new Exception(
                    sprintf('File "%s" is not readable!', $path)
                );
            }
        }
        return $path;
    }

// -------------------------
// ActionsInterface
// -------------------------
    
    /**
     * The demo page view builder
     */
    public function pageAction()
    {
        $arg_config_file = $arg_directory = null;

        // current page directory
        $dir = $this->locateDirectory( $this->getOption('dir').$this->getOption('page') );
        $arg_directory = new DemoPageDirectory( $dir );

        // current page config file ?
        $cf = $this->locateFile( slashDirname($dir).$this->getOption('demo_config'), false );
        if (!is_null($cf)) {
            $arg_config_file = new ConfigFile($cf);
        }
    
        // current page entity
        $this->setPage( new Page( $arg_config_file, $arg_directory ) );

        $this->render( $this->getOption('template') );
    }

    /**
     * The not foud page view builder
     */
    public function notFoundAction()
    {
        $this->setOption('page', 'db:not_found');
        $this->setPage( new Page() );
        $not_found_tpl = self::$templates['not_found'];
        $this->render( $not_found_tpl );
    }

    /**
     * The configuration form page view builder
     */
    public function indexAction()
    {
        $this->setOption('page', 'db:config');

        // current page entity
        $this->setPage( new Page() );
        $this->getPage()->title = 'Configuration';

        $configuration_tpl = self::$templates['configuration'];
        $this->render( $configuration_tpl );
    }

}

// Endfile