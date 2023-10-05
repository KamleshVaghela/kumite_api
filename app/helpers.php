<?php
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
?>