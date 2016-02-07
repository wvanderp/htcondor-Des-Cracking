<?php
define('MODE', MCRYPT_MODE_CBC);

$keyArray = array();
for($i = 0;$i < 16;$i++) {
    $keyArray[] = 0;
}

$keyArray[15] = "A";
//echo implode(keyMaker($keyArray))."<br>";
$key = pack('H*', implode($keyArray));
$key_size =  strlen($key);


$iv_size = mcrypt_get_iv_size(MCRYPT_DES, MODE);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

$input = "lol forsen";
$result = mcrypt_encrypt(MCRYPT_DES, $key, $input, MODE, $iv);


$output = array();
$output["Ciphertext"] = base64_encode($result);
$output["iv"] = base64_encode($iv);
$output["hash"] = md5($input);

//echo json_encode($output);
file_put_contents("cipher.json", json_encode($output));

function keyMaker($keyArray){
    $key = array();
    for($i = 0;$i < 16;$i++) {
        switch($keyArray[$i]) {
            case 10:
                $key[$i] = "A";
                break;
            case 11:
                $key[$i] = "B";
                break;
            case 12:
                $key[$i] = "C";
                break;
            case 13:
                $key[$i] = "D";
                break;
            case 14:
                $key[$i] = "E";
                break;
            case 15:
                $key[$i] = "F";
                break;
            default:
                $key[$i] = $keyArray[$i];
                break;
        }
    }
    return $key;
}