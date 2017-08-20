<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $page;


    public function __construct() {
        $this->module = 'master.banner';
        $this->page = 'banner';
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
            'result' => Banner::all(),
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
            'banner_name' => 'required',
            'image'       => 'required|mimes:png,jpeg,jpg'
        ]);

        $create = [
            'name'  => $request->input('banner_name'),
            'created_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $request->image->getClientOriginalName();
            $request->image->move(
                base_path() . '/public/images/banner/', $name
            );
            $create['file'] = $name;
        }

        Banner::create($create);

        $message = setDisplayMessage('success', "Success to create new ".$this->page);
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
        //
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
            'row' => Banner::find($id)
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
            'banner_name' => 'required',
            'image'       => 'mimes:png,jpeg,jpg'
        ]);

        $data = Banner::find($id);

        $update = [
            'name'  => $request->input('banner_name'),
            'updated_by' => Auth::id()
        ];

        if ($request->file('image')) {
            $name = $request->image->getClientOriginalName();
            $request->image->move(
                base_path() . '/public/images/banner/', $name
            );
            $update['file'] = $name;
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
        Banner::find($id)->delete();
        $message = setDisplayMessage('success', "Success to delete ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }

    /**
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id, $status) {
        $data = Banner::find($id);

        $data->status = $status;

        $desc = ($status == 1) ? 'activate' : 'deactivate';

        $data->save();

        $message = setDisplayMessage('success', "Success to $desc ".$this->page);
        return redirect(route($this->page.'.index'))->with('displayMessage', $message);
    }
}
