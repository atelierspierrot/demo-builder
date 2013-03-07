<?php
?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if (!empty($page->description)) : ?>
    <meta name="description" content="<?php echo $page->description; ?>">
<?php endif; ?>
<?php if (!empty($page->authors)) : 
    $author_str = '';
?>
    <?php foreach ($page->authors as $i=>$author) :
        $author_str .= $author->name;
        if (isset($author->url)) $author_str .= ' <'.$author->url.'>';
        elseif (isset($author->homepage)) $author_str .= ' <'.$author->homepage.'>';
        elseif (isset($author->email)) $author_str .= ' <'.$author->email.'>';
        if ($i<count($package->authors)-1) : $author_str .= ','; endif;
    endforeach; ?>
    <meta name="author" content="<?php echo $author_str; ?>">
<?php endif; ?>
