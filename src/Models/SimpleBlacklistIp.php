<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleBlacklistIp extends Model
{
    protected $table = 'simple_blacklist_ips';

    protected $fillable = ['ip'];
}
