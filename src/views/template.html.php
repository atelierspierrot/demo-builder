<?php
if (!isset($package) || !isset($demo) || !isset($page)) return 'ERROR IN RENDERING';
?><!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="<?php echo $charset; ?>">
    <title><?php
        if (!empty($page->title)) echo $page->title.' - ';
        echo $package->title;
    ?></title>

    <?php echo renderView('metas'); ?>

    <link href="<?php echo $vendor_assets; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets; ?>styles.css" rel="stylesheet">
    <link href="<?php echo $vendor_assets; ?>bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="<?php echo $vendor_assets; ?>html5shiv/html5shiv.js"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#inpage_menu">

    <div class="navbar navbar-inverse navbar-fixed-top">

        <div class="navbar-inner">
            <?php echo renderView('demo_page_navbar'); ?>
        </div>

    </div>

    <div class="container-fluid">
        <div class="row-fluid">

            <!-- column -->
            <div class="span3">
            <nav>
                <div class="well sidebar-nav affix" data-spy="affix-top">
                    <!-- demo menu -->
                    <ul id="navigation_menu" class="nav nav-list" role="navigation">
                        <li><span class="label">Demo Map</span></li>
<?php foreach($page->menu as $path=>$item) : ?>
    <?php if ($item->getBasename()==='index') : /* write home first */ ?>
                        <li><a href="<?php echo getRouteUrl($path); ?>">Home</a></li>
    <?php endif; ?>
<?php endforeach; ?>
<?php foreach($page->menu as $path=>$item) : ?>
    <?php if ($item->getBasename()!=='index') : /* then others */ ?>
                        <li><a href="<?php echo getRouteUrl($path); ?>"><?php
                            echo $item->getHumanReadableFilename();
                        ?></a></li>
    <?php endif; ?>
<?php endforeach; ?>
<!--
                        <li><span class="label">Demo Map</span></li>
                        <li><a href="index.html">Homepage</a></li>
                        <li><a href="#">Examples</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Tests</a></li>
                        <li class="nav-header">Examples</li>
                        <li><a href="demo_html.html">Classic HTML example</a></li>
                        <li><a href="demo_php_standard.php">With PHP standard dir scanning</a></li>
                        <li><a href="demo_php_classes.php">With PHP internal plugin's classes</a></li>
                        <li><a href="demo_interface.php">With PHP plugin's interface</a></li>
                        <li><a href="standalone.html">Standalone (all HTML)</a></li>
                        <li class="nav-header">Documentation</li>
                        <li><a href="test_internal_php.php">PHP internal tools doc</a></li>
-->
                    </ul>

<?php if (true===$page->blocks['browser_info']) : ?>
                    <ul id="navigation_menu" class="nav nav-list" role="navigation">
                        <li class="divider"></li>
                        <li style="max-width:200px">
                            <span class="label">Browser Infos</span>
                            <p><small class="muted" id="user_agent" data-insert="user_agent"></small></p>
                        </li>
                    </ul>
<?php endif; ?>
                </div>
            </nav>
            </div>

            <!-- content -->
            <div class="span9">
        
                <!--[if lt IE 7]>
                    <div class="alert alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Warning!</h4>
                        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
                    </div>
                <![endif]-->

                <header id="top" role="banner">
                <hgroup>
                    <div class="page-header">
                        <h1><?php echo $package->title; ?>&nbsp;<small><?php echo $package->slogan; ?></small></h1>
                    </div>
                </hgroup>
<?php if (true===$page->blocks['header_description']) : ?>
                <div class="hero-unit">
                    <p><?php echo $page->header_description; ?></p>
                </div>
<?php endif; ?>
                </header>

