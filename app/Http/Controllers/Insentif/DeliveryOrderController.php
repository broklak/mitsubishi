<?php

namespace App\Http\Controllers\Insentif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryOrder;
use App\Models\OrderCredit;
use App\Models\OrderPrice;
use App\Models\OrderHead;
use App\Models\Customer;

class DeliveryOrderController extends Controller
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
    	$this->model = new DeliveryOrder();
    	$this->module = 'insentif.delivery-order';
        $this->page = 'delivery-order';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderHead = new OrderHead();
        $orderHead->setNotif();
        $checked = ($request->input('type') == 'checked') ? true : false;
        $data = [
            'result' => ($checked) ? $this->model->notChecked() : $this->model->list(),
            'page' => $this->page
        ];
        return view($this->module . ".index", $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = $this->model->find($id);
        $init = $this->initValue($row->spk_id);
        $data = [
            'page' => $this->page,
            'title' => 'Delivery Order '.$row->do_code,
            'row'   => $row,
            'init'  => $init
        ];

        return view($this->module.".show", $data);
    }

    protected function initValue($orderId = null) {

        $orderHead = OrderHead::find($orderId);
        $customer = Customer::find($orderHead->customer_id);
        $orderPrice = OrderPrice::where('order_id', $orderId)->first();
        $orderCredit = OrderCredit::where('order_id', $orderId)->first();

        return [
            'dealer_id'             => $orderHead->dealer_id,
            'spk_code'             => $orderHead->spk_code,
            'date'             => $orderHead->date,
            'customer_name'         => $customer->first_name,
            'customer_last_name'    => $customer->last_name,
            'id_type'               => $customer->id_type,
            'model_id'               => $orderHead->model_id,
            'type_id'               => $orderHead->type_id,
            'bbn_type'               => $orderHead->type_id,
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

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeType($id, $type) {
        $data = $this->model->find($id);

        $data->is_fleet = $type;

        $data->save();

        $message = setDisplayMessage('success', "Success to change type of selected DO");
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
