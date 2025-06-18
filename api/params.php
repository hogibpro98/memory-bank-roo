<?php

define("KEY_AES", "abdfeeb22d8050524daa44810c29b69f");

function encryptAesBase64(array $data = []) {
    $cipher = "aes-256-cbc";
    $data = json_encode($data);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, KEY_AES, OPENSSL_RAW_DATA, $iv);
    $encryptedBase64 = base64_encode($iv . $encrypted);
    return rawurlencode($encryptedBase64);
}

function decryptAesBase64(string $encryptedBase64) {
    $encryptedBase64 = rawurldecode($encryptedBase64);
    $cipher = "aes-256-cbc";
    $data = base64_decode($encryptedBase64);
    $ivLength = openssl_cipher_iv_length($cipher);

    if (strlen($data) < $ivLength) {
        throw new Exception('Invalid encrypted data (too short).');
    }

    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    $decrypted = openssl_decrypt($encrypted, $cipher, KEY_AES, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        throw new Exception('Decryption failed.');
    }

    return json_decode($decrypted, true);
}

$data = 
    [ 
	    'houjin_no' => '9271000',
	    'doc_id'=> 'vis200001816'
    ];

var_dump(encryptAesBase64($data));