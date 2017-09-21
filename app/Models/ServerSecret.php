<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerSecret extends Model
{
    /**
     * @var string
     */
    protected $table = 'server_secret';

    /**
     * @var array
     */
    protected $fillable = [
        'secret'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
