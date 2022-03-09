<?php
namespace SRA\Passport\Facades;

use SRA\Passport\Facades\BaseFacade;

/**
 * @class \SRA\Passport\Facades\PassportFacade
 *
 * @method static object create_token($type, $username, $password, $expiration_date)
 * @method static object create_secondary_token($token_string)
 * @method static object create_token_record($user_id,$username,$password,$token,$refresh_token,$expiration_date)
 * @method static object findToken($request_token)
 *
 * @see \SRA\Passport\Repositories\PassportRepository
 */

class PassportFacade extends BaseFacade
{

}
