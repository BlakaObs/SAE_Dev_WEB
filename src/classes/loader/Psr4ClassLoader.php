<?php

namespace sae\web\loader;

class Psr4ClassLoader
{
    protected string $prefixe;
    protected string $chemin;

    public function __construct(string $p, string $c)
    {
        $this->chemin = $c;
        $this->prefixe = $p;
    }

    public function loadClass(string $fichier): void
    {
        $fp = substr($fichier, 0, strlen($this->prefixe));
        if ($fp === $this->prefixe) {
            $fichier = str_replace($this->prefixe, $this->chemin, $fichier);
            $fichier = str_replace("\\", "/", $fichier . ".php");
        }
        require_once $fichier;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }
}