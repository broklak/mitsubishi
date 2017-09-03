<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LeasingRateHead;
use App\Models\LeasingRateDetail;
use App\Models\Leasing;
use App\Models\CarType;
use App\Models\Area;

class LeasingRateController extends Controller
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
        $this->model = new LeasingRateHead();
        $this->module = 'spk.leasing-rate';
        $this->page = 'leasing-rate';
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
            'page' => $this->page,
            'leasing' => Leasing::all(),
            'carType' => CarType::all(),
            'areas' => Area::all()
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
            'leasing_id'     => 'required',
            'car_type_id'     => 'required',
            'areas'     => 'required',
            'start_date'     => 'required',
            'end_date'     => 'required'
        ]);

        $create = [
            'leasing_id'  => $request->input('leasing_id'),
            'car_type_id'  => $request->input('car_type_id'),
            'car_model_id'  => CarType::getModel($request->input('car_type_id')),
            'month_duration'  => $request->input('month_duration'),
            'areas'  => implode(',', $request->input('areas')),
            'start_date'  => $request->input('start_date'),
            'end_date'  => $request->input('end_date'),
            'karoseri'  => $request->input('karoseri'),
            'created_by' => Auth::id()
        ];

        $head = $this->model->create($create);

        // LEASING RATE DETAIL PROCESS
        $months = $request->input('months');
        $dp_min = $request->input('dp_min');
        $dp_max = $request->input('dp_max');
        $rate = $request->input('rate');

        foreach ($months as $key => $value) {
            $createDetail = [
                'leasing_rate_id'   => $head->id,
                'dp_min'            => $dp_min[$key],
                'dp_max'            => $dp_max[$key],
                'months'            => $value,
                'rate'              => $rate[$key],
                'created_by' => Auth::id()
            ];

            LeasingRateDetail::create($createDetail);
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
        $data = [
            'page' => $this->page,
            'row' => $this->model->find($id),
            'detail' => LeasingRateDetail::where('leasing_rate_id', $id)->get(),
            'leasing' => Leasing::all(),
            'carType' => CarType::all(),
            'areas' => Area::all(),
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
            'leasing_id'     => 'required',
            'car_type_id'     => 'required',
            'areas'     => 'required',
            'start_date'     => 'required',
            'end_date'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'leasing_id'  => $request->input('leasing_id'),
            'car_type_id'  => $request->input('car_type_id'),
            'car_model_id'  => CarType::getModel($request->input('car_type_id')),
            'month_duration'  => $request->input('month_duration'),
            'areas'  => implode(',', $request->input('areas')),
            'start_date'  => $request->input('start_date'),
            'end_date'  => $request->input('end_date'),
            'karoseri'  => $request->input('karoseri'),
            'updated_by' => Auth::id()
        ];

        $data->update($update);

        // LEASING RATE DETAIL PROCESS
        $months = $request->input('months');
        $dp_min = $request->input('dp_min');
        $dp_max = $request->input('dp_max');
        $rate = $request->input('rate');
        LeasingRateDetail::where('leasing_rate_id', $id)->delete();
        foreach ($months as $key => $value) {
            if($value != null) {
                $createDetail = [
                    'leasing_rate_id'   => $id,
                    'dp_min'            => $dp_min[$key],
                    'dp_max'            => $dp_max[$key],
                    'months'            => $value,
                    'rate'              => $rate[$key],
                    'created_by' => Auth::id()
                ];
                LeasingRateDetail::create($createDetail);
            }
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
        LeasingRateDetail::where('leasing_rate_id', $id)->delete();
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id, $status) {
        $data = $this->model->find($id);

        $data->status = $status;

        $desc = ($status == 1) ? 'activate' : 'deactivate';

        $data->save();

        $message = setDisplayMessage('success', "Success to $desc ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
