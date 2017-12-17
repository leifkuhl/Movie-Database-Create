<?php

use Illuminate\Database\Seeder;

/**
 * Seeder for the MovieDB
 *
 * @author mstu15
 * @version 16.12.2017
 */
class MovieDBSeeder extends Seeder {

    /**
     * Seeds the MovieDB is executed with the default Database if not otherwise
     * selected with the:
     * Artisan::call('db:seed', ['--database' => 'selecedDB', '--class' => 'MovieDBSeeder']);
     * command
     * @return void
     */
    public function run() {
        
        DB::unprepared(File::get('database/sql_files/1_create_all-lower.sql'));
        /* DB::unprepared(File::get('database/sql_files/2_movie_table.sql'));
          DB::unprepared(File::get('database/sql_files/3_person_table.sql'));
          DB::unprepared(File::get('database/sql_files/4_award_and_restriction_cat.sql'));
          DB::unprepared(File::get('database/sql_files/5_staff_tables.sql'));
          DB::unprepared(File::get('database/sql_files/6_movie_awards.sql'));
          DB::unprepared(File::get('database/sql_files/7_scene_appearance.sql'));
          DB::unprepared(File::get('database/sql_files/insert.sql'));
          DB::unprepared(File::get('database/sql_files/update_2005.sql'));
          DB::unprepared(File::get('database/sql_files/update_2005_2.sql')); */
    }

}
