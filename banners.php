<?php

$banners = array(
	IMAGETYPE_PNG,
    IMAGETYPE_PNG
);

$banner = array_rand($banners);
$type = $banners[$banner];

header('Content-Type: '.image_type_to_mime_type($type));
readfile('banners/'.$banner.image_type_to_extension($type, true));

?>
