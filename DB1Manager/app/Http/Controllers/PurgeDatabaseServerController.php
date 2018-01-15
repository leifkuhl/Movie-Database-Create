<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;

use Exception;

/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 15.01.2017
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
     * @return Success or Failure
     */
    public function purgeDatabaseServer(Request $request) {
        try {
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
                        $customDBManager->dropAccount($accName, $host);
                    }
                    /*
                     *  Drop the private DBs (movieDB last because it is used to
                     *  get the account names
                     */
                    $customDBManager->dropDB($accName . "_testDB");
                    $customDBManager->dropDB($accName . "_movieDB");
                }
                return view('success', ['operation' => 'Purge Database Server', 'message' => 'Purged Database Server.']);
            } else {
                return view('failure', ['operation' => 'Purge Database Server', 'pointOfFailure' => 'Sure Check', 'message' => 'Checkbox was not ticked.']);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Purge Database Server', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

}
