<?php

namespace App\Http\Controllers;

include '../app/CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;
use Exception;
use Illuminate\Support\Facades\File;

/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 14.03.2018
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


                // Get account and host names
                $names = $customDBManager->getAccountNamesAndHosts($accTypePrefix);
                $accNames = $names[0];
                $hostNames = $names[1];

                /*
                 * Get prefixes by getting account names. 
                 * 
                 * the getAccountNamesAndHosts fuction does not require accounts to have
                 * databases to exist. The getAccountNames function just returns account
                 * names when the database exists but does not require for an account to
                 * exist on any Host. 
                 * 
                 */
                
                $dbPrefixes = $customDBManager->getAccountNames($accTypePrefix);
                
                $accNameCount = count($accNames);
                $dbPrefixesCount = count($dbPrefixes);
                
                foreach ($accNames as $index => $accName) {
                    File::put('..\app\statusMessage.txt',"<h4><strong>Purging Database Server</strong></h4>
                                <p><strong>Deleting accounts:</strong> In progress ($index/$accNameCount)</p>
                                <p><strong>Deleting databases:</strong> On hold (0/$dbPrefixesCount)</p>");
                    // Drop account on its host
                    $customDBManager->dropAccount($accName, $hostNames[$index]);
                }
                
                foreach ($dbPrefixes as $index => $dbPrefix){
                    File::put('..\app\statusMessage.txt',"<h4><strong>Purging Database Server</strong></h4>
                                <p><strong>Deleting accounts:</strong> Completed ($accNameCount/$accNameCount)</p>
                                <p><strong>Deleting databases:</strong> In progress ($index/$dbPrefixesCount)</p>");
                    /*
                     *  Drop all existing private DBs 
                     */
                    $customDBManager->dropDB($dbPrefix . "_testDB");
                    $customDBManager->dropDB($dbPrefix . "_movieDB");
                }
                
                File::put('..\app\statusMessage.txt',"");
                return view('success', ['operation' => 'Purge Database Server', 'message' => 'Purged Database Server.']);
            } else {
                File::put('..\app\statusMessage.txt',"");
                return view('failure', ['operation' => 'Purge Database Server', 'pointOfFailure' => 'Validating Inputs', 'message' => 'Checkbox was not ticked.']);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            File::put('..\app\statusMessage.txt',"");
            return view('failure', ['operation' => 'Purge Database Server', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

}
