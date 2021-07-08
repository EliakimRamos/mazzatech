<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleCreateRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:schedule-list|schedule-create|schedule-edit|schedule-delete', ['only' => ['index','show']]);
         $this->middleware('permission:schedule-create', ['only' => ['create','store']]);
         $this->middleware('permission:schedule-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:schedule-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Schedule::orderby('scheduling', 'DESC')->get();
        $i = 0;
        return view('schedules.index', compact('data', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctorsAll = Doctor::all();
        $patientAll = Patient::all();
        $doctors = [];
        $patients= [];
        foreach($doctorsAll as $doc){
            $doctors[$doc->id]=  $doc->user->name;
        }
        foreach($patientAll as $pa){
            $patients[$pa->id] = $pa->user->name;
        }
        return view('schedules.create', compact('doctors', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleCreateRequest $request)
    {
        $payload = $request->validated();
         Schedule::create($payload);

         return redirect()->route('schedules.index')->with('sucesso','Consulta agendada com sucesso!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schedule = Schedule::find($id);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleUpdateRequest $request, $id)
    {
        $payload = $request->all();
        $schedule = Schedule::find($id);
        $schedule->update($payload);
        return redirect()->route('schedules.index')->with('sucesso','Agendamento da consulta atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
