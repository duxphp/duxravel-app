<?php

namespace Duxravel\Composer\Install;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;

class Installer extends LibraryInstaller
{

    private $config = [];

    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $config = $this->getAppConfig($package);
        $then = parent::install($repo, $package);
        $appDir = './modules/' . ucfirst($config['name']);

        /*if (is_dir($appDir . '/database')) {
            Filesystem::copy($appDir . '/database', './database');
        }*/
        return $then;
    }

    /**
     * @param PackageInterface $package
     * @return string
     * @throws \Exception
     */
    public function getInstallPath(PackageInterface $package)
    {
        $prefix = 'duxphp/duxravel-';
        if (strpos($package->getPrettyName(), $prefix, 0) === false) {
            throw new \InvalidArgumentException('Unable to install non official application of duxravel temporarily');
        }

        $config = $this->getAppConfig($package);

        if ($config['type'] === 'theme') {
            return './public/themes/' . ucfirst($config['name']);
        }
        return './modules/' . ucfirst($config['name']);
    }

    private function getAppConfig($package)
    {
        if ($this->config) {
            return $this->config;
        }
        $extra = $package->getExtra();
        $extra = $extra['duxravel'];
        if (!$extra) {
            throw new \InvalidArgumentException('Duxravel extension information does not exist');
        }
        $name = $extra['name'];
        $type = $extra['type'];
        if (!$name || !$type) {
            throw new \InvalidArgumentException('Duxravel is missing an extension parameter');
        }
        $this->config = $extra;
        return $extra;
    }

    /**
     * @param string $packageType
     * @return bool
     */
    public function supports($packageType)
    {
        return 'duxravel-app' === $packageType;
    }
}
