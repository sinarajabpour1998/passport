<?php

namespace SRA\Passport\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportTokens extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "token", "status", "last_used_at", "username", "password", "refresh_token", "expired_at"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
