<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $hobbies = Hobby::select()->where('user_id',auth()->id())->orderBy('updated_at', 'DESC')->get();
        return view('home')->with([
            'hobbies'=>$hobbies
        ]);
    }
}
