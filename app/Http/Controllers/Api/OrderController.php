<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\OrderHead;
use App\Models\OrderPrice;
use App\Models\OrderCredit;
use App\Models\OrderLog;
use App\Models\OrderApproval;
use App\Models\OrderAttachment;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\CarColor;
use App\Models\ServerSecret;
use App\Models\Bbn;
use App\Models\DeliveryOrder;
use App\Models\Customer;
use App\Models\CustomerImage;
use App\Models\Leasing;
use App\Models\Dealer;
use App\Models\CreditMonth;
use App\Models\LeasingRateHead;
use App\Models\LeasingRateDetail;
use App\Models\InsuranceRateHead;
use App\Models\InsuranceRateDetail;
use App\User;
use App\RoleUser;
use App\Role;
use App\PermissionRole;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api', ['except' => ['syncSpk', 'doPost']]);
	}

    public function list(Request $request) { 
    	try {
            $type = $request->input('type');
            $time = $request->input('timestamp');
            if($type == 'sync') {
                return $this->shortSpk($request->input());
            }

    		$approval = ($request->input('type') == 'approval') ? true : false;
	    	$limit = ($request->input('limit')) ? $request->input('limit') : 0;
	    	$page = ($request->input('page')) ? $request->input('page') : 0;
	        $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
            $query = $request->input('query');
            $status = $request->input('status');

	        if($limit < 0) return $this->apiError($statusCode = 400, 'Limit data must not be negative number', 'Something went wrong with the request');	

	        $order = new OrderHead();
            $data = $order->list($approval, $query, $sort, $limit, $page, $time, null, null, $api = true, $status);
            $data = $order->filterResult($data, $api = true);
            $pagination = ($limit == 0) ? null : $this->getPagination($data, $order->countList($approval, $query), $page, $limit);
    	} catch (Exception $e) {
    		return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');          
    	}

    	return $this->apiSuccess($data, $request->input(), $pagination);
    }

    public function detail(Request $request, $id) {
        try {
            $head = new OrderHead();
            $orderHead = $head->find($id);
            if(!isset($orderHead->id)) return $this->apiError($statusCode = 400, "SPK with ID $id is not found", 'No result found');

            $data = $head->detailSpk($orderHead, $id);

        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }

        return $this->apiSuccess($data, $request->input());
    }

    public function post(Request $request) {
        try {
            $orderHead = new OrderHead();
            $validator = Validator::make($request->input(), $this->rules());
            if ($validator->fails()) {    
                return $this->apiError($statusCode = 400, $validator->messages(), 'Some fields must be filled');
            }

            $create = $request->input();

            if($create['payment_method'] == 2) { // IF LEASING THEN VALIDATE AGAIN
                $validatorCredit = Validator::make($request->input(), $this->rulesLeasing());

                if ($validatorCredit->fails()) {    
                    return $this->apiError($statusCode = 400, $validatorCredit->messages(), 'Leasing fields must be filled');
                }
            }

            $create['created_by'] = Auth::id();

            if ($request->file('npwp_image')) {
                $name = str_replace(' ', '-', $request->npwp_image->getClientOriginalName());
                $request->npwp_image->move(
                    base_path() . '/public/images/npwp/', $name
                );
                $create['npwp_image'] = $name;
            }

            if ($request->file('id_image')) {
                $nameCust = str_replace(' ', '-', $request->id_image->getClientOriginalName());

                $folder = ($create['id_type'] == 1) ? 'ktp' : 'sim';
                $folder = ($create['id_type'] == 3) ? 'passport' : $folder;
                $request->id_image->move(
                    base_path() . '/public/images/customer/'.$folder.'/', $nameCust
                );
                $create['image'] = $nameCust;
            }

            $customer = Customer::validateSpk($create);
            $create['customer_id'] = $customer['customerId'];
            $create['customer_id_image'] = isset($customer['imageId']) ? $customer['imageId'] : null;

            $createHead = $orderHead->create($create);

            $create['order_id'] = $createHead->id;

            $createPrice = OrderPrice::createData($create);

            //CREATE ATTACHMENT
            OrderAttachment::createData($request->file('attachment'), $createHead->id);

            //CREATE LOG
            OrderLog::create([
                'order_id'      => $createHead->id,
                'desc'          => 'Created',
                'created_by'    => Auth::id()
            ]);

            if($create['payment_method'] == 2) {
                $create['installment_cost'] = floor($create['installment_cost']);
                $create['insurance_cost'] = floor($create['insurance_cost']);
                $create['total_down_payment'] = floor($create['total_down_payment']);
                $createCredit = OrderCredit::createData($create);
            }

            logUser('Create SPK '.$createHead->id);    

            // SEND EMAIL NOTIF
            OrderApproval::sendEmailNotif('create', $createHead);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }
        
        $data['created'] = $createHead;

        if(isset($create['image'])) {
            $data['created']->id_image = asset('images/customer'). '/' .$folder.'/'.$create['image'];
        }

        if(isset($create['npwp_image'])) {
            $data['created']->npwp_image = asset('images/npwp'). '/' . $create['npwp_image'];
        }

        return $this->apiSuccess($data, $request->input(), $pagination = null, $statusCode = 201);
    }

    public function put(Request $request, $id)
    {
        try {
            $type = $request->input('type');

            if($type == 'approve') {
                return $this->approveSpk($id);
            }

            if($type == 'reject') {
                $reason = ($request->input('reason')) ? $request->input('reason') : null;
                return $this->rejectSpk($id, $reason);
            }

            $orderHead = new OrderHead();

            $rules = $this->rules();
            unset($rules['uuid']);
            $headNew = $orderHead->findOrFail($id);
            $priceOld = OrderPrice::where('order_id', $id)->first();
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {    
                return $this->apiError($statusCode = 400, $validator->messages(), 'Some fields must be filled');
            }

            // IF SPK IS CANT UPDATED
            $headNew->payment_method = $priceOld->payment_method;
            if(!OrderApproval::canEdit($headNew)) {
                return $this->apiError($statusCode = 400, 'SPK status can not be updated', 'SPK status can not be updated');   
            }

            $update = $request->input();

            if($update['payment_method'] == 2) { // IF LEASING THEN VALIDATE AGAIN
                $validatorCredit = Validator::make($request->input(), $this->rulesLeasing());

                if ($validatorCredit->fails()) {    
                    return $this->apiError($statusCode = 400, $validatorCredit->messages(), 'Leasing fields must be filled');
                }
            }

            $update['updated_by'] = Auth::id();

            if ($request->file('npwp_image')) {
                $name = $request->npwp_image->getClientOriginalName();
                $request->npwp_image->move(
                    base_path() . '/public/images/npwp/', $name
                );
                $update['npwp_image'] = $name;
            }

            if ($request->file('id_image')) {
                $nameCust = $request->id_image->getClientOriginalName();
                $folder = ($update['id_type'] == 1) ? 'ktp' : 'sim';
                $folder = ($update['id_type'] == 3) ? 'passport' : $folder;
                $request->id_image->move(
                    base_path() . '/public/images/customer/'.$folder.'/', $nameCust
                );
                $update['image'] = $nameCust;
            }

            $customer = Customer::validateSpk($update);
            $update['customer_id'] = $customer['customerId'];
            $update['customer_id_image'] = isset($customer['imageId']) ? $customer['imageId'] : null;

            $updateHead = $orderHead->updateData($id, $update);

            $updatePrice = OrderPrice::updateData($id, $update);

            // DELETE ALL APPROVAL
            OrderApproval::where('order_id', $id)->delete();

            //CREATE LOG
            OrderLog::create([
                'order_id'      => $id,
                'desc'          => 'Updated',
                'created_by'    => Auth::id()
            ]);

            $update['order_id'] = $id;
            $update['created_by'] = Auth::id();
            OrderCredit::where('order_id', $id)->delete();
            if($update['payment_method'] == 2) {
                $update['installment_cost'] = floor($update['installment_cost']);
                $update['insurance_cost'] = floor($update['insurance_cost']);
                $update['total_down_payment'] = floor($update['total_down_payment']);
                $createCredit = OrderCredit::createData($update);
            }

            if(isset($update['image'])) {
                $update['image'] = asset('images/customer'). '/' .$folder.'/'.$update['image'];
            }

            if(isset($update['npwp_image'])) {
                $update['npwp_image'] = asset('images/npwp'). '/' . $update['npwp_image'];
            }

            $update['id_image'] = $update['customer_id_image'];
            unset($update['customer_id_image']);
            $update['update_at'] = date('Y-m-d H:i:s');
            $update['created_at'] = date('Y-m-d H:i:s',strtotime($headNew->created_at));

            $update['id'] = $id;
            $update['spk_code'] = $headNew->spk_code;
            OrderApproval::sendEmailNotif('update', (object) $update);

            logUser('Update SPK '.$id);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }
        // $data['message'] = 'Success to update SPK';
        return $this->apiSuccess($update, $request->input());
        
    }

    protected function rules() {
        return [
            'spk_doc_code'     => 'required',
            'uuid'      => 'required|unique:order_head',
            'date'     => 'required',
            'dealer_id' => 'required',
            'id_number' => 'required',
            'id_type' => 'required',
            'customer_first_name' => 'required',
            'customer_address' => 'required',
            'customer_phone' => 'required',
            'id_type' => 'required',
            'car_year' => 'required',
            'stnk_name' => 'required',
            'stnk_address' => 'required',
            'type_id' => 'required',
            'model_id' => 'required',
            'color' => 'required',
            'bbn_type' => 'required',
            'qty' => 'required',
            'plat' => 'required',
            'total_sales_price' => 'required',
            'payment_method' => 'required'
        ];
    }

    protected function rulesLeasing() {
        return [
            'leasing_id'    => 'required',
            'credit_duration'    => 'required',
            'credit_owner_name'    => 'required',
            'interest_rate'    => 'required',
            'admin_cost'    => 'required',
            'insurance_cost'    => 'required',
            'installment_cost'    => 'required',
            'other_cost'    => 'required',
            'total_down_payment'    => 'required'
        ];
    }

    public function doPost(Request $request) {
        $true = $false = 0;
        try {
            $body = $request->getContent();
            $param = json_decode($body, true);

            $secret = isset($param['server-secret']) ? $param['server-secret'] : null;

            $validSecret = ServerSecret::find(1)->value('secret');

            if($secret != $validSecret) {
                return $this->apiError($statusCode = 401, 'Wrong Server Secret', 'Unauthenticated');   
            }

            if(!isset($param['do'])) {
                return $this->apiError($statusCode = 400, 'No DO key in body param', 'Invalid Request Body Param');            
            }

            $do = $param['do'];
            foreach ($do as $key => $value) {
                $spk = $value['spkNo'];
                $findSpk = OrderHead::where('spk_doc_code', $spk)
                                    ->orWhere('spk_code', $spk)
                                    ->first();

                if(isset($findSpk->spk_code)) {
                    if(DeliveryOrder::validToDO($findSpk->id)) {
                        $true++;
                        DeliveryOrder::create([
                            'spk_id'    => $findSpk['id'],
                            'spk_doc_code' => $spk,
                            'do_code'   => $value['doNo'],
                            'do_date'   => date('Y-m-d', strtotime($value['date']))
                        ]);
                    } else {
                        $false++;
                    }
                } else {
                    $false++;
                }
            }

            $data = [
                'processed'   => $true,
                'notProcessed' => $false,
            ];    
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');            
        }
        

        if($true == 0) {
            $statusCode = 200;
        }

        $statusCode = 201;
        return $this->apiSuccess($data, $request->input(), $pagination = null, $statusCode);
    }

    public function insentif(Request $request) {
        try {
            $userId = Auth::id();
            $month = ($request->input('month')) ? $request->input('month') : date('m');
            $year = ($request->input('year')) ? $request->input('year') : date('Y');

            $do = new DeliveryOrder();

            $insentif = $do->getInsentif($month, $year);

            if(!isset($insentif[$userId])) {
                return $this->apiError($statusCode = 400, 'No insentif data for this user', 'No insentif data for this user');     
            }

            return $this->apiSuccess($insentif[$userId], $request->input());

        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');        
        }
    }

    protected function approveSpk($orderId) {
        try {
            $user = Auth::user();
            $orderHead = OrderHead::find($orderId);
            $eligible = OrderApproval::eligibleToApprove($orderHead);

            if(!$eligible) {
                return $this->apiError($statusCode = 401, 'Unauthorized', 'You are not eligible to approve');
            }

            $roles = RoleUser::getRoleForUser(Auth::id());
            if(count($roles) == 1) {
                $roleName = Role::getRoleName($roles[0]);
            } else {
                $approver = PermissionRole::getSPKApprover();
                foreach ($roles as $key => $value) {
                    if(isset($approver[$value])) {
                        $roleName = $approver[$value];
                    }
                }
            }

            $approve = OrderApproval::create([
                'order_id'  => $orderId,
                'level_approved' => 0,
                'role_name' => $roleName,
                'type'  => 1,
                'job_position_id' => 0,
                'approved_by'   => Auth::id()
            ]);

            //EXTEND USER CREATOR SPK
            Auth::user()->extendLoginValidity($orderHead->created_by);

            //CREATE LOG
            OrderLog::create([
                'order_id'      => $orderId,
                'desc'          => 'Approved',
                'created_by'    => Auth::id()
            ]);

            $orderHead->approved_by = $user->first_name . ' '. $user->last_name;
            OrderApproval::sendEmailNotif('approve', $orderHead);

            logUser('Approve SPK '.$orderId);
            $data = [
                'message'   => 'Success to approve SPK'
            ];
            return $this->apiSuccess($data);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');   
        }
        
    }

    protected function rejectSpk($orderId, $reason = null) {
        try {
            $eligible = OrderApproval::eligibleToApprove(OrderHead::find($orderId));

            if(!$eligible) {
                return $this->apiError($statusCode = 401, 'Unauthorized', 'You are not eligible to approve');
            }

            $order = OrderHead::find($orderId);
            $user = Auth::user();

            $roles = RoleUser::getRoleForUser(Auth::id());
            if(count($roles) == 1) {
                $roleName = Role::getRoleName($roles[0]);
            } else {
                $approver = PermissionRole::getSPKApprover();
                foreach ($roles as $key => $value) {
                    if(isset($approver[$value])) {
                        $roleName = $approver[$value];
                    }
                }
            }

            $approve = OrderApproval::create([
                'order_id'  => $orderId,
                'level_approved' => 0,
                'role_name' => $roleName,
                'reject_reason' => $reason,
                'type'  => 2,
                'job_position_id' => 0,
                'approved_by'   => Auth::id()
            ]);

            //CREATE LOG
            OrderLog::create([
                'order_id'      => $orderId,
                'desc'          => 'Rejected',
                'created_by'    => Auth::id()
            ]);

            $order->reject_reason = $reason;
            $order->reject_by = $user->first_name . ' '. $user->last_name;
            OrderApproval::sendEmailNotif('reject', $order);

            logUser('Reject SPK '.$orderId);    
            $data = [
                'message'   => 'Success to reject SPK'
            ];
            return $this->apiSuccess($data);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');   
        }
        
    }

    public function syncSpk(Request $request) {
        $secret = $request->input('secret');
        $head = new OrderHead();

        $validSecret = ServerSecret::find(1)->value('secret');

        if($secret != $validSecret) {
            return $this->apiError($statusCode = 401, 'Wrong Server Secret', 'Unauthenticated');   
        }

        $where = [];
        if($request->input('timestamp')) {
            $where[] = ['updated_at', '>', $request->input('timestamp')];
        }

        if($request->input('dealer_id')) {
            $where[] = ['dealer_id', '=', $request->input('dealer_id')];
        }

        $data = OrderHead::where($where)
                            ->get();

        $data = $head->filterResult($data, $api = true);

        return $this->apiSuccess($data, $request->input());
    }

    public function fields() {
        $dealer = Dealer::select('id as value', 'name as display')->get();
        $carModel = CarModel::getOptionValue();
        $carType = CarType::getOptionValue();
        $carType[] = ['value' => 0, 'display' => 'Others'];

        $carColor = CarColor::getOptionValue();
        $carColor[] = ['value' => 0, 'display' => 'Others'];

        $idType = [
            ['value' => 1, 'display' => 'KTP'],
            ['value' => 2, 'display' => 'SIM'],
            ['value' => 3, 'display' => 'Passport']
        ];

        $platType = [
            ['value' => 1, 'display' => 'Hitam'],
            ['value' => 2, 'display' => 'Kuning'],
            ['value' => 3, 'display' => 'Merah'],
        ];

        $bbnType = Bbn::getOption();

        $priceType = [
            ['value' => 1, 'display' => 'On The Road'],
            ['value' => 2, 'display' => 'Off The Road'],
        ];

        $paymentMethod = [
            ['value' => 1, 'display' => 'Cash'],
            ['value' => 2, 'display' => 'Leasing'],
        ];

        $leasing = Leasing::getOption();
        $months = CreditMonth::select(DB::raw('months as value, CONCAT(months, " Months") as display'))->get();

        $field['generalField'] = [
                    generateApiField($fieldName = 'spk_doc_code', $label = 'Nomor Dokumen'),
                    generateApiField($fieldName = 'date', $label = 'Date', $type = 'date', $required = true, $options = null, $desc = 'YYYY-MM-DD'),
                    generateApiField($fieldName = 'dealer_id', $label = 'Dealer', $type = 'select', $required = true, $options = $dealer),
                    generateApiField($fieldName = 'customer_phone', $label = 'Nomor Handphone Pemesan'),
                    generateApiField($fieldName = 'customer_address', $label = 'Alamat'),
                    generateApiField($fieldName = 'customer_phone_home', $label = 'Nomor Telepon Pemesan'),
                    generateApiField($fieldName = 'customer_business', $label = 'Jenis Usaha'),
                    generateApiField($fieldName = 'id_type', $label = 'Jenis Identitas', $type = 'select', $required = true, $options = $idType),
                    generateApiField($fieldName = 'id_image', $label = 'Foto Tanda Identitas', $type = 'file', $required = false),
                    generateApiField($fieldName = 'id_number', $label = 'Nomor Tanda Identitas'),
                    generateApiField($fieldName = 'customer_first_name', $label = 'Nama Pemesan'),
                    generateApiField($fieldName = 'customer_last_name', $label = 'Nama Belakang Pemesan', $type = 'string', $required = false),
                    generateApiField($fieldName = 'npwp', $label = 'NPWP', $type = 'string', $required = false),
                    generateApiField($fieldName = 'npwp_image', $label = 'Foto NPWP', $type = 'file', $required = false),
                    generateApiField($fieldName = 'stnk_name', $label = 'Nama STNK'),
                    generateApiField($fieldName = 'stnk_address', $label = 'Alamat STNK'),
                    generateApiField($fieldName = 'faktur_conf', $label = 'Faktur Konfirmasi', $type = 'string', $required = false),
                    generateApiField($fieldName = 'model_id', $label = 'Model Mobil', $type = 'select', $required = true, $options = $carModel),
                    generateApiField($fieldName = 'type_id', $label = 'Tipe Mobil', $type = 'select', $required = true, $options = $carType),
                    generateApiField($fieldName = 'type_others', $label = 'Nama Tipe Mobil', $type = 'string', false, null, $desc = 'Fill if type_id is others'),
                    generateApiField($fieldName = 'color', $label = 'Warna Mobil', $type = 'select', true, $carColor),
                    generateApiField($fieldName = 'color_others', $label = 'Nama Warna', $type = 'string', false, null, $desc = 'Fill if color is others'),
                    generateApiField($fieldName = 'car_year', $label = 'Tahun Kendaraan', $type = 'string', $required = true, $options = null, $desc = 'YYYY-MM-DD'),
                    generateApiField($fieldName = 'qty', $label = 'Quantity', $type = 'integer'),
                    generateApiField($fieldName = 'plat', $label = 'Jenis Plat', $type = 'select', $required = true, $options = $platType),
                    generateApiField($fieldName = 'bbn_type', $label = 'Jenis BBN', $type = 'select', $required = true, $options = $bbnType),
                    generateApiField($fieldName = 'karoseri', $label = 'Karoseri', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_type', $label = 'Jenis Karoseri', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_spec', $label = 'Spesifikasi', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_price', $label = 'Harga', $type = 'integer', $required = false),
                    generateApiField($fieldName = 'price_type', $label = 'Jenis Harga', $type = 'select', $required = true, $options = $priceType),
                    generateApiField($fieldName = 'discount', $label = 'Discount', $type = 'integer'),
                    generateApiField($fieldName = 'price_off', $label = 'Harga Off The Road', $type = 'integer'),
                    generateApiField($fieldName = 'price_on', $label = 'Harga On The Road', $type = 'integer'),
                    generateApiField($fieldName = 'cost_surat', $label = 'Biaya Surat Kendaraan', $type = 'integer'),
                    generateApiField($fieldName = 'total_sales_price', $label = 'Total Harga Jual', $type = 'integer'),
                    generateApiField($fieldName = 'booking_fee', $label = 'Uang Panjar', $type = 'integer'),
                    generateApiField($fieldName = 'down_payment_date', $label = 'Tanggal Pembayaran', $type = 'date'),
                    generateApiField($fieldName = 'dp_percentage', $label = 'DP (%)', $type = 'integer'),
                    generateApiField($fieldName = 'dp_amount', $label = 'DP (Rp)', $type = 'integer'),
                    generateApiField($fieldName = 'total_unpaid', $label = 'Sisa Pembayaran', $type = 'integer'),
                    generateApiField($fieldName = 'payment_method', $label = 'Cara Pembayaran', $type = 'select', $required = true, $options = $paymentMethod),
        ];

        $field['leasingField'] = [
            generateApiField($fieldName = 'leasing_id', $label = 'Leasing', $type = 'select', $required = true, $options = $leasing),
            generateApiField($fieldName = 'credit_duration', $label = 'Lama Kredit', $type = 'select', $required = true, $options = $months),
            generateApiField($fieldName = 'credit_owner_name', $label = 'Kontrak atas Nama'),
            generateApiField($fieldName = 'interest_rate', $label = 'Suku Bunga', $type = 'float'),
            generateApiField($fieldName = 'admin_cost', $label = 'Biaya Administrasi', $type = 'integer'),
            generateApiField($fieldName = 'insurance_cost', $label = 'Biaya Asuransi', $type = 'integer'),
            generateApiField($fieldName = 'installment_cost', $label = 'Cicilan Perbulan', $type = 'integer'),
            generateApiField($fieldName = 'other_cost', $label = 'Biaya Lain', $type = 'integer'),
            generateApiField($fieldName = 'total_down_payment', $label = 'TDP', $type = 'integer')
        ];

        return $this->apiSuccess($field);
    }

    public function leasingFormula(Request $request) {
        try {
            $data['dp'] = $request->input('dp_percentage');
            $data['leasing'] = $request->input('leasing');
            $data['duration'] = $request->input('credit_duration');
            $data['carType'] = $request->input('car_type');
            $data['dealer'] = $request->input('dealer');
            $data['karoseri'] = $request->input('karoseri_price');
            $data['car_year'] = $request->input('car_built_year');

            $interestRate = LeasingRateHead::getRate($data);
            $insuranceRate = InsuranceRateHead::getRate($data);
            $unpaid = parseMoneyToInteger($request->input('unpaid'));
            $year = floor($data['duration'] / 12);

            // INSTALLMENT FORMULA
            $interest = ($interestRate / 100 * $unpaid) * $year;
            $unpaidAndInterest = $unpaid + $interest;
            $installment =  floor($unpaidAndInterest / $data['duration']);

            // INSURANCE FORMULA
            $totalSales = parseMoneyToInteger($request->input('total_sales_price'));
            $insuranceCost = $totalSales * ($insuranceRate / 100);

            $return['interestRate'] = ($interestRate != null) ? $interestRate : 0;
            $return['insuranceCost'] = ($insuranceRate != null) ? $insuranceCost : 0;
            $return['insuranceCostHuman'] = moneyFormat($return['insuranceCost']);
            $return['installmentCost'] = ($interestRate != null) ? $installment : 0;
            $return['installmentCostHuman'] = moneyFormat($return['installmentCost']);

            return $this->apiSuccess($return, $request->input());

        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');               
        }
    }

    public function customerData (Request $request) {
        try {
            $phone = $request->input('phone');
            $data = Customer::select(DB::raw("id, first_name, last_name, address, 
                                            (select id_number from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_number,
                                            (select type from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_type,
                                            (select filename from customer_image where customer_id = customers.id order by type, id desc limit 1) AS idImage"
                                            ))
                            ->where('phone', $phone)
                            ->first();

            if($data->id_type == 1) {
                $folder = 'ktp/';
            } else if($data->id_type == 2) {
                $folder = 'sim/';
            } else {
                $folder = 'passport/';
            }

            $data->idImage = asset('images/customer').'/'.$folder.$data->idImage;
            return $this->apiSuccess($data, $request->input());
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');   
        }
    }
}