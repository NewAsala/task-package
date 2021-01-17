<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Mail\Email;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
    public function sendEmail(Request $request) {

        $email = 'asala.alakrad@gmail.com';
   
        $mailData = [
            'reciver' =>  $request->reciver,
            'sender' =>  $request->sender,
            'body' =>  $request->body
        ];
  
        Mail::to($email)->send(new Email($mailData));
   
        //$user = User::where('name','asala')->first();
        
        // Mail::send('bridge::Email.Email',$user->toArray(),function($mssage){
        //     $mssage->to('asala.alakrad@gmail.com','NewAsala')->subject('subject');
        // });
        
        return response()->json([
            'message' => 'Email has been sent.'
        ], Response::HTTP_OK);
    }

    public function sendSMS(Request $request) {
   
        $data = [
            'name' =>$request->name,
            'msg' => $request->message,
        ];

        //dd('send SMS' ,$data);
        
        return response()->json([
            'message' => 'SMS has been sent.'
        ], Response::HTTP_OK);
    }
}
