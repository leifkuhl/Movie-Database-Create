<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 16.12.2017
 */
class ShowGrantsController extends Controller{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the showGrants website
     *
     * @return showGrants view
     */
    public function index()
    {
        return view('showGrants');
    }
    
    /**
     * Shows the grants of selected Account type on selected Host
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     *      hostName the name from the host
     * @return request echo
     */
    public function showGrants(Request $request)
    {
        $accType = $request->input('accType');
        $hostName = $request->input('hostName');
        
        return $request;
    }
}
