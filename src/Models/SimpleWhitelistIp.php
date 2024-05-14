<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleWhitelistIp extends Model
{
    protected $table = 'simple_whitelist_ips';

    protected $fillable = ['ip'];
}
