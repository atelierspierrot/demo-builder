<?php
// show errors at least initially
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);

// set a default timezone to avoid PHP5 warnings
$dtmz = date_default_timezone_get();
date_default_timezone_set( !empty($dtmz) ? $dtmz:'Europe/Paris' );

// paths settings
$ds = DIRECTORY_SEPARATOR;
$root_dir = __DIR__ . $ds . '..';
$assets_dir = __DIR__ . $ds;
$assets_relative_dir = 'www';
$document_root = __DIR__;
$cache_dir = __DIR__ . $ds . 'tmp';
$views_dir = $root_dir . $ds . 'src' . $ds . 'DemoBuilder' . $ds . 'views' . $ds;

// the Composer autoloader
if (file_exists($a = $root_dir.'/vendor/autoload.php')) {
    require_once $a;
} else {
    die('You need to run Composer on your project to use this interface!');
}

// the assets loader
$assets_loader = Assets\Loader::getInstance($root_dir, $assets_relative_dir, $document_root);

// the template engine
$template_engine = TemplateEngine\TemplateEngine::getInstance()
    ->guessFromAssetsLoader($assets_loader)
    ->setLayoutsDir($views_dir)
    ->setToTemplate('setCachePath', $cache_dir)
    ->setToTemplate('setAssetsCachePath', $cache_dir)
    ->setToView('setIncludePath', $views_dir)
    ;

// the controller
DemoBuilder\Controller::create()
    ->setAssetsLoader($assets_loader)
    ->setTemplateEngine($template_engine)
    ->parseRequest()
    ->distribute();

exit('ERROR IN RENDERING !');
// Endfile