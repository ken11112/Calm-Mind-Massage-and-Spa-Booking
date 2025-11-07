<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookSetting extends Model
{
    protected $table = 'facebook_settings';

    protected $fillable = [
        'page_access_token',
        'page_id',
        'page_username',
        'admin_psid',
        'verify_token',
    ];
}
