<?php

use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\App;

define('DELIMITER','*');

define('VPN', '%.vpn.tu-clausthal.de');			//VPN
define('RZ', '%.139.174.32.1');			//RZ & WLAN for TUC
define('IFI', '%.139.174.100.3');			//IFI
define('TUC', '139.174.%');				//TUC
define('DEFAULT_HOST', VPN.DELIMITER.IFI.DELIMITER.RZ);	//default host
/**
 * Extends the DatabaseManager with custom Database Functions
 * 
 *
 * @author mstu15
 * @version 02.01.2018
 */
class CustomDatabaseManager extends \Illuminate\Database\DatabaseManager {

    /**
     * Creates a new Account with set name, replicates the MovieDB and sets permissions 
     * 
     * @param $accName Name of the Account to create
     */
    function createAccount($accName, $accType, $host = DEFAULT_HOST) 
    {
        // Replicates MovieDB
        CustomDatabaseManager::replicateMovieDB($accName.'_movieDB');
        // Creates TestDB
        CustomDatabaseManager::createDB($accName.'_testDB');
        // Gets the Default Password for the user
        $pwd = CustomDatabaseManager::getDefaultPwd($accName);
        // Creates the User
        DB::statement("DROP USER IF EXISTS $accName");
        DB::statement("CREATE USER '$accName'@'$host' IDENTIFIED BY '$pwd'");
        DB::connection()->statement("FLUSH PRIVILEGES");
        // Sets Permissions
        CustomDatabaseManager::setPermissions($accName, $accType, $host);
    }
    
    
    /**
     * Creates a new Database with set name
     * 
     * @param $db_name Name of the Database to create
     */
    function createDB($db_name) {

        CustomDatabaseManager::deleteDB($db_name);
        DB::connection()->statement("CREATE DATABASE IF NOT EXISTS $db_name");
    }
    
    /**
     * Deletes the Database with set name
     * 
     * @param $db_name Name of the Database to delete
     */
    function deleteDB($db_name) {

        DB::connection()->statement("DROP DATABASE IF EXISTS $db_name");
    }

    /**
     * Set a Password for given user
     * 
     * @param $user the given user
     * @param $pwd the new password
     */
    function setPwd($accName, $pwd) {


        DB::connection()->statement("UPDATE mysql.user SET Password=PASSWORD('$pwd')
		WHERE User='$accName'");
        DB::connection()->statement("FLUSH PRIVILEGES");

        //return "Update password for $user.";
    }
    
    /**
     * creates a MovieDB with set name
     * 
     * @param $db_name
     */
    
    function replicateMovieDB($db_name) {
        
        
        CustomDatabaseManager::createDB($db_name);
        // Just get access to the config. 
        $config = App::make('config');

        // Will contain the array of connections that appear in our database config file.
        $connections = $config->get('database.connections');

        // This line pulls out the default connection by key (by default it's `mysql`)
        $defaultConnection = $connections[$config->get('database.default')];

        // Now we simply copy the default connection information to our new connection.
        $newConnection = $defaultConnection;
        // Override the database name.
        $newConnection['database'] = $db_name;

        // This will add our new connection to the run-time configuration for the duration of the request.
        App::make('config')->set('database.connections.' . $db_name, $newConnection);
        
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/1_create_all-lower.sql'));
        /*DB::connection($db_name)->unprepared(File::get('../database/sql_files/2_movie_table.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/3_person_table.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/4_award_and_restriction_cat.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/5_staff_tables.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/6_movie_awards.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/7_scene_appearance.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/insert.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/update_2005.sql'));
        DB::connection($db_name)->unprepared(File::get('../database/sql_files/update_2005_2.sql'));*/
        
        
    }
    
    
    /**
     * Searches the highest number of the DB with $prefix and returns the numberr
     * 
     * @param $prefix
     * @return String with the highest Number of the DB after the prefix
     */
    function getMaxDBNumber($prefix)
    {
        // Gets all DB names with the prefix
        $databases = array_map('reset', DB::select("SHOW DATABASES LIKE '$prefix%'"));
        $maxNumber = 0;
        
        foreach($databases as &$dbName)
        {
            /* Checks if the Number after the prefix is higher the the previous 
             * max and replaces it with the newest highest value when this is the case.
             */
            if (intval(substr($dbName, strlen($prefix)))> $maxNumber)
            {
                $maxNumber = intval(substr($dbName, strlen($prefix)));
            }

        }
        
        return $maxNumber;
    }
    /**
     * Set the Permissions for the Account
     * 
     * @param $accName the given username
     * @param $accType tutor or student
     */
    function setPermissions($accName, $accType, $host = DEFAULT_HOST) {
        
        //TODO Tutor Stuff
        
        $movieDBprivate = $accName.'_movieDB';
        $testDBprivate = $accName.'_testDB';

        $priv = CustomDatabaseManager::getDBPrivileges('moviePrivate');
        DB::connection()->statement("GRANT $priv ON $movieDBprivate.* TO '$accName'@'$host'");

        $priv = CustomDatabaseManager::getDBPrivileges('testPrivate');
        DB::connection()->statement("GRANT $priv ON $testDBprivate.* TO '$accName'@'$host'");
        
        
    }
    
    /**
     * Lookup for Privileges on Databases
     * 
     * @param $accName the given username
     * @param $accType tutor or student
     */
    function getDBPrivileges($db) {

		$priv = array(
			'SELECT',
			'ALTER, CREATE, CREATE VIEW, DELETE, DROP, INSERT, SELECT, SHOW VIEW, UPDATE',
			'ALL',
			'USAGE'
		);

		switch ($db){
                        // Shared DBs
			case 'movieShared':
				return $priv[0];
			case 'testShared':
				return $priv[2];
                        // Private DBs
			case 'moviePrivate':
				return $priv[1];
			case 'testPrivate':
				return $priv[2];
			case 'tutor':			//for students personal DB
				return $priv[0];	
			default:
				return $priv[3];
			
		}
	}
    
    /**
     * Get the Default Password for the Account
     * 
     * @param $accName the given username
     * @param $accType tutor or student
     */
    function getDefaultPwd($accName) {
        // Dummy
        return 123456;
    }
    
}
