<?php
// show errors at least initially
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);

// set a default timezone to avoid PHP5 warnings
$dtmz = @date_default_timezone_get();
@date_default_timezone_set( !empty($dtmz) ? $dtmz:'Europe/Paris' );

// the required files
define('_MODE', 'dev');
define('_ROOTDIR', __DIR__);
define('_ROOTFILE', basename(__FILE__));
define('_SRC', __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR);
define('_VENDORSRC', _SRC.'vendor'.DIRECTORY_SEPARATOR);
$composerAutoLoader = _VENDORSRC.'autoload.php';
if (@file_exists($composerAutoLoader)) {
    require_once $composerAutoLoader;
} else {
    die("You need to run Composer on the project to build dependencies and auto-loading (see: <a href=\"http://getcomposer.org/doc/00-intro.md#using-composer\">http://getcomposer.org/doc/00-intro.md#using-composer</a>)!");
}
require_once _SRC.'library.php';

// arguments settings
$args = array();
/*
// User defined options passed to constructor
$args['dir'] = isset($_GET['dir']) ? $_GET['dir'] : 'samples';
$args['root_dir'] = isset($_GET['root']) ? $_GET['root'] : __DIR__;
$args['manifest'] = isset($_GET['manifest']) ? $_GET['manifest'] : __DIR__.'/samples/sample.composer.json'; // sample.jquery.ini sample.composer.php sample.jquery.json
$args['demo_config'] = isset($_GET['config']) ? $_GET['config'] : 'demo.config.php'; // demo.config.ini demo.config.php demo.config.json
$args['i'] = isset($_GET['i']) ? $_GET['i'] : 0;
$args['ln'] = isset($_GET['ln']) ? $_GET['ln'] : 'en';
//$args['template'] = isset($_GET['template']) ? $_GET['template'] : 'template.html.php';
*/

// building the whole thing
$demo = \DemoBuilder\Controller::getInstance( $args );
if ($demo) $demo->distribute();

// error if nothing rendered
exit('ERROR IN RENDERING');

// Endfile