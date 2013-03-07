<?php

namespace FileSystem;

use \SplFileInfo, \Exception;

class ConfigFile extends SplFileInfo
{

    protected $config;

    public function __construct($filename)
    {
        parent::__construct($filename);
        if (!$this->isFile()) {
            throw new Exception(
                sprintf('Configuration file "%s" is not a file!', $filename)
            );
        }
        if (!$this->isReadable()) {
            throw new Exception(
                sprintf('Configuration file "%s" is not readable!', $filename)
            );
        }
        $this->parse();
    }

    public function getConfig()
    {
        return (array) $this->config;
    }

    public function get($name, $default = null)
    {
        return (isset($this->config[$name]) ? $this->config[$name] : $default);
    }

    public function parse()
    {
        $ext = $this->getExtension();
        $_meth = '_parse'.ucfirst($ext);
        if (method_exists($this, $_meth)) {
            $this->{$_meth}( file_get_contents($this->getRealPath()) );
        } else {
            throw new Exception(
                sprintf('Unknown configuration file type (got extension "%s")!', $ext)
            );
        }
    }

    protected function _parseJson($content)
    {
        $this->config = json_decode($content);
    }

    protected function _parsePhp($content)
    {
        $this->config = eval( str_replace(array('<?php', '?>'), '', $content) );
    }

    protected function _parseIni($content)
    {
        $this->config = parse_ini_string($content, true);
    }

}

// Endfile