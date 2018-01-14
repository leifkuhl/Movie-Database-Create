<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;

/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 14.01.2017
 */
class PurgeDatabaseServerController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the purgeDatabaseServer website
     *
     * @return showGrants view
     */
    public function index() {
        return view('purgeDatabaseServer');
    }

    /**
     * Purges the database server
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     *      sure checkbox if the user is sure (values are "yes" or null)
     * @return request echo
     */
    public function purgeDatabaseServer(Request $request) {
        $accType = $request->input('accType');
        $sure = $request->input('sure');

        // if user is sure
        if (strcmp($sure, "yes") == 0) {
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
            $accTypePrefix;
            
            // Sets the accTypePrefix
            if (strcasecmp("Tutor", $accType) == 0) {
                $accTypePrefix = "t";
            } else {
                $accTypePrefix = "s";
            }
            
            $hosts = $customDBManager->getHosts();
            $accNames = $customDBManager->getAccountNames($accTypePrefix);
            
            foreach ($accNames as $accName) {
                foreach ($hosts as $host) {
                    // Drop the Accounts
                     $customDBManager->dropAccount($accName, $host);
                }
                // Drop the private DBs
                $customDBManager->dropDB($accName."_movieDB");
                $customDBManager->dropDB($accName."_testDB");
            }

        }
        return $request;
    }

}
