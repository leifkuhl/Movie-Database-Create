<?php

namespace App\Http\Controllers;

include '..\app\CustomDatabaseManager.php';

use Illuminate\Http\Request;
use CustomDatabaseManager;

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
     * List all Hosts
     * 
     * @return view with Hostlist
     */
    public function listHosts()
    {
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $hostNames = $customDBManager->getHosts();
        
        return view ('hostList',['tabledata' => $hostNames]);
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
        
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $success = $customDBManager->addHost($hostName);
        
        if($success > 0)
        {
            // Add Accounts on new Host
            foreach ($customDBManager->getAccountNames("") as $accName)
            {
                // Find position of last "_" to get Account Type (db_SSYY_sx or _tx)
                // $accType is currently unused
                $pos = strripos($accName,'_');
                $accTypePrefix = substr($accName, $pos+1,1);
                if($accTypePrefix == "s")
                {
                    $accType = "Student";
                }else if($accTypePrefix == "t"){
                    $accType = "Tutor";
                }else{
                    continue;
                }
                // Create Account
                $customDBManager->createAccount($accName,$hostName);
                // Set Permissions
                $customDBManager->setPermissions($accName, $accType, $hostName);
            }
        }
        else
        {
            return "FAILED TO ADD HOST";
        }
        
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
        
        $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));
        
        $success = $customDBManager->removeHost($hostName);
        if($success > 0)
        {
            // Delete Accounts on Host
            foreach ($customDBManager->getAccountNames("") as $accName)
            {
                $customDBManager->dropAccount($accName,$hostName);
            }
        }
        else
        {
            return "FAILED TO REMOVE HOST";
        }
        
        return $request;
    }
    
}
