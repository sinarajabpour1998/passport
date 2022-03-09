<?php

namespace SRA\Passport\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SRA\Passport\Models\PassportTokens;

class PassportRepository
{
    public function create_token($type, $username, $password, $expiration_date)
    {
        switch ($type) {
            case "token":

                $token_string = $username . '+' . $password . '+' . $expiration_date . '+' . random_bytes(60) . "+" . "token";

                break;
            case "refresh_token":

                $token_string = $password . '+' . $username . '+' . $expiration_date . '+' . random_bytes(60) . "+" . "refresh_token";

                break;
            default:
                return null;
        }

        // generate token
        $token = Crypt::encryptString($token_string);

        return (object) [
            'encrypted' => $token
        ];
    }

    public function create_secondary_token($token_string)
    {
        $token = Crypt::encryptString($token_string);

        return (object) [
            'encrypted' => $token
        ];
    }

    public function create_token_record($user_id,$username,$password,$token,$refresh_token,$expiration_date)
    {
        return PassportTokens::query()->create([
            'user_id' => $user_id,
            'username' => $username,
            'password' => $password,
            'token' => $token->encrypted,
            'refresh_token' => $refresh_token->encrypted,
            'expired_at' => $expiration_date,
            'status' => 'enable'
        ]);
    }

    public function findToken($request_token)
    {
        $token = PassportTokens::query()
            ->where("token", '=', $request_token)
            ->where("status", '=', 'enable')
            ->first();
        if (is_null($token)) {
            return (object) [
                'status' => 422,
                'message' => "Invalid Token."
            ];
        }
        if (Carbon::now() > $token->expired_at) {
            return (object) [
                'status' => 422,
                'message' => "Token has been expired."
            ];
        }else {
            $token->update([
                'last_used_at' => Carbon::now()
            ]);
            return (object) [
                'status' => 200,
                'message' => "ok."
            ];
        }
    }
}
