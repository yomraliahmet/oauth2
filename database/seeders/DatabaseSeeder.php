<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(1)->create()->each(function($relationFactory){
             $relationFactory->adresses()->createMany([
                 [
                     'title' => 'Ev Adresi',
                     'detail' => 'Burası Ev Adresi',
                 ],
                 [
                     'title' => 'İş Adresi',
                     'detail' => 'Burası İş Adresi',
                 ]
             ]);
         });

         Artisan::call("passport:install");
    }
}
