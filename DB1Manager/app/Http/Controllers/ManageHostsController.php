<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;

/**
 * The controller for the host manager used to add and remove Hosts
 *
 * @author mstu15
 * @version 16.12.2017
 */
class ManageHostsController extends Controller{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the manageHosts website
     *
     * @return manageHosts view
     */
    public function index()
    {
        return view('manageHosts');
    }
    
    /**
     * Adds a new Host
     * @param request the form data consists of:
     *      hostName the name from the new host
     * @return request echo
     */
    public function addHost(Request $request)
    {
        $hostName = $request->input('hostName');
        
        $customDBManager = new \CustomDatabaseManager(app(), app('db.factory'));
        
        $customDBManager->replicateMovieDB($hostName);
        
        return $request;
    }
    
    /**
     * Removes a Host
     * @param request the form data consists of:
     *      hostName the name from the host to be removed
     * @return request echo
     */
    public function removeHost(Request $request)
    {
        $hostName = $request->input('hostName');
        
        $customDBManager = new \CustomDatabaseManager(app(), app('db.factory'));
        
        $customDBManager->deleteDB($hostName);
        
        return $request;
    }
    
}
