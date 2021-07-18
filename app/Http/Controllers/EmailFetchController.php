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
        //dd($request->email);
        //dd($request->password);
        
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => 'imap.googlemail.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'protocol'      => 'imap',
            'username'      => $request->email,
            //'password'      => 'shwiwqfyfeqvzish'
            'password'      => $request->password
         ]);
         //dd($client->connect());
         $client->connect();
        
         return redirect()->route('indexPage',['client'=>$client]);
    }

    public function index(Request $request){
        set_time_limit(0);
        
        $cm = new ClientManager();

// or use an array of options instead
//$cm = new ClientManager($options = []);

/** @var \Webklex\PHPIMAP\Client $client */
//$client = $cm->account('account_identifier');
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
        //  $folders = $client->getFolders($hierarchical = true);
        //   dd($folders);
           $folders = $client->getFolderByName('Sent Mail');
        //$folders = $client->getFolders();
        //$folders = $client->getFolderByPath('[Gmail]/Sent Mail');
        //$folders = $client->getFolderByPath('INBOX');
        //  dd($folders);
         $messages = $folders->messages()->all()->get();
         //->to('hassannaqvi118@gmail.com')
        //dd(count($messages));
        // foreach($messages as $message){
        //     dd($message->getHeader());
        // }

        return View('emails')->with(['messages'=>$messages]);   
        // foreach($messages as $message){
            
        //     echo $message->getSubject().'<br />';
        //     echo 'Attachments: '.$message->getAttachments()->count().'<br />';
        //     echo $message->getHTMLBody();
            //dd("sdsd");
            //Move the current Message to 'INBOX.read'
            // if($message->move('INBOX.read') == true){
            //     echo 'Message has ben moved';
            // }else{
            //     echo 'Message could not be moved';
            // }
        }

        public function custom_search(Request $request){
            // dd($client->isConnected());
            // if(!$client->isConnected()){
            //     if($client->checkConnection()){
            //         $client->reconnect();
            //     }
            // }
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
        //  foreach($folders as $folder){
        //      //dd($folder);
        //     //dd($folder);
        //     //Get all Messages of the current Mailbox $folder
        //     /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
        //     //$paginator = $messages->paginate($per_page = 5, $page = null, $page_name = 'imap_page');
        //     $messages = $folder->messages()->all()->get();
        //    // dd(count($messages));   
        //     /** @var \Webklex\PHPIMAP\Message $message */
        //     foreach($messages as $message){
        //         //echo "sds";
        //         echo $message->getSubject().'<br />';
        //         echo 'Attachments: '.$message->getAttachments()->count().'<br />';
        //         echo $message->getHTMLBody();
        //         //dd("sdsd");
        //         //Move the current Message to 'INBOX.read'
        //         // if($message->move('INBOX.read') == true){
        //         //     echo 'Message has ben moved';
        //         // }else{
        //         //     echo 'Message could not be moved';
        //         // }
        //     }
        //}
         //dd($folders);

    //}
    
}
