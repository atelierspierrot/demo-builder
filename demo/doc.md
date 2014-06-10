jQuery AccessKey
================

## a jQuery plugin to manage accesskeys on a document model

These pages show and demonstrate the use and functionality of the [jQuery AccessKey plugin](http://github.com/piwi/jQuery-AccessKey)
you just downloaded, based on the [jQuery javascript library](http://jquery.com/).

You've downloaded the whole plugin's package ; to test it in your system, please see [the examples pages](classic.html) ;
to learn how to use it and the required files, please see [the usage page](doc.html#clientusage) ;
finally, a complete documentation is available in [the doc page](doc.html#doc).

If you encounter errors to view these pages, please see the [demo note](index.html#demo_note).


## Technical informations

### Compatibility

The plugin was developed under **jQuery 1.9** and (*for now*) had not been tested on older versions.

The plugin functionalities should be compatible with any web browser on any operating system.

If you find any bug or mis-compatibility, please post a message on website
[http://github.com/piwi/jQuery-AccessKey/issues](http://github.com/piwi/jQuery-AccessKey/issues)
with a description of the error in code or rendering. A test suite using [jQuery QUnit](http://api.qunitjs.com/)
is [proposed in this package](test_suite.html).

### Condensed summary of specifications

As we can read in the [HTML4's W3C Recommendations](http://www.w3.org/TR/html401/interact/forms.html#h-17.11.2),
the `accesskey` attribute can be used on the following tags [^1]:

[^1]: I based this work on the W3C [UUAG Test Suite](http://www.w3.org/WAI/UA/TS/html401/Overview.html).

-   [a](http://www.w3.org/TR/html401/struct/links.html#edef-A) : the link tag, for which using
    the accesskey may ask the user agent to follow the link (*follow the `href` attribute*),
-   [area](http://www.w3.org/TR/html401/struct/objects.html#edef-AREA) : for which using
    the accesskey may run the area's action,
-   [button](http://www.w3.org/TR/html401/interact/forms.html#edef-BUTTON) : for which using
    the accesskey may trigger a button's click,
-   [input](http://www.w3.org/TR/html401/interact/forms.html#edef-INPUT) : for which using
    the accesskey may select or focus the form field,
-   [label](http://www.w3.org/TR/html401/interact/forms.html#edef-LABEL) : for which using
    the accesskey may give the focus to the label, which gives it to its field (*through its `for` attribute*),
-   [legend](http://www.w3.org/TR/html401/interact/forms.html#edef-LEGEND) : for which using
    the accesskey may give the focus to the concerned fieldset (*there, the specifications
    are not very clear : the best practice may be to give the focus to the first fieldset's field*),
-   [textarea](http://www.w3.org/TR/html401/interact/forms.html#edef-TEXTAREA) : for which using
    the accesskey may give the focus to the field.

But the [HTML5's W3C Recommendations](http://www.w3.org/TR/html5/editing.html) do not restrict
the "accesskey" usage allowing its definition for any element.

Another difference is in the way to use the attribute : HTML4 says you must define the
`accesskey` as a single character while HTML5 allows a list of characters, separated by space,
where user agent must choose the first one available on the current user's device.

Be careful when developing about these usage differences between the HTML4 and HTML5 specifications.

### Plugin implementations

The plugin takes the HTML5 **"all elements" unrestraint rule** while it (*for now*) **do not
allow to assign a list of accesskeys** but a single character, as in HTML4 (*take a look at
the [Todo section](#todo) of this documentation to learn more*).

As it seems to be the case in many operating systems, plugin considers any pressed key as
a capital letter, even if the system finally attaches a lowercase key character to it. Be very
careful at this point : it restricts available accesskeys to `[A-Z]` non-special uppercase
characters and `[0-9]` numeric characters.

Furthermore, the default behavior of pressing an access-key constructed by the plugin depends
on the concerned element type. These default behaviors are listed below ; note that you can
overwrite them defining the `onClick` option value for each instances.

-   for a tag containing an `href` attribute (*such as `a` and `area` elements*), a click
    is triggered so the user agent is requested to follow the `href` value ; this can be
    forced using the `follow_href` value of option `onClick`.
-   for a tag representing a form input field, the field is "selected" (*the user can directly enter a value*) ;
    this means that input type `checkbox` or `radio` are toggled (*`checked` if they were not
    or `unchecked` if they were checked*) using the accesskey ; this can be forced using
    the `select` value of option `onClick`.
-   for a tag representing a button, it is "selected" and it's action is toggled ; this can
    be forced using the `submit` value of option `onClick` ; be very careful here: **this is the default
    plugin's behavior for "submit" or "reset" buttons or inputs**, which means that
    the concernend form will be submitted or reset.
-   for a label tag of a form field or button, the corresponding field is "focused" and that's it ;
    field's action is NOT toggled ; this can be forced using the `focus` value of option `onClick`.


## Usage for client users

### First thoughts

As described in [this page of Wikipedia](Wikipedia page for 'accesskeys'|http://en.wikipedia.org/wiki/Access_key),
the required combination of keys pressed to finaly access the accesskey of an element largely
differs between browsers and operating systems. Let's say in most cases, Windows users have
to press `Ctrl` key first and Mac OSX users have to press `Alt` key first. In some cases,
the `Shift` key is also required or a combination of the two keys `Ctrl + Alt`.

To build this plugin, I first let these default behaviors by their own, testing the examples
by pressing the OS/browser specific required keys first. Then I realize that it was finally
part of the reasons why accesskeys are considered by most of the concerned people as a failure
in accessibility recommandations.

Indeed, it seems logical that the users do not have the habit of a behavior that would be
specific to each software and environnement ... Imagine that every store where you shop
accepts a different currency, you would be lost!

So, regarding what OS or softwares does with the common shortcuts, I decided to choose a unique
and uniform keys combination, making the usability of the plugin cross-platform and cross-browser
and allowing final user to start construct its navigation habit.

### And the winner is ...

In conclusion, I rebuilt the plugin code to work with a specific and unique keys combination
to call an accesskey, with some customizable options to allow developers to use their own [^2].

[^2]: Well, this is part of the *work to do* ... allowing developer to really fit this behavior to its need. Any contribution is very welcome ;)

By default, the plugin works with invokation like:

<div class="info" style="text-align: center; font-weight: bold;">
`Alt` key + `Ctrl` key + `AccessKey`
</div>

## Usage for developers

### Requirements

As it depends on **jQuery**, you must include the library first, as for any plugin.

Then, the only required file is the javascript code. Some CSS definitions are proposed with
the plugin, that are required only if you use the default plugin's tooltips setup.

~~~~code:html
&lt;!-- jQuery (required) --&gt;
&lt;script src="../js/jquery-1.9.0.js"&gt;&lt;/script&gt;

&lt;!-- jQuery AccesKey (required) --&gt;
&lt;script src="../src/jquery-accesskey.js"&gt;&lt;/script&gt;

&lt;!-- jQuery AccesKey default CSS (optional) --&gt;
&lt;link rel="stylesheet" href="../src/jquery-accesskey.css" type="text/css" media="screen" /&gt;
~~~~

### On document ready

The plugin adds the `.accesskey()` function to jQuery, that had to be called after document is ready:

~~~~code
$(function() {
    $('selection').accesskey();
});
~~~~

You can specify some options or callbacks to your call:

~~~~code
$(function() {
    $('selection').accesskey({
        option_name: "my user value",
        my_callback: function() {
            // your stuff ...
        }
    });
});
~~~~

### Full overview

The code below is the working code on the [classic example](classic.html) demonstration.

~~~~code

// re-define a plugin default setting for all instances
$.fn.accesskey.defaults.debug = true;

// on document ready call
$(function() {

    // a simple call on a classic jQuery selection
    $(".intext").accesskey();

    // a simple call on a classic jQuery selection specifying the accesskey and the help text for the element
    $("#link_A")
        .accesskey({
            accesskey: "A", text: "Typing Ctrl+Al+A will select this link"
        });

    // a simple call on a classic jQuery selection with specific options
    $("#link_B")
        .accesskey({
            accesskey: "B", text: "Option text from javascript call",
            showOptions: "slow", hideOptions: "slow"
        })
        .click(function(){
            alert('Link '+$(this).attr('id')+' just clicked !');
        });

    // a complete call with specific user callback options
    var AK_var = $(".access-link")
        .accesskey({
            onCreate: function(){
                console.debug('"onCreate" user defined call, receiving args ', arguments);
                // your stuff ...
            },
            onShow: function(){
                console.debug('"onShow" user defined call, receiving args ', arguments);
                // your stuff ...
            },
            onHide: function(){
                console.debug('"onHide" user defined call, receiving args ', arguments);
                // your stuff ...
            },
            onDestroy: function(){
                console.debug('"onDestroy" user defined call, receiving args ', arguments);
                // your stuff ...
            }
        })
        .click(function(){
            alert('Link '+$(this).attr('id')+' just clicked !');
        });

});
~~~~


## Documentation

### Creating an instance

Full usage review of the `$.fn.accesskey` function:

~~~~code
$( jQuery selector ).accesskey();

$( jQuery selector ).accesskey( {options} );

$( jQuery selector ).accesskey( method [, {options}] );
~~~~

All arguments are optional. When the first one is an object, it's considered as a set of
options to extend the default settings of the plugin. See ["options" section](#options),
the [specific "tooltips" section](#tooltips) and the ["callbacks" section](#callbacks) of
this documentation for more infos. You can also access a public method of the plugin writing
it's name as first parameter. In this case, you can set some options as second parameter.
See ["methods" section](#methods) for more infos.

It is important to understand that the `accesskey` function works on a set of jQuery selected elements.
For example, you can't write directly `$.accesskey(options)` because the plugin will work on nothing.
You have to write something like `$('.a-class-of-objects').accesskey(options)` to initiate
the plugin on a collection of jQuery elements (*a set of DOM selections*).

Knowing that, the simpliest usage of the plugin, in a page where all accesskeys are defined
in the HTML code (*using the `accesskey` attribute of tags*) should be something like
the following, which will select all DOM elements containing an `accesskey` attribute and
pass them to the `accesskey()` plugin:

~~~~code
$('[accesskey]').accesskey();
~~~~

It returns a jQuery object collection, for chainability:

~~~~code
$( 'my jQuery selector' ).accesskey().bind( 'my event' );
~~~~

Contrary to what had been said, some global methods, concerning the plugin itself and
not a jQuery selection, can be accessed directly writing:

~~~~code
$.fn.accesskey.method();
~~~~

See ["global handlers" section](#handlers) of this documentation for more infos.

### Elements manipulation

The plugin works on each element of the jQuery selection rebuilding it to record in the HTML
tag the required infos. These data are recorded using the HTML5 feature `data-...`
constructing each element like:

~~~~code:html
&lt;a id="..." class="..." href="..."
    accesskey="" title=""
    data-accesskey="X" data-accesskeyhelper="[X] My help text"&gt;
        ...
&lt;/a&gt;
~~~~

In this example, the `id` and `class` attributes are not modified ; the `accesskey` is empty
to avoid its classic behavior ; and the `title` attribute is left unmodified if a text is
defined as an instance option, otherwise, as the title will be used to build the tooltip's
help message, it is removed from the original element. The two attributes `data-accesskey`
and `data-accesskeyhelper` are created to store the single letter accesskey and the full
help message.

As they are added to the element in the HTML document, you can access these attributes like
you do for others, and eventually modify them at runtime.

### Options

The plugin propose a set of options you can overwrite to your convenience.
To set one of these options globally for any future plugin usage, write:

~~~~code
$.fn.accesskey.defaults.optionName = value;
~~~~

To set one of these options for a selected collection of jQuery objects, write:

~~~~code
$('selection').accesskey( { optionName: value } );
~~~~

To retrieve the value of an option for a plugin's instance, you can use:

~~~~code
var myAK = $('selection').accesskey( { optionName: value } );

var option_value = myAK.accesskey('getOption', 'optionName');
~~~~

Here is the list of the plugin options, with their default values:

<table class="tablesorter">
    <thead><tr>
        <th title="Sort entries by this column value">Option name</th>
        <th title="Sort entries by this column value">Default value</th>
        <th title="Sort entries by this column value">Type</th>
        <th title="Sort entries by this column value">Option action</th>
    </tr></thead>
    <tbody>

    <tr>
        <td class="var">`accesskey`</td>
        <td class="val">null</td>
        <td class="val">string</td>
        <td>The accesskey to set for the element (one single character in `[A-Z0-9]` - alpha-numeric non-special uppercase characters)</td>
    </tr>

    <tr>
        <td class="var">`text`</td>
        <td class="val">null</td>
        <td class="val">string</td>
        <td>The helper text to show in the element tooltip (the `title` attribute will be used by default)</td>
    </tr>

    <tr>
        <td class="var">`useTitle`</td>
        <td class="val">true</td>
        <td class="val">bool</td>
        <td>Use the title as default tooltip message</td>
    </tr>

    <tr>
        <td class="var">`propagateOnce`</td>
        <td class="val">false</td>
        <td class="val">bool</td>
        <td>Force the element action to propagate just once</td>
    </tr>

    <tr>
        <td class="var">`altKey`</td>
        <td class="val">true</td>
        <td class="val">bool</td>
        <td>Is the "Alt" key required for accesskeys action?</td>
    </tr>

    <tr>
        <td class="var">`ctrlKey`</td>
        <td class="val">true</td>
        <td class="val">bool</td>
        <td>Is the "Ctrl" key required for accesskeys action?</td>
    </tr>

    <tr>
        <td class="var">`helpKey`</td>
        <td class="val">H</td>
        <td class="val">null / char</td>
        <td>Key character to activate the HELP tool of the plugin, showing all elements tooltips at once. Set on empty string to use only "Ctrl+Alt" ; set on NULL to deactivate this feature ; this key must follow the same rules as the "accesskey" option.</td>
    </tr>

    <tr>
        <td class="var">`showHelp`</td>
        <td class="val">false</td>
        <td class="val">bool</td>
        <td>Use the HELP message indicating how to use access-keys</td>
    </tr>

    <tr>
        <td class="var">`helpText`</td>
        <td class="val"><small>
Access-Keys Information
<br />
Keyboard shortcuts are defined to access some of the page elements or actions.
<br />
Press "%s[key]" to activate the element with this accesskey.
        </small></td>
        <td class="val">string</td>
        <td>The global helper text to show when the "help" key shortcut is used. Any `%s` in this value will be replaced by the plugin activation keys suite (*"Ctrl+Alt+" by default*).</td>
    </tr>

    <tr>
        <td class="var">`prefix`</td>
        <td class="val">[%s]</td>
        <td class="val">string</td>
        <td>The string prefix used at the beginning at the help tooltip's text. Any `%s` in this value will be replaced by the element accesskey letter.</td>
    </tr>

    <tr>
        <td class="var">`safeHTML`</td>
        <td class="val">false</td>
        <td class="val">bool</td>
        <td>Set to true to force the default HTML accesskey feature <span class="note">By default, the `accesskey` attribute is removed in each element and added as an HTML5 `data-accesskey` attribute ; this way, the plugin can control each element key-pressed action, handle multiple calls (*with the "propagateOnce" option*) and totally disables the accesskeys actions if required. See <a href="#manip">the elements manipulation</a> section of this page for more infos.</span></td>
    </tr>

    <tr>
        <td class="var">`noDuplicates`</td>
        <td class="val">false</td>
        <td class="val">bool</td>
        <td>Set to true to throw an error on duplicate key usage (*when the same key is used more than once in DOM*)</td>
    </tr>

    <tr>
        <td class="var">`showOptions`</td>
        <td class="val">null</td>
        <td class="val">misc</td>
        <td>
            A set of options or an option string passed to the `jQuery.show()` function when showing a tooltip, or a specific user defined callback.
            <br />For more infos, see <a href="http://api.jquery.com/show/">$().show()</a> or, if you use the jQuery UI native Tooltips, <a href="http://api.jqueryui.com/position/">$.ui.position()</a>.
        </td>
    </tr>

    <tr>
        <td class="var">`hideOptions`</td>
        <td class="val">null</td>
        <td class="val">misc</td>
        <td>
            A set of options or an option string passed to the `jQuery.hide()` function when hiding a tooltip, or a specific user defined callback.
            <br />For more infos, see <a href="http://api.jquery.com/hide/">$().hide()</a> or, if you use the jQuery UI native Tooltips, <a href="http://api.jqueryui.com/position/">$.ui.position()</a>.
        </td>
    </tr>

    <tr>
        <td class="var">`showEvents`</td>
        <td class="val">mouseover</td>
        <td class="val">event type list separated by space</td>
        <td>
            A list of events toggling the `showTooltip()` action (*in addition to the keydown*).
            <br />For more infos, see <a href="http://api.jquery.com/bind/">$().bind()</a>.
        </td>
    </tr>

    <tr>
        <td class="var">`hideEvents`</td>
        <td class="val">mouseout</td>
        <td class="val">event type list separated by space</td>
        <td>
            A list of events toggling the `hideTooltip()` action (*in addition to the keyup*).
            <br />For more infos, see <a href="http://api.jquery.com/bind/">$().bind()</a>.
        </td>
    </tr>

    <tr>
        <td class="var">`onClick`</td>
        <td class="val">
            `"follow_href"` for elements with an "href" attribute
            <br />
            `"select"` for form elements
            <br />
            `"submit"` for buttons
            <br />
            `"focus"` for the rest
        </td>
        <td class="val">string / null</td>
        <td>A special method to execute on element action (*such as an href following for links, which is the default behavior*).</td>
    </tr>

    <tr>
        <td class="var">`timeout`</td>
        <td class="val">3000</td>
        <td class="val">numeric (in milliseconds)</td>
        <td>The value of the timeout before hiding tooltips.</td>
    </tr>

    <tr>
        <td class="var">`debug`</td>
        <td class="val">false</td>
        <td class="val">bool</td>
        <td>Activate the plugin debug : write some infos in console</td>
    </tr>

    </tbody>
</table>

### Plugin's tooltips

The plugin defines its own tooltip display object, constructed as:

~~~~code:html
&lt;div class="accesskey-tooltip" role="tooltip"&gt;
    &lt;span class="accesskey-tooltip-content"&gt;
        The helper text of my object
    &lt;/span&gt;
&lt;/div&gt;
~~~~

which is rendered in page as:

<div class="accesskey-tooltip" style="position: relative !important; width: auto;">
    <span class="accesskey-tooltip-content">
        The helper text of my object
    </span>
</div>
<br class="clear" />

You can style these tooltips overwriting the CSS definitions of the file `jquery-accesskey.css`.

Additionally to the options described above, you can specify the following options for
the tooltips' construction:

~~~~code
    $.fn.accesskey.defaults.tooltip = {
        // tooltip wrapper settings
        wrapper: {
            element:    'div',
            attributes: {
                class:  'accesskey-tooltip',
                role:   'tooltip'
            },
        },
        // tooltip content settings
        content: {
            element:    'span',
            attributes: {
                class:  'accesskey-tooltip-content',
            },
        },
        // special wrapper class for the HELP tooltip
        helperClass:    'accesskey-tooltip-helper'
    };
~~~~

### Callbacks

The default life-cycle of the plugin could be schematize like that:

1.  at each first call of the plugin, an element or a collection of elements is `initialized`,
2.  on a keypress event, the corresponding `keydownAction` method is toggled and the tooltip
    message is shown, with a specific life-cycle like:
    2.a.   the tooltip is first `created` if it dosen't exist,
    2.b.   the created tooltip is `shown`, displayed on screen,
3.  on a keyup event (*when user release the key*), the corresponding `keyupAction` method
    is toggled and the tooltip message is hidden, with a specific life-cycle like:
    3.a.   after the `timeout` defined in the options, the tooltip is `hidden`, removed from screen,
    3.b.   then it is `deleted` from the HTML,
4.  if you call the `destroy` method on an element, everything goes back as it was written
    initially in your HTML code.

To define a callback, use:

~~~~code
$('selection').accesskey({
    onShow: function( $element ){
        // write an info on console
        console.debug('"onShow" user defined callback receiving args ', arguments);
    }
});
~~~~

Here is the list of the callbacks automatically called during the life-cycle of the plugin:

<table class="tablesorter">
    <thead><tr>
        <th title="Sort entries by this column value">Callback name</th>
        <th title="Sort entries by this column value">Type</th>
        <th title="Sort entries by this column value">Parameters</th>
        <th title="Sort entries by this column value">Callback life cycle</th>
    </tr></thead>
    <tbody>

    <tr>
        <td class="var">`onInit`</td>
        <td class="val">function( $elements )</td>
        <td>`$elements` : a jQuery object of the selected elements</td>
        <td>Called after plugin initialization.</td>
    </tr>

    <tr>
        <td class="var">`onDestroy`</td>
        <td class="val">function( $elements )</td>
        <td>`$elements` : a jQuery object of the selected elements</td>
        <td>Called after destruction of an initialized instance.</td>
    </tr>

    <tr>
        <td class="var">`onCreate`</td>
        <td class="val">function( $element )</td>
        <td>`$element` : a single jQuery object representing the active element</td>
        <td>Called after a tooltip element creation.</td>
    </tr>

    <tr>
        <td class="var">`onDelete`</td>
        <td class="val">function( $element )</td>
        <td>`$element` : a single jQuery object representing the active element</td>
        <td>Called after a tooltip element deletion.</td>
    </tr>

    <tr>
        <td class="var">`onShow`</td>
        <td class="val">function( $element )</td>
        <td>`$element` : a single jQuery object representing the active element</td>
        <td>Called after a tooltip element is shown.</td>
    </tr>

    <tr>
        <td class="var">`onHide`</td>
        <td class="val">function( $element )</td>
        <td>`$element` : a single jQuery object representing the active element</td>
        <td>Called after a tooltip element is hidden.</td>
    </tr>

    </tbody>
</table>

### Methods

You can access plugin's methods on a selection writing:

~~~~code
$('selection').accesskey( "method name" , arg, uments, ... );
~~~~

Here is the list of the methods of the plugin:

<table class="tablesorter">
    <thead><tr>
        <th title="Sort entries by this column value">Method name</th>
        <th title="Sort entries by this column value">Type</th>
        <th title="Sort entries by this column value">Parameters</th>
        <th title="Sort entries by this column value">Return / Action</th>
    </tr></thead>
    <tbody>

    <tr>
        <td class="var">`getData`</td>
        <td class="val">function()</td>
        <td></td>
        <td>Returns an object of the "accesskey" data of an element as they are handled by `$.data()`. For more infos, see <a href="http://api.jquery.com/data/">$().data()</a>.</td>
    </tr>

    <tr>
        <td class="var">`getOption`</td>
        <td class="val">function( option_name )</td>
        <td>`option_name` : the name of the option te get</td>
        <td>Returns the current value of the option for the selection.</td>
    </tr>

    <tr>
        <td class="var">`showAll`</td>
        <td class="val">function( {options} )</td>
        <td>`options` : a set of options that would be valid during this call only</td>
        <td>Show all tooltips of instance on screen.</td>
    </tr>

    <tr>
        <td class="var">`hideAll`</td>
        <td class="val">function( {options} )</td>
        <td>`options` : a set of options that would be valid during this call only</td>
        <td>Hide all tooltips of instance from screen.</td>
    </tr>

    <tr>
        <td class="var">`toggleAll`</td>
        <td class="val">function( {options} )</td>
        <td>`options` : a set of options that would be valid during this call only</td>
        <td>Toggle all tooltips (show or hide) of instance.</td>
    </tr>

    </tbody>
</table>

### Internal methods

In the same idea as callbacks of the section above, you can overwrite some of the internal
plugin methods managing the events handling and the tooltips life. **Defining these functions
yourself, be aware that they are necessary to the normal life of the plugin as they handle
the tooltip creation and display!**

Here is the list of the methods automatically called during the life-cycle of the plugin:

<table class="tablesorter">
    <thead><tr>
        <th title="Sort entries by this column value">Variable name</th>
        <th title="Sort entries by this column value">Calling method</th>
        <th title="Sort entries by this column value">Parameters</th>
        <th title="Sort entries by this column value">Method action</th>
    </tr></thead>
    <tbody>

    <tr>
        <td class="var">`keydownAction`</td>
        <td class="val">function( event, $element )</td>
        <td>
            `event` : the jQuery event as it was received, for more infos, see <a href="http://api.jquery.com/category/events/event-object/">$.Event()</a>.
            <br />
            `$element` : a single jQuery object representing the active element
        </td>
        <td>Method called on the `keydown` event on the key of an element <span class="note" data-noteref="events">The default behavior of the `keydown/keyup` events are set to emulate a classic usage of accesskeys : the target element is "clicked" and "focused".</span></td>
    </tr>

    <tr>
        <td class="var">`keyupAction`</td>
        <td class="val">function( event, $element )</td>
        <td>
            `event` : the jQuery event as it was received, for more infos, see <a href="http://api.jquery.com/category/events/event-object/">$.Event()</a>.
            <br />
            `$element` : a single jQuery object representing the active element
        </td>
        <td>Method called on the `keyup` event on the key of an element <span class="note" data-noteref="events"></span></td>
    </tr>

    <tr>
        <td class="var">`createTooltip`</td>
        <td class="val">function( $element, {options} )</td>
        <td>
            `element` : the target DOM element (a jQuery object)
            <br />
            `options` : a set of options that will extend the element options for now and in the future <span class="note" data-noteref="global_opts">Keep in mind that if you set some options calling one of these methods, they will overwrite the original options you set at the `$().accesskey()` call for now and for any future usage.</span>
        </td>
        <td>Method called at each tooltip creation ; default is the creation of internal tooltip <span class="note" data-noteref="overwrite">These methods can be overwritten for example when you want to use the <a href="http://jqueryui.com/tooltip/">*jQuery UI Tooltips*</a> instead of the internal ones ; see <a href="jquery_ui.html">this example page</a> for more infos.</span></td>
    </tr>

    <tr>
        <td class="var">`showTooltip`</td>
        <td class="val">function( $element, {options} )</td>
        <td>
            `element` : the target DOM element (a jQuery object)
            <br />
            `options` : a set of options that will extend the element options for now and in the future <span class="note" data-noteref="global_opts"></span>
        </td>
        <td>Method called at each tooltip displaying ; default is showing the internal tooltip <span class="note" data-noteref="overwrite"></span></td>
    </tr>

    <tr>
        <td class="var">`hideTooltip`</td>
        <td class="val">function( $element, {options} )</td>
        <td>
            `element` : the target DOM element (a jQuery object)
            <br />
            `options` : a set of options that will extend the element options for now and in the future <span class="note" data-noteref="global_opts"></span>
        </td>
        <td>Method called at each tooltip hiding ; default is hiding the internal tooltip <span class="note" data-noteref="overwrite"></span></td>
    </tr>

    <tr>
        <td class="var">`deleteTooltip`</td>
        <td class="val">function( $element, {options} )</td>
        <td>
            `element` : the target DOM element (a jQuery object)
            <br />
            `options` : a set of options that will extend the element options for now and in the future <span class="note" data-noteref="global_opts"></span>
        </td>
        <td>Method called at each tooltip deletion ; default is deleting the internal tooltip <span class="note" data-noteref="overwrite"></span></td>
    </tr>

    </tbody>
</table>

### Global handlers

The plugin propose a set of global methods to handle one/all accesskey element and manage
the plugin's action. To call one of these methods, write:

~~~~code
var myvar = $.fn.accesskey.methodName( arguments );
~~~

<table class="tablesorter">
    <thead><tr>
        <th title="Sort entries by this column value">Method name</th>
        <th title="Sort entries by this column value">Calling method</th>
        <th title="Sort entries by this column value">Method action</th>
    </tr></thead>
    <tbody>

    <tr>
        <td class="var">`enable`</td>
        <td class="val">function()</td>
        <td>Enables the plugin features (enabled by default).</td>
    </tr>

    <tr>
        <td class="var">`disable`</td>
        <td class="val">function()</td>
        <td>Disbles the plugin features (enabled by default).</td>
    </tr>

    <tr>
        <td class="var">`isEnable`</td>
        <td class="val">function()</td>
        <td>Returns `true` if the plugin is currently enabled.</td>
    </tr>

    <tr>
        <td class="var">`showAll`</td>
        <td class="val">function( {options} )</td>
        <td>Show all tooltips on screen ; you can specify a set of options that would be valid during this call only.</td>
    </tr>

    <tr>
        <td class="var">`hideAll`</td>
        <td class="val">function( {options} )</td>
        <td>Hide all tooltips from screen ; you can specify a set of options that would be valid during this call only.</td>
    </tr>

    <tr>
        <td class="var">`toggleAll`</td>
        <td class="val">function( {options} )</td>
        <td>Toggle all tooltips (show or hide) ; you can specify a set of options that would be valid during this call only.</td>
    </tr>

    <tr>
        <td class="var">`overview`</td>
        <td class="val">function()</td>
        <td>This method returns an object containing all active elements of the DOM, in an array-like object `key => message`.</td>
    </tr>

    </tbody>
</table>

### Open-Source & Community

This plugin is a free software, available under [General Public License version 3.0](http://opensource.org/licenses/GPL-3.0) ;
you can freely use it, for yourself or a commercial use, modify its source code according
to your needs, freely distribute your work and propose it to the community, as long as you
let an information about its first author.

As the sources are hosted on a [GIT](http://git-scm.com/) repository on [GitHub](http://github.com/piwi/jQuery-AccessKey),
you can modify it, to ameliorate a feature or correct an error, by [creating your own fork](See instructions online|https://help.github.com/articles/fork-a-repo)
of this repository, modifying it and [asking to pull your modifications]((See instructions online|http://github.com/piwi/jQuery-AccessKey/pulls))
on the original branch.

    jQuery AccessKey Plugin vX.Y.Z
    http://github.com/piwi/jQuery-AccessKey
    Copyleft 2013, Pierre Cassat

### Warnings

When developing with accesskeys, keep in mind that the *key codes* largely depends on the
keyboard and the system that runs your website. To test your thoughts validity, you can use
the excellent [JavaScript and jQuery Key Code Checker](http://www.west-wind.com/WestwindWebToolkit/samples/Ajax/html5andCss3/keycodechecker.aspx)
written by Rick Strahl [^3].

[^3]: Rick Strahl's blog can be found at [http://www.west-wind.com/weblog/](http://www.west-wind.com/weblog/).

    <footer class="footnotes" role="contentinfo">
    <h4>NOTES:</h4>
    <ol id="footnotes_list"></ol>

    <h4 id="todo">TODOS:</h4>
    <ol>
        <li>allow the new HTML5 "multiple characters" definition of the accesskeys, to let the current device choose the one corresponding the most to it</li>
        <li>allow to truly choose the keys combination to call an access-key (*which is now "Ctrl+Alt"*)</li>
    </ol>

    <p class="credits">Last update of this page <time datetime="2013-02-05">Feb 5, 2013</time>.</p>
    </footer>
