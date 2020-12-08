<?php

namespace App\Http\Controllers;

use App\UserSubscribe;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class SubscribersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        return view('subscribers.create');
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|min:2',
            'lastname' => 'required|min:2',
            'email' => 'required|email',
        ]);


        $subscribe = new UserSubscribe();
        $subscribe->name = request('name');
        $subscribe->lastname = request('lastname');
        $subscribe->email = request('email');
        $subscribe->is_subscribed = true;
        $subscribe->save();

        return redirect()->back()->with('message', 'Сохранено');
    }
}
