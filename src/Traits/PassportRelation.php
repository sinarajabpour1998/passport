<?php
namespace SRA\Passport\Traits;

use SRA\Passport\Models\PassportTokens;

trait PassportRelation {

    public function passportTokens()
    {
        return $this->hasMany(PassportTokens::class, 'user_id');
    }

}
