<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Admin;
use Hash;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Admin::whereHas('roles', function ($query) {
            return $query->where('name', 'ROLE_ADMIN');
        })->first();
        if($user){
            return redirect('login');
        }

        return view('installation');        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'appName' => 'required|string',
            'databaseName' => 'required|string',
            'databaseUser' => 'required|string',
            'databasePassword' => 'required|string',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:255',
        ]);

        Role::firstOrCreate(['name' => 'ROLE_ADMIN']);
        Role::firstOrCreate(['name' => 'ROLE_USER']);

        $role = Role::where('name' , 'ROLE_ADMIN')->first();
        $user = Admin::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        if($role){
            $user->roles()->attach($role->id);
        }
        $this->setEnvironmentValue('APP_NAME', $request->appName);
        $this->setEnvironmentValue('DB_DATABASE', $request->databaseName);
        $this->setEnvironmentValue('DB_USERNAME', $request->datbaseUser);
        $this->setEnvironmentValue('DB_PASSWORD', $request->databasePassword);

        return true;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setEnvironmentValue($envKey, $envValue) {
        $envFile = app()->environmentFilePath();
        $str = @file_get_contents($envFile);

        $oldValue = strtok($str, "{$envKey}=");

        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}\n", $str);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

}
