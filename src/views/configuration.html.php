<?php
if (!isset($package) || !isset($page)) return 'ERROR IN RENDERING';
?><!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="<?php echo $charset; ?>">
    <title><?php
        if (!empty($page->title)) echo $page->title.' - ';
        echo $package->title;
    ?></title>

    <?php echo renderView('metas', array('page'=>$page)); ?>

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

          <form class="form-horizontal form-config" action="<?php echo _ROOTFILE; ?>" method="post">

            <div class="text-right form-config-header">
                <a class="popoverable_bottom" href="#" onclick="testDoc();" title="DemoBuilder's DemoBuilder" data-content="See the DemoBuilder's documentation built with itself.">See the DemoBuilder's DemoBuilder</a>
            </div>

            <h2 class="form-config-heading">DemoBuilder configuration</h2>
        
            <fieldset>

                <div class="control-group">
                    <label class="control-label" for="root_directory">Root package directory</label>
                    <div class="controls">
                        <input type="text" class="span4 popoverable" placeholder="Root directory" id="root_directory" name="root_directory" title="Your root package directory" data-content="This is the global absolute path to your package, containing the directory below. This directory must exist." required>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="directory">Demo pages directory</label>
                    <div class="controls">
                        <input type="text" class="span4 popoverable" placeholder="Base documentation directory" id="directory" name="directory" title="Your demo pages directory" data-content="This is the demo pages relative path of your package, constructed from the root directory above. This directory must exist." required>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="manifest">Package manifest</label>
                    <div class="controls">
                        <div class="input-append">
                            <input type="text" class="input-block-level span4 popoverable" placeholder="Package manifest" id="manifest" name="manifest" title="Your package manifest" data-content="The manifest file of your package, as a jquery or composer manifest. This file must exist and can be either a JSON, PHP or INI file." required>
                            <div class="btn-group">
                                <button class="btn dropdown-toggle btn-config" data-toggle="dropdown">Presets</button>
                                <button class="btn dropdown-toggle btn-config" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="#" onclick="insertManifest('composer.json');" title="Set this value on the 'composer.json' manifest file at the root directory of your package">composer.json</a></li>
                                    <li><a tabindex="-1" href="#" onclick="insertManifest('jquery.json');" title="Set this value on the 'jquery.json' manifest file at the root directory of your package">jquery.json</a></li>
                                    <li><a tabindex="-1" href="#" onclick="insertManifest('manifest.json');" title="Set this value on 'manifest.json' file at the root directory of your package">manifest.json</a></li>
                                    <li><a tabindex="-1" href="#" onclick="insertManifest('manifest.php');" title="Set this value on 'manifest.php' file at the root directory of your package">manifest.php</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="demo_config">Configuration filename</label>
                    <div class="controls">
                        <div class="input-append">
                            <input type="text" class="input-block-level span4 popoverable" placeholder="Demo manifest" id="demo_config" name="demo_config" title="Your demo pages configuration filename" data-content="The configuration filename used in your package's demo. This file must exist at the root of the demo pages but is not required for each page and can be either a JSON, PHP or INI file." required>
                            <div class="btn-group">
                                <button class="btn dropdown-toggle btn-config" data-toggle="dropdown">Presets</button>
                                <button class="btn dropdown-toggle btn-config" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="#" onclick="insertDemoConfig('demo.config.json');" title="Set this value on the default 'demo.config.json' file">demo.config.json</a></li>
                                    <li><a tabindex="-1" href="#" onclick="insertDemoConfig('demo.config.php');" title="Set this value on the default 'demo.config.php' file">demo.config.php</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <a href="#" class="toggler" data-toggle="collapse" data-target="#more_options" title="Define other configuration options">+ More Configuration Options</a>
            <div id="more_options" class="collapse">
            <fieldset class="second">

                <div class="control-group">
                    <label class="control-label" for="template">Global template file</label>
                    <div class="controls">
                        <input type="text" class="span4" placeholder="Template file path" id="template" name="template" value="<?php echo $DB->getOption('template'); ?>">
                        <span class="help-block muted"><small>This is the file used to build the HTML view of each demo page. This file must exist.</small></span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="charset">Pages charset</label>
                    <div class="controls">
                        <input type="text" class="span4" placeholder="Pages charset" id="charset" name="charset" value="<?php echo $charset; ?>">
                        <span class="help-block muted"><small>This is the HTML charset used for demo pages. This value must be <a href="http://www.unicode.org/standard/standard.html">Unicode</a> compliant.</small></span>
                    </div>
                </div>

            </fieldset>
            </div>

            <br />
            <div class="text-right">
                <button class="btn btn-large btn-primary" type="submit">Submit</button>
                &nbsp;
                <button class="btn btn-large" type="reset">Cancel</button>
            </div>
          </form>

        </div>

        <?php echo renderView('footer'); ?>

    </div> <!-- /container -->

    <div id="message_box" class="msg_box"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $vendor_assets; ?>jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script>$.uiBackCompat = false;</script>
<script src="<?php echo $vendor_assets; ?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $assets; ?>scripts.js"></script>
<script>
function insertManifest(str)
{
    $('#manifest').val(str);
}
function insertDemoConfig(str)
{
    $('#demo_config').val(str);
}
$(function() {
    addCSSValidatorLink('perso/styles.css');
    addHTMLValidatorLink();

    $(".popoverable").popover({
        placement: "top",
        toggle: "popover",
        trigger: "hover"
    });
    $(".popoverable_bottom").popover({
        placement: "bottom",
        toggle: "popover",
        trigger: "hover"
    });

});
</script>
</body>
</html>
