<?php

namespace App\Http\Controllers;

include '..\\app\\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;

use Exception;
/**
 * The controller for users setup to create the users table and the default user
 *
 * @author mstu15
 * @version 14.03.2018
 */
class SetupUsersController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the Users Setup view
     *
     * @return setupUsers view
     */
    public function index()
    {
        return view('setupUsers');
    }
    /**
     * Sets the Users table up
     *
     * @return redirect to setupHosts or Failure
     */
    public function setupUsers() {
        try{
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $success = $customDBManager->setupUsers();
        if($success)
        {
                   return redirect('setupHosts'); 
        }else{
            return view('failure', ['operation' => 'Setup Users', 'pointOfFailure' => "Setup Users Table", 'message' => "Table already exists and is not empty. (Already set up)"]);
        
        }
        

        }
        catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Setup Users', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }
    
}
