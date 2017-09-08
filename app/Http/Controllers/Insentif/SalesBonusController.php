<?php

namespace App\Http\Controllers\Insentif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesBonusHead;
use App\Models\SalesBonusDetail;

class SalesBonusController extends Controller
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
        $this->model = new SalesBonusHead();
        $this->module = 'insentif.sales-bonus';
        $this->page = 'sales-bonus';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'result' => $this->model->all(),
            'page' => $this->page
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
            'page' => $this->page
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
        $this->validate($request,[
            'start_date'     => 'required',
            'end_date'     => 'required'
        ]);

        $create = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'created_by' => Auth::id()
        ];

        $head = $this->model->create($create);

        // SALES BONUS DETAIL PROCESS
        $min = $request->input('min');
        $max = $request->input('max');
        $amount = $request->input('amount');

        foreach ($min as $key => $value) {
            $createDetail = [
                'sales_bonus_id'   => $head->id,
                'min_car'            => $value,
                'max_car'            => $max[$key],
                'amount'         => parseMoneyToInteger($amount[$key])
            ];

            SalesBonusDetail::create($createDetail);
        }

        logUser('Create Sales Bonus Formula '.$head->id);

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
        $data = [
            'page' => $this->page,
            'row' => $this->model->find($id),
            'detail' => SalesBonusDetail::where('sales_bonus_id', $id)->get()
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
        $this->validate($request,[
            'start_date'     => 'required',
            'end_date'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'updated_by' => Auth::id()
        ];

        $data->update($update);

        // SALES BONUS DETAIL PROCESS
        $min = $request->input('min');
        $max = $request->input('max');
        $amount = $request->input('amount');
        SalesBonusDetail::where('sales_bonus_id', $id)->delete();
        foreach ($min as $key => $value) {
            if($value != null) {
                $createDetail = [
                    'sales_bonus_id'   => $id,
                    'min_car'            => $value,
                    'max_car'            => $max[$key],
                    'amount'         => parseMoneyToInteger($amount[$key])
                ];
                SalesBonusDetail::create($createDetail);
            }
        }

        logUser('Update Sales Bonus Formula '.$id);

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
        SalesBonusDetail::where('insurance_rate_id', $id)->delete();
        logUser('Delete Sales Bonus Formula '.$id);
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
