<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;

/**
 * The controller for the account manager used to create and list accounts
 * generate the login list and reset passwords
 *
 * @author mstu15
 * @version 14.01.2018
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
        return view('manageAccounts');
    }

    /**
     * Create new accounts
     * @param request the form data consists of:
     *      accType student or tutor accounts,
     *      count number of accounts to create,
     *      semesterType summer or winter semester,
     *      semesterYear Year (e.g. for 2017/18: 1718, for 2018: 18)
     *      startIndex the starting account index
     * @return account prefix
     */
    public function createAccounts(Request $request) {
        $accType = $request->input('accType');
        $count = $request->input('count');
        $semesterType = $request->input('semesterType');
        $semesterYear = $request->input('semesterYear');
        $startIndex = $request->input('startIndex');
        $accTypePrefix;


        /* * * * *
         * Setup *
         * * * * */

        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

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


        $prefix = "db_$semesterType$semesterYear" . "_$accTypePrefix";

        // Set the startindex if left empty

        if ($startIndex == null) {
            $startIndex = $customDBManager->getMaxDBNumber($prefix);
            $startIndex += 1;
        }
        
        // Get the Hosts
        
        $hosts = $customDBManager->getHosts();
        
        /* * * * * * * * * * * *
         * Create the Accounts *
         * * * * * * * * * * * */

        for ($i = $startIndex; $i < $count + $startIndex; $i++) {
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
        }



        return $prefix;
    }

    /**
     * List all Accounts of selected type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return new view with Account List
     */
    public function listAccounts(Request $request) {
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

        $accNames = $customDBManager->getAccountNames($accTypePrefix);

        return view('accountList', ['tabledata' => $accNames]);
    }

    /**
     * Generate a list with default logins and passwords for selected account type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return new view with Login List
     */
    public function generateLoginList(Request $request) {
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
        $accNames = $customDBManager->getAccountNames($accTypePrefix);
        $passwords;


        foreach ($accNames as $accName) {
            $passwords[$accName] = $customDBManager->getDefaultPwd($accName);
        }


        return view('loginList', ['tabledata' => $passwords]);
    }

    /**
     * Reset the password of selected account to default
     * @param request the form data consists of:
     *      accountName the full account name (e.g. db_ws1718_s1)
     * @return request echo
     */
    public function resetPassword(Request $request) {
        $accName = $request->input('accountName');

        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

        $customDBManager->setPwd($accName, $customDBManager->getDefaultPwd($accName));

        return $request;
    }

}
