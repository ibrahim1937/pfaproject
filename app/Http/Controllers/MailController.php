<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    public function sendmail(){
        $details = [
            'title' => 'Testing the email',
            'body' => 'body of the email'
        ];

        Mail::to('ibrahimchahboune@gmail.com')->send(new TestMail($details));
        dd('Email Sent');
    }
}
