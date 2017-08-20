<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDealer extends Model
{
    protected $table = 'user_dealer';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'dealer_id'
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

    public static function insert($userId, $dealers) {
    	// DELETE FIRST
    	parent::where('user_id', $userId)->delete();

    	foreach ($dealers as $key => $value) {
    		parent::create([
    			'user_id' 	=> $userId,
    			'dealer_id'	=> $value
    		]);	
    	}
    }
}
