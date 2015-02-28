<?php

// include the demo-builder-library
if (file_exists($lib = __DIR__.'/demo-builder-lib.php')) {
    require_once $lib;
} else {
    die( sprintf('Demo-Builder library not found at "%s"!', $lib) );
}

// the base directory
if (!defined('DEMO_BASEDIR')) {
    define('DEMO_BASEDIR', isset($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) : __DIR__.'/../');
}
settings('app_name', md5(DEMO_BASEDIR));

// config.json
if (file_exists($cfg = __DIR__.'/config.json')) {
    $config = load_config($cfg);
    settings($config);
} else {
    die( sprintf('Demo-Builder default config not found at "%s"!', $cfg) );
}

// defaults
$settings = load_config(get_demo_builder_file_path(settings('default_config_filename')));
settings($settings);
//settings('hqt_settings', $settings['hqt_settings']);

// MDE defaults
$mde_settings = load_config(get_demo_builder_file_path(settings('md_config_filename')));
settings('mde_settings', $mde_settings);

// load config
load_user_config();
$cfg = settings('user_config');

// request args
if (!IS_CLI) {
    settings(array(
        'arg_ln'        => isset($_GET['ln'])       ? $_GET['ln']       : settings('default_language'),
        'arg_page'      => isset($_GET['page'])     ? $_GET['page']     : settings('default_page'),
        'arg_action'    => isset($_GET['action'])   ? $_GET['action']   : null,
    ));
}

// distribute
if (is_null($action = settings('arg_action'))) {
    $content = load_page(settings('arg_page'));
    echo $content;
} else {
    switch ($action) {
        case 'template':
            $path = settings('output_path');
            if (empty($path)) {
                $path = get_cache_path(settings('mde_template_filename'));
            }
            $unlinked = file_exists($path) ? unlink($path) : true;
            if ($unlinked && prepare_markdown_template($path)) {
                ok(
                    sprintf('template file created at "%s"', $path)
                );
            } else {
                error(
                    sprintf('template file NOT created! (check user rights on file "%s")', $path)
                );
            }
            break;
        case 'flush':
            if (flush_cache()) {
                ok(
                    sprintf('app-cache has been flushed at "%s"', settings('temporary_directory'))
                );
            } else {
                error(
                    sprintf('app-cache has NOT been flushed! (check user rights on directory "%s")',
                        settings('temporary_directory'))
                );
            }
            break;
        case 'debug':
            $content = load_page(settings('arg_page'));
            header('Content-Type: text/plain');
            var_export(settings());
            exit('-- debug --');
            break;
        default: echo sprintf('Unknown action "%s"!', $action); break;
    }
}
exit();
