<?php

namespace FileSystem;

use \WebFilesystem\WebRecursiveDirectoryIterator;

class DemoPageDirectory extends WebRecursiveDirectoryIterator
{

    protected static $valid_files_masks = array( '(.*)\.html', '(.*)\.md', '(.*)\.txt' );

    public function __construct($path)
    {
        parent::__construct($path);
        $this->setFileValidationCallback( array(__CLASS__, 'validateFileItem') );
        $this->setDirectoryValidationCallback( array(__CLASS__, 'validateDirectoryItem') );
    }

    public static function validateDirectoryItem($path)
    {
        return true;
    }

    public static function validateFileItem($path)
    {
        $return = false;
        $file_path = end(explode('/', $path));
        foreach(self::$valid_files_masks as $mask) {
            if (0!==preg_match('/^'.$mask.'$/i', $file_path)){
                $return = true;
            }
        }
        return $return;
    }

}

// Endfile