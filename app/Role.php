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

    public static function getRoleName($id) {
        $data = parent::find($id);
        return $data->name;
    }

    public static function getSupervisor() {
    	$data = parent::select('first_name', 'last_name', 'users.id')
    				->join('role_user', 'role_user.role_id', '=', 'roles.id')
    				->join('users', 'role_user.user_id', '=', 'users.id')
    				->where('name', 'supervisor')->get();
    	return $data;
    }
}