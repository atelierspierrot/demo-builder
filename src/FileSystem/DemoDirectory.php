<?php

namespace FileSystem;

use \WebFilesystem\WebRecursiveDirectoryIterator;

class DemoDirectory extends WebRecursiveDirectoryIterator
{

    protected static $ignore_dir_masks = array( '_(.*)' );

    public function __construct($path)
    {
        parent::__construct($path);
        $this->setFileValidationCallback( array(__CLASS__, 'validateFileItem') );
        $this->setDirectoryValidationCallback( array(__CLASS__, 'validateDirectoryItem') );
    }

    public static function validateDirectoryItem($path)
    {
        $file_path = end(explode('/', $path));
        foreach(self::$ignore_dir_masks as $mask) {
            if (0!==preg_match('/^'.$mask.'$/i', $file_path)){
                return false;
            }
        }
        return true;
    }

    public static function validateFileItem($path)
    {
        return true;
    }

}

// Endfile