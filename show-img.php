<?php
// display random image in browser.

$num = $_GET["fileNum"]; //for passing var to script

// $num = rand(1,32);
$fileDir = "../../images/rand-img/";
$file =  ( $num. ".jpg" );



    if (file_exists($fileDir . $file))
    {
        // Note: You should probably do some more checks 
        // on the filetype, size, etc.
        $image = file_get_contents($fileDir . $file);

        // Note: You should probably implement some kind 
        // of check on filetype
        header('Content-type: image/jpeg');

        echo $image;
    }
// }

?>
