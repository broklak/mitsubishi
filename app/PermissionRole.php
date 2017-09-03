<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    /**
     * @var string
     */
    protected $table = 'permission_role';

    public static function getRolePermission($roleId) {
    	$data = parent::where('role_id', $roleId)->get();
    	$results = [];
    	foreach ($data as $key => $value) {
    		$results[] = $value->permission_id;
    	}
    	return $results;
    }

    public static function getSPKApprover() {
    	$data = parent::select('role_id', 'roles.name')
    					->where('permissions.name', 'approve.spk')
                        ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
    					->join('roles', 'roles.id', '=', 'permission_role.role_id')
    					->get();
    	$result = [];

    	foreach ($data as $key => $value) {
    		$result[$value->role_id] = $value->name;
    	}
    	return $result;
    }
}
