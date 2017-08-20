<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ApprovalSetting;

class ApprovalController extends Controller
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
        $this->module = 'setting.approval';
        $this->model = new ApprovalSetting();
        $this->page = 'approval';
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
            'result' => ApprovalSetting::orderBy('level')->get(),
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
            'page'  => $this->page,
            'role'  => $this->model->getNonApproverRole()
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
            'job_position_id' => 'required',
            'level' => 'required|numeric'
        ]);

        $create = [
            'job_position_id'  => $request->input('job_position_id'),
            'level'  => $request->input('level'),
            'updated_by' => Auth::id()
        ];

        $this->model->create($create);

        $message = setDisplayMessage('success', "Success to add new approver");
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->model->find($id)->delete();
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLevel(Request $request) {
        $level = $request->input('level');
        foreach ($level as $key => $value) {
            $this->model->find($key)->update([
                'level'         => $value,
                'updated_by'    => Auth::id()
            ]);
        }
        $message = setDisplayMessage('success', "Success to change level of approver ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
