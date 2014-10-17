<?php

require 'inc/functions.php';

$build_pages = range(1, $config['max_pages']);

foreach(array('a', 'lewd') as $boardName) {
    openBoard($boardName);
    buildIndex();
}

?>
