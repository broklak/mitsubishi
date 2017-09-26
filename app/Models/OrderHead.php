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
        'car_year', 'qty', 'plat', 'bbn_type', 'karoseri', 'karoseri_type', 'karoseri_spec', 'karoseri_price', 'status', 'created_by', 'updated_by', 'customer_image_id'
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

    public function list($approval = false, $query = null, $sort = 'desc', $limit = 1000, $page = 1, $timestamp = null, $month = null, $year = null) {
        $user = Auth::user();
        $userId = $user->id;
        $job = $user->job_position_id;
        $where = 'order_head.status <> 0 ';
        $offset = ($page * $limit) - $limit;

        if($approval) {
            $where .= "and (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) = 0 ";
        }

        if($query != null) {
            $where .= "and (spk_code LIKE '%$query%' OR spk_doc_code LIKE '%$query%') ";   
        }

        if($timestamp != null) {
            $where .= "and order_head.created_at > '$timestamp' ";      
        }

        if($month != null && $year != null) {
            $start = $year.'-'.$month.'-01';
            $end = $year.'-'.$month.'-'.date('t', strtotime($start));
            $where .= "and (date >= '$start' && date <= '$end') ";   
        }


        $data = parent::select(DB::raw("order_head.id, spk_code, spk_doc_code, first_name, last_name, date, qty, order_head.created_by,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method,
                            (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) AS is_approved,
                            car_types.name as type_name, car_models.name as model_name"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->whereRaw($where)
                        ->orderBy('id', $sort)
                        ->offset($offset)
                        ->limit($limit)
                        ->paginate($limit);

        return $data;
    }

    public function countList($approval = false, $query = null) {
        $user = Auth::user();
        $userId = $user->id;
        $job = $user->job_position_id;
        $where = 'order_head.status <> 0 ';
        
        if($approval) {
            $where .= "and (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) = 0 ";
        }

        if($query != null) {
            $where .= "and (spk_code LIKE '%$query%' OR spk_doc_code LIKE '%$query%') ";   
        }

        $count = parent::select(DB::raw("order_head.id, spk_code, first_name, last_name, date, qty, order_head.created_by,
                            (select payment_method from order_price where order_id = order_head.id) as payment_method,
                            (select count(order_id) from order_approval where order_id = order_head.id and job_position_id = $job) AS is_approved,
                            car_types.name as type_name, car_models.name as model_name"))
                        ->join('customers', 'order_head.customer_id', '=', 'customers.id')
                        ->join('car_types', 'car_types.id', '=', 'order_head.type_id')
                        ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                        ->whereRaw($where)
                        ->count();

        return $count;
    }

    public function create($data) {
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
    		'model_id'		=> CarType::getModel($data['type_id']),
    		'type_id'		=> $data['type_id'],
            'car_year'      => $data['car_year'],
    		'color'		    => $data['color'],
    		'qty'			=> $data['qty'],
    		'plat'			=> $data['plat'],
    		'bbn_type'		=> $data['bbn_type'],
    		'karoseri'      => (isset($data['karoseri'])) ? $data['karoseri'] : null,
            'karoseri_type' => (isset($data['karoseri_type'])) ? $data['karoseri_type'] : null,
            'karoseri_spec' => (isset($data['karoseri_spec'])) ? $data['karoseri_spec'] : null,
            'karoseri_price'    => (isset($data['karoseri_price'])) ? parseMoneyToInteger($data['karoseri_price']) : null,
    		'status'		=> 1,
    		'created_by'	=> $data['created_by']
    	]);
    }

    public function updateData($id, $data) {
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
            'model_id'      => CarType::getModel($data['type_id']),
            'type_id'       => $data['type_id'],
            'car_year'      => $data['car_year'],
            'color'         => $data['color'],
            'qty'           => $data['qty'],
            'plat'          => $data['plat'],
            'bbn_type'      => $data['bbn_type'],
            'karoseri'      => (isset($data['karoseri'])) ? $data['karoseri'] : null,
            'karoseri_type' => (isset($data['karoseri_type'])) ? $data['karoseri_type'] : null,
            'karoseri_spec' => (isset($data['karoseri_spec'])) ? $data['karoseri_spec'] : null,
            'karoseri_price'    => (isset($data['karoseri_price'])) ? parseMoneyToInteger($data['karoseri_price']) : null,
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
        $data = parent::select('car_models.insentif_amount', 'order_head.created_by')
                    ->where('order_head.id', $orderId)
                    ->join('car_models', 'car_models.id', '=', 'order_head.model_id')
                    ->first();
        return $data;
    }

    public static function getInsentifByType($orderId) {
        $data = parent::select('car_types.insentif_amount', 'order_head.created_by')
                    ->where('order_head.id', $orderId)
                    ->join('car_types', 'car_types.id', '=', 'order_head.model_id')
                    ->first();
        return $data;
    }

    public function filterResult($data, $api = false) {
        $user= Auth::user();
        $userId = $user->id;
        $isSupervisor = $user->hasRole('supervisor');
        $isManager = $user->hasRole('manager');
        $isSuperUser = $user->hasRole('super_admin');

        foreach ($data as $key => $value) {
            $head = parent::find($value->id);
            if($api)  $data[$key]->detail = $this->detailSpk($head, $value->id);
        }

        if($isManager || $isSuperUser) {
            return $data;
        }

        if($isSupervisor) {
            $salesOwned = User::salesOwned($userId);

            foreach ($data as $key => $value) {
                if(!in_array($value->created_by, $salesOwned)) unset($data[$key]);
            }

            return $data;
        }

        foreach ($data as $key => $value) {
            if($value->created_by != $userId) unset($data[$key]);
        }

        return $data;

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

        $data['number'] = $orderHead->spk_code;
        $data['documentNumber'] = $orderHead->spk_doc_code;
        $data['createdBy'] = User::find($orderHead->created_by)->value('username');
        $data['date'] = $orderHead->date;
        $data['dateHuman'] = date('j F Y', strtotime($orderHead->date));

        $data['carData'] = [
            'stnkName'             => $orderHead->stnk_name,
            'stnkAddress'          => $orderHead->stnk_address,
            'fakturConf'           => $orderHead->faktur_conf,
            'typeId'               => $orderHead->type_id,
            'typeName'             => CarModel::getName($orderHead->model_id) .' '. CarType::getName($orderHead->type_id),
            'color'                => $orderHead->color,
            'qty'                  => $orderHead->qty,
            'plat'                 => $orderHead->plat,
            'carYear'              => $orderHead->car_year,
            'bbnType'              => $orderHead->bbn_type,
            'karoseri'             => $orderHead->karoseri,
            'karoseriType'         => $orderHead->karoseri_type,
            'karoseriSpec'         => $orderHead->karoseri_spec,
            'karoseriPrice'        => $orderHead->karoseri_price
        ];   

        $data['priceData'] = [
            'priceType'            => ($orderPrice->price_off == 0) ? 2 : 1,
            'priceOff'             => moneyFormat($orderPrice->price_off),
            'priceOn'              => moneyFormat($orderPrice->price_on),
            'costSurat'            => moneyFormat($orderPrice->cost_surat),
            'discount'             => moneyFormat($orderPrice->discount),
            'totalSalesPrice'      => moneyFormat($orderPrice->total_sales_price),
            'downPaymentAmount'    => moneyFormat($orderPrice->down_payment_amount),
            'downPaymentDate'      => $orderPrice->down_payment_date,
            'jaminanCostAmount'    => moneyFormat($orderPrice->jaminan_cost_amount),
            'jaminanCostPercentage' => $orderPrice->jaminan_cost_percentage,
            'totalUnpaid'          => moneyFormat($orderPrice->total_unpaid),
            'paymentMethod'        => $orderPrice->payment_method
        ];

        $data['customerData'] = [
            'customerName'         => (isset($customer->id)) ? $customer->first_name : null,
            'customerLastName'      => (isset($customer->id)) ? $customer->last_name : null,
            'idType'               => (isset($customerImage->type)) ? $customerImage->type : null,
            'idNumber'             => (isset($customerImage->id_number)) ? $customerImage->id_number : null,
            'customerAddress'      => (isset($customer->id)) ?  $customer->address : null,
            'idImage'              => (isset($customerImage->filename)) ? asset('images/customer') . '/' . $folder . '/' . $customerImage->filename : null,
            'customerPhone'        => (isset($customer->id)) ? $customer->phone : null,
            'customerNpwp'         => (isset($customer->id)) ? $customer->npwp : null,
            'npwpImage'            => ($orderHead->npwp_image) ? asset('images/npwp') . '/' . $orderHead->npwp_image : null,
        ];

        if(isset($orderCredit->leasing_id)) {
            $data['leasingData'] = [
                'leasingId'            => (isset($orderCredit->leasing_id)) ? $orderCredit->leasing_id : null,
                'yearDuration'         => (isset($orderCredit->year_duration)) ? $orderCredit->year_duration : null,
                'ownerName'            => (isset($orderCredit->owner_name)) ? $orderCredit->owner_name : null,
                'interestRate'         => (isset($orderCredit->interest_rate)) ? $orderCredit->interest_rate : null,
                'adminCost'            => (isset($orderCredit->admin_cost)) ? moneyFormat($orderCredit->admin_cost) : null,
                'insuranceCost'        => (isset($orderCredit->insurance_cost)) ? moneyFormat($orderCredit->insurance_cost) : null,
                'installmentCost'      => (isset($orderCredit->installment_cost)) ? moneyFormat($orderCredit->installment_cost) : null,
                'otherCost'            => (isset($orderCredit->other_cost)) ? moneyFormat($orderCredit->other_cost) : null,
                'totalDownPayment'    => (isset($orderCredit->total_down_payment)) ? moneyFormat($orderCredit->total_down_payment) : null
            ];
        }

        return $data;
    }

}
