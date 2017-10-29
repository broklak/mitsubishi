<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;

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
        'car_year', 'qty', 'plat', 'bbn_type', 'karoseri', 'karoseri_type', 'karoseri_spec', 'karoseri_price', 'status', 'created_by', 'updated_by', 
        'customer_image_id', 'uuid', 'customer_name', 'type_others'
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

    public function list($approval = false, $query = null, $sort = 'desc', $limit = 1000, $page = 1, $timestamp = null, $start = null, $end = null, $api = false) {
        $user = Auth::user();
        $userId = $user->id;
        $job = $user->job_position_id;
        $where = 'order_head.status <> 0 AND uuid is not null ';
        $offset = ($page * $limit) - $limit;

        $isSupervisor = $user->hasRole('supervisor');
        $isManager = $user->hasRole('manager');
        $isSuperUser = $user->hasRole('super_admin');

        if($isSupervisor) {
            $salesOwned = User::salesOwned($userId);
            $salesOwned = implode(',', $salesOwned);
            $where .= "and order_head.created_by in ($salesOwned) ";
        }

        if(!$isManager && !$isSuperUser && !$isSupervisor) {
            $where .= "and order_head.created_by = $userId ";   
        }

        if($approval) {
            $where .= "and (select count(order_id) from order_approval where order_id = order_head.id) = 0 ";
        }

        if($query != null) {
            $where .= "and (spk_code LIKE '%$query%' OR spk_doc_code LIKE '%$query%') ";   
        }

        if($timestamp != null) {
            $where .= "and order_head.updated_at > '$timestamp' ";      
        }

        if($start != null && $end != null) {
            $where .= "and (date >= '$start' && date <= '$end') ";   
        }


        if($api){
            if($page == 0 || $limit == 0) { // SYNC SPK
                $data = parent::select(DB::raw("order_head.id, spk_code, spk_doc_code, first_name, last_name, date, qty, order_head.created_by, dealer_id,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method, uuid, reject_reason, type_others,
                            car_types.name as type_name, car_models.name as model_name, order_head.created_at, order_head.updated_at"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->leftJoin('order_approval', 'order_head.id', '=', 'order_approval.order_id')
                        ->whereRaw($where)
                        ->orderBy('id', $sort)
                        ->get();
            } else { // GET SPK
                $data = parent::select(DB::raw("order_head.id, spk_code, spk_doc_code, first_name as customer_first_name, last_name as customer_last_name, 
                            date, qty, order_head.created_by, dealer_id, type_others,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method, uuid, reject_reason,
                            car_types.name as type_name, car_models.name as model_name, order_head.created_at, order_head.updated_at"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->leftJoin('order_approval', 'order_head.id', '=', 'order_approval.order_id')
                        ->whereRaw($where)
                        ->orderBy('id', $sort)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
            }
        } else {
            $data = parent::select(DB::raw("order_head.id, spk_code, spk_doc_code, customer_name, date, qty, order_head.created_by,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method,
                            car_types.name as type_name, car_models.name as model_name, type_others"))
                        // ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->leftJoin('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->leftJoin('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->whereRaw($where)
                        ->orderBy('id', $sort)
                        ->offset($offset)
                        ->limit($limit)
                        ->paginate($limit);
        }

        return $data;
    }

    public function countList($approval = false, $query = null) {
        $user = Auth::user();
        $userId = $user->id;
        $job = $user->job_position_id;
        $where = 'order_head.status <> 0 ';
        
        if($approval) {
            $where .= "and (select count(order_id) from order_approval where order_id = order_head.id) = 0 ";
        }

        if($query != null) {
            $where .= "and (spk_code LIKE '%$query%' OR spk_doc_code LIKE '%$query%') ";   
        }

        $count = parent::select(DB::raw("order_head.id, spk_code, first_name, last_name, date, qty, order_head.created_by,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method,
                            (select count(order_id) from order_approval where order_id = order_head.id) AS is_approved,
                            car_types.name as type_name, car_models.name as model_name"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->whereRaw($where)
                        ->count();

        return $count;
    }

    public function create($data) {
        if($data['color'] == '0') {
            CarColor::create([
                'name' => $data['color_others'],
                'created_by' => $data['created_by']
            ]);
        }

    	return parent::create([
    		'dealer_id'		=> $data['dealer_id'],
            'customer_id'   => $data['customer_id'],
    		'customer_image_id'	=> $data['customer_id_image'],
    		'spk_code'		=> $this->generateSPKCode($data['dealer_id']),
    		'spk_doc_code'	=> $data['spk_doc_code'],
    		'date'			=> $data['date'],
    		'npwp_image'	=> (isset($data['npwp_image'])) ? $data['npwp_image'] : null,
    		'stnk_name'		=> $data['stnk_name'],
    		'stnk_address'	=> $data['stnk_address'],
    		'faktur_conf'   => (isset($data['faktur_conf'])) ? $data['faktur_conf'] : null,
    		'model_id'		=> $data['model_id'],
    		'type_id'		=> $data['type_id'],
            'car_year'      => $data['car_year'],
    		'color'		    => ($data['color'] != '0') ? $data['color'] : $data['color_others'],
    		'qty'			=> $data['qty'],
            'plat'          => $data['plat'],
    		'type_others'	=> ($data['type_id'] == 0) ? $data['type_others'] : null,
    		'bbn_type'		=> $data['bbn_type'],
    		'karoseri'      => (isset($data['karoseri'])) ? $data['karoseri'] : null,
            'karoseri_type' => (isset($data['karoseri_type'])) ? $data['karoseri_type'] : null,
            'karoseri_spec' => (isset($data['karoseri_spec'])) ? $data['karoseri_spec'] : null,
            'karoseri_price'    => (isset($data['karoseri_price'])) ? parseMoneyToInteger($data['karoseri_price']) : null,
    		'status'		=> 1,
    		'created_by'	=> $data['created_by'],
            'uuid'          => isset($data['uuid']) ? $data['uuid'] : null,
            'customer_name' => $data['customer_first_name']
    	]);
    }

    public function updateData($id, $data) {
        if($data['color'] == '0') {
            CarColor::create([
                'name' => $data['color_others'],
                'created_by' => $data['updated_by']
            ]);
        }
        return parent::find($id)->update([
            'dealer_id'     => $data['dealer_id'],
            'customer_id'   => $data['customer_id'],
            'customer_image_id' => $data['customer_id_image'],
            'spk_doc_code'  => $data['spk_doc_code'],
            'date'          => $data['date'],
            'npwp_image'    => (isset($data['npwp_image'])) ? $data['npwp_image'] : null,
            'stnk_name'     => $data['stnk_name'],
            'stnk_address'  => $data['stnk_address'],
            'faktur_conf'   => (isset($data['faktur_conf'])) ? $data['faktur_conf'] : null,
            'model_id'      => $data['model_id'],
            'type_id'       => $data['type_id'],
            'type_others'   => ($data['type_id'] == 0) ? $data['type_others'] : null,
            'car_year'      => $data['car_year'],
            'color'         => ($data['color'] != '0') ? $data['color'] : $data['color_others'],
            'qty'           => $data['qty'],
            'plat'          => $data['plat'],
            'bbn_type'      => $data['bbn_type'],
            'karoseri'      => (isset($data['karoseri'])) ? $data['karoseri'] : null,
            'karoseri_type' => (isset($data['karoseri_type'])) ? $data['karoseri_type'] : null,
            'karoseri_spec' => (isset($data['karoseri_spec'])) ? $data['karoseri_spec'] : null,
            'karoseri_price'    => (isset($data['karoseri_price'])) ? parseMoneyToInteger($data['karoseri_price']) : null,
            'updated_by'    => $data['updated_by'],
            'customer_name' => $data['customer_first_name']
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
        $data = Auth::user();
        $spkNotApproved = ($data->can('approve.spk')) ? count($this->list($approval = true)) : 0;
        $doNotChecked = ($data->can('*.do')) ? count(DeliveryOrder::notChecked()) : 0;
        $session = [
            'spk_notif' => $spkNotApproved,
            'do_notif' => $doNotChecked,
            'total_notif' => $doNotChecked + $spkNotApproved
        ];
        session($session);
    }

    public static function getInsentifByModel($orderId) {
        $data = parent::select('car_models.insentif_amount', 'order_head.type_id', 'qty', 'order_head.date', 'order_head.created_by', 'order_head.spk_code', 'order_head.spk_doc_code')
                    ->where('order_head.id', $orderId)
                    ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                    ->first();
        return $data;
    }

    public static function getInsentifByType($orderId) {
        $data = parent::select('car_types.insentif_amount', 'order_head.type_id', 'qty', 'order_head.date', 'order_head.created_by', 'order_head.spk_code', 'order_head.spk_doc_code')
                    ->where('order_head.id', $orderId)
                    ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                    ->first();
        return $data;
    }

    public function filterResult($data, $api = false) {
        foreach ($data as $key => $value) {
            $head = parent::find($value->id);
            $approveLabel = OrderApproval::getLabelStatus($head);
            if($api){
                $data[$key]->approval_status = str_replace('<br />', '.', $approveLabel);
                $data[$key]->detail = $this->detailSpk($head, $value->id);
            }
        }

        $clean = [];
        foreach ($data as $key => $value) {
            $clean[] = $data[$key];
        }


        return ($api) ? $clean : $data;

    }

    public function graphDo() {
        $data = parent::select(DB::raw("SUM(order_head.qty) as totalSales, 
                                        CONCAT(MONTHNAME(date), ' ', YEAR(date)) AS period"))
                        ->whereRaw('date > DATE_SUB(now(), INTERVAL 12 MONTH)')
                        ->groupBy(DB::raw("period"))
                        ->orderBy(DB::raw("period"))
                        ->get();

        foreach ($data as $key => $value) {
            $start = date('Y-m-01', strtotime($value->period));
            $end = date('Y-m-t', strtotime($value->period));
            $do = DeliveryOrder::whereBetween('do_date', [$start, $end])->count();

            $data[$key]['totalDo'] = $do;
        }

        $data = json_decode(json_encode($data), True);

        usort($data, function ($a, $b) {
          $a_val = strtotime($a['period']);
          $b_val = strtotime($b['period']);

          if($a_val > $b_val) return 1;
          if($a_val < $b_val) return -1;
          return 0;
        });

        return $data;
    }

    public function graphSPK() {
        $data = parent::whereRaw('date > DATE_SUB(now(), INTERVAL 12 MONTH)')
                        ->get();

        $status = [
            'processed' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];
        foreach ($data as $key => $value) {
            $monthYear = date('F-Y', strtotime($value->date));
            $label = OrderApproval::getLabelStatus($value);

            if(stristr(strtolower($label), 'pending')) { // APPROVED
                $status['processed'] += 1;
            } else if(stristr(strtolower($label), 'reject')) { // REJECTED
                $status['rejected'] += 1;
            } else if(stristr(strtolower($label), 'approve')) {
                $status['approved'] += 1;
            }
        }

        $total = $status['processed'] + $status['approved'] + $status['rejected'];

        $result = [
            [
                'label' => "Active SPK ($total)", 
                'processed' => $status['processed'],
                'approved' => $status['approved'],
                'rejected' => $status['rejected'],
            ]
        ];

        return $result;
    }

    public function detailSpk($orderHead, $orderId) {
        $customer = Customer::find($orderHead->customer_id);
        $orderPrice = OrderPrice::where('order_id', $orderId)->first();
        $orderCredit = OrderCredit::where('order_id', $orderId)->first();
        

        if($orderHead->customer_image_id == null) {
            $customerImage = CustomerImage::where('customer_id', $orderHead->customer_id)->orderBy('type')->orderBy('id', 'desc')->first();
        } else {
            $customerImage = CustomerImage::find($orderHead->customer_image_id);
        }
        

        $folder = '';
        if(isset($customerImage->type)) {
            if($customerImage->type == 1) {
                $folder = 'ktp';
            } else if($customerImage->type == 2) {
                $folder = 'sim';
            } else {
                $folder = 'passport';
            }
        }

        $data['uuid'] = $orderHead->uuid;
        $data['spk_code'] = $orderHead->spk_code;
        $data['spk_doc_code'] = $orderHead->spk_doc_code;
        $data['created_by'] = User::find($orderHead->created_by)->value('username');
        $data['date'] = $orderHead->date;
        $data['date_human'] = date('j F Y', strtotime($orderHead->date));

        $data['car_data'] = [
            'stnk_name'             => $orderHead->stnk_name,
            'stnk_address'          => $orderHead->stnk_address,
            'faktur_conf'           => $orderHead->faktur_conf,
            'type_id'               => $orderHead->type_id,
            'type_name'             => CarModel::getName($orderHead->model_id) .' '. CarType::getName($orderHead->type_id),
            'color'                => $orderHead->color,
            'qty'                  => $orderHead->qty,
            'plat'                 => $orderHead->plat,
            'car_year'              => (int) $orderHead->car_year,
            'bbn_type'              => $orderHead->bbn_type,
            'karoseri'             => $orderHead->karoseri,
            'karoseri_type'         => $orderHead->karoseri_type,
            'karoseri_spec'         => $orderHead->karoseri_spec,
            'karoseri_price'        => $orderHead->karoseri_price
        ];   

        if(isset($orderPrice->id)) {
            $data['price_data'] = [
                'price_type'            => ($orderPrice->price_off == 0) ? 2 : 1,
                'price_off'             => $orderPrice->price_off,
                'price_on'              => $orderPrice->price_on,
                'cost_surat'            => $orderPrice->cost_surat,
                'discount'             => $orderPrice->discount,
                'total_sales_price'      => $orderPrice->total_sales_price,
                'booking_fee'    => $orderPrice->down_payment_amount,
                'down_payment_date'      => $orderPrice->down_payment_date,
                'dp_amount'    => $orderPrice->jaminan_cost_amount,
                'dp_percentage' => $orderPrice->jaminan_cost_percentage,
                'total_unpaid'          =>$orderPrice->total_unpaid,
                'payment_method'        => $orderPrice->payment_method
            ];
        }

        $data['customer_data'] = [
            'customer_first_name'         => (isset($customer->id)) ? $customer->first_name : null,
            'customer_last_name'      => (isset($customer->id)) ? $customer->last_name : null,
            'customer_business'      => (isset($customer->id)) ? $customer->job : null,
            'id_type'               => (isset($customerImage->type)) ? $customerImage->type : null,
            'id_number'             => (isset($customerImage->id_number)) ? $customerImage->id_number : null,
            'customer_address'      => (isset($customer->id)) ?  $customer->address : null,
            'id_image'              => (isset($customerImage->filename)) ? asset('images/customer') . '/' . $folder . '/' . $customerImage->filename : null,
            'customer_phone'        => (isset($customer->id)) ? $customer->phone : null,
            'customer_phone_home'        => (isset($customer->id)) ? $customer->phone_home : null,
            'npwp'         => (isset($customer->id)) ? $customer->npwp : null,
            'npwp_image'            => ($orderHead->npwp_image) ? asset('images/npwp') . '/' . $orderHead->npwp_image : null,
        ];

        if(isset($orderCredit->leasing_id)) {
            $data['leasing_data'] = [
                'leasing_id'            => (isset($orderCredit->leasing_id)) ? $orderCredit->leasing_id : null,
                'credit_duration'         => (isset($orderCredit->year_duration)) ? $orderCredit->year_duration : null,
                'credit_owner_name'            => (isset($orderCredit->owner_name)) ? $orderCredit->owner_name : null,
                'interest_rate'         => (isset($orderCredit->interest_rate)) ? $orderCredit->interest_rate : null,
                'admin_cost'            => (isset($orderCredit->admin_cost)) ? $orderCredit->admin_cost : null,
                'insurance_cost'        => (isset($orderCredit->insurance_cost)) ? $orderCredit->insurance_cost : null,
                'installment_cost'      => (isset($orderCredit->installment_cost)) ? $orderCredit->installment_cost : null,
                'other_cost'            => (isset($orderCredit->other_cost)) ? $orderCredit->other_cost : null,
                'total_down_payment'    => (isset($orderCredit->total_down_payment)) ? $orderCredit->total_down_payment : null
            ];
        }

        return $data;
    }

}
