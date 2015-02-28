<?php

// include the PHPQuickLibrary if needed
if (!defined('PHPQLIB_NAME')) {
    if (file_exists($phpqlib = __DIR__.'/bin/php-quick-lib.php')) {
        require_once $phpqlib;
    } elseif (file_exists($phpqlib = __DIR__.'/../../bin/php-quick-lib.php')) {
        require_once $phpqlib;
    } else {
        die('PHP-Quick-Library not found!');
    }
}

// fit settings to your needs
//*//
//ini_set('display_errors', 0);
register_shutdown_function('shutdown_handler');
//set_error_handler('error_handler', E_ALL | E_STRICT);
set_exception_handler('exception_handler');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set('html_errors', 0);
if (function_exists('xdebug_disable')) {
    xdebug_disable();
}
//*/

function get_demo_builder_file_path($file_name)
{
    $_fp = realpath($file_name);
    if (!empty($_fp) && file_exists($_fp)) {
        return $_fp;
    } elseif (file_exists($local = get_path(DEMO_BASEDIR.'/'.$file_name))) {
        return $local;
    } elseif (file_exists($demo = get_path(__DIR__.'/'.$file_name))) {
        return $demo;
    } elseif (file_exists($vendor = get_path(__DIR__.'/../../'.$file_name))) {
        return $vendor;
    }
    return null;
}

function get_demo_url($page, $hash = null)
{
    $index  = settings('interface_filename');
    $req    = get_current_url();
    if (substr($req, -1)!='/') $req = dirname($req);
    return $req . '/' . $index . '?page=' . $page . (!is_null($hash) ? '#' . $hash  : '');
}

function load_user_config()
{
    $config         = load_config(settings('user_config_filename'));
    $settings       = settings();
    $hqt_settings   = $settings['hqt_settings'];
    // parse user config
    foreach ($settings['user_config_items'] as $item=>$var) {
        if (is_string($var) && strpos($var, ':')!==false) {
            list($subvar,$var) = explode(':', $var);
            if ($subvar=='hqt_settings') {
                $hqt_settings[$var] = isset($config[$item]) ? $config[$item] : '';
            } else {
                if (!isset($settings[$subvar])) {
                    $settings[$subvar] = array();
                }
                $settings[$subvar][$var] = isset($config[$item]) ? $config[$item] : '';
            }
        } elseif (is_string($var)) {
            $settings[$var] = isset($config[$item]) ? $config[$item] : '';
        }
    }
    // store all
    settings($settings);
    settings('user_config', $config);
    settings('hqt_settings', $hqt_settings);
    // special content header
    if (isset($settings['pre_content_closure']) && is_callable($settings['pre_content_closure'])) {
        settings('pre_content', callback($settings['pre_content_closure']));
    }
    // special treatment for the menu
    $menu = $config['pages'];
    foreach ($menu as $page=>$item) {
        if (!isset($item['url'])) {
            $menu[$page]['url'] = get_demo_url($page);
        }
        if (isset($item['items']) && !empty($item['items'])) {
            foreach ($item['items'] as $i=>$subitem) {
                if (!isset($subitem['url']) && isset($subitem['hash'])) {
                    $menu[$page]['items'][$i]['url'] = get_demo_url($page, $subitem['hash']);
                }
            }
        }
    }
    settings('menu', $menu);
}

function get_page_path($page_name)
{
    $page_config     = get_page_config($page_name);
    if (!empty($page_config)) {
        $pages_dir   = get_demo_builder_file_path(settings('pages_dirname'));
        $_pp         = get_path($pages_dir.'/'.(isset($page_config['page']) ? $page_config['page'] : $page_name));
        if (file_exists($_pp)) {
            return $_pp;
        }
    }
    return null;
}

function get_page_config($page_name)
{
    $cfg = settings('user_config');
    return (
        isset($cfg['pages']) &&
        isset($cfg['pages'][$page_name]) ?
            $cfg['pages'][$page_name] : null
    );
}

