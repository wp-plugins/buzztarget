<?php

namespace BuzzTargetLive;

class Autoloader
{
    protected $namespace;
    protected $includePath;

    public function __construct()
    {
        
    }

    public function setNamespace($namespace)
    {
        if (is_string($namespace))
        {
            $this->namespace = $namespace;
        }
        return $this;
    }

    public function setIncludePath($includePath)
    {
        if (is_string($includePath))
        {
            $this->includePath = $includePath;
        }
        return $this;
    }

    public function register($throw = false, $prepend = false)
    {
        return spl_autoload_register(array($this, 'loadClass'), $throw, $prepend);
    }
    
    protected function loadClass($class)
    {
        if (strpos($class, $this->namespace) !== false)
        {
            $class = $this->includePath . $class . '.php';

            $class = str_replace(
                array($this->namespace, '\\'), 
                array('', DIRECTORY_SEPARATOR), 
                $class
            );

            if (is_readable($class))
            {
                require_once $class;
            }
        }
    }
}