<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserLogs extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'desc', 'user_id'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function createLog($desc) {
    	return parent::create([
    		'user_id' 	=> Auth::id(),
    		'desc'		=> $desc
    	]);
    }
}
