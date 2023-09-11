<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Config;

class BeforeRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->getContent()!=""){
            $data = $this->decrypt_val($request->getContent());
            $request->merge(json_decode($data, true));
        }
        
        $response = $next($request);
        
        if($response->content()!="") {
            $response_data = $this->encrypt_val($response->content());
            $response->setContent($response_data);
        }       
        return $response;
    }
    function encrypt_val($value){
        $key = Config::get('constants.AES_KEY'); //combination of 16 character
        $iv = Config::get('constants.AES_IV'); //combination of 16 character
        $method = 'aes-256-cbc';
        $encryptedString = openssl_encrypt($value, $method, $key, 0, $iv);
        return $encryptedString;
    }
    
    function decrypt_val($value){
        $key = Config::get('constants.AES_KEY'); //combination of 16 character
        $iv = Config::get('constants.AES_IV'); //combination of 16 character
        $method = 'aes-256-cbc';
        $decryptedString = openssl_decrypt($value, $method, $key, 0, $iv);
        return $decryptedString;
    }
}
