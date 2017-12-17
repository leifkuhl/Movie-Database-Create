<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * For testing purposes
         * 
         */
        DB::connection()->statement('CREATE DATABASE IF NOT EXISTS testdb');
        // Just get access to the config. 
        $config = App::make('config');

        // Will contain the array of connections that appear in our database config file.
        $connections = $config->get('database.connections');

        // This line pulls out the default connection by key (by default it's `mysql`)
        $defaultConnection = $connections[$config->get('database.default')];

        // Now we simply copy the default connection information to our new connection.
        $newConnection = $defaultConnection;
        // Override the database name.
        $newConnection['database'] = 'testdb';

        // This will add our new connection to the run-time configuration for the duration of the request.
        App::make('config')->set('database.connections.' . 'testdb', $newConnection);

        Artisan::call('db:seed', ['--database' => 'testdb', '--class' => 'MovieDBSeeder']);
    }
}
