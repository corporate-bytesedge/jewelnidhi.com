<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('m.bhagya@bytesedge.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('bhagyaprathima@gmail.com','bhagya');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"bhagya");
      Mail::send('mail', $data, function($message) {
         $message->to('m.bhagya@bytesedge.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('bhagyaprathima@gmail.com','bhagya');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      
      echo "Email Sent with attachment. Check your inbox.";
   }
}
