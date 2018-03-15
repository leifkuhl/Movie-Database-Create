<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
/**
 * To get the Current Status Message
 *
 * @author mstu15
 * @version 15.03.2018
 */
class StatusMessageController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Returns the status message
     *
     * @return statusMessage.txt content
     */
    public function getMessage() {
        return File::get('../app/statusMessage.txt');
    }
}
