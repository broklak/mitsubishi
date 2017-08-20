<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;


class CustomerController extends Controller
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
        $this->model = new Customer();
        $this->module = 'master.customer';
        $this->page = 'customer';
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
            'first_name'     => 'required',
            'id_type'     => 'required',
            'id_number'     => 'required',
            'phone'     => 'required',
            'image'       => 'mimes:png,jpeg,jpg'
        ]);

        $create = [
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'id_type'  => $request->input('id_type'),
            'id_number'  => $request->input('id_number'),
            'phone'  => $request->input('phone'),
            'email'  => $request->input('email'),
            'address'  => $request->input('address'),
            'job'  => $request->input('job'),
            'npwp'  => $request->input('npwp'),
            'created_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $request->image->getClientOriginalName();
            $folder = ($create['id_type'] == 1) ? 'ktp' : 'sim';
            $request->image->move(
                base_path() . '/public/images/customer/'.$folder.'/', $name
            );
            $create['image'] = $name;
        }

        $this->model->create($create);

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
            'row' => $this->model->find($id)
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
            'id_type'     => 'required',
            'id_number'     => 'required',
            'phone'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'id_type'  => $request->input('id_type'),
            'id_number'  => $request->input('id_number'),
            'phone'  => $request->input('phone'),
            'email'  => $request->input('email'),
            'address'  => $request->input('address'),
            'job'  => $request->input('job'),
            'npwp'  => $request->input('npwp'),
            'updated_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $request->image->getClientOriginalName();
            $folder = ($update['id_type'] == 1) ? 'ktp' : 'sim';
            $request->image->move(
                base_path() . '/public/images/customer/'.$folder.'/', $name
            );
            $update['image'] = $name;
        }

        $data->update($update);

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
