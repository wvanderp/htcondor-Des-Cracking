<?php

$jobSize = "16";

$base = "Universe = vanilla
Executable = run.bat

#getenv = true

output = des.output.$(Process)
transfer_input_files = cipher.json, php.zip, hallo.php, unzip.exe
#transfer_output_files = out.txt

should_transfer_files = YES
when_to_transfer_output = ON_EXIT

Requirements = (Arch == \"INTEL\" && OpSys == \"WINDOWS\") || (Arch == \"X86_64\" && OpSys == \"WINDOWS\") \n\n\n\n\n";
$keyArray = array();
$out = "";
for ($i = 0; $i < 16; $i++) {
    $keyArray[] = 0;
}

$updateTime = 0;
$i = 0;
$j = 0;
while (true) {
    if($i == 20){
        write($out, $j);
        $i = 0;
        $j++;
        $out = "";
    }

    $out .= "Arguments = " . implode(keyMaker($keyArray)) . " " . $jobSize . "\n";
    $out .= "Queue\n\n";
    echo implode(keyMaker($keyArray)) . "\n";
    if ($keyArray == array(15, 15, 15, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)) {
        $out .= "Arguments = " . implode(keyMaker($keyArray)) . " " . $jobSize . "\n";
        $out .= "Queue\n\n";
        $j++;
        write($out, $j);
        die("done");
    }
    $then = microtime();
    $keyArray = updateKey($keyArray);
    $now = microtime();

    $updateTime = (($now - $then) + $updateTime) / 2;
    //echo $updateTime . "\n";

    $i++;
}

function write($out, $j){
    global $base;
    $out = $base.$out;
    file_put_contents("./sub/des.".$j.".submit", $out);
    //die("done");
}

function updateKey($keyArray)
{
    $keyArray[14]++;
    for ($i = 15; $i != 0; $i--) {

        if ($keyArray[$i] == 16) {
            $keyArray[$i - 1]++;
            $keyArray[$i] = 0;
        }
        //echo $keyArray[$i];
    }
    return $keyArray;
}

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