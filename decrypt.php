<?php
ini_set('max_execution_time', -1);
define('MODE', MCRYPT_MODE_CBC);
$cipherFile = file_get_contents("Cipher.json");
$cipherJson = json_decode($cipherFile, true);

$cipher = base64_decode($cipherJson["Ciphertext"]);
$iv = base64_decode($cipherJson["iv"]);
$hash = $cipherJson["hash"];
$needle = "lol ";

$initKey = $argv[1];
$iterations = $argv[2];
$guessArray = array();

if (strlen($initKey) != 16) {
    die("init key is niet de goede lengte");
}

$initKeyArray = str_split($initKey);


for ($i = 0; $i < 16; $i++) {
    $guessArray[] = reverseLookup($initKeyArray[$i]);
}

//echo implode($guessArray, ", ");

$result = "";
for ($i = 0; $i < $iterations; $i++) {
    $guess = pack('H*', implode(keyMaker($guessArray)));
    $plaintext = mcrypt_decrypt(MCRYPT_DES, $guess, $cipher, MODE, $iv);
    //echo implode(keyMaker($guessArray))."\n";
    if (strstr($plaintext, $needle)) {
        $result = array();
        $result["key"] = implode(keyMaker($guessArray));
        $result["plainText"] = $plaintext;
        $result["hashCheck"] = (md5(trim($plaintext)) == $hash);
        $result["hash"] = md5(trim($plaintext));
//        file_put_contents("result.json", str_replace('\u0000', "", json_encode($result)));
        die("found it" . str_replace('\u0000', "", json_encode($result)));
        break;
    }

    $guessArray = updateKey($guessArray);
}

//file_put_contents("result.json", "{\"result\": \"no\"}");
die("didnt find");

function keyMaker($keyArray)
{
    $key = array();
    for ($i = 0; $i < 16; $i++) {
        switch ($keyArray[$i]) {
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

function updateKey($keyArray)
{
    $keyArray[15]++;
    for ($i = 15; $i != 0; $i--) {

        if ($keyArray[$i] == 16) {
            $keyArray[$i - 1]++;
            $keyArray[$i] = 0;
        }
        //echo $keyArray[$i];
    }
    return $keyArray;
}

function reverseLookup($letter)
{
    switch ($letter) {
        case "A":
            return 10;
            break;
        case "B":
            return 11;
            break;
        case "C":
            return 12;
            break;
        case "D":
            return 13;
            break;
        case "E":
            return 14;
            break;
        case "F":
            return 15;
            break;
        default:
            return $letter;
            break;
    }
}