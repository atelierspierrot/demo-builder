<?php

?>
        <hr>
        <footer id="footer">
            <div class="muted credits pull-left">
                This page is created with <a href="http://jquery.com/">jQuery</a>, <a href="http://twitter.github.com/bootstrap/index.html">Bootstrap</a> and <a href="http://code.google.com/p/html5shiv/">HTML5shiv</a>.
                <br />
                Icons by <a href="http://glyphicons.com/">Glyphicons</a>.
                <br />
                This page is <a href="" title="Check now online" id="html_validation" data-insert-href="html_validation">HTML5</a> & <a href="" title="Check now online" id="css_validation" data-insert-href="css_validation" data-validation-argument="perso/styles.css">CSS3</a> valid.
            </div>
            <div class="muted credits pull-right">
                <a href="<?php echo (!empty($package->web['homepage']) ? $package->web['homepage'] : $package->sources['url']); ?>"><?php echo $package->name; ?></a> package by 
<?php foreach ($package->authors as $i=>$author) :
    $author_url = null;
    $url_title = 'View its webpage online';
    if (isset($author->url)) $author_url = $author->url;
    elseif (isset($author->homepage)) $author_url = $author->homepage;
    elseif (isset($author->email)) {
        $author_url = 'mailto:'.$author->email;
        $url_title = 'Contact this author';
    }
?>
    <?php if (!is_null($author_url)) : ?>
                    <a href="<?php echo $author_url; ?>" title="<?php echo $url_title; ?>">
    <?php endif; ?>
                    <?php echo $author->name; ?>
    <?php if (!is_null($author_url)) : ?>
                    </a>
    <?php endif; ?>
    <?php if (count($package->authors)>1) : ?>
                     (<em><?php echo $author->role; ?></em>)
    <?php endif; ?>
    <?php if ($i<count($package->authors)-1) : ?>, <?php endif; ?>
<?php endforeach; ?>
                     under 
<?php foreach ($package->licenses as $i=>$license) : ?>
    <?php if (isset($license['url'])) : ?>
                     <a href="<?php echo $license['url']; ?>" title="Read this license text online">
    <?php endif; ?>
                     <?php echo $license['type']; ?>
    <?php if (isset($license['url'])) : ?>
                     </a>
    <?php endif; ?>
    <?php if ($i<count($package->licenses)-1) : ?>, <?php endif; ?>
<?php endforeach; ?>
    <?php if (count($package->licenses)>1) : ?>
                     licenses
    <?php else: ?>
                     license
    <?php endif; ?>
                     .
            </div>
        </footer>
