<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttachment extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_attachment';

    /**
     * @var array
     */
    protected $fillable = [
    	"order_id", "file"
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public static function createData($file, $orderId = null) {
    	if(count($file) > 0) {
    		foreach ($file as $key => $value) {
    		 $name = str_replace(' ', '_', $orderId.'-'.$value->getClientOriginalName());
    		 $value->move(base_path() . '/public/images/order/'.$orderId.'/', $name);
    		 parent::create([
    		 	'order_id'	=> $orderId,
    		 	'file'		=> $name
    		 ]);
    	}
    	}
    }
}