<?php if (true===$page->blocks['sources'] || true===$page->blocks['manifest'] || true===$page->blocks['repository']) : ?>
    <div class="accordion" id="accordion_blocks">

    <?php if (true===$page->blocks['manifest']) : ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a id="manifest_handler" class="accordion-toggle" href="#manifest_handler" title="Infos extracted from your package version manifest">
                Full package manifest
                </a>
            </div>
            <div id="manifest_collapse" class="accordion-body collapse in">
                <div class="accordion-inner" id="manifest">
                    <ul class="list_infos" data-insert="manifestlist"></ul>
                    <p class="muted credits">Infos extracted from your current package manifest file: <span class="text-warning"><?php echo $manifest_url; ?></span>.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (true===$page->blocks['sources']) : ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a id="sources_handler" class="accordion-toggle" href="#sources_handler" title="Infos about the package sources">
                Follow sources
                </a>
            </div>
            <div id="sources_collapse" class="accordion-body collapse in">
                <div class="accordion-inner" id="sources">
                    <p>The sources of this package are hosted on <a href="http://github.com">GitHub</a>. To follow sources updates, report a bug or read opened bug tickets and any other information, please see the GitHub website above.</p>
                    <p><a href="<?php echo $package->sources['url']; ?>" class="btn">See online on <?php echo $package->sources['name']; ?> &raquo;</a></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (true===$page->blocks['repository']) : ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a id="github_handler" class="accordion-toggle" href="#github_handler" title="Infos extracted from the repository on GitHub.com">
                Sources repository current infos
                </a>
            </div>
            <div id="github_collapse" class="accordion-body collapse in">
                <div class="accordion-inner" id="github">
                    <strong>Last commits</strong>
                    <ul id="commits_list" data-insert="repo_commitslist"></ul>
                    <strong>Last bugs</strong>
                    <ul id="bugs_list" data-insert="repo_bugslist"></ul>
                    <p class="muted credits">Infos requested to the repository on GitHub.com: <span class="text-warning"><?php echo $package->sources['url']; ?></span>.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    </div>
<?php endif; ?>

<?php foreach ($page->sections_contents as $section) : ?>
                <section>
    <?php echo $section; ?>
                </section>
<?php endforeach; ?>

                <hr>
                <footer class="footnotes" role="contentinfo">
                    <div id="footnotes" style="display:none">
                        <h4>NOTES:</h4>
                        <ol id="footnotes_list"></ol>
                    </div>
                </footer>

                <footer class="footnotes" role="contentinfo">
                    <div id="glossaries" style="display:none">
                        <h4>GLOSSARY:</h4>
                        <ol id="glossaries_list"></ol>
                    </div>
                </footer>

                <footer class="footnotes" role="contentinfo">
                    <div id="todos" style="display:none">
                        <h4>TODOS:</h4>
                        <ol id="todos_list"></ol>
                    </div>
                </footer>

                <footer class="footnotes" role="contentinfo">
                    <div class="well well-small">
                        <p class="credits">Last update of this content: <time datetime="<?php
                            echo $page->update->format('Y-m-d');
                        ?>" title="<?php
                            echo $page->update->format('c');
                        ?>"><?php echo $page->update->format('M j, Y'); ?></time>.</p>
                    </div>
                </footer>

            </div>
        </div>

    <?php echo renderView('footer'); ?>

    </div>

    <div id="message_box" class="msg_box"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $vendor_assets; ?>jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script>$.uiBackCompat = false;</script>
<script src="<?php echo $vendor_assets; ?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $vendor_assets; ?>jquery/jquery.highlight.js"></script>
<script src="<?php echo $vendor_assets; ?>jquery/jquery.tablesorter.min.js"></script>
<script src="<?php echo $assets; ?>scripts.js"></script>
<script>
$(function() {
    initBacklinks();
    activateMenuItem();
    getToHash();
    buildFootNotes();
    buildGlossaryNotes();
    buildTodoNotes();
    addCSSValidatorLink('<?php echo $assets; ?>styles.css');
    addHTMLValidatorLink();
<?php if (true===$page->blocks['browser_info']) : ?>
    $("#user_agent").html( navigator.userAgent );
<?php endif; ?>
    initHighlighted('pre.code');
    initTablesorter('table.tablesorter');
    initInpageNavigation();
});
</script>
<script id="js_code">
$(function() {

<?php if (true===$page->blocks['manifest']) : ?>
    writeManifestInfos( '<?php echo $manifest_url; ?>' );
<?php endif; ?>

<?php if (true===$page->blocks['repository']) : ?>
    writeRepoInfos( '<?php echo slashDirname($package->sources['url']); ?>' );
<?php endif; ?>

});
</script>
</body>
</html>
