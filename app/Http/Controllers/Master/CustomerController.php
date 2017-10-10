<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CustomerImage;


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
            'result' => $this->model->list(),
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
            'id_type'  => '1',
            'id_number'  => '0',
            'phone'  => $request->input('phone'),
            'phone_home'  => $request->input('phone_home'),
            'email'  => $request->input('email'),
            'address'  => $request->input('address'),
            'job'  => $request->input('job'),
            'npwp'  => $request->input('npwp'),
            'created_by' => Auth::id()
        ];

        $customer = $this->model->create($create);

        if ($request->file('image')) {
            $name = $request->input('id_number').'-'.$request->image->getClientOriginalName();
            $folder = ($create['id_type'] == 1) ? 'ktp' : 'sim';
            $folder = ($create['id_type'] == 3) ? 'passport' : $folder;
            $request->image->move(
                base_path() . '/public/images/customer/'.$folder.'/', $name
            );
            
            CustomerImage::create([
                'customer_id'       => $customer->id,
                'type'              => $request->input('id_type'),
                'id_number'         => $request->input('id_number'),
                'filename'          => $name,
                'created_by'        => Auth::id()
            ]);       

        }

        

        logUser('Create Customer '.$create['first_name'].' '.$create['last_name']);

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
        $ktp = CustomerImage::where('type', 1)->where('customer_id', $id)->orderBy('id', 'desc')->limit(2)->get();
        $sim = CustomerImage::where('type', 2)->where('customer_id', $id)->orderBy('id', 'desc')->limit(2)->get();
        $passport = CustomerImage::where('type', 3)->where('customer_id', $id)->orderBy('id', 'desc')->limit(2)->get();
        $data = [
            'page'  => $this->page,
            'row'   => $this->model->find($id),
            'ktp'   => $ktp,
            'sim'   => $sim,
            'passport' => $passport
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
            'phone'     => 'required'
        ]);

        $data = $this->model->find($id);

        $update = [
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'id_type'  => '1',
            'id_number'  => '0',
            'phone'  => $request->input('phone'),
            'phone_home'  => $request->input('phone_home'),
            'email'  => $request->input('email'),
            'address'  => $request->input('address'),
            'job'  => $request->input('job'),
            'npwp'  => $request->input('npwp'),
            'updated_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $request->image->getClientOriginalName();
            $folder = ($update['id_type'] == 1) ? 'ktp' : 'sim';
            $folder = ($update['id_type'] == 3) ? 'passport' : $folder;
            $request->image->move(
                base_path() . '/public/images/customer/'.$folder.'/', $name
            );
            $update['image'] = $name;
        }

        $data->update($update);

        logUser('Update Customer '.$update['first_name'] . ' ' . $update['last_name']);

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
        $data = $this->model->find($id);
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        CustomerImage::where('customer_id', $id)->delete();
        logUser('Delete Customer '.$data->first_name . ' ' . $data->last_name);
        $data->delete();
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

        logUser('Change Status Customer '.$data->first_name . ' ' . $data->last_name);

        $message = setDisplayMessage('success', "Success to $desc ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    public function deleteImage($id) {
        $data = CustomerImage::find($id);
        $idCust = $data->customer_id;
        $data->delete();
        $message = setDisplayMessage('success', "Success to delete image".$this->page);
        return redirect(route($this->page.'.edit', ['id' => $idCust]))->with('displayMessage', $message);
    }

    public function addImage(Request $request) {
        $number = $request->input('id_number');
        $type = $request->input('type');
        $customer_id = $request->input('customer_id');

        $create = [
            'id_number' => $number,
            'type'    => $type,
            'customer_id' => $customer_id,
            'created_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $create['id_number'].'-'.$request->image->getClientOriginalName();
            $folder = ($type == 1) ? 'ktp' : 'sim';
            $folder = ($type == 3) ? 'passport' : $folder;
            $request->image->move(
                base_path() . '/public/images/customer/'.$folder.'/', $name
            );
            $create['filename'] = $name;
        }

        CustomerImage::create($create);
        $message = setDisplayMessage('success', "Success to add new $folder image".$this->page);
        return redirect(route($this->page.'.edit', ['id' => $create['customer_id']]))->with('displayMessage', $message);
    }
}
