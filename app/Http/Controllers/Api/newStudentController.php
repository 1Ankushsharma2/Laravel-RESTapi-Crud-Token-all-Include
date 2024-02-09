<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\new_student; // Adjust the case to match the actual model's namespace

class newStudentController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['min:6', 'confirmed'],
        ]);

        // Use the correct case for the model class
        $Student = new_student::create($validateData);
        $token = $Student->createToken("auth_token")->accessToken;

        return response()->json([
            'token'=>$token,
            'message'=> 'user created successfully',
            'Student'=>$Student,
            'status'=>1,
        ],200);

      
    }

    public function login(Request $request , $id)
    {

        // dd($request);
        $validateData = $request->validate([
            'email'=>['required'],
            'password'=>['required']
        ]);

        $Student = new_Student::where(['email'=>$validateData['email'],'password'=>$validateData['password']] )->first();
        // echo"<pre>";
        // print_r($Student);
        $token = $Student->createToken("auth_token")->accessToken;
        return response()->json([
            'token'=>$token,
            'message'=>'login successfully',
            'Student'=>$Student,
        ]);

    }

    public function fetchdata($id)
    {
        $Student=new_student::find($id);
        if(is_null($Student)){
            return response()->json([
                'student'=>'null',
                'message'=>'student was not find',
                'status'=>0
            ],400);
        }else{
            return response()->json([
                'student'=>$Student,
                'message'=>'student data find successfully',
                'status'=>1,
            ],200);
        }
    }
}
