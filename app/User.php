<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function otpKey(){
        return "OTP_$this->id";
    }

    public function getOTP(){
       return Cache::get($this->otpKey());
    }
    public function setOTP(){
        $otp = rand(100000,999999);
        Cache::put($this->otpKey(),$otp,now()->addMinutes(1));
        return $otp;
    }

    public function sendOTP(){
        $response = Http::withHeaders([
            "Authorization"=>"Bearer yDfh6jEk6kDzb2U6Z0bVE6AsobPi5YomOkFSXFUhcXwJfugVEUxEqpU0kI0MSWKy",
            "Content-type" => "application/json",
            "Accept" => "application/json"
        ])->post('https://smspoh.com/api/v2/send', [
            "to"=> "$this->phone",
            "message"=>"OTP is ".$this->setOTP(),
            "sender"=>"microstack",
            "test"=> "true"
        ]);
    //     dd([
            
            // "to"=> "$this->phone",
            // "message"=>"OTP is ".$this->setOTP(),
            // "sender"=>"microstack",
            // "test"=> "true"
            
    // ]);

        dd($response);
    }
}
