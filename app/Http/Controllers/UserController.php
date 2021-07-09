<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();
        $i = 0;
        return view('users.index', compact('data','i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $payload = $request->validated();
        $user = User::create($payload);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('sucesso','Usuário criado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request,$id)
    {
        $user = User::find($id);
        $payload = $request->validated();
        if (empty($payload['password'])) {
            $payload = Arr::except($payload,'password');
        }

        $user->update($payload);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();

        $user->assignRole($payload['roles']);

        return redirect()->route('users.index')->with('sucesso','Usuário Alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('sucesso','Usário excluido com sucesso!');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $payload = $request->validated();
        $user = User::where('email',$payload['email'])->get();
        if(!empty(count($user))){
            $novaSenha = Str::random(8);
            $nomeUser = $user[0]->name;
            $newPassword['password'] = $novaSenha;
            $texto = 'Olá '.$nomeUser.' \n segue sua nova senha: '.$novaSenha;
            if(mail($payload['email'],'Nova senha',$texto)){
                $user[0]->update($newPassword);
            }
            return redirect()->route('login')->with('sucesso','E-mail enviado com sua senha');
        } else {
            return redirect()->route('login')->with('error','E-mail não encontrado');
        }
    }
}
