<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 

//        $field = 'username';

//        if (is_numeric($request->input('login')) {
//          $field = 'phone';
//        } elseif (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
//        $field = 'email';
// }

// $request->merge([$field => $request->input('login')]);

// if ($this->auth->attempt($request->only($field, 'password'))) {
//     return redirect('/');
// }

// return redirect('/login')->withErrors([
//     'error' => 'These credentials do not match our records.',
// ]);

        if(Auth::attempt(['email' => request('user_name'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            if($user){
            	if($user['verify']==1)
            	{
            	//return $user;
            	return response()->json(array('success'=>'successfully login','data'=>$user));
            }
            else 
            	 {
            	 	return response()->json(array('success'=>'You need to verify your account'));
            	 }
            }
            
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return $success; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else if(Auth::attempt(['phone' => request('user_name'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            if($user){
            	if($user['verify']==1)
            	{
            	//return $user;
            	//$success['token'] =  $user->createToken('MyApp')-> accessToken;
            	//return $success['token'];
            	return response()->json(array('success'=>'successfully login','data'=>$user));
            	 }
            	 else 
            	 {
            	 	return response()->json(array('success'=>'You need to verify your account'));
            	 }
            }
        }
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            //'name' => 'required', 
            'email' => 'required|email',
             'phone' => 'required',
            'password' => 'required', 
            'otp' => ' '
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
          
        $input['password'] = bcrypt($input['password']);
        $num=rand(1000,9999);
        $input['otp'] = $num;
        $user = User::create($input);
        // return $user;
        unset($user->password);
        unset($user->otp);
        return response()->json(array('success'=>'successfully registered', 'status'=>200,'data'=>$input)); 
         //return $user;
        // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        // $success['name'] =  $user->name;
          //return response()->json(['success'=>$success], $this-> successStatus); 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
    
}
