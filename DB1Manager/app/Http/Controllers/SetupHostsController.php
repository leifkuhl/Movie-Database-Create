<?php

namespace App\Http\Controllers;

include '../app/CustomDatabaseManager.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CustomDatabaseManager;

use Exception;
/**
 * The controller for hosts setup to create the host table and the default host
 *
 * @author mstu15
 * @version 14.03.2018
 */
class SetupHostsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the Host Setup view
     *
     * @return setupHosts view
     */
    public function index() {
        return view('setupHosts');
    }

    /**
     * Sets the Hosts table up
     *
     * @return Success or Failure
     */
    public function setupHosts() {
        try {
            $customDBManager = new CustomDatabaseManager(app(), app('db.factory'));

            $success = $customDBManager->setupHosts();

            if ($success) {
                return view('success', ['operation' => 'Setup Hosts', 'message' => 'Set host table up.']);
            } else {
                return view('failure', ['operation' => 'Setup Hosts', 'pointOfFailure' => "Setup Hosts Table", 'message' => "Table already exists. (Already set up)"]);
            }
        } catch (Exception $ex) {
            $line = $ex->getLine();
            $message = $ex->getMessage();
            $fileName = $ex->getFile();
            return view('failure', ['operation' => 'Setup Hosts', 'pointOfFailure' => "$fileName Line: $line", 'message' => "$message"]);
        }
    }

}
