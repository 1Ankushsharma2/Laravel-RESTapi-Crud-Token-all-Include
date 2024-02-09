<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\new_student;

class userController extends Controller
{
    public function register(Request $request)
    {
         $newStudent = newStudent::create($validateData);
        echo "<pre>";
        print_r($newStudent);


        // $validateData = $request->validate([
        //     'name'=>'required',
        //     'email' => ['required','email'],
        //     'password'=>['min:6','confirmed'],
        // ]);

        // $newStudent = newStudent::create($validateData);
        // echo "<pre>";
        // print_r($newStudent);
    }
}
