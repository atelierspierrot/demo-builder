            <div class="container-fluid">

                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>

                <a class="brand" href="<?php echo getRouteUrl('index'); ?>"><?php echo $page->demo_title; ?></a>

                <div class="nav-collapse collapse">
                    <p class="navbar-text pull-right">
                        <a href="<?php echo $package->sources['url']; ?>" class="navbar-link">See on <?php echo $package->sources['name']; ?></a>
                    </p>

                    <div class="navbar-text pull-right"><small>
                        <ul class="nav">
                            <li class="active"><a href="#top" title="Reach the top ot this page">&uarr;&nbsp;Top</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Reach a section in this page">Inpage navigation <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="inpage_menu"></ul>
                            </li>
                            <li><a href="#footer" title="Reach the bottom of this page">Bottom&nbsp;&darr;</a></li>
                        </ul>
                    </small></div>

                    <!-- global menu -->
                    <ul class="nav">
<?php foreach($page->header_menu as $path=>$item) : ?>
    <?php if ($item->getBasename()==='index') : /* write home first */ ?>
                        <li><a href="<?php echo getRouteUrl($path); ?>">Home</a></li>
    <?php endif; ?>
<?php endforeach; ?>
<?php foreach($page->header_menu as $path=>$item) : ?>
    <?php if ($item->getBasename()!=='index') : /* then others */ ?>
                        <li><a href="<?php echo getRouteUrl($path); ?>"><?php
                            echo $item->getHumanReadableFilename();
                        ?></a></li>
    <?php endif; ?>
<?php endforeach; ?>
                    </ul>
                </div>

            </div>
