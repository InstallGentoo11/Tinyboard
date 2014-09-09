<?php

$banners = array(
	0 => IMAGETYPE_PNG,
    1 => IMAGETYPE_PNG,
    2 => IMAGETYPE_PNG,
    3 => IMAGETYPE_PNG,
    4 => IMAGETYPE_PNG,
    5 => IMAGETYPE_PNG,
    6 => IMAGETYPE_GIF,
    7 => IMAGETYPE_PNG,
    8 => IMAGETYPE_GIF,
    9 => IMAGETYPE_PNG,
    10 => IMAGETYPE_JPEG
);

$banner = array_rand($banners);
$type = $banners[$banner];

header('Content-Type: '.image_type_to_mime_type($type));
readfile('banners/'.$banner.image_type_to_extension($type, true));

?>
