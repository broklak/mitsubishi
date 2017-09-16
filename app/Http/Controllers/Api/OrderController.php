<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\OrderHead;
use App\Models\OrderPrice;
use App\Models\OrderCredit;
use App\Models\OrderLog;
use App\Models\OrderApproval;
use App\Models\CarModel;
use App\Models\DeliveryOrder;
use App\Models\CarType;
use App\Models\Customer;
use App\User;
use App\RoleUser;
use App\Role;
use App\PermissionRole;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api');
	}

    public function list(Request $request) { 
    	try {
    		$approval = ($request->input('type') == 'approval') ? true : false;
	    	$limit = ($request->input('limit')) ? $request->input('limit') : 10;
	    	$page = ($request->input('page')) ? $request->input('page') : 1;
	        $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
	        $query = $request->input('query');

	        if($limit < 1) return $this->apiError($statusCode = 400, 'Limit data must be greater than zero', 'Something went wrong with the request');	

	        $order = new OrderHead();
            $data = $order->list($approval, $query, $sort, $limit, $page);
            $data = $order->filterResult($data);
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

            $customer = Customer::find($orderHead->customer_id);
            $orderPrice = OrderPrice::where('order_id', $id)->first();
            $orderCredit = OrderCredit::where('order_id', $id)->first();
            if($customer->id_type == 1) {
                $folder = "ktp";
            } else if($customer->id_type == 2) {
                $folder = "/sim/";
            } else {
                $folder = "passport";
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
                'customerName'         => $customer->first_name,
                'customerLastName'    => $customer->last_name,
                'idType'               => $customer->id_type,
                'idNumber'             => $customer->id_number,
                'customerAddress'      => $customer->address,
                'idImage'              => asset('images/customer') . $folder . $customer->image,
                'customerPhone'        => $customer->phone,
                'customerNpwp'         => $customer->npwp,
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

            $create['customer_id'] = Customer::validateSpk($create);

            $createHead = $orderHead->create($create);

            $create['order_id'] = $createHead->id;

            $createPrice = OrderPrice::createData($create);

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
        
        $data['message'] = 'Success to create SPK';
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

            $update['customer_id'] = Customer::validateSpk($update);

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
            'customer_npwp' => 'required',
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
                'type'  => 1,
                'job_position_id' => 0,
                'approved_by'   => Auth::id()
            ]);

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
}
