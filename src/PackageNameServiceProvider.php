<?php

namespace Vendor\PackageName;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vendor\PackageName\Commands\PackageNameCommand;

class PackageNameServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('package-name')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_package_name_table')
            ->hasCommand(PackageNameCommand::class);
    }
}
