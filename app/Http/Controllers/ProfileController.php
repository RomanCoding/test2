<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('notAdmin');
    }
    
    public function index()
    {
        $workers = User::workers()->where('id', '!=', auth()->id())->get();
        return view('profile.index', [
            'workers' => $workers
        ]);
    }
}
