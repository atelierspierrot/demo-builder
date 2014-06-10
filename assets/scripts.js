/* Scripts for demo */

// Avoid `console` errors in browsers that lack a console.
// http://github.com/h5bp/html5-boilerplate
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

function closeProfiler()
{
    $('#DemoBuilder-profiler').fadeOut();
    return false;
}

// ---------------------------
// Utilities
// ---------------------------

function uniqid()
{
    var n = Math.floor(Math.random()*11);
    var k = Math.floor(Math.random()*1000000);
    return String.fromCharCode(n)+k;
}

function getUrlFilename( _url )
{
    var url = _url || document.location.href,
        filename, qm = url.lastIndexOf('?');
    if (qm!==-1) { filename = url.substr(0,qm); }
    else { filename = url; }
    return filename.substring(filename.lastIndexOf('/')+1);
}

function getUrlHash( _url )
{
    var url = _url || document.location.href,
        hash = '',
        sm = url.lastIndexOf('#');
    if (sm!==-1) { hash = url.substr(sm+1); }
    return hash;    
}

// ---------------------------
// Elements creations
// ---------------------------

function getNewLi( str )
{
    return $('<li />').html(str);
}

function getNewA( href, str )
{
    return $('<a />', {'href':href}).html(str);
}

function getNewInfoItem( str, title, href )
{
    var strong = $('<strong />').html( href!==undefined ? getNewA(href, str) : str );
    return getNewLi( title!==undefined ? title+' : ' : '' ).append( strong );
}

// ---------------------------
// Page tools
// ---------------------------

function initHandler( _name )
{
    var elt_handler = $('#'+_name+'_handler'),
        elt_block = $('#'+_name);
    elt_block.hide();
    elt_handler.click(function(){ 
        var tltp = elt_handler.accesskey ? elt_handler.accesskey('getTooltip') : false;
        if (tltp && elt_block.is(':visible')) { tltp.hide(); }
        elt_block.toggle('slow');
        elt_handler.toggleClass('down');
    });
}

function initCollapseHandler( _name )
{
    var elt_handler = $('#'+_name+'_handler'),
        elt_collapse = $('#'+_name+'_collapse'),
        elt_block = $('#'+_name);
    elt_collapse.collapse();
    elt_handler.click(function(){ 
        elt_collapse.collapse('toggle');
        return false;
    });
}

function getPluginManifest( url, callback )
{
    $.ajax(url, {
        error: function(jqXHR, textStatus, error) {
            addMessage('AJAX error! ['+textStatus+(error ? ' : '+error : '')+']');
            return false;
        },
        success: function(data) { callback.apply(this, [data]); }
    });
}

function getGitHubCommits( github, callback )
{
    $.ajax(github+'commits', {
        method: 'GET',
        crossDomain: true,
        data: { page: 1, per_page: 5 },
        dataType: 'json',
        error: function(jqXHR, textStatus, error) {
            addMessage('AJAX error! ['+textStatus+(error ? ' : '+error : '')+']');
            return false;
        },
        success: function(data, textStatus, jqXHR) { 
            if (data.length>1 || data[0]!==undefined) {
                callback.apply(this, [data]);
            } else {
                callback.apply(this, [null]);
            }
        }
    });
}

function getGitHubBugs( github, callback )
{
    $.ajax(github+'issues', {
        method: 'GET',
        crossDomain: true,
        data: { page: 1, per_page: 5 },
        dataType: 'json',
        error: function(jqXHR, textStatus, error) {
            addMessage('AJAX error! ['+textStatus+(error ? ' : '+error : '')+']');
            return false;
        },
        success: function(data, textStatus, jqXHR) {
            if (data.length>1 || data[0]!==undefined) {
                callback.apply(this, [data]);
            } else {
                callback.apply(this, [null]);
            }
        }
    });
}

function activateMenuItem()
{
    var page = getUrlFilename( window.location.href );
    $('nav li').each(function(i,o){
        var atag = $(o).find('a').first();
        if (atag && atag.attr('href')===page) { atag.closest('li').addClass('active'); }
    });
}

function getToHash()
{
    var _hash = window.location.hash;
    if (_hash!==undefined) {
        var hash = $('#'+_hash.replace('#', ''));
        if (hash.length) {
            var poz = hash.position();
            $("html:not(:animated),body:not(:animated)").animate({ scrollTop: poz.top });
        }
    }
}

function updateBacklinks()
{
    $('#short_menu').html( $('#navigation_menu').html() );
}

function initBacklinks()
{
    $('#short_navigation').hide();
    $('#short_menu').hide();
    $('#short_menu_handler').bind('mouseover', function(){
        var short_menu = $('#short_menu'),
            short_menu_ln = $('#short_menu').html().length;
        updateBacklinks();
        $('#short_menu').fadeIn('slow', function(){
            $('#short_navigation').bind('mouseleave', function(){ $('#short_menu').fadeOut('slow'); });
        });
    });
    $(window).scroll(function(){
        var nav = $('nav'),
            nav_poz = nav.position();
        if ((nav_poz.top+$('nav').height()) < $(window).scrollTop()) {
            $('#short_navigation').fadeIn('slow');
        } else {
            $('#short_navigation').fadeOut('slow');
        }
    });
}

