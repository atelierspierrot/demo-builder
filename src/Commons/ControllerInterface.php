<?php

namespace Commons;

interface ControllerInterface
{

    /**
     * Constructor with user options
     */
    public function __construct(array $user_options = array());

    public function debug();

    /**
     * GET, POST or SESSION arguments parser
     */
    public function parseArguments();

    /**
     * Distribution of requested route
     */
    public function distribute();

}

// Endfile