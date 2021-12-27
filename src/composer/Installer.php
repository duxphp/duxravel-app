<?php

namespace Duxravel\Composer;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;
use React\Promise\PromiseInterface;

class Installer extends LibraryInstaller
{
    private $process;

    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
        $this->process = new ProcessExecutor($io);
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        return parent::install($repo, $package);
    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        if (!$repo->hasPackage($initial)) {
            throw new \InvalidArgumentException('Package is not installed: ' . $initial);
        }

        $this->initializeVendorDir();

        $this->binaryInstaller->removeBinaries($initial);
        $promise = $this->updateCode($initial, $target);
        if (!$promise instanceof PromiseInterface) {
            $promise = \React\Promise\resolve();
        }

        $binaryInstaller = $this->binaryInstaller;
        $installPath = $this->getInstallPath($target);

        return $promise->then(function () use ($binaryInstaller, $installPath, $target, $initial, $repo) {
            $binaryInstaller->installBinaries($target, $installPath);
            $repo->removePackage($initial);
            if (!$repo->hasPackage($target)) {
                $repo->addPackage(clone $target);
                $this->process->execute('php artisan app:install ' . $target->getName());
            } else {
                $this->process->execute('php artisan app:install ' . $target->getName() . ' --update');
            }
        });
    }

    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->process->execute('php artisan app:uninstall ' . $package->getName());
        return parent::uninstall($repo, $package);
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
