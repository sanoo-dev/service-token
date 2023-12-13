<?php

if (!function_exists('hidePhoneNumber')) {
    function hidePhoneNumber($number): ?string
    {
        if (empty($number)) {
            return null;
        }

        return substr($number, 0, 4) . '******' . substr($number, -2);
    }
}

if (!function_exists('hideEmailAddress')) {
    function hideEmailAddress($email): ?string
    {
        if (empty($email)) {
            return null;
        }
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = min(floor(strlen($name)/2), 4);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}

if (!function_exists('setKeysByFieldValueOfSubArray')) {
    function setKeysByFieldValueOfSubArray(?array $arr, string $field): ?array
    {
        if (!empty($arr)) {
            return array_combine(array_column($arr, $field), $arr);
        } else {
            return null;
        }
    }
}

if(!function_exists('arrangeIdArray')) {
    function arrangeIdArray(?array $a, ?array $b){
        $countA = count($a);
        for($i=0; $i<count($b); $i++){
            $countA = $countA + 1;
            $b[$i]['id'] = $countA;
        }
        return array_merge($a, $b);
    }
}

if(!function_exists('encryptOrDecrypt')) {
    function encryptOrDecrypt($simple_string, $action = 'encrypt'){

        $ciphering = "AES-256-CBC";
        $options = 0;

        $encryption_key = env('ENCRYPTION_KEY', 'fzJ2LKxPeguL/eOLz0K7jA==');

        $secret_iv = env('SECRET_IV', '5fgf5HJ5g27');
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if($action == 'encrypt'){
            $encryptOrDecrypt = openssl_encrypt($simple_string, $ciphering,
                $encryption_key, $options, $iv);
        }else{
            $encryptOrDecrypt = openssl_decrypt ($simple_string, $ciphering,
                $encryption_key, $options, $iv);
        }
        return $encryptOrDecrypt;
    }
}

if(!function_exists('myPagination')) {
    function myPagination($c, $m){
        $current = $c;
        $last = $m;
        $delta = 2;
        $left = $current - $delta;
        $right = $current + $delta + 1;
        $range = [];
        $rangeWithDots = [];
        $l = null;

        for($i =1; $i<= $last; $i++){
            if($i == 1 || $i == $last || $i >= $left && $i < $right){
                $range[] = $i;
            }
        }

        for($i =0; $i<count($range); $i ++){
            if(!empty($l)){
                if ($range[$i] - $l == 2) {
                    $rangeWithDots[]  = $l + 1;
                }
                elseif($range[$i] - $l != 1){
                    $rangeWithDots[] = "...";
                }
            }
            $rangeWithDots[] = $range[$i];
            $l = $range[$i];
        }
        return $rangeWithDots;
    }
}
