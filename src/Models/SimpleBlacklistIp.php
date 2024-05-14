<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleBlacklistIp extends Model
{
    protected $table = 'blacklist_ips';

    protected $fillable = ['ip'];
}
