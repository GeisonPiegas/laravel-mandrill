<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MailSentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->headers->get('key'))
            return response()->json(["error" => "Necessário KEY para utilização do endpoint."], 401);

        if($request->headers->get('key') !== env('MAIL_SEND_KEY'))
            return response()->json(["error" => "KEY para acesso ao endpoint inválida."], 401);

        return $next($request);
    }
}
