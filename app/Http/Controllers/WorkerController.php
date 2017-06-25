<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * WorkerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (request()->expectsJson()) {
            return User::where('admin', 0)->get();
        }
    }

    public function store(Request $request)
    {
        $this->middleware('admin');
        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());
        if ($request->expectsJson()) {
            return $user;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::findOrFail($id)->update(request()->all());
        if ($request->expectsJson()) {
            return ['success' => true];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->expectsJson())
            return (['success' => User::findOrFail($id)->delete()]);
    }
}
