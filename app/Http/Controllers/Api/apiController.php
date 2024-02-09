<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class apiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($greensign)    
    {

        //now i want all active and inactive apis come
        //greensign ==1 (active)
        //greensign ==0 (inactive)

        $query=api::select('name','email');
        if($greensign==1){
            $query->where('status',1);
        }elseif($greensign==0){
           //empty
        }
        else{
            return response()->json([
                'messege'=>'invalid parameter you sent , you cansent only 1 or 0',
                
            ],400);
        }
        $apis = $query->get();
        // elseif($greensign == 0){
        //     $query->where()
        // }



        // $apis = api::select('name','email')->where('status',1)->get();
        // $apis =api::select('name','mobile')->where('status',0)->get();
        if (count($apis) >1){
            //message show
            $response = [
                'message' => count($apis).'apis found',
                'status' => 1,
                'data' => $apis
            ];
            return response()->json($response, 200);

        }else{
            //data doesn,t exit

            $response =[
                'message' => count($apis) .'apis not found',
                'status' => 1,
            ];
            return response()->json($response, 500);

        };
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'mobile'=>['required','unique:apis,mobile'],
            'address'=>['required','unique:apis,address'],
            'pincode'=>['required'],
            'email' => ['required', 'email','unique:apis,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }else{
            $data=[
                'name'=>$request->name,
                'email'=>$request->email,
                'address'=>$request->address,
                'pincode'=>$request->pincode,
                'mobile'=>$request->mobile,
                'password'=>Hash::make($request->password)
            ];
            DB::beginTransaction();
            try{
                $api =api::create($data);
                DB::commit();
            }catch(\Exception $e){
               DB::rollBack();
               p($e->getMessage());
               $api =null;
            }
            if($api !=null)
            return response()->json([
              'message'=>'user registered successfully'
        
            ],200); 
            else{
                return response()->json([
                    'messege'=>'Internal server error'
                ] ,500);

            }
        }

        p($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $api = api::find($id);

        if ($api){
            return response()->json([
                'messege'=>'i got user',
                'status'=>1,
                'data'=>$api
            ],200);
        }else{
            return response()->json([
                'messege'=>'invalid user',
                // 'status'=>0,

            ]);
            return response()->json($api,200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)

    {
        $api = api::find($id);

        if (is_null($api)) {
            return response()->json([
                'message' => 'User not found',
                'status' => 0,
            ], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'mobile' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'unique:apis,email'],
            'pincode' => ['required'],
        ]);


        
        if ($validator->fails()) {
            
            return response()->json($validator->errors(), 400);
        } else {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'email' => $request->email,
                'pincode' => $request->pincode,
            ];
        
            DB::beginTransaction();
            try {
                $api->update($data);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                p($e->getMessage());
                return response()->json([
                    'message' => 'Internal server error',
                    'status' => 500,
                ]);
            }
        
            return response()->json([
                'message' => 'Data successfully updated',
                'status' => 1,
                'data' => $api
            ], 200);
        }

        
        

    }

    

    public function NewPassword(Request $request ,$id)
        {
            $api = api::find($id);
            if(is_null($api)){
                return response()->json([
                    'message' => 'Data was not found',
                    'status' => 0,
                ], 400);
            } else {
                // Check if the old password matches
                if(password_verify($request['old_password'], $api->password)){
            
                    if($request['new_password'] == $request['confirm_password']){
            
                        DB::beginTransaction();
                        try {
                            // Hash the new password
                            $newPasswordHash = bcrypt($request['new_password']);
                            
                            // Update the password
                            $api->password = $newPasswordHash;
                            $api->save();
                            
                            // Commit transaction
                            DB::commit();
                            
                            return response()->json([
                                'message' => 'Password updated successfully',
                                'status' => 1,
                            ], 200);
                            
                        } catch(\Exception $e) {
                            // Rollback transaction
                            DB::rollback();
                            
                            return response()->json([
                                'message' => 'Internal server error',
                                'status' => 0,
                                'error_msg' => $e->getMessage(),
                            ], 500);
                        }
            
                    } else {
                        return response()->json([
                            'message' => 'New password and confirm password do not match',
                            'status' => 0,
                        ], 400);
                    }
            
                } else {
                    return response()->json([
                        'message' => 'Old password does not match',
                        'status' => 0,
                    ], 400);
                }
            }
            

        }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $api=api::find($id);
        if(is_null($api)){
            return response()->json([
                'messege'=>'user not found',
                'status'=>0,
            ],400);            
        }else{
            $api->delete();  
            return response()->json([
                'messege'=>'user deleted successfullly',
                'status'=>1,
            ],200);
        }
        
    }
}
