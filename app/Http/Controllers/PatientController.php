<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientCreateRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PatientController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:patient-list|patient-create|patient-edit|patient-delete', ['only' => ['index','show']]);
         $this->middleware('permission:patient-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Patient::all();
        $i =0;
        return view('patients.index', compact('data','i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('patients.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientCreateRequest $request)
    {
        $payload = $request->validated();
        $user = User::create($payload);
        $user->assignRole($request->input('roles'));
        $payload['user_id'] = $user->id;
        $patient = Patient::create($payload);
        return redirect()->route('patients.index')->with('sucesso', "Paciente cadastrado com sucesso");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::find($id);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::find($id);
        $roles = Role::pluck('name','name')->all();
        $user = User::find($patient->user_id);
        $userRole = $user->roles->pluck('name','name')->all();
        return view('patients.edit', compact('patient','roles','user','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatientUpdateRequest $request, $id)
    {
        $payload = $request->validated();
        $patient = Patient::find($id);
        if (empty($payload['password'])) {
            $payload = Arr::except($payload,'password');
        }
        $user = User::find($patient->user_id);
        $user->update($payload);
        $patient->update($payload);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();

        $user->assignRole($payload['roles']);

        return redirect()->route('patients.index')->with('sucesso','Paciente editado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);
        $user = User::find($patient->user_id);
        $patient->delete();
        $user->delete();
        return redirect()->route('patients.index')->with('sucesso','Paciente excluido com sucesso');
    }
}
