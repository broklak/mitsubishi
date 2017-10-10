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
		$this->middleware('auth:api', ['except' => ['syncSpk']]);
	}

    public function list(Request $request) { 
    	try {
            $type = $request->input('type');
            $time = $request->input('timestamp');
            if($type == 'sync') {
                return $this->shortSpk($request->input());
            }

    		$approval = ($request->input('type') == 'approval') ? true : false;
	    	$limit = ($request->input('limit')) ? $request->input('limit') : 10;
	    	$page = ($request->input('page')) ? $request->input('page') : 1;
	        $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
	        $query = $request->input('query');

	        if($limit < 1) return $this->apiError($statusCode = 400, 'Limit data must be greater than zero', 'Something went wrong with the request');	

	        $order = new OrderHead();
            $data = $order->list($approval, $query, $sort, $limit, $page, $time);
            $data = $order->filterResult($data, $api = true);
            $pagination = $this->getPagination($data, $order->countList($approval, $query), $page, $limit);
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
                $name = $request->npwp_image->getClientOriginalName();
                $request->npwp_image->move(
                    base_path() . '/public/images/npwp/', $name
                );
                $create['npwp_image'] = $name;
            }

            if ($request->file('id_image')) {
                $nameCust = $request->id_image->getClientOriginalName();
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
                $createCredit = OrderCredit::createData($create);
            }

            logUser('Create SPK '.$createHead->id);    
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }
        
        $data['created'] = $createHead;
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
            $validator = Validator::make($request->input(), $this->rules());
            if ($validator->fails()) {    
                return $this->apiError($statusCode = 400, $validator->messages(), 'Some fields must be filled');
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
                $createCredit = OrderCredit::createData($update);
            }

            logUser('Update SPK '.$id);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }
        $data['message'] = 'Success to update SPK';
        return $this->apiSuccess($data, $request->input());
        
    }

    protected function rules() {
        return [
            'spk_doc_code'     => 'required',
            'date'     => 'required',
            'dealer_id' => 'required',
            'id_number' => 'required',
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_address' => 'required',
            'customer_phone' => 'required',
            'id_type' => 'required',
            'car_year' => 'required',
            'stnk_name' => 'required',
            'stnk_address' => 'required',
            'type_id' => 'required',
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

            if(!isset($param['do'])) {
                return $this->apiError($statusCode = 400, 'No DO key in body param', 'Invalid Request Body Param');            
            }

            $do = $param['do'];
            foreach ($do as $key => $value) {
                $spk = $value['spkNo'];
                $findSpk = OrderHead::where('spk_doc_code', $spk)->first();

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
                'notProcessd' => $false,
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

        $validSecret = ServerSecret::find(1)->value('secret');

        if($secret != $validSecret) {
            return $this->apiError($statusCode = 401, 'Wrong Server Secret', 'Unauthenticated');   
        }

        $where = [];
        if($request->input('timestamp')) {
            $where[] = ['created_at', '>', $request->input('timestamp')];
        }

        $data = OrderHead::where($where)
                            ->get();

        return $this->apiSuccess($data, $request->input());
    }

    public function fields() {
        $dealer = Dealer::select('id as value', 'name as display')->get();
        $carType = CarType::getOptionValue();
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
                    generateApiField($fieldName = 'spk_doc_code', $label = 'Document Control Number'),
                    generateApiField($fieldName = 'date', $label = 'Date', $type = 'date', $required = true, $options = null, $desc = 'YYYY-MM-DD'),
                    generateApiField($fieldName = 'dealer_id', $label = 'Dealer', $type = 'select', $required = true, $options = $dealer),
                    generateApiField($fieldName = 'customer_phone', $label = 'Customer Phone'),
                    generateApiField($fieldName = 'id_type', $label = 'Customer ID Type', $type = 'select', $required = true, $options = $idType),
                    generateApiField($fieldName = 'id_image', $label = 'Customer ID Image', $type = 'file', $required = false),
                    generateApiField($fieldName = 'id_number', $label = 'Customer ID Number'),
                    generateApiField($fieldName = 'first_name', $label = 'Customer First Name'),
                    generateApiField($fieldName = 'last_name', $label = 'Customer Last Name', $type = 'string', $required = false),
                    generateApiField($fieldName = 'npwp', $label = 'Customer NPWP', $type = 'string', $required = false),
                    generateApiField($fieldName = 'npwp_image', $label = 'Customer NPWP Image', $type = 'file', $required = false),
                    generateApiField($fieldName = 'stnk_name', $label = 'STNK Name'),
                    generateApiField($fieldName = 'stnk_address', $label = 'STNK Address'),
                    generateApiField($fieldName = 'faktur_conf', $label = 'Faktur Confirmation', $type = 'string', $required = false),
                    generateApiField($fieldName = 'type_id', $label = 'Car Type', $type = 'select', $required = true, $options = $carType),
                    generateApiField($fieldName = 'color', $label = 'Car Color'),
                    generateApiField($fieldName = 'car_year', $label = 'Car Year', $type = 'string', $required = true, $options = null, $desc = 'YYYY-MM-DD'),
                    generateApiField($fieldName = 'qty', $label = 'Total Unit', $type = 'integer'),
                    generateApiField($fieldName = 'plat', $label = 'Car Plat Type', $type = 'select', $required = true, $options = $platType),
                    generateApiField($fieldName = 'bbn_type', $label = 'BBN Type', $type = 'select', $required = true, $options = $bbnType),
                    generateApiField($fieldName = 'bbn_type', $label = 'BBN Type', $type = 'select', $required = true, $options = $bbnType),
                    generateApiField($fieldName = 'karoseri', $label = 'Karoseri', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_type', $label = 'Karoseri Type', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_spec', $label = 'Karoseri Spesification', $type = 'string', $required = false),
                    generateApiField($fieldName = 'karoseri_price', $label = 'Karoseri Price', $type = 'integer', $required = false),
                    generateApiField($fieldName = 'price_type', $label = 'Price Type', $type = 'select', $required = true, $options = $priceType),
                    generateApiField($fieldName = 'discount', $label = 'Discount', $type = 'integer'),
                    generateApiField($fieldName = 'price_off', $label = 'Price Off The Road', $type = 'integer'),
                    generateApiField($fieldName = 'price_on', $label = 'Price On The Road', $type = 'integer'),
                    generateApiField($fieldName = 'cost_surat', $label = 'STNK Cost', $type = 'integer'),
                    generateApiField($fieldName = 'total_sales_price', $label = 'Total Sales Price', $type = 'integer'),
                    generateApiField($fieldName = 'booking_fee', $label = 'Booking Fee', $type = 'integer'),
                    generateApiField($fieldName = 'down_payment_date', $label = 'Down Payment Date', $type = 'date'),
                    generateApiField($fieldName = 'dp_percentage', $label = 'DP Percentage', $type = 'integer'),
                    generateApiField($fieldName = 'dp_amount', $label = 'DP Amount', $type = 'integer'),
                    generateApiField($fieldName = 'total_unpaid', $label = 'Total Unpaid', $type = 'integer'),
                    generateApiField($fieldName = 'payment_method', $label = 'Payment Method', $type = 'select', $required = true, $options = $paymentMethod),
        ];

        $field['leasingField'] = [
            generateApiField($fieldName = 'leasing_id', $label = 'Leasing', $type = 'select', $required = true, $options = $leasing),
            generateApiField($fieldName = 'credit_duration', $label = 'Credit Duration', $type = 'select', $required = true, $options = $months),
            generateApiField($fieldName = 'credit_owner_name', $label = 'Credit Owner'),
            generateApiField($fieldName = 'interest_rate', $label = 'Interest Rate', $type = 'float'),
            generateApiField($fieldName = 'admin_cost', $label = 'Admin Cost', $type = 'integer'),
            generateApiField($fieldName = 'insurance_cost', $label = 'Insurance Cost', $type = 'integer'),
            generateApiField($fieldName = 'installment_cost', $label = 'Installment Cost', $type = 'integer'),
            generateApiField($fieldName = 'other_cost', $label = 'Other Cost', $type = 'integer'),
            generateApiField($fieldName = 'total_down_payment', $label = 'Total Down Payment', $type = 'integer')
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