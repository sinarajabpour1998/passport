<?php

namespace SRA\Passport\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SRA\Passport\Facades\PassportFacade;
use SRA\Passport\Http\Requests\PassportTokenRequest;
use SRA\Passport\Models\PassportTokens;

class PassportController extends Controller
{
    public function list()
    {
        return response([
            'status' => 200,
            "message" => "ok.",
            "data" => \auth()->user()->passportTokens
        ]);
    }

    public function enable_list()
    {
        return response([
            'status' => 200,
            "message" => "ok.",
            "data" => \auth()->user()->passportTokens()->where('status', 'enable')->get()
        ]);
    }

    public function create(PassportTokenRequest $request)
    {
        // check if username already exists
        $passport_token = PassportTokens::query()->where("username", '=', $request->username)->first();
        if (!is_null($passport_token)) {
            return response([
                'status' => 422,
                "message" => "username already exists."
            ]);
        }

        $password = bcrypt($request->password);

        $expiration_date = Carbon::now()->addDays(config('passport.token.expiration'));

        $token = PassportFacade::create_token('token', $request->username, $password, $expiration_date);

        // generate refresh_token

        $refresh_token = PassportFacade::create_token('refresh_token', $request->username, $password, $expiration_date);

        PassportFacade::create_token_record(Auth::user()->id,$request->username, $password, $token, $refresh_token, $expiration_date);

        return response([
            'status' => 200,
            'token' => $token->encrypted,
            'refresh_token' => $refresh_token->encrypted,
            'expiration_date' => $expiration_date->toDateTimeString()
        ], 200);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => ["required", "string"]
        ]);
        $token_by_refresh = PassportTokens::query()
            ->where("refresh_token", '=', $request->refresh_token)
            ->first();
        if (is_null($token_by_refresh)) {
            return response([
                'status' => 422,
                "message" => "Token does not exists."
            ]);
        }
        if ($token_by_refresh->status != "enable") {
            return response([
                'status' => 422,
                "message" => "Token is disabled."
            ]);
        }
        // create new token
        $new_expiration_date = Carbon::now()->addDays(config('passport.token.expiration'));
        $new_token_string = str_replace($token_by_refresh->expired_at, $new_expiration_date, Crypt::decryptString($token_by_refresh->token));
        $token = PassportFacade::create_secondary_token($new_token_string);

        $new_refresh_token_string = str_replace($token_by_refresh->expired_at, $new_expiration_date, Crypt::decryptString($token_by_refresh->refresh_token));
        $refresh_token = PassportFacade::create_secondary_token($new_refresh_token_string);

        PassportFacade::create_token_record($token_by_refresh->user_id,$token_by_refresh->username, $token_by_refresh->password, $token, $refresh_token, $new_expiration_date);

        // disable old token
        $token_by_refresh->update([
            'status' => 'disable'
        ]);

        return response([
            'status' => 200,
            'token' => $token->encrypted,
            'refresh_token' => $refresh_token->encrypted,
            'expiration_date' => $new_expiration_date->toDateTimeString()
        ], 200);

    }

    public function revoke(Request $request)
    {
        $request->validate([
            'token' => ["required", "string"]
        ]);
        $token = PassportTokens::query()
            ->where("token", '=', $request->token)
            ->first();
        if (is_null($token)) {
            return response([
                'status' => 422,
                "message" => "Token does not exists."
            ]);
        }
        $token->delete();

        return response([
            'status' => 200,
            'message' => "Token has been deleted."
        ], 200);

    }
}
