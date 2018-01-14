<?php

use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\App;
use App\User;

define('VPN', '%.vpn.tu-clausthal.de');   //VPN
define('RZ', '%.rz.tu-clausthal.de');   //RZ & WLAN for TUC
define('IFI', '%.in.tu-clausthal.de');    //IFI
define('TUC', '139.174.%');    //TUC
define('DEFAULT_HOST', [VPN, IFI, RZ]); //default host
/**
 * Extends the DatabaseManager with custom Database Functions
 * 
 *
 * @author mstu15
 * @version 14.01.2018
 */

class CustomDatabaseManager extends \Illuminate\Database\DatabaseManager {

    /**
     * Creates a new Account with set name on host
     * 
     * @param $accName Name of the Account to create
     * @param $host host for the account
     * 
     */
    function createAccount($accName, $host) {
        // Gets the Default Password for the user
        $pwd = CustomDatabaseManager::getDefaultPwd($accName);
        DB::statement("DROP USER IF EXISTS '$accName'@'$host'");
        DB::connection()->statement("FLUSH PRIVILEGES");
        DB::statement("CREATE USER '$accName'@'$host' IDENTIFIED BY '$pwd'");
        DB::connection()->statement("FLUSH PRIVILEGES");
    }

    /**
     * Drops the Account with set name on host
     * 
     * @param $accName Name of the Account to create
     * @param $host host for the account
     * 
     */
    function dropAccount($accName, $host) {
        DB::statement("DROP USER IF EXISTS '$accName'@'$host'");
    }

    /**
     * Creates a new Database with set name
     * 
     * @param $db_name Name of the Database to create
     */
    function createDB($db_name) {

        CustomDatabaseManager::dropDB($db_name);
        DB::connection()->statement("CREATE DATABASE IF NOT EXISTS $db_name");
    }

    /**
     * Deletes the Database with set name
     * 
     * @param $db_name Name of the Database to delete
     */
    function dropDB($db_name) {
        DB::connection()->statement("DROP DATABASE IF EXISTS $db_name");
    }

