<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mail;
use App\Http\Requests as Requests;
use App\Events as Events;

class MailController extends Controller
{
    /**
     * create function
     *
     * @param Requests\MailCreateRequest $request
     * @return void
     */
    public function create(Requests\MailCreateRequest $request){
        
        $mail = new Mail();
        $mail->name = $request->input("nome");
        $mail->email = $request->input("email");
        $mail->subject = $request->input("assunto");
        $mail->body = $request->input("corpo_email");
        $mail->send_date = $request->input("agendar");

        if($mail->save()){
            event(new Events\CreatedMailEvent($mail));
            return response()->json(["success" => "Sucesso ao criar e-mail."], 201);
        }
        return response()->json(["error" => "Não foi possível criar e-mail."], 400);

    }
}