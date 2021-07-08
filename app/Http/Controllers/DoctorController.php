<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorCreateRequest;
use App\Http\Requests\DoctorUpdateRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DoctorController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:doctor-list|doctor-create|doctor-edit|doctor-delete', ['only' => ['index','show']]);
         $this->middleware('permission:doctor-create', ['only' => ['create','store']]);
         $this->middleware('permission:doctor-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:doctor-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Doctor::all();
        $i =0;
        return view('doctors.index', compact('data','i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('doctors.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorCreateRequest $request)
    {
        $payload = $request->validated();
        $user = User::create($payload);
        $user->assignRole($request->input('roles'));
        $payload['user_id'] = $user->id;
        $doctor = Doctor::create($payload);
        return redirect()->route('doctors.index')->with('sucesso', "Medico cadastrado com sucesso");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = Doctor::find($id);
        return view('doctors.show',compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doctor = Doctor::find($id);
        $roles = Role::pluck('name','name')->all();
        $user = User::find($doctor->user_id);
        $userRole = $user->roles->pluck('name','name')->all();
        return view('doctors.edit', compact('doctor','roles','user','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorUpdateRequest $request, $id)
    {
        $payload = $request->validated();
        $doctor = Doctor::find($id);
        if (empty($payload['password'])) {
            $payload = Arr::except($payload,'password');
        }
        $user = User::find($doctor->user_id);
        $user->update($payload);
        $doctor->update($payload);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();

        $user->assignRole($payload['roles']);

        return redirect()->route('doctors.index')->with('sucesso','Medico editado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $user = User::find($doctor->user_id);
        $doctor->delete();
        $user->delete();
        return redirect()->route('doctors.index')->with('sucesso','Medico excluido com sucesso');
    }
}