function addCSSValidatorLink( css_filename )
{
    var url = window.location.href,
        cssfile = url.replace(/(.*)\/.*(\.html$)/i, '$1/'+css_filename);
    $('#footer a#css_validation').attr('href', 'http://jigsaw.w3.org/css-validator/validator?uri='+encodeURIComponent(cssfile));
}

function addHTMLValidatorLink( url )
{
    if (url===undefined || url===null) { var url = window.location.href; }
    $('#footer a#html_validation').attr('href', 'http://html5.validator.nu/?showimagereport=yes&showsource=yes&doc='+encodeURIComponent(url));
}

var FootNotesStack = [];
function buildFootNotes()
{
    var bl_sup = $('<sup />'),
        bl_a_hdlr = $('<a />', { 'class':'footnote_link', 'title':'See footnote' }),
        bl_a_back = $('<a />', { 'class':'footnote_link', 'title':'Back in content' }).html('&#8617;'),
        bl_note = $('<li />');
    $('.note').each(function(i,o){
        var ref = $(this).attr('data-noteref'), hdlr_id, note_id;
        if ($.inArray(ref, FootNotesStack)!==-1) {
            var j = parseInt($.inArray(ref, FootNotesStack)+1);
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
        }
        else {
            var j = parseInt(FootNotesStack.length+1),
                note_ctt = $(this).html(),
                note_item = bl_note.clone(),
                note_back = bl_a_back.clone();
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
            note_back.attr('href', '#'+hdlr_id);
            note_item.attr('id', note_id);
            note_item.html(note_ctt+'&nbsp;');
            note_item.append(note_back);
            $('#footnotes_list').append(note_item);
            FootNotesStack.push(ref || j);
        }
        var note_hdlr = bl_a_hdlr.clone(),
            note_sup = bl_sup.clone();
        note_hdlr.attr('href', '#'+note_id);
        note_hdlr.attr('id', hdlr_id);
        note_hdlr.html(j);
        note_sup.append(note_hdlr);
        $(this).replaceWith(note_sup);
        $('#footnotes').show();
    });
}

var GlossaryNotesStack = [];
function buildGlossaryNotes()
{
    var bl_sup = $('<sup />'),
        bl_a_hdlr = $('<a />', { 'class':'glossary_link', 'title':'See glossary note' }),
        bl_a_back = $('<a />', { 'class':'glossary_link', 'title':'Back in content' }).html('&#8617;'),
        bl_note = $('<li />');
    $('.glossary').each(function(i,o){
        var ref = $(this).attr('data-noteref'), hdlr_id, note_id;
        if ($.inArray(ref, GlossaryNotesStack)!==-1) {
            var j = parseInt($.inArray(ref, GlossaryNotesStack)+1);
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
        }
        else {
            var j = parseInt(GlossaryNotesStack.length+1),
                note_ctt = $(this).html(),
                note_item = bl_note.clone(),
                note_back = bl_a_back.clone();
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
            note_back.attr('href', '#'+hdlr_id);
            note_item.attr('id', note_id);
            note_item.html(note_ctt+'&nbsp;');
            note_item.append(note_back);
            $('#glossaries_list').append(note_item);
            GlossaryNotesStack.push(ref || j);
        }
        var note_hdlr = bl_a_hdlr.clone(),
            note_sup = bl_sup.clone();
        note_hdlr.attr('href', '#'+note_id);
        note_hdlr.attr('id', hdlr_id);
        note_hdlr.html(j);
        note_sup.append(note_hdlr);
        $(this).replaceWith(note_sup);
        $('#glossaries').show();
    });
}

var TodoNotesStack = [];
function buildTodoNotes()
{
    var bl_sup = $('<sup />'),
        bl_a_hdlr = $('<a />', { 'class':'todo_link', 'title':'See todo note' }),
        bl_a_back = $('<a />', { 'class':'todo_link', 'title':'Back in content' }).html('&#8617;'),
        bl_note = $('<li />');
    $('.todo').each(function(i,o){
        var ref = $(this).attr('data-noteref'), hdlr_id, note_id;
        if ($.inArray(ref, TodoNotesStack)!==-1) {
            var j = parseInt($.inArray(ref, TodoNotesStack)+1);
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
        }
        else {
            var j = parseInt(TodoNotesStack.length+1),
                note_ctt = $(this).html(),
                note_item = bl_note.clone(),
                note_back = bl_a_back.clone();
            hdlr_id = 'note_'+j+'_intext';
            note_id = 'note_'+j;
            note_back.attr('href', '#'+hdlr_id);
            note_item.attr('id', note_id);
            note_item.html(note_ctt+'&nbsp;');
            note_item.append(note_back);
            $('#todos_list').append(note_item);
            TodoNotesStack.push(ref || j);
        }
        var note_hdlr = bl_a_hdlr.clone(),
            note_sup = bl_sup.clone();
        note_hdlr.attr('href', '#'+note_id);
        note_hdlr.attr('id', hdlr_id);
        note_hdlr.html(j);
        note_sup.append(note_hdlr);
        $(this).replaceWith(note_sup);
        $('#todos').show();
    });
}

