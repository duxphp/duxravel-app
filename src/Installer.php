<?php

namespace Duxravel\Composer\Install;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Util\Filesystem;

class Installer extends LibraryInstaller
{

    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
    }

    /**
     * {@inheritDoc}
     */
    public function getPackageBasePath(PackageInterface $package)
    {
        $prefix = 'duxphp/duxravel-';
        if (strpos($package->getPrettyName(), $prefix, 0) === false) {
            throw new \Exception('Restrict package permissions in the name "duxphp/duxravel-"');
        }
        return 'testapp/' . substr($package->getPrettyName(), count($prefix));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'duxravel-app' === $packageType;
    }
}
