<?php

//This script converts all files in an folder to an jpeg, png or gif file.
//This script is not safe (because of shell executions), and it is not recommended to use it on servers
//This script makes use of the imageMagic-plugin for the terminal, witch can be downloaded here: https://www.imagemagick.org/script/download.php#windows

set_time_limit(0);

//Convertion Settings
$target_dir = "./assets/img/toConvert/"; //Where to get images?
$new_filename = "./assets/img/eerste_zending"; //Where to place images (folder needs to exitst)
$newName_Prefix = "eerste_zending"; //Prefix of the files and after that comes the file number
$convertTo = "jpeg"; // Options: ['jpeg', 'png', 'gif']

//Server safe states. Exits the application if no good ;-)
$count = 0;
$limit = 150;



//Start convertion script
echo "Calculating files to convert..." . "<br>";
$fileCount = 0;
foreach (scandir($target_dir) as $file) {
    if ($file != "." && $file != "..") {
        $fileCount++;
    }
}
echo $fileCount . " Files found. Converting all to $convertTo files...." . "<br>";
if($fileCount > $limit){
    $diff = $fileCount - $limit;
    echo "<style>body{background-color: red}</style>";
    echo "<h1>You uploaded $fileCount files. The maximum amount is $limit. Remove $diff files, or upload your files in parts</h1>";
    exit();
}
foreach (scandir($target_dir) as $file) {
    if ($file != "." && $file != "..") {
        $count++;
        $filePath = $target_dir . "/" . $file;
        $info = getimagesize($filePath);
        $newName = $newName_Prefix . "_" . $count . "." . $convertTo;
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($filePath);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($filePath);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($filePath);
        } else {
            echo "Image converted to " . $newName . "<br>";
            $command = "convert " . $filePath . "[0] " . $new_filename . "/" . $newName;
            exec($command);
            continue;
        }
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        if($convertTo == "jpeg") {
            imagejpeg($image, $new_filename . "/" . $newName, 50);
        }elseif($convertTo == "png"){
            imagepng($image, $new_filename . "/" . $newName, 5);
        } elseif($convertTo == "gif"){
            imagegif($image, $new_filename . "/" . $newName, 50);
        }
        echo "Image converted to " . $newName . "<br>";
    }
}
echo "<style>body{background-color: green;}</style>";
echo "Succesfull convertion, " . $count . " Files converted!";
unset($target_dir);

//End of script. Wrote on 11-10-2021 (PHP version 7.3)
