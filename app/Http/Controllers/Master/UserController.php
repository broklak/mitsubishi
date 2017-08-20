<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\JobPosition;
use App\Models\Dealer;
use App\Models\UserDealer;


class UserController extends Controller
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
        $this->model = new User();
        $this->module = 'master.user';
        $this->page = 'user';
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
            'position' => JobPosition::all(),
            'dealer' => Dealer::all(),
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
            'first_name'     => 'required',
            'last_name'     => 'required',
            'username'     => 'required|unique:users',
            'job_position_id'     => 'required',
            'dealer_id'     => 'required',
            'password' => 'required|string|min:4',
        ]);

        $create = [
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'job_position_id'  => $request->input('job_position_id'),
            'username'  => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'created_by' => Auth::id()
        ];

        $user = $this->model->create($create);

        // INSERT USER DEALER MAPPING
        $userDealer = UserDealer::insert($user->id, $request->input('dealer_id'));

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
        $getAssignDealer = UserDealer::where('user_id', $id)->get();
        $activeDealer = [];
        foreach ($getAssignDealer as $key => $value) {
            $activeDealer[] = $value->dealer_id;
        }
        $data = [
            'page' => $this->page,
            'row' => $this->model->find($id),
            'position' => JobPosition::all(),
            'dealer' => Dealer::all(),
            'assignDealer' => $activeDealer
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
            'first_name'     => 'required',
            'job_position_id'     => 'required',
            'dealer_id'     => 'required',
            'last_name'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'job_position_id'  => $request->input('job_position_id'),
            'updated_by' => Auth::id()
        ];

        if($request->input('password')) {
            $update['password'] = bcrypt($request->input('password'));
        }

        $data->update($update);

        // UPDATE USER DEALER MAPPING
        $userDealer = UserDealer::insert($id, $request->input('dealer_id'));

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
