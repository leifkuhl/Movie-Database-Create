<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use CustomDatabaseManager;
use Exception;

/**
 * The controller for the host manager used to add and remove Hosts
 *
 * @author mstu15
 * @version 15.01.2017
 */
class ManageHostsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the manageHosts website
     *
     * @return manageHosts view
     */
    public function index() {
        return view('manageHosts');
    }

    /**
     * List all Hosts
     * 
     * @return view with Hostlist or Failure
     */
    public function listHosts() {
        try {
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            $hostNames = $customDBManager->getHosts();

            return view('hostList', ['tabledata' => $hostNames]);
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'List Hosts', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

    /**
     * Adds a new Host
     * @param request the form data consists of:
     *      hostName the name from the new host
     * @return Success or Failure
     */
    public function addHost(Request $request) {
        try {
            $hostName = $request->input('hostName');
            
            if(strlen($hostName) == 0){
                return view('failure', ['operation' => 'Add Host', 'pointOfFailure' => "Set Host Name", 'message' => "Host name is empty."]);
            }
            
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            $success = $customDBManager->addHost($hostName);

            if ($success > 0) {
                $count = 0;
                // Add Accounts on new Host
                foreach ($customDBManager->getAccountNames("") as $accName) {
                    // Find position of last "_" to get Account Type (db_SSYY_sx or _tx)
                    // $accType is currently unused
                    $pos = strripos($accName, '_');
                    $accTypePrefix = substr($accName, $pos + 1, 1);
                    if ($accTypePrefix == "s") {
                        $accType = "Student";
                    } else if ($accTypePrefix == "t") {
                        $accType = "Tutor";
                    } else {
                        continue;
                    }
                    // Create Account
                    $customDBManager->createAccount($accName, $hostName);
                    // Set Permissions
                    $customDBManager->setPermissions($accName, $accType, $hostName);
                    $count++;
                }
                return view('success', ['operation' => 'Add Host', 'message' => "Added Host: \"$hostName\" and created: \"$count\" accounts on it."]);
            } else {
                return view('failure', ['operation' => 'Add Host', 'pointOfFailure' => "Add Host", 'message' => "Failed to add host or host already existed."]);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Add Host', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

    /**
     * Removes a Host
     * @param request the form data consists of:
     *      hostName the name from the host to be removed
     * @return request echo
     */
    public function removeHost(Request $request) {
        try {
            $hostName = $request->input('hostName');
            if(strlen($hostName) == 0){
                return view('failure', ['operation' => 'Remove Host', 'pointOfFailure' => "Set Host Name", 'message' => "Host name is empty."]);
            }
            
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            $success = $customDBManager->removeHost($hostName);
            if ($success > 0) {
                 $count = 0;
                // Delete Accounts on Host
                foreach ($customDBManager->getAccountNames("") as $accName) {
                    $customDBManager->dropAccount($accName, $hostName);
                    $count++;
                }
                return view('success', ['operation' => 'Remove Host', 'message' => "Remove Host: \"$hostName\" and dropped: \"$count\" accounts from it."]);
            } else {
                return view('failure', ['operation' => 'Remove Host', 'pointOfFailure' => "Remove Host", 'message' => "Failed to remove host. Host did not exist. (wrong name?)"]);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Remove Host', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

}
