<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderCredit;
use App\Models\OrderPrice;
use App\Models\OrderHead;
use App\Models\CarType;
use App\Models\Leasing;
use App\PermissionRole;
use App\Models\OrderApproval;
use App\Models\OrderLog;
use App\Models\DeliveryOrder;
use App\Models\Bbn;
use App\Models\DefaultAdminFee;
use App\Models\UserDealer;
use App\Models\Customer;
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
        $where = [];
        $data = [
            'result' => ($approval) ? $this->model->notApproved() : $this->model->list(),
            'page' => $this->page,
            'title' => ($approval) ? 'SPK To Approve' : 'SPK List',
            'approval' => $approval
        ];
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
        $create['customer_id'] = Customer::validateSpk($create);

        $createHead = $this->model->create($create);

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
        $update['customer_id'] = Customer::validateSpk($update);

        $updateHead = $this->model->updateData($id, $update);

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
            'customer_name' => 'required',
            'id_type' => 'required',
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
                'customer_name'         => old('customer_name'),
                'customer_last_name'    => old('customer_last_name'),
                'id_type'               => old('id_type'),
                'id_number'             => old('id_number'),
                'customer_address'      => old('customer_address'),
                'customer_phone'        => old('customer_phone'),
                'customer_npwp'         => old('customer_npwp'),
                'stnk_name'             => old('stnk_name'),
                'stnk_address'          => old('stnk_address'),
                'faktur_conf'           => old('faktur_conf'),
                'type_id'               => old('type_id'),
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
                'down_payment_amount'   => old('down_payment_amount'),
                'down_payment_date'     => old('down_payment_date'),
                'jaminan_cost_amount'   => old('jaminan_cost_amount'),
                'jaminan_cost_percentage' => old('jaminan_cost_percentage'),
                'total_unpaid'          => old('total_unpaid'),
                'payment_method'        => old('payment_method'),
                'leasing_id'            => old('leasing_id'),
                'year_duration'         => old('year_duration'),
                'owner_name'            => old('owner_name'),
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

        return [
            'customer_name'         => $customer->first_name,
            'customer_last_name'    => $customer->last_name,
            'id_type'               => $customer->id_type,
            'id_number'             => $customer->id_number,
            'customer_address'      => $customer->address,
            'customer_phone'        => $customer->phone,
            'customer_npwp'         => $customer->npwp,
            'stnk_name'             => $orderHead->stnk_name,
            'stnk_address'          => $orderHead->stnk_address,
            'faktur_conf'           => $orderHead->faktur_conf,
            'type_id'               => $orderHead->type_id,
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
            'price_type'            => ($orderPrice->price_off == 0) ? 2 : 1,
            'price_off'             => moneyFormat($orderPrice->price_off),
            'price_on'              => moneyFormat($orderPrice->price_on),
            'cost_surat'            => moneyFormat($orderPrice->cost_surat),
            'discount'              => moneyFormat($orderPrice->discount),
            'total_sales_price'     => moneyFormat($orderPrice->total_sales_price),
            'down_payment_amount'   => moneyFormat($orderPrice->down_payment_amount),
            'down_payment_date'     => $orderPrice->down_payment_date,
            'jaminan_cost_amount'   => moneyFormat($orderPrice->jaminan_cost_amount),
            'jaminan_cost_percentage' => $orderPrice->jaminan_cost_percentage,
            'total_unpaid'          => moneyFormat($orderPrice->total_unpaid),
            'payment_method'        => $orderPrice->payment_method,
            'leasing_id'            => (isset($orderCredit->leasing_id)) ? $orderCredit->leasing_id : null,
            'year_duration'         => (isset($orderCredit->year_duration)) ? $orderCredit->year_duration : null,
            'owner_name'            => (isset($orderCredit->owner_name)) ? $orderCredit->owner_name : null,
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
        $eligible = OrderApproval::eligibleToApprove($this->model->find($orderId));

        if(!$eligible) {
            $message = setDisplayMessage('error', "You are not eligible to approve this SPK");
            return redirect(route($this->page.'.show', ['id' => $orderId]))->with('displayMessage', $message);
        }

        $approve = OrderApproval::create([
            'order_id'  => $orderId,
            'level_approved' => 0,
            'role_name' => $level,
            'job_position_id' => $user['job_position_id'],
            'approved_by'   => $user['id']
        ]);

        //CREATE LOG
        OrderLog::create([
            'order_id'      => $orderId,
            'desc'          => 'Approved',
            'created_by'    => Auth::id()
        ]);

        $message = setDisplayMessage('success', "Success to approve SPK");
        return redirect(route($this->page.'.show', ['id' => $orderId]))->with('displayMessage', $message);
    }
}
