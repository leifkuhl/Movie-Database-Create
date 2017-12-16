<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * The controller for the showGrants to show Grants
 *
 * @author mstu15
 * @version 16.12.2017
 */

class PurgeDatabaseServerController extends Controller {
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the purgeDatabaseServer website
     *
     * @return showGrants view
     */
    public function index()
    {
        return view('purgeDatabaseServer');
    }
    
     
    /**
     * Purges the database server
     * @param request the form data consists of:
     *      accType all, student or tutor accounts
     *      sure checkbox if the user is sure (values are "yes" or null)
     * @return request echo
     */
    public function purgeDatabaseServer(Request $request)
    {
        $accType = $request->input('accType');
        $sure = $request->input('sure');
        
        return $request;
    }
    
}
