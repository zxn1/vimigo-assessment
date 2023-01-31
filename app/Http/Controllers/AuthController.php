<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    //register
    public function register(Request $request){ 

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),202);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $resArr = [];
        $resArr['token'] = $user->createToken('vimigoApp')->accessToken;
        $resArr['name'] = $user->name;
        
        return response()->json($resArr,200);  
    }

    public function login(Request $request){ 
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $resArr = [];
            $resArr['token'] = $user->createToken('vimigoApp')->accessToken;
            $resArr['name'] = $user->name;
            
            return response()->json($resArr,200);

        }else{
            return response()->json(['error'=>'Failed to login! Wrong authentication details!'],203);
        }
    }
}
