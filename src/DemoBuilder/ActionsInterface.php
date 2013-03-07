<?php

namespace DemoBuilder;

interface ActionsInterface
{

    /**
     * The demo page view builder
     */
    public function pageAction();

    /**
     * The not foud page view builder
     */
    public function notFoundAction();

    /**
     * The configuration form page view builder
     */
    public function indexAction();

}

// Endfile