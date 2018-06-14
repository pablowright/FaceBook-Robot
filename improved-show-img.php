<?php

$files = glob(realpath('../../images/rand-img') . '/*.*');
$file = array_rand($files);
$image = $files[$file];

header('Content-type: image/jpeg');
readfile($image);

?>
