<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'customers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'phone', 'id_type', 'id_number', 'email', 'address', 'job', 'npwp', 'image', 'status', 'created_by', 'updated_by',
        'phone_home'
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

    public static function validateSpk($data) {
        $where['phone'] = $data['customer_phone'];
        $validateId = parent::where($where)->first();

        $dataChange = [
            'first_name' => $data['customer_first_name'],
            'last_name' => isset($data['customer_last_name']) ? $data['customer_last_name'] : "",
            'phone_home' => $data['customer_phone_home'],
            'job'       => $data['customer_business'],
            'id_type'   => '0',
            'id_number' => '',
            'address'   => $data['customer_address'],
            'phone'     => $data['customer_phone'],
            'npwp'      => isset($data['npwp']) ? $data['npwp'] : null
        ];

        $image = [
            'id_number'     => $data['id_number'],
            'type'          => $data['id_type'],
            'created_by'    => Auth::id()
        ];

        if(isset($validateId->id)) { // CUSTOMER EXIST THEN UPDATE DATA
            $dataChange['updated_by'] = Auth::id();
            parent::where($where)->first()->update($dataChange);

            if(isset($data['image'])) {
                $image['filename'] = $data['image'];
                $image['customer_id'] = $validateId->id;
                $createImage = CustomerImage::create($image);
                $return['imageId'] = $createImage->id; 
            }
            $return['customerId'] = $validateId->id;
            return $return;
        }

        $dataChange['created_by'] = Auth::id();
        $created = parent::create($dataChange);

        if(isset($data['image'])) {
            $image['filename'] = $data['image'];
            $image['customer_id'] = $created->id;
            $createImage = CustomerImage::create($image);
            $return['imageId'] = $createImage->id;
        }
        $return['customerId'] = $created->id;
        return $return;
    }

    public static function getName($id) {
        $data = parent::find($id);
        return $data->first_name.' '.$data->last_name;
    }

    public function list() {
        $data = parent::select(DB::raw("id, first_name, last_name, phone, address, status, 
                                        (select type from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_type,
                                        (select id_number from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_number,
                                        (select filename from customer_image where customer_id = customers.id order by type, id desc limit 1) AS filename"
                                        ))
                        ->where('deleted_at', null)
                        ->get();
        return $data;
    }
}
