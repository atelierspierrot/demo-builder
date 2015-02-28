<?php

// MDE template defaults
$config                   = array();
$config['hqt_settings']   = array();

// MDE version
$mde_version = function(){ return exec(settings('mde_binary').' -qV'); };

$config['update'] = '{% DATE %}';
$config['title'] = false;
$config['content'] = function() { return settings('pre_content', null, '').'{% BODY %}'; };
$config['toc'] = '{% TOC %}';
$config['notes'] = '{% NOTES %}';
$config['metas'] = '{% META %}';

// template settings
$config['hqt_settings']['charset'] = '{% CHARSET %}';
$config['hqt_settings']['language_strings'] = array();
$config['hqt_settings']['language_strings']['toc_block_header'] = '';
$config['hqt_settings']['language_strings']['notes_block_header'] = '';
$config['hqt_settings']['profiler_user_stack'] = array(
    'MDE' => $mde_version
);
/*
$config['hqt_settings']['app_dependencies'] = function() {
    $hqt_settings = settings('hqt_settings');
    return array_unshift($hqt_settings,
        array('name'=>'PHP-MarkdownExtended', 'version'=>$mde_version, 'home'=>'http://github.com/piwi/markdown-extended', 'license'=>'Apache 2.0 license', 'license_url'=>'http://www.apache.org/licenses/LICENSE-2.0.html')
    );
};
*/
