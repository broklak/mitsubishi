<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderHead extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'order_head';

    /**
     * @var array
     */
    protected $fillable = [
        'spk_code', 'spk_doc_code', 'date', 'npwp_image', 'stnk_name', 'stnk_address', 'faktur_conf', 'model_id', 'type_id', 'color', 'dealer_id', 'customer_id',
        'car_year', 'qty', 'plat', 'bbn_type', 'karoseri', 'karoseri_type', 'karoseri_spec', 'karoseri_price', 'status', 'created_by', 'updated_by'
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

    public function list($where = []) {
        $user = Auth::user();
        $job = $user->job_position_id;
        $data = parent::select(DB::raw("order_head.id, spk_code, first_name, last_name, model_id, type_id,date, 
                            (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) AS is_approved"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->where($where)
                        ->orderBy('date', 'desc')
                        ->get();

        return $data;
    }

    public static function notApproved() {
        $user = Auth::user();
        $job = $user->job_position_id;
        $data = parent::select(DB::raw("order_head.id, spk_code, first_name, last_name, model_id, type_id,date,
                            (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) AS is_approved"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->whereRaw("(select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) = 0")
                        ->orderBy('date', 'desc')
                        ->get();

        return $data;
    }

    public function create($data) {
    	return parent::create([
    		'dealer_id'		=> $data['dealer_id'],
    		'customer_id'	=> $data['customer_id'],
    		'spk_code'		=> $this->generateSPKCode($data['dealer_id']),
    		'spk_doc_code'	=> $data['spk_doc_code'],
    		'date'			=> $data['date'],
    		'npwp_image'	=> (isset($data['npwp_image'])) ? $data['npwp_image'] : null,
    		'stnk_name'		=> $data['stnk_name'],
    		'stnk_address'	=> $data['stnk_address'],
    		'faktur_conf'	=> $data['faktur_conf'],
    		'model_id'		=> CarType::getModel($data['type_id']),
    		'type_id'		=> $data['type_id'],
            'car_year'      => $data['car_year'],
    		'color'		    => $data['color'],
    		'qty'			=> $data['qty'],
    		'plat'			=> $data['plat'],
    		'bbn_type'		=> $data['bbn_type'],
    		'karoseri'		=> $data['karoseri'],
    		'karoseri_type'	=> $data['karoseri_type'],
    		'karoseri_spec'	=> $data['karoseri_spec'],
    		'karoseri_price'	=> parseMoneyToInteger($data['karoseri_price']),
    		'status'		=> 0,
    		'created_by'	=> $data['created_by']
    	]);
    }

    public function updateData($id, $data) {
        return parent::find($id)->update([
            'dealer_id'     => $data['dealer_id'],
            'customer_id'   => $data['customer_id'],
            'spk_doc_code'  => $data['spk_doc_code'],
            'date'          => $data['date'],
            'npwp_image'    => (isset($data['npwp_image'])) ? $data['npwp_image'] : null,
            'stnk_name'     => $data['stnk_name'],
            'stnk_address'  => $data['stnk_address'],
            'faktur_conf'   => $data['faktur_conf'],
            'model_id'      => CarType::getModel($data['type_id']),
            'type_id'       => $data['type_id'],
            'car_year'      => $data['car_year'],
            'color'         => $data['color'],
            'qty'           => $data['qty'],
            'plat'          => $data['plat'],
            'bbn_type'      => $data['bbn_type'],
            'karoseri'      => $data['karoseri'],
            'karoseri_type' => $data['karoseri_type'],
            'karoseri_spec' => $data['karoseri_spec'],
            'karoseri_price'    => parseMoneyToInteger($data['karoseri_price']),
            'updated_by'    => $data['updated_by']
        ]);
    }

    private function generateSPKCode($dealerId) {
        $date = trim(date('Ymd'));
        $code = $this->getDealerCode($dealerId);
        $lastId = parent::orderBy('id', 'desc')->first();
        $id = (isset($lastId->id)) ? $lastId->id + 1 : 1;
    	return "SPK-$id/$code/$date";
    }

    protected function getDealerCode($dealerId) {
        $name = Dealer::getName($dealerId);
        $words = explode(" ", $name);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= strtoupper($w[0]);
        }

        return $acronym;
    }

    public function setNotif() {
        $spkNotApproved = count($this->notApproved());
        $doNotChecked = count(DeliveryOrder::notChecked());
        $session = [
            'spk_notif' => $spkNotApproved,
            'do_notif' => $doNotChecked,
            'total_notif' => $doNotChecked + $spkNotApproved
        ];
        session($session);
    }

    public static function getInsentifByType($orderId) {
        $data = parent::select('car_models.insentif_amount', 'order_head.created_by')
                    ->where('order_head.id', $orderId)
                    ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                    ->first();
        return $data;
    }
}