function addMessage( str )
{
    var msg = $('<span />').html(str),
        msgbox = $('#message_box');
    msgbox.append(msg);
    if (!msgbox.is(':visible')) { msgbox.show(1000); }
    msgbox.delay(5000).hide(1000);
}

function initHighlighted( sel )
{
    $(sel).highlight({indent:'tabs', code_lang: 'data-language'});
}

function initTablesorter( sel )
{
    $(sel).find('th').each(function(i,v){
        var _wrapper = $('<span>', {'class':'pull-right'}),
            _icon = $('<i>', {'class':'icon-resize-vertical'});
        _wrapper.append( _icon );
        $(this).attr('title', 'Sort entries by this column value').append( _wrapper );
    });
    $(sel).tablesorter()
        .bind('sortEnd', function(data){
            $(data.target).find('th').each(function(i,v){
                if ($(this).hasClass('headerSortDown')) {
                    $(this).find('i')
                        .removeClass('icon-resize-vertical icon-arrow-up')
                        .addClass('icon-arrow-down');
                }
                else if ($(this).hasClass('headerSortUp')) {
                    $(this).find('i')
                        .removeClass('icon-resize-vertical icon-arrow-down')
                        .addClass('icon-arrow-up');
                }
                else {
                    $(this).find('i')
                        .removeClass('icon-arrow-up icon-arrow-down')
                        .addClass('icon-resize-vertical');
                }
            });
        });
}

function initInpageNavigation()
{
    $('section,section h2,section h3,section h4,section h5,section h6').each(function(i,el){
        var _id = $(this).attr('id');
        if (_id) {
            var inpage_menu = $('ul#inpage_menu'),
                _li = $('<li>'),
                _a = $('<a>', {'href':'#'+_id}).html( $(this).html() );
            inpage_menu.append( _li.append(_a) );
            $('[data-spy="scroll"]').each(function () {
                var $spy = $(this).scrollspy('refresh')
            });
        }
    });
}

function writeManifestInfos( url, _id )
{
    if (url) {
        var id = _id || 'manifest',
            manifest_ul = $('#'+id).find('ul');
        getPluginManifest(url, function(data){
            if (data.title) {
                manifest_ul.append( getNewInfoItem( data.title, 'title' ) );
            } else if (data.name) {
                manifest_ul.append( getNewInfoItem( data.name, 'title' ) );
            }
            manifest_ul.append( getNewInfoItem( data.version, 'version' ) );
            manifest_ul.append( getNewInfoItem( data.description, 'description' ) );
            if (data.licenses) {
                manifest_ul.append( getNewInfoItem( data.licenses[0].type, 'license', data.licenses[0].url ) );
            } else if (data.license) {
                manifest_ul.append( getNewInfoItem( data.license, 'license' ) );
            }
            if (data.homepage) {
                manifest_ul.append( getNewInfoItem( data.homepage, 'homepage', data.homepage ) );
            }
            if (data.author) {
                if (data.author.name) {
                    manifest_ul.append( getNewInfoItem( data.author.name, 'author', data.author.url ) );
                } else {
                    manifest_ul.append( getNewInfoItem( data.author.name, 'author' ) );
                }
            } else if (data.authors) {
                manifest_ul.append( getNewInfoItem( data.authors[0].name, 'author', (data.authors[0].homepage || data.authors[0].email) ) );
            }
            if (data.bugs) {
                manifest_ul.append( getNewInfoItem( data.bugs, 'bugs', data.bugs ) );
            }
            if (data.download) {
                manifest_ul.append( getNewInfoItem( data.download, 'download latest', data.download ) );
            }
            initCollapseHandler( id );
        });
    }
}

function writeRepoInfos( url, _id, _commits_id, _bugs_id )
{
    if (url) {
        var id = _id || 'github',
            commits_id = _commits_id || 'commits_list',
            bugs_id = _bugs_id || 'bugs_list';

        // commits list
        var github_commits = $('#'+id).find('#'+commits_id);
        getGitHubCommits(url, function(data){
            if (data!==undefined && data!==null)
            {
                $.each(data, function(i,o) {
                    if (o!==null && typeof o==='object' && o.commit.message!==undefined && o.commit.message.length)
                        github_commits.append( getNewInfoItem( (o.commit.message || ''), (o.commit.committer.date || ''), (o.commit.url || '') ) );
                });
            } else {
                github_commits.append( getNewInfoItem( 'No commit for now.', '' ) );
            }
        });

        // bugs list
        var github_bugs = $('#'+id).find('#'+bugs_id);
        getGitHubBugs(url, function(data){
            if (data!==undefined && data!==null)
            {
                $.each(data, function(i,o) {
                    if (o!==null && typeof o==='object' && o.title!==undefined && o.title.length)
                        github_bugs.append( getNewInfoItem( (o.title || ''), (o.created_at || ''), (o.html_url || '') ) );
                });
            } else {
                github_bugs.append( getNewInfoItem( 'No opened bug for now.', '' ) );
            }
            initCollapseHandler( id );
        });

    }
}