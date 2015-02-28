jQuery AccessKey
================

## a jQuery plugin to manage accesskeys on a document model

These pages show and demonstrate the use and functionality of the [jQuery AccessKey plugin](http://github.com/piwi/jQuery-AccessKey)
you just downloaded, based on the [jQuery javascript library](http://jquery.com/).

You've downloaded the whole plugin's package ; to test it in your system, please see [the examples pages](classic.html) ;
to learn how to use it and the required files, please see [the usage page](doc.html#clientusage) ;
finally, a complete documentation is available in [the doc page](doc.html#doc).

If you encounter errors to view these pages, please see the [demo note](index.html#demo_note).


## Test with inline accesskeys

-   <a href="#link_A_url" id="link_A" tabindex="1" class="access-link" title="My title for first test link" accesskey="A">Test for accesskey "A"</a>
-   <a href="#link_B_url" id="link_B" tabindex="2" class="access-link" title="My title for second test link" accesskey="B">Test for accesskey "B"</a>
-   <a href="http://www.test.com/" id="link_C" tabindex="3" class="access-link" title="My title for third test link" accesskey="C">Test for accesskey "C"</a>


## Test with javascript defined accesskeys

-   <a id="link_D" title="My title for first test link" tabindex="4">Test for accesskey "D"</a>
-   <a href="#link_E_url" id="link_E" title="My title for second test link" tabindex="5">Test for accesskey "E"</a>
-   <a href="http://www.test.com/" id="link_F" title="My title for third test link" tabindex="6">Test for accesskey "F"</a>


## Test with accesskeys on form inputs

<form name="test_form_1" id="test_form_1" action="#" method="get">
    <fieldset>
        <label>Your name <input type="text" id="input_G" name="name" value="" accesskey="G" /></label>
        <label>Your mother's name <input type="text" id="input_H" name="mother_name" value="" /></label>
    </fieldset>
    <input type="submit" id="btn_I" accesskey="I" />
    <input type="reset" id="btn_J" accesskey="J" />
</form>


## Test with some click handlers

<a href="http://test.com/" id="test_clickHandler" onClick="console.debug('triggering onClick')">test clickHandler</a>

<a href="#href_link" id="test_clickHandler_bis" onClick="console.debug('triggering onClick')">test clickHandler with href following</a>


## Test with accesskeys in a text

Sed ut perspiciatis unde omnis <a href="" class="intext" accesskey="K">iste natus error</a>
sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
<a href="" class="intext" accesskey="L">Nemo enim ipsam voluptatem quia voluptas</a> sit
aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem
sequi nesciunt. Neque porro quisquam est, <a href="" class="intext" accesskey="M">qui dolorem
ipsum quia dolor sit amet</a>, consectetur, adipisci velit, sed quia non numquam eius modi
tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam,
quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi
consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil
molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?


## Test of plugin handlers

Plugin is currently <strong><span id="plugin_status"></span></strong>.
&nbsp;<input type="button" id="plugin_disable" onclick="disablePlugin();" value="Disable plugin" />
<input type="button" id="plugin_enable" onclick="enablePlugin();" value="Enable plugin" />

-   <a onclick="return $.fn.accesskey.toggleAll({showOptions: 'slow', hideOptions: 'slow'});">Toggle all tooltips</a>
-   <a onclick="return TooltipTest.accesskey('showAll', {showOptions: 'slow', hideOptions: 'slow'});">Show all tooltips of the first paragraph</a> (from first call of the plugin)
-   <a onclick="return TooltipTest.accesskey('hideAll', {showOptions: 'slow', hideOptions: 'slow'});">Hide all tooltips of the first paragraph</a> (from first call of the plugin)
-   <a onclick="return testOverview();">test plugin's overview</a>


<script id="js_code">
// activate plugin debug (infos are written in console)
$.fn.accesskey.defaults.debug = true;

// use the HELP tool
$.fn.accesskey.defaults.showHelp = true;

// declaration of the TooltipTest variable for the global window
var TooltipTest;

// on document ready call
$(function() {
    var UserConsole = $('#console');

    TooltipTest = $(".access-link")
        .accesskey({
            onCreate: function(){
                console.debug('"onCreate" user defined call, receiving args ', arguments);
            },
            onShow: function(){
                console.debug('"onShow" user defined call, receiving args ', arguments);
            },
            onHide: function(){
                console.debug('"onHide" user defined call, receiving args ', arguments);
            },
            onDestroy: function(){
                console.debug('"onDestroy" user defined call, receiving args ', arguments);
            }
        })
        .click(function(){
            var _name = $(this).attr('id');
            UserConsole.append('Link '+_name+' just clicked !');
        });

    // let's see what is our result object
    console.debug('TooltipTest= ', TooltipTest);

    var linkd = $("#link_D")
        .accesskey({
            accesskey: "D", text: "Option text from javascript call",
            propagateOnce: true
        })
        .click(function(){
            UserConsole.append('OK, click on link D triggered !');
        });

    // test of option retrieving
    console.debug('link_D at option "text" has value: ', linkd.accesskey('getOption', 'text'));

    console.debug('link_D accesskey data: ', linkd.accesskey('getData'));

    $("#link_E")
        .accesskey({
            accesskey: "E", text: "Option text from javascript call",
            showOptions: "slow", hideOptions: "slow"
        })
        .click(function(){
            UserConsole.append('OK, click on link D triggered !');
        });

    $("#input_G")
        .accesskey({
            accesskey: "G", text: "Typing Ctrl+Al+G will select this field"
        });
/*
// error because duplicate accesskey
    $("#input_H")
        .accesskey({
            accesskey: "H", text: "This will send an error as the 'H' accesskey is reserved for plugin help"
        });
*/
    $("#input_H")
        .accesskey({
            accesskey: "Z", text: "This will send an error as the 'H' accesskey is reserved for plugin help"
        });

    // simple call
    $(".intext").accesskey();

    $('#test_form_1').submit(function(){
        UserConsole.append('formSubmit OK, get data :'+$(this).serialize());
        return false;
    });

    // special on/off plugin
    if ($.fn.accesskey.isEnabled()) { enablePlugin(); }
    else { disablePlugin(); }

    // test of "click" event on accesskey press
    // : all events below may be triggered but href must NOT be followed
    $('#test_clickHandler').accesskey({
        accesskey: 'R',
        text: 'Press "Ctrl+Alt+R to access this link',
        onClick: 'follow_href'
    });
    $('#test_clickHandler').click(function(){
        console.debug('triggering click()');
        if ($.fn.accesskey.isEnabled()) return false;
    });
    $('#test_clickHandler').on('click', function(){
        console.debug('triggering on(click)');
        if ($.fn.accesskey.isEnabled()) return false;
    });
    $('#test_clickHandler').bind('click', function(){
        console.debug('triggering bind(click)');
        if ($.fn.accesskey.isEnabled()) return false;
    });

    // test of "click" event on accesskey press
    // : all events below may be triggered but href must NOT be followed
    $('#test_clickHandler_bis').accesskey({
        accesskey: 'S',
        text: 'Press "Ctrl+Alt+S to access this link',
        onClick: 'follow_href'
    });
    $('#test_clickHandler_bis').click(function(){
        console.debug('triggering click()');
    });
    $('#test_clickHandler_bis').on('click', function(){
        console.debug('triggering on(click)');
    });
    $('#test_clickHandler_bis').bind('click', function(){
        console.debug('triggering bind(click)');
    });

});

function testOverview() {
    var tbl = $.fn.accesskey.overview();
    console.debug(tbl);
}

function enablePlugin() {
    if ($.fn.accesskey.enable()) {
        $('#plugin_status').html('enabled');
        $('#plugin_enable').hide();
        $('#plugin_disable').show();
    }
}

function disablePlugin() {
    if ($.fn.accesskey.disable()) {
        $('#plugin_status').html('disabled');
        $('#plugin_enable').show();
        $('#plugin_disable').hide();
    }
}

</script>
