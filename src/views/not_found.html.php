<?php
if (!isset($package) || !isset($demo) || !isset($page)) return 'ERROR IN RENDERING';
?><!DOCTYPE html>
<html lang="<?php echo $page->language; ?>">
<head>
    <meta charset="<?php echo $page->charset; ?>">
    <title>Not Found - <?php
        echo $package->title;
    ?></title>
    <meta name="robots" content="none">

    <?php echo renderView('metas'); ?>

    <link href="<?php echo $vendor_assets; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets; ?>styles.css" rel="stylesheet">
    <link href="<?php echo $vendor_assets; ?>bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="<?php echo $vendor_assets; ?>html5shiv/html5shiv.js"></script>
    <![endif]-->

    <link href="<?php echo $assets; ?>demobuilder_styles.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        
        <!--[if lt IE 7]>
            <div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Warning!</h4>
                <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
            </div>
        <![endif]-->

        <div class="demobuilder-wrapper">
            <header id="top" role="banner">
            <hgroup>
                <div class="page-header">
                    <h1>Page not found <span>:(</span></h1>
                </div>
            </hgroup>
            </header>

            <div class="hero-unit">
                <p>Sorry, but the page you were trying to view does not exist.</p>
                <p>It looks like this was the result of either:</p>
                <ul>
                    <li>a mistyped address</li>
                    <li>an out-of-date link</li>
                </ul>
            </div>
        </div>

        <?php echo renderView('footer'); ?>

    </div>

    <div id="message_box" class="msg_box"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $vendor_assets; ?>jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script>$.uiBackCompat = false;</script>
<script src="<?php echo $vendor_assets; ?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $assets; ?>scripts.js"></script>
<script>
$(function() {
    addCSSValidatorLink('perso/styles.css');
    addHTMLValidatorLink();
});
</script>
</body>
</html>
