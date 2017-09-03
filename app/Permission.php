<?php 

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	public static function list() {
    	$data = parent::all();
    	$result = [];

    	foreach ($data as $key => $value) {
    		$getKey = explode('.', $value->name);

    		if(stristr($value->name, 'report')) {
    			$result['Report'][$value->id] = $value->name;
    		} else {
    			$keyBox = [];
	    		foreach ($getKey as $keyCheck => $valueCheck) {
	    			if($keyCheck > 0) {
	    				$keyBox[] = $valueCheck;
	    			}
	    		}
	    		$keyBoxString = ucwords(implode(' ', $keyBox));

	    		$result[$keyBoxString][$value->id] = $value->name;
    		}
    	}

    	return $result;
    }
}