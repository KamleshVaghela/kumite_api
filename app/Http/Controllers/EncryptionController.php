<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class EncryptionController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function base64url_encode($data) {
        return rtrim(strtr($data, '+/', '-_'), '=');
    }
    
    function base64_url_decode($input) {
       return strtr($input, '._-', '+/+');
    }

    public function encrypt_data(Request $request)
    {
        // $data = openssl_encrypt($request->getContent(), 'AES-256-CBC', Config::get('constants.AES_KEY'), 0, Config::get('constants.AES_IV'));
        $data = $this->encrypt_val($request->getContent());
        return response($data, 200);
    }

    public function decrypt_data(Request $request)
    {
        $data = $this->decrypt_val($request->getContent());
        return response($data, 200);
    }

    function encrypt_val($value){
        $key = Config::get('constants.AES_KEY');
        $iv = Config::get('constants.AES_IV');
        $method = 'aes-256-cbc';
        $encryptedString = openssl_encrypt($value, $method, $key, 0, $iv);
        return $encryptedString;
    }
    
    function decrypt_val($value){
        $key = Config::get('constants.AES_KEY');
        $iv = Config::get('constants.AES_IV');
        $method = 'aes-256-cbc';
        $decryptedString = openssl_decrypt($value, $method, $key, 0, $iv);
        return $decryptedString;
    }
}
