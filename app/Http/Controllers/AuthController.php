<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

  public function register(Request $request)
  {
    $validator = $request->validate([
      'name'      => 'required|min:1',
      'email'     => 'required',
      'phone'      => 'required|numeric',
      'password'  => 'required|min:6'
    ]);
    
    $validator['password'] = bcrypt($validator['password'] );

    \App\User::create($validator);
   return $this->login($request);
  }

  public function login(Request $request)
  {
   
    $request->validate([

      'phone'      => 'required|numeric',
      'password'  => 'required|min:6'
    ]);
    $credentials = $request->only('phone', 'password');
    if (Auth::attempt($credentials)) {
      
      $user = auth()->user();
      $user->sendOTP();

     
      // $user->tokens()->delete();
      // $token = $user->createToken("token");
      // return response()->json(["access_token" => $token->plainTextToken]);
    }
    return "Error";
  }
}
