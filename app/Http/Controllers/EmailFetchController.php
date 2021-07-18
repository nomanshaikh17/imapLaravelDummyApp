<?php

namespace App\Http\Controllers;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;

use Illuminate\Http\Request;
use Session;

class EmailFetchController extends Controller
{
    //
    public function connectPage(){
        return View('connect');
    }
    public function connect(Request $request){
        
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => 'imap.googlemail.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'protocol'      => 'imap',
            'username'      => $request->email,
            'password'      => $request->password
         ]);
         $client->connect();
        
         return redirect()->route('indexPage',['client'=>$client]);
    }

    public function index(Request $request){
        set_time_limit(0);
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => 'imap.googlemail.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'protocol'      => 'imap',
            'username'      => $request->email,
            'password'      => $request->password
         ]);

        $client->connect();
        Session::put('email', $request->email);
        Session::put('password', $request->password);
        $folders = $client->getFolderByName('Sent Mail');
        $messages = $folders->messages()->all()->get();
        return View('emails')->with(['messages'=>$messages]);       
    }

        public function custom_search(Request $request){
            set_time_limit(0);
        
            $cm = new ClientManager();
            $client = $cm->make([
                'host'          => 'imap.googlemail.com',
                'port'          => 993,
                'encryption'    => 'ssl',
                'validate_cert' => true,
                'protocol'      => 'imap',
                'username'      => Session::get('email'),
                'password'      => Session::get('password')
             ]);
    
            $client->connect();
            $folders = $client->getFolderByName('Sent Mail');
            $messages = $folders->messages()->to($request->search)->all()->get();
            return View('emails')->with(['messages'=>$messages]);   

        }
    
}
