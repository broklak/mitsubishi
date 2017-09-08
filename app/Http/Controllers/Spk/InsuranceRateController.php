<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\InsuranceRateHead;
use App\Models\InsuranceRateDetail;
use App\Models\Leasing;
use App\Models\CarModel;
use App\Models\CarCategory;
use App\Models\Area;

class InsuranceRateController extends Controller
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
        $this->model = new InsuranceRateHead();
        $this->module = 'spk.insurance-rate';
        $this->page = 'insurance-rate';
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
            'carModel' => CarModel::all(),
            'carCategory' => CarCategory::all(),
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
            'car_model_id'     => 'required',
            'area'     => 'required'
        ]);

        $create = [
            'leasing_id'  => $request->input('leasing_id'),
            'car_category_id'  => CarModel::getCategory($request->input('car_model_id')),
            'car_model_id'  => $request->input('car_model_id'),
            'area'  => implode(',', $request->input('area')),
            'created_by' => Auth::id()
        ];

        $head = $this->model->create($create);

        // LEASING RATE DETAIL PROCESS
        $years = $request->input('years');
        $rate = $request->input('rate');
        $type = $request->input('type');

        foreach ($years as $key => $value) {
            $createDetail = [
                'insurance_rate_id'   => $head->id,
                'years'            => $value,
                'rate'              => $rate[$key],
                'type'              => $type[$key],
                'created_by' => Auth::id()
            ];

            InsuranceRateDetail::create($createDetail);
        }

        logUser('Create Insurance Formula '.$head->id);

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
            'detail' => InsuranceRateDetail::where('insurance_rate_id', $id)->get(),
            'leasing' => Leasing::all(),
            'carModel' => CarModel::all(),
            'carCategory' => CarCategory::all(),
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
            'car_model_id'     => 'required',
            'area'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'leasing_id'  => $request->input('leasing_id'),
            'car_category_id'  => CarModel::getCategory($request->input('car_model_id')),
            'car_model_id'  => $request->input('car_model_id'),
            'area'  => implode(',', $request->input('area')),
            'updated_by' => Auth::id()
        ];

        $data->update($update);

        // LEASING RATE DETAIL PROCESS
        $years = $request->input('years');
        $rate = $request->input('rate');
        $type = $request->input('type');
        InsuranceRateDetail::where('insurance_rate_id', $id)->delete();
        foreach ($years as $key => $value) {
            if($value != null) {
                $createDetail = [
                    'insurance_rate_id'   => $id,
                    'years'            => $value,
                    'rate'              => $rate[$key],
                    'type'              => $type[$key],
                    'created_by' => Auth::id()
                ];
                InsuranceRateDetail::create($createDetail);
            }
        }

        logUser('Update Insurance Formula '.$id);

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
        InsuranceRateDetail::where('insurance_rate_id', $id)->delete();
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        logUser('Delete Insurance Formula '.$id);
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

        logUser('Change Status Insurance Formula '.$id);

        $message = setDisplayMessage('success', "Success to $desc ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
