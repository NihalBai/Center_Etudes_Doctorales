<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller
{
    

public function index(Request $request)
{
    return view('emails.sendmail');
}
public function store(Request $request)
{
    $mail=new PHPMailer(true);
    try{
        $mail->SMTPDebug = 0; 
        //Server settings
        $mail->isSMTP();
        $mail->Host = config('mail.host');
        $mail->SMTPAuth = true;
        $mail->Username = config('mail.username');
        $mail->Password = config('mail.password');
        $mail->SMTPSecure = config('mail.encryption');
        $mail->Port = config('mail.port');
        //Recipients
        $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
        $mail->addAddress('adresse_destinataire@example.com'); // Adresse du destinataire
        // $mail->addReplyTo(config('mail.from.address'), config('mail.from.name'));

        $mail->isHTML(true);
        $subject = $request->subject;
        $body = $request->body;

       if( !$mail->send()){
        return back()->with('error','email not sent');

       }else
       {
        return back()->with('success','email envoye avec succes!');
    }



    
        
    }
    catch (Exception $e) {
        // Gérer les exceptions
    }
}
}