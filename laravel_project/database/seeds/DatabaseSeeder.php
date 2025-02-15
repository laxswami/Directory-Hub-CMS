<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Run seeder only when first installation. Update don't need seeder
         */
        if(!site_already_installed() || app()->runningInConsole())
        {
            $this->call([
                InstallSeeder::class,
            ]);
        }

    }
}
