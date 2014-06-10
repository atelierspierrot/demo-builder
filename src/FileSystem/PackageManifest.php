<?php

namespace FileSystem;

use \DateTime;
use \FileSystem\AbstractConfigFileDependent;

class PackageManifest extends AbstractConfigFileDependent
{

    public static $defaults = array(
        'name'             => '@PACKAGE_NAME@',
        'title'            => '@PACKAGE_TITLE@',
        'version'          => '@X.Y.Z@',
        'state'            => 'stable',
        'description'      => '@PACKAGE_DESCRIPTION@',
        'slogan'           => '@PACKAGE_SLOGAN@',
        'keywords'         => array(),
        'web'              => array(),
        'sources'          => array(
            'url' => 'http://github.com/%s',
            'type' => 'git',
            'name' => 'GitHub.com'
        ),
        'authors'          => array( array('name'=>'@AUTHOR@') ),
        'licenses'         => array( array('type'=>'@LICENSE@') ),
        'dependencies'     => array(),
        'compatibilities'  => array(),
        'incompatibilities'=> array(),
        'time'             => null,
    );

    public function parse(array $data = array())
    {
        foreach(self::$defaults as $name=>$value) {
            if (isset($data[$name])) {
                if (is_array($value) && !is_array($data[$name])) {
                    $this->{$name} = explode(',', $data[$name]);
                } else {
                    $this->{$name} = $data[$name];
                }
            }
        }
        
        // title
        if ($this->title===self::$defaults['title'] && $this->name!==self::$defaults['name']) {
            $this->title = getSlug($this->name);
        }

        // slogan
        if ($this->slogan===self::$defaults['slogan'] && $this->description!==self::$defaults['description']) {
            $this->slogan = $this->description;
        }

        // license
        if (isset($data['license'])) {
            if ($this->licenses!==self::$defaults['licenses']) {
                $this->licenses[] = $data['license'];
            } else {
                $this->licenses = array( array( 'type'=>$data['license'] ) );
            }
        }

        // author
        if (isset($data['author'])) {
            if ($this->authors!==self::$defaults['authors']) {
                $this->authors[] = $data['author'];
            } else {
                $this->authors = array( array( $data['author'] ) );
            }
        }

        // dependency
        if (isset($data['dependency'])) {
            if ($this->dependencies!==self::$defaults['dependencies']) {
                $this->dependencies[] = $data['dependency'];
            } else {
                $this->dependencies = array( array( $data['dependency'] ) );
            }
        }

        // compatibility
        if (isset($data['compatibility'])) {
            if ($this->compatibilities!==self::$defaults['compatibilities']) {
                $this->compatibilities[] = $data['compatibility'];
            } else {
                $this->compatibilities = array( array( $data['compatibility'] ) );
            }
        }

        // require
        if (isset($data['require']) && $this->dependencies===self::$defaults['dependencies']) {
            $this->dependencies = $data['require'];
        }

        // suggest
        if (isset($data['suggest']) && $this->compatibilities===self::$defaults['compatibilities']) {
            $this->compatibilities = $data['suggest'];
        }

        // conflict
        if (isset($data['conflict']) && $this->incompatibilities===self::$defaults['incompatibilities']) {
            $this->incompatibilities = $data['conflict'];
        }

        // time
        if (!empty($this->time)) {
            $this->time = new DateTime( $this->time );
        } else {
            $this->time = getDateTimeFromTimestamp( $this->file->getMTime() );
        }

        // the web infos
        $infos = array('homepage', 'url', 'docs', 'demo', 'bugs','download');
        foreach($infos as $_info) {
            if (isset($data[$_info])) {
                $this->web[$_info] = $data[$_info];
            }
        }
        
        // the support infos
        if (isset($data['support']) && $this->web===self::$defaults['web']) {
            $this->web = $data['support'];
        }

        // the sources
        if (isset($data['repository'])) {
            if ($this->sources!==self::$defaults['sources']) {
                $this->sources[] = $data['repository'];
            } else {
                $this->sources = $data['repository'];
            }
        }
        
        // the 'download' link
        if (isset($data['download']) && $this->sources!==self::$defaults['sources'] && $this->sources['url']===self::$defaults['sources']['url']) {
            $this->sources['url'] = $data['download'];
        }
        
        // the 'source' link
        if (isset($data['support']) && isset($data['support']['source']) && $this->sources!==self::$defaults['sources'] && $this->sources['url']===self::$defaults['sources']['url']) {
            $this->sources['url'] = $data['support']['source'];
        }
        
        // last
        $this->sources = array_merge(self::$defaults['sources'], (array) $this->sources);
        if ($this->sources['url']===self::$defaults['sources']['url']) {
            $this->sources['url'] = sprintf($this->sources['url'], $this->name);
        }
    }
    
}

// Endfile