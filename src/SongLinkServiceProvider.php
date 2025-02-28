<?php

namespace Gnarhard\SongLink;

use Gnarhard\SongLink\Commands\StoreSongLinksCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SongLinkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('songlink')
            ->hasConfigFile()
            ->hasRoute('web')
            ->hasViews()
            ->hasMigration('create_songlink_table')
            ->hasCommand(StoreSongLinksCommand::class);
    }
}
