<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    public function handle(Request $request, Closure $next)
    {
        // Capture les informations de base
        $logData = [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user' => Auth::check() ? Auth::user()->username : 'guest',
            'payload' => $request->all()
        ];

        // Log l'entrée de la requête
        Log::channel('api')->info('API Request', $logData);

        // Continue le traitement de la requête
        $response = $next($request);

        // Log la réponse
        $logData['response_status'] = $response->status();
        Log::channel('api')->info('API Response', $logData);

        return $response;
    }
}
