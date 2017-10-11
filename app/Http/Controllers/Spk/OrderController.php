<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderCredit;
use App\Models\OrderPrice;
use App\Models\OrderHead;
use App\Models\OrderAttachment;
use App\Models\CarType;
use App\Models\CarModel;
use App\Models\Leasing;
use App\PermissionRole;
use App\Models\OrderApproval;
use App\Models\OrderLog;
use App\Models\DeliveryOrder;
use App\Models\Bbn;
use App\Models\DefaultAdminFee;
use App\Models\UserDealer;
use App\Models\Customer;
use App\Models\CustomerImage;
use App\Models\CreditMonth;


class OrderController extends Controller
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $page;

    /**
     * @var string
     */
    private $model;


    public function __construct() {
        $this->model = new OrderHead();
        $this->module = 'spk.order';
        $this->page = 'order';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->model->setNotif();
        $approval = ($request->input('type') == 'approval') ? true : false;
        $query = $request->input('query');
        $data = [
            'result' => $this->model->list($approval, $query, $sort = 'desc', $limit = 10),
            'page' => $this->page,
            'title' => ($approval) ? 'SPK To Approve' : 'SPK List',
            'approval' => $approval,
            'query' => $query
        ];

        $data['result'] = $this->model->filterResult($data['result']);

        return view($this->module . ".index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'page' => $this->page,
            'carType' => CarType::all(),
            'customer' => Customer::all(),
            'leasing' => Leasing::all(),
            'months' => CreditMonth::all(),
            'bbn' => Bbn::all(),
            'dealer' => UserDealer::where('user_id', Auth::id())->get(),
            'init' => $this->initValue($type = 'create'),
        ];

        return view($this->module.".create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $create = $request->input();
        $create['created_by'] = Auth::id();

        if ($request->file('npwp_image')) {
            $name = $request->npwp_image->getClientOriginalName();
            $request->npwp_image->move(
                base_path() . '/public/images/npwp/', $name
            );
            $create['npwp_image'] = $name;
        }

        if ($request->file('id_image')) {
            $nameCust = $create['id_number'].'-'.$request->id_image->getClientOriginalName();
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

        $createHead = $this->model->create($create);

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

        $message = setDisplayMessage('success', "Success to create new ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->model->setNotif();
        $data = [
            'page' => $this->page,
            'row' => $this->model->find($id),
            'carType' => CarType::all(),
            'customer' => Customer::all(),
            'attachment' => OrderAttachment::where('order_id', $id)->get(),
            'leasing' => Leasing::all(),
            'months' => CreditMonth::all(),
            'bbn' => Bbn::all(),
            'dealer' => UserDealer::where('user_id', Auth::id())->get(),
            'init' => $this->initValue($type = 'update', $id)
        ];

        return view($this->module.".edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules());

        $update = $request->input();
        $update['updated_by'] = Auth::id();

        if ($request->file('npwp_image')) {
            $name = $request->npwp_image->getClientOriginalName();
            $request->npwp_image->move(
                base_path() . '/public/images/npwp/', $name
            );
            $update['npwp_image'] = $name;
        }

        if ($request->file('id_image')) {
            $nameCust = $update['id_number'].'-'.$request->id_image->getClientOriginalName();
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

        $updateHead = $this->model->updateData($id, $update);

        $updatePrice = OrderPrice::updateData($id, $update);

        //CREATE ATTACHMENT
        OrderAttachment::createData($request->file('attachment'), $id);

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

        $message = setDisplayMessage('success', "Success to update ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->model->find($id)->delete();
        OrderPrice::where('order_id', $id)->delete();
        OrderCredit::where('order_id', $id)->delete();
        OrderApproval::where('order_id', $id)->delete();
        OrderLog::create([
            'order_id'      => $id,
            'desc'          => 'Deleted',
            'created_by'    => Auth::id()
        ]);

        logUser('Delete SPK '.$id);

        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->model->setNotif();
        $data = [
            'page' => $this->page,
            'title' => 'Surat Pesanan Kendaraan',
            'row' => $this->model->find($id),
            'carType' => CarType::all(),
            'leasing' => Leasing::all(),
            'bbn' => Bbn::all(),
            'dealer' => UserDealer::where('user_id', Auth::id())->get(),
            'attachment' => OrderAttachment::where('order_id', $id)->get(),
            'init' => $this->initValue($type = 'update', $id),
            'approver' => PermissionRole::getSPKApprover(), 
            'approval'  => OrderApproval::getOrderApproval($id),
            'toApprove' => OrderApproval::eligibleToApprove($this->model->find($id)),
            'authId'    => Auth::id()
        ];

        return view($this->module.".show", $data);
    }

    protected function rules() {
        return [
            'spk_doc_code'     => 'required',
            'date'     => 'required',
            'dealer_id' => 'required',
            'id_number' => 'required',
            'customer_first_name' => 'required',
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

    protected function initValue($type, $orderId = null) {
        if($type == 'create') {
            return [
                'customer_first_name'   => old('customer_first_name'),
                'customer_last_name'    => old('customer_last_name'),
                'id_type'               => old('id_type'),
                'id_number'             => old('id_number'),
                'customer_address'      => old('customer_address'),
                'customer_phone'        => old('customer_phone'),
                'customer_phone_home'   => old('customer_phone_home'),
                'customer_business'     => old('customer_business'),
                'customer_npwp'         => old('customer_npwp'),
                'stnk_name'             => old('stnk_name'),
                'stnk_address'          => old('stnk_address'),
                'faktur_conf'           => old('faktur_conf'),
                'type_id'               => old('type_id'),
                'type_name'             => old('type_name'),
                'color'                 => old('color'),
                'qty'                   => old('qty'),
                'car_year'              => old('car_year'),
                'plat'                  => old('plat'),
                'bbn_type'              => old('bbn_type'),
                'karoseri'              => old('karoseri'),
                'karoseri_type'         => old('karoseri_type'),
                'karoseri_spec'         => old('karoseri_spec'),
                'karoseri_price'        => old('karoseri_price'),
                'price_type'            => old('price_type'),
                'price_off'             => old('price_off'),
                'price_on'              => old('price_on'),
                'cost_surat'            => old('cost_surat'),
                'discount'              => old('discount'),
                'total_sales_price'     => old('total_sales_price'),
                'booking_fee'           => old('booking_fee'),
                'down_payment_date'     => old('down_payment_date'),
                'dp_amount'             => old('dp_amount'),
                'dp_percentage'         => old('dp_percentage'),
                'total_unpaid'          => old('total_unpaid'),
                'payment_method'        => old('payment_method'),
                'leasing_id'            => old('leasing_id'),
                'credit_duration'       => old('credit_duration'),
                'credit_owner_name'     => old('credit_owner_name'),
                'interest_rate'         => old('interest_rate'),
                'admin_cost'            => (old('admin_cost')) ? old('admin_cost') : moneyFormat(DefaultAdminFee::getCost()),
                'insurance_cost'        => old('insurance_cost'),
                'installment_cost'      => old('installment_cost'),
                'other_cost'            => old('other_cost'),
                'total_down_payment'    => old('total_down_payment')
            ];
        }

        $orderHead = $this->model->find($orderId);
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
        return [
            'customer_first_name'   => $customer->first_name,
            'customer_last_name'    => $customer->last_name,
            'id_type'               => (isset($customerImage->type)) ? $customerImage->type : null,
            'id_number'             => (isset($customerImage->id_number)) ? $customerImage->id_number : null,
            'customer_address'      => $customer->address,
            'folder_id_image'       => $folder,
            'id_image'              => (isset($customerImage->filename)) ? $customerImage->filename : null,
            'customer_phone'        => $customer->phone,
            'customer_phone_home'   => $customer->phone_home,
            'customer_business'     => $customer->job,
            'customer_npwp'         => $customer->npwp,
            'stnk_name'             => $orderHead->stnk_name,
            'stnk_address'          => $orderHead->stnk_address,
            'faktur_conf'           => $orderHead->faktur_conf,
            'type_id'               => $orderHead->type_id,
            'type_name'             => CarModel::getName($orderHead->model_id) .' '. CarType::getName($orderHead->type_id),
            'npwp_image'            => $orderHead->npwp_image,
            'color'                 => $orderHead->color,
            'qty'                   => $orderHead->qty,
            'plat'                  => $orderHead->plat,
            'car_year'              => $orderHead->car_year,
            'bbn_type'              => $orderHead->bbn_type,
            'karoseri'              => $orderHead->karoseri,
            'karoseri_type'         => $orderHead->karoseri_type,
            'karoseri_spec'         => $orderHead->karoseri_spec,
            'karoseri_price'        => $orderHead->karoseri_price,
            'price_type'            => ($orderPrice->price_off == 0) ? 1 : 2,
            'price_off'             => moneyFormat($orderPrice->price_off),
            'price_on'              => moneyFormat($orderPrice->price_on),
            'cost_surat'            => moneyFormat($orderPrice->cost_surat),
            'discount'              => moneyFormat($orderPrice->discount),
            'total_sales_price'     => moneyFormat($orderPrice->total_sales_price),
            'booking_fee'           => moneyFormat($orderPrice->down_payment_amount),
            'down_payment_date'     => $orderPrice->down_payment_date,
            'dp_amount'             => moneyFormat($orderPrice->jaminan_cost_amount),
            'dp_percentage'         => $orderPrice->jaminan_cost_percentage,
            'total_unpaid'          => moneyFormat($orderPrice->total_unpaid),
            'payment_method'        => $orderPrice->payment_method,
            'leasing_id'            => (isset($orderCredit->leasing_id)) ? $orderCredit->leasing_id : null,
            'credit_duration'         => (isset($orderCredit->year_duration)) ? $orderCredit->year_duration : null,
            'credit_owner_name'     => (isset($orderCredit->owner_name)) ? $orderCredit->owner_name : null,
            'interest_rate'         => (isset($orderCredit->interest_rate)) ? $orderCredit->interest_rate : null,
            'admin_cost'            => (isset($orderCredit->admin_cost)) ? moneyFormat($orderCredit->admin_cost) : null,
            'insurance_cost'        => (isset($orderCredit->insurance_cost)) ? moneyFormat($orderCredit->insurance_cost) : null,
            'installment_cost'      => (isset($orderCredit->installment_cost)) ? moneyFormat($orderCredit->installment_cost) : null,
            'other_cost'            => (isset($orderCredit->other_cost)) ? moneyFormat($orderCredit->other_cost) : null,
            'total_down_payment'    => (isset($orderCredit->total_down_payment)) ? moneyFormat($orderCredit->total_down_payment) : null
        ];
    }

    public function approveSpk($orderId, $level) {
        $user = Auth::user();
        $orderHead = $this->model->find($orderId);
        $eligible = OrderApproval::eligibleToApprove($orderHead);

        if(!$eligible) {
            $message = setDisplayMessage('error', "You are not eligible to approve this SPK");
            return redirect(route($this->page.'.show', ['id' => $orderId]))->with('displayMessage', $message);
        }

        $approve = OrderApproval::create([
            'order_id'  => $orderId,
            'level_approved' => 0,
            'role_name' => $level,
            'type'  => 1,
            'job_position_id' => $user['job_position_id'],
            'approved_by'   => $user['id']
        ]);

        //CREATE LOG
        OrderLog::create([
            'order_id'      => $orderId,
            'desc'          => 'Approved',
            'created_by'    => Auth::id()
        ]);

        //EXTEND USER CREATOR SPK
        Auth::user()->extendLoginValidity($orderHead->created_by);

        logUser('Approve SPK '.$orderId);

        $message = setDisplayMessage('success', "Success to approve SPK");
        return redirect(route($this->page.'.show', ['id' => $orderId]))->with('displayMessage', $message);
    }

    public function rejectSPK(Request $request) {
        $user = Auth::user();
        $orderId = $request->input('order_id');
        $role = $request->input('role');
        $reason = $request->input('reject_reason');

        $approve = OrderApproval::create([
            'order_id'  => $orderId,
            'level_approved' => 0,
            'role_name' => $role,
            'reject_reason' => $reason,
            'type'  => 2,
            'job_position_id' => $user['job_position_id'],
            'approved_by'   => $user['id']
        ]);

        //CREATE LOG
        OrderLog::create([
            'order_id'      => $orderId,
            'desc'          => 'Rejected',
            'created_by'    => Auth::id()
        ]);

        logUser('Reject SPK '.$orderId);

        $message = setDisplayMessage('success', "Success to reject SPK");
        return redirect(route($this->page.'.show', ['id' => $orderId]))->with('displayMessage', $message);
    }

    public function deleteAttachment($id) {
        $data = OrderAttachment::find($id);
        $orderId = $data->order_id;
        $data->delete();
        $message = setDisplayMessage('success', "Success to delete attachment");
        return redirect(route($this->page.'.edit', ['id' => $orderId]))->with('displayMessage', $message);   
    }
}
