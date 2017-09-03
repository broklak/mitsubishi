<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'role_user';

    public static function getRoleForUser($userId) {
    	$data = parent::where('user_id', $userId)->get();
    	$results = [];
    	foreach ($data as $key => $value) {
    		$results[] = $value->role_id;
    	}
    	return $results;
    }
}