function load_page($page_name, array $params = array())
{
    $page_cfg   = get_page_config($page_name);
    $page_path  = get_page_path($page_name);
    if (!file_exists($page_path)) {
        return error_404($page_name, $params);
    }
    list($content, $template) = treat_file_by_extension($page_path, $params);
    if ($template) {
        settings('content',
            settings('pre_content', null, '').$content);
        settings('subtitle', $page_name);
        $content = load_template($params);
    }
    return $content;
}

function load_template(array $params = array())
{
    $params             = array_merge(settings(), $params);
    $params['settings'] = settings('hqt_settings');
    $hqt_template       = get_demo_builder_file_path(settings('html5_quick_template'));
    return get_view($hqt_template, $params);
}

function treat_file_by_extension($file_path, array $params = array())
{
    $exts_cfg   = settings('extensions_callbacks');
    $file_ext   = substr($file_path, -(strlen($file_path)-strrpos($file_path, '.')-1));
    $content    = null;
    $template   = false;
    if (array_key_exists($file_ext, $exts_cfg)) {
        if (substr_count($file_path, '.')>1) {
            $alt_fp     = substr($file_path, 0, (strlen($file_path)-strlen($file_ext)-1));
            $tmp_file   = get_cache_path(basename($alt_fp));
            if (is_cached($tmp_file, filemtime($file_path))) {
                return treat_file_by_extension($tmp_file, $params);
            }
        }
        $_cb = $exts_cfg[$file_ext];
        if (function_exists($_cb)) {
            list($content, $template) = callback($_cb, $file_path, $params);
        } else {
            throw new ErrorException(
                sprintf('Callback "%s" defined for extension type "%s" not found!', $_cb, $file_ext)
            );
        }
        if (substr_count($file_path, '.')>1) {
            set_cache($tmp_file, $content);
            return treat_file_by_extension($tmp_file, $params);
        }
    } else {
        throw new ErrorException(
            sprintf('Unknown page extension "%s"!', $file_ext)
        );
    }
    return array($content, $template);
}

function load_file_content($file_path, array $params = array())
{
    return array(get_view($file_path, $params), true);
}

function parse_markdown_file($file_path, array $params = array(), $mde_template_path = null)
{
    if (is_null($mde_template_path)) {
        $mde_template_path = get_cache_path(settings('mde_template_filename'));
    }
    prepare_markdown_template($mde_template_path);
    $params = array_merge(settings(), $params);
    extract($params);
    $mde_binary = get_demo_builder_file_path($mde_binary);
    $cmd = "$mde_binary --template=$mde_template_path $file_path";
    list($ctt, $status) = execute($cmd);
    if ($status==0) {
        return array(implode(PHP_EOL, $ctt), false);
    } else {
        throw new ErrorException(
            sprintf('An error occurred while trying to build markdown page view "%s": "%s"!',
                $file_path, implode('', $ctt))
        );
    }
}

function prepare_markdown_template($path = null)
{
    if (is_null($path)) {
        $path = get_cache_path(settings('mde_template_filename'));
    }
    if (
        !file_exists($path) ||
        filemtime($path)<filemtime(settings('user_config_filename'))
    ) {
        $html5_quick_template = get_demo_builder_file_path(settings('html5_quick_template'));
        $mde_opts = settings('mde_settings');
        $opts = array_merge(settings(), $mde_opts);
        $opts['settings'] = array_merge(settings('hqt_settings'), $mde_opts['hqt_settings']);
        $ctt = get_view($html5_quick_template, $opts);
        if (false===file_put_contents($path, $ctt)) {
            throw new ErrorException(
                sprintf('Cannot write template file "%s"!', $path)
            );
        }
    }
    return true;
}

function error_404($page_name = null, array $params = array())
{
    $_cb    = settings('404_content_closure');
    $_ctt   = callback($_cb, $page_name, $params);
    settings('content', $_ctt);
    $content = load_template($params);
    return $content;
}
