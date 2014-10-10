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
    10 => IMAGETYPE_JPEG,
    11 => IMAGETYPE_PNG,
    12 => IMAGETYPE_GIF,
    13 => IMAGETYPE_GIF,
    14 => IMAGETYPE_PNG,
    15 => IMAGETYPE_PNG,
    16 => IMAGETYPE_PNG,
    17 => IMAGETYPE_PNG,
    18 => IMAGETYPE_PNG,
    19 => IMAGETYPE_PNG,
    20 => IMAGETYPE_PNG,
    21 => IMAGETYPE_PNG,
    22 => IMAGETYPE_PNG,
    23 => IMAGETYPE_PNG,
    24 => IMAGETYPE_PNG,
    24 => IMAGETYPE_PNG,
    25 => IMAGETYPE_GIF
);

$banner = array_rand($banners);
$type = $banners[$banner];

header('Content-Type: '.image_type_to_mime_type($type));
readfile('banners/'.$banner.image_type_to_extension($type, true));

?>
