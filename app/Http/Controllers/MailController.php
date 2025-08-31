<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\DemoMail;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            'title' => 'Form CED',
            'body' => 'this is a testing email',
        ];

        Mail::to('tii73145@gmail.com')->send(new DemoMail($mailData));
        dd('Email envoyé avec succès !');
    }
}