<?php

namespace SRA\Passport\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SRA\Passport\Facades\PassportFacade;

class ValidateToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has("token")) {
            return response([
                'status' => 422,
                'message' => "token is required."
            ]);
        } else {
            $token_check_result = PassportFacade::findToken($request->token);
            if ($token_check_result->status != 200) {
                return response([
                    'status' => $token_check_result->status,
                    'message' => $token_check_result->message
                ]);
            }
        }
        return $next($request);
    }
}
