<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;
/**
 * The controller for hosts setup to create the host table and the default host
 *
 * @author mstu15
 * @version 13.01.2018
 */
class SetupHostsController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the Host Setup view
     *
     * @return setupHosts view
     */
    public function index()
    {
        return view('setupHosts');
    }
    public function setupHosts() {
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $customDBManager->setupHosts();
        
        return redirect('home');
    }
    
}
