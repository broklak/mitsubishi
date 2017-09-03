<?php 

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	public function users()
	{
		return $this->belongsToMany(
			config('auth.providers.users.model'), 
			config('entrust.role_user_table'),
			config('entrust.role_foreign_key'),
			config('entrust.user_foreign_key'));
	}

	public static function getName($id) {
        $data = parent::find($id);
        return $data->display_name;
    }
}