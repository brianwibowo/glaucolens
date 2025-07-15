<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Exception as DBALException;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            if (!Type::hasType('enum')) {
                Type::addType('enum', 'Doctrine\DBAL\Types\StringType');
            }
            Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        } catch (DBALException $e) {
            // Log or handle the exception if the type is already registered
        }

        Schema::defaultStringLength(191);
    }
}