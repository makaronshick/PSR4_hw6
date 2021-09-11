<?php

declare(strict_types=1);

namespace Vend;

class Autoloader
{
    protected array $namespaces = array();

    public function addNamespace(string $prefix, string $dir)
    {
        if (is_dir($dir)) {
            $this->namespaces[$prefix] = $dir;
            return true;
        }

        return false;
    }

    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    protected function autoload($class)
    {
        $pathParts = explode('\\', $class);

        if (is_array($pathParts)) {
            $prefix = array_shift($pathParts);

            if (!empty($this->namespaces[$prefix])) {
                $filePath = $this->namespaces[$prefix] . '/' . implode('/', $pathParts) . '.php';
                require_once $filePath;
                return true;
            }
        }

        return false;
    }
}

$autoloader = new Autoloader();
$autoloader->addNamespace('App', '../src');
$autoloader->register();
