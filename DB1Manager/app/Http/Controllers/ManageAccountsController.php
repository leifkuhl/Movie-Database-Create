<?php

namespace App\Http\Controllers;

include '../app/CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;
use Exception;
use Illuminate\Support\Facades\File;

/**
 * The controller for the account manager used to create and list accounts
 * generate the login list and reset passwords
 *
 * @author mstu15
 * @version 15.03.2018
 */
class ManageAccountsController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
    }
    
     
    /**
     * Show the manageAccounts website
     *
     * @return manageAccounts view
     */
    public function index() {
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

        return view('manageAccounts', ['accounts' => $customDBManager->getAccountNames("")]);
    }

    /**
     * Create new accounts
     * @param request the form data consists of:
     *      accType student or tutor accounts,
     *      count number of accounts to create,
     *      semesterType summer or winter semester,
     *      semesterYear Year (e.g. for 2017/18: 1718, for 2018: 18)
     *      startIndex the starting account index
     * @return Success or Failure
     */
    public function createAccounts(Request $request) {
        $addedCount = 0;
        $countToAdd;
        try {
            $accType = $request->input('accType');
            $countToAdd = $request->input('count');
            $semesterType = $request->input('semesterType');
            $semesterYear = $request->input('semesterYear');
            $startIndex = $request->input('startIndex');
            $accTypePrefix;

            /* * * * *
             * Setup *
             * * * * */

            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));


            if ($countToAdd <= 0) {
                return view('failure', ['operation' => 'Create Accounts', 'pointOfFailure' => "Validating Inputs", 'message' => "Number of accounts: \"$countToAdd\" is 0 or less or not a Number"]);
            }

            // Checks if the semesterYear was left empty and fills it if that was the case
            if ($semesterYear == null) {
                $year = substr(date('Y'), 2);

                $semesterYear = $year;

                // for WS als add the nextyear to the semesterYear 
                if (strcasecmp("WS", $semesterType) == 0) {
                    $nextYear = substr(date('Y', strtotime('+1 year')), 2);
                    $semesterYear .= $nextYear;
                }
            }

            // Sets the accTypePrefix
            if (strcasecmp("Tutor", $accType) == 0) {
                $accTypePrefix = "t";
            } else {
                $accTypePrefix = "s";
            }

            $semesterTypeSuffix = strtolower($semesterType);
            $prefix = "db_$semesterTypeSuffix$semesterYear" . "_$accTypePrefix";

            // Set the startindex if left empty

            if ($startIndex == null) {
                $startIndex = $customDBManager->getMaxDBNumber($prefix);
                $startIndex += 1;
            } else if ($startIndex <= 0) {
                return view('failure', ['operation' => 'Create Accounts', 'pointOfFailure' => "Validating Inputs", 'message' => "Start index: \"$startIndex\" is 0 or less or not a Number"]);
            }

            // Get the Hosts

            $hosts = $customDBManager->getHosts();

            /* * * * * * * * * * * *
             * Create the Accounts *
             * * * * * * * * * * * */

            for ($i = $startIndex; $i < $countToAdd + $startIndex; $i++) {
                File::put('../app/statusMessage.txt',"Added $addedCount/$countToAdd Accounts");
                $accName = $prefix . $i;

                // Replicates MovieDB
                $customDBManager->replicateMovieDB($accName . '_movieDB');
                // Creates TestDB
                $customDBManager->createDB($accName . '_testDB');
                foreach ($hosts as $host) {
                    $customDBManager->createAccount($accName, $host);
                    // Sets Permissions
                    $customDBManager->setPermissions($accName, $accType, $host);
                }
                $addedCount++;
            }
            $highestAccountIndex = $startIndex + $addedCount - 1;
            $currentAccountCount = count($customDBManager->getAccountNames($accTypePrefix));
            File::put('../app/statusMessage.txt',"");
            return view('success', ['operation' => 'Create Accounts', 'message' => "Created: \"$addedCount\" from \"$countToAdd\" account(s).\nAccount prefix: \"$prefix\"\nTotal number of accounts: \"$currentAccountCount\"\nHighest index of an account with current prefix: \"$highestAccountIndex\""]);
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            File::put('../app/statusMessage.txt',"");
            return view('failure', ['operation' => 'Create Accounts', 'pointOfFailure' => "$fileName Line: $line", 'message' => "Created: \"$addedCount\" from \"$countToAdd\" account(s). Exception message: $message"]);
        }
    }

    /**
     * Delete accounts
     * @param request the form data consists of (all optional):
     *      select-all = "on"
     *      account Names (e.g. db1_ws1718_s1) = "on"
     * @return Success or Failure
     */
    public function deleteAccounts(Request $request) {
        $deletedCount = 0;
        $countToDelete;
        try {
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            // Get account and host names
            $names = $customDBManager->getAccountNamesAndHosts("");
            $accNames = $names[0];
            $hostNames = $names[1];
            
            // get unique Account Names
            $uniqueAccNames = $customDBManager->getAccountNames("");
            
            $countToDelete = count($request->all());
            if($request->input('select-all'))
            {
                $countToDelete--;
            }
            $maxNames = count($uniqueAccNames);
            
            // check every account if the checkbox to delete it is ticked
            for ($i = 0; $i < $maxNames; $i++) {
                File::put('../app/statusMessage.txt',"Deleted $deletedCount/$countToDelete account(s)");
                $input = $request->input($uniqueAccNames[$i]);
                // input is null if checkbox of the account is not checked otherwise it is "on"
                if ($input == null) {
                    continue;
                } else {
                    // Drop Dbs
                    $customDBManager->dropDB($uniqueAccNames[$i] . "_testDB");
                    $customDBManager->dropDB($uniqueAccNames[$i] . "_movieDB");
                    foreach ($accNames as $index => $accName) {
                        if (strcasecmp($uniqueAccNames[$i], $accName) == 0) {
                            // Drop account on its host
                            $customDBManager->dropAccount($accName, $hostNames[$index]);
                        }
                    }
                    $deletedCount++;
                }
            }
            File::put('../app/statusMessage.txt',"");
            return view('success', ['operation' => 'Delete Accounts', 'message' => "Deleted: \"$deletedCount\" from \"$countToDelete\" account(s)."]);
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            File::put('../app/statusMessage.txt',"");
            return view('failure', ['operation' => 'Delete Accounts', 'pointOfFailure' => "$fileName Line: $line", 'message' => "Deleted \"$deletedCount\" from \"$countToDelete\" account(s). Exception message: \"$message\""]);
        }
    }

    /**
     * List all Accounts of selected type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return new view with Account List or Failure
     */
    public function listAccounts(Request $request) {
        try {
            $accType = $request->input('accType');
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

            $names = $customDBManager->getAccountNamesAndHosts($accTypePrefix);
            $accNames = $names[0];
            $hostNames = $names[1];

            return view('accountList', ['tabledataAccNames' => $accNames, 'tabledataHostNames' => $hostNames, 'accounts' => $customDBManager->getAccountNames("")]);
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'List Accounts', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

    /**
     * Generate a list with default logins and passwords for selected account type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return new view with Login List or Failure
     */
    public function generateLoginList(Request $request) {
        try {
            $accType = $request->input('accType');
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
            // Get Names from all existing accounts
            $names = $customDBManager->getAccountNamesAndHosts($accTypePrefix);
            $accNames = $names[0];

            // remove duplicants from $accNames
            $accNames = array_unique($names[0]);

            $passwords = [];

            foreach ($accNames as $accName) {
                $passwords[$accName] = $customDBManager->getDefaultPwd($accName);
            }
            return view('loginList', ['tabledata' => $passwords, 'accounts' => $customDBManager->getAccountNames("")]);
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Generate Login List', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

    /**
     * Reset the password of selected account to default
     * @param request the form data consists of:
     *      accountName the full account name (e.g. db_ws1718_s1)
     * @return Success or Failure
     */
    public function resetPassword(Request $request) {
        try {
            $accName = $request->input('accountName');

            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            $pwd = $customDBManager->getDefaultPwd($accName);

            $success = $customDBManager->setPwd($accName, $pwd);
            if ($success) {
                return view('success', ['operation' => 'Reset Password', 'message' => "Reset Password for: \"$accName\" to \"$pwd\"."]);
            } else {
                return view('failure', ['operation' => 'Reset Password', 'pointOfFailure' => "Reset Password", 'message' => "Account: \"$accName\" does not exist or password is already set to default."]);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Reset Password', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

}
