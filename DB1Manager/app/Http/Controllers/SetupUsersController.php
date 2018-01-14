<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;
/**
 * The controller for users setup to create the users table and the default user
 *
 * @author mstu15
 * @version 13.01.2018
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
    public function setupUsers() {
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $customDBManager->setupUsers();
        
        return redirect('setupHosts');
    }
    
}
