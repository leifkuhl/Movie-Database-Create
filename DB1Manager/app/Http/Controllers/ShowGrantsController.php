<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use CustomDatabaseManager;

/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 13.01.2018
 */
class ShowGrantsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the showGrants website
     *
     * @return showGrants view
     */
    public function index() {
        return view('showGrants');
    }

    /**
     * Shows the grants of selected Account type on selected Host
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     *      hostName the name from the host
     * @return request echo
     */
    public function showGrants(Request $request) {
        $accType = $request->input('accType');
        $hostName = $request->input('hostName');

        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

        $accTypePrefix;

        // Sets the accTypePrefix
        if (strcasecmp("Tutor", $accType) == 0) {
            $accTypePrefix = "t";
        } else if (strcasecmp("Student", $accType) == 0) {
            $accTypePrefix = "s";
        } else {
            $accTypePrefix = "";
        }
        $accNames = $customDBManager->getAccountNames($accTypePrefix);

        // The Collumns for the html table
        $userCollumn;
        $hostCollumn;
        $databaseCollumn;
        $privilegesCollumn;


        $rowIndex = 0;

        // When no hostname provided get grants from all hosts  
        if ($hostName == null) {
            $hosts = $customDBManager->getHosts();
            foreach ($accNames as $accName) {
                foreach ($hosts as $host) {
                    $grants = $customDBManager->getGrants($accName, $host);
                    /*
                     * Decode Grants
                     * Grants have the Form:
                     * 
                     * GRANT {PRIVILEGES} ON {DATABASE} TO USER
                     * 
                     */
                    foreach ($grants as $grant) {

                        // Remove The Starting "GRANT "
                        $grant = substr($grant, 6);
                        // Find end of privileges by finding the " ON "
                        $endIndex = strpos($grant, " ON ");
                        // get the privileges with strlengt = $endIndex;
                        $privileges = substr($grant, 0, $endIndex);
                        // remove the Privileges and the " ON "(+4)
                        $grant = substr($grant, $endIndex + 4);
                        // Find end of Database by finding the " TO "
                        $endIndex = strpos($grant, " TO ");
                        // get the database with strlengt = $endIndex;
                        $database = substr($grant, 0, $endIndex);

                        $userCollumn[$rowIndex] = $accName;
                        $hostCollumn[$rowIndex] = $host;
                        $databaseCollumn[$rowIndex] = $database;
                        $privilegesCollumn[$rowIndex] = $privileges;
                        $rowIndex++;
                    }
                }
            }
        } else {
            foreach ($accNames as $accName) {
                $grants = $customDBManager->getGrants($accName, $hostName);
                foreach ($grants as $grant) {
                    // Remove The Starting "GRANT "
                    $grant = substr($grant, 6);
                    // Find end of privileges by finding the " ON "
                    $endIndex = strpos($grant, " ON ");
                    // get the privileges with strlengt = $endIndex;
                    $privileges = substr($grant, 0, $endIndex);
                    // remove the Privileges and the " ON "(+4)
                    $grant = substr($grant, $endIndex + 4);
                    // Find end of Database by finding the " TO "
                    $endIndex = strpos($grant, " TO ");
                    // get the database with strlengt = $endIndex;
                    $database = substr($grant, 0, $endIndex);

                    $userCollumn[$rowIndex] = $accName;
                    $hostCollumn[$rowIndex] = $host;
                    $databaseCollumn[$rowIndex] = $database;
                    $privilegesCollumn[$rowIndex] = $privileges;
                    $rowIndex++;
                }
            }
        }

        return view('grantList',['users' => $userCollumn, 'hosts' => $hostCollumn, 'databases' => $databaseCollumn, 'privileges' => $privilegesCollumn]);
    }

}
