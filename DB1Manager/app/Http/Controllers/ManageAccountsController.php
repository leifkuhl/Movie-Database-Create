<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * The controller for the account manager used to create and list accounts
 * generate the login list and reset passwords
 *
 * @author mstu15
 * @version 16.12.2017
 */
class ManageAccountsController extends Controller{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the manageAccounts website
     *
     * @return manageAccounts view
     */
    public function index()
    {
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
     * @return request echo
     */
    
    public function createAccounts(Request $request)
    {
        $this->middleware('auth');
        $accType = $request->input('accType');
        $count = $request->input('count');
        $semesterType = $request->input('semesterType');
        $semesterYear = $request->input('semesterYear');
        $startIndex = $request->input('startIndex');
        
             
        if(strcasecmp("student", $accType) == 0)
        {
            
        }
        
        
        return $request;
    }
    /**
     * List all Accounts of selected type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return request echo
     */
    
    public function listAccounts(Request $request)
    {
        $accType = $request->input('accType');
        
        return $request;
    }
    /**
     * Generate a list with default logins and passwords for selected account type
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     * @return request echo
     */
    public function generateLoginList(Request $request)
    {
         $accType = $request->input('accType');
        
        return $request;
    }
    
    /**
     * Reset the password of selected account to default
     * @param request the form data consists of:
     *      accType student or tutor accounts (this is maybe redundant)
     *      accountName the full account name (e.g. db_ws1718_s1)
     * @return request echo
     */
    public function resetPassword(Request $request)
    {
        $accType = $request->input('accType');
        $accountName = $request->input('accountName');
        
        return $request;
    }
    
}