    /**
     * Set a Password for given user
     * 
     * @param $user the given user
     * @param $pwd the new password
     */
    function setPwd($accName, $pwd) {


        DB::connection()->statement("UPDATE mysql.user SET Password = PASSWORD('$pwd')
		WHERE LOWER(USER)=LOWER('$accName')");
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
        /* DB::connection($db_name)->unprepared(File::get('../database/sql_files/2_movie_table.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/3_person_table.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/4_award_and_restriction_cat.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/5_staff_tables.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/6_movie_awards.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/7_scene_appearance.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/insert.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/update_2005.sql'));
          DB::connection($db_name)->unprepared(File::get('../database/sql_files/update_2005_2.sql')); */
    }

    /**
     * Searches the highest number of the DB with $prefix and returns the numberr
     * 
     * @param $prefix
     * @return String with the highest Number of the DB after the prefix
     */
    function getMaxDBNumber($prefix) {
        // Gets all DB names with the prefix
        $databases = array_map('reset', DB::select("SHOW DATABASES LIKE '$prefix%'"));
        $maxNumber = 0;

        foreach ($databases as &$dbName) {
            /*
             * Checks if the Number after the prefix is higher than the previous 
             * max and replaces it with the highest value when this is the case.
             * Note: the intval function ignores the _moviedb _testdb part of
             * the databases because it is not a numeric value (PHP 7.1.11)
             */
            if (intval(substr($dbName, strlen($prefix))) > $maxNumber) {
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
     * @param $host host to set permissions on
     * 
     */
    function setPermissions($accName, $accType, $host) {

        //TODO Tutor Stuff
        // Set the names of movieDB and testDB
        $movieDBprivate = $accName . '_movieDB';
        $testDBprivate = $accName . '_testDB';

        $priv = CustomDatabaseManager::getDBPrivileges('moviePrivate');
        DB::connection()->statement("GRANT $priv ON $movieDBprivate.* TO '$accName'@'$host'");

        $priv = CustomDatabaseManager::getDBPrivileges('testPrivate');
        DB::connection()->statement("GRANT $priv ON $testDBprivate.* TO '$accName'@'$host'");
    }

    /**
     * Lookup for Privileges on Databases
     * 
     * @param $db string with information which db
     * 
     * @return String with Privileges
     */
    function getDBPrivileges($db) {

        $priv = array(
            'SELECT',
            'ALTER, CREATE, CREATE VIEW, DELETE, DROP, INSERT, SELECT, SHOW VIEW, UPDATE',
            'ALL',
            'USAGE'
        );

        switch ($db) {
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
            case 'tutor':   //for students personal DB
                return $priv[0];
            default:
                return $priv[3];
        }
    }

    /**
     * Get the Default Password for the Account
     * 
     * @param $accName the given username
     * 
     * @return default password
     */
    function getDefaultPwd($accName) {
        // dummy
        return 123456;
    }

    /**
     * Get the Account names 
     * 
     * @param $accTypePrefix student tutor or all accounts
     * 
     * @return array with account names
     */
    function getAccountNames($accTypePrefix) {
        return array_map('reset', DB::select("SELECT DISTINCT user FROM mysql.user WHERE user LIKE 'db_%_$accTypePrefix%' ORDER BY CHAR_LENGTH(user) ASC, user ASC"));
    }

    /**
     * Get the Grants for Account on host 
     * 
     * @param $accName Name of Account
     * @param $host Host from account
     * 
     * @return array with grants
     */
    function getGrants($accName, $hostname) {
        return array_map('reset', DB::select("SHOW GRANTS FOR '$accName'@'$hostname'"));
    }

    /**
     * Get the Hosts
     * 
     * @return array with hosts
     */
    function getHosts() {
        return array_map('reset', DB::select("SELECT * FROM dbManagerHosts"));
        ;
    }

    /**
     * Adds a new Host to the host Table
     * 
     * $hostName Hostname from the new Host
     * 
     * @return $success 0 if not successful 1 if successfull
     */
    function addHost($hostName) {
        CustomDatabaseManager::setupHosts();
        $success;
        try {
            // success will be set to the count of altered rows (1) or throw exception because the uniqueness is violated
            $success = DB::insert("INSERT INTO dbManagerHosts(Host) values (?)", [$hostName]);
        } catch (PDOException $ex) {
            $success = 0;
        }
        return $success;
    }

    /**
     * Removes a Host from the host Table
     * 
     * $hostName Hostname from the new Host
     * 
     * @return $success 0 if not successful 1 if successfull
     */
    function removeHost($hostName) {
        // success will be set to the count of altered rows 1 if it existed 0 if not
        $success = DB::delete("DELETE FROM dbManagerHosts WHERE LOWER(Host) = LOWER('$hostName')");
        return $success;
    }

    /**
     * Adds the host tabel to database if it doesn't exist 
     * 
     */
    function setupHosts() {
        // Check if dbManagerHosts table already exists
        $hostCheck = DB::select("SHOW TABLES LIKE 'dbManagerHosts'");
        if ($hostCheck == null) {
            DB::statement('CREATE TABLE IF NOT EXISTS dbManagerHosts(Host varchar(60) NOT NULL UNIQUE)');
            foreach (DEFAULT_HOST as $defaultHost) {
                DB::insert("INSERT INTO dbManagerHosts(Host) values ('$defaultHost')");
            }
        }
    }

    /**
     * Adds the users tabel to database if it doesn't exist 
     * 
     */
    function setupUsers() {
        // Check if dbManagerHosts table already exists
        $usersCheck = DB::select("SHOW TABLES LIKE 'users'");
        $createTable = false;
        if ($usersCheck == null) {
            // Create Table if it not exists
            $createTable = true;
        } else {
            // Check if the table is empty
            $count = array_map('reset',DB::select("SELECT COUNT(*) FROM users"));
            if (intval($count[0]) == 0) {
                // recreate Table if it is empty
                $createTable = true;
            }
        }
        if ($createTable) {
            DB::statement("DROP TABLE IF EXISTS users");
            DB::statement("CREATE TABLE IF NOT EXISTS users(id int(11) AUTO_INCREMENT PRIMARY KEY,"
                    . "name varchar(32) UNIQUE NOT NULL,"
                    . "password varchar(64) NOT NULL,"
                    . "created_at date NOT NULL,"
                    . "updated_at date NOT NULL,"
                    . "remember_token varchar(64)"
                    . ")");
            User::create([
                'name' => 'admin',
                'password' => bcrypt("dummy"),
            ]);
        }
    }

}
