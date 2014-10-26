<?php

require 'inc/functions.php';

$pathBits = explode('/', trim($_SERVER['REQUEST_URI'], '/?'));

$boardName = current($pathBits);

if(empty($boardName)) { // we're at the root (/)
    //error($config['error']['404']);
    header('Location: /a/');
    exit;
}

$pageNo = 1;
$threadId = null;
$jsonOutput = false;

if(count($pathBits) > 1) {
    if($pathBits[1] === 'catalog.json') {
        die('not implemented');
    }
    elseif($pathBits[1] === 'res') {
        if(count($pathBits) === 3) {
            $threadId = $pathBits[2];

            // check for api
            if(pathinfo($threadId, PATHINFO_EXTENSION) === 'json') {
                $jsonOutput = true;
                $threadId = trim($threadId, '.json');
            }

            if(!is_numeric($threadId)) {
                $threadId = null;
            }
        }
        else {
            error($config['error']['404']);
        }
    }
    elseif(count($pathBits) === 2) {
        $pageNo = preg_replace('/\.json$/', '', $pathBits[1], 1, $replaceCount);

        if(is_numeric($pageNo)) {
            $pageNo = (int)$pageNo;

            // check for api
            if($replaceCount === 1) {
                $jsonOutput = true;
                $pageNo += 1; // pages are 0-indexed
            }
        }
        else {
            error($config['error']['404']);
        }
    }
    else {
        error($config['error']['404']);
    }
}

$mod = false;

if (!openBoard($boardName)) {
    error($config['error']['noboard']);
}

if(!$threadId) { // index page
    if (!$page = index($pageNo, $mod)) {
        error($config['error']['404']);
    }

    $page['pages'] = getPages($mod);
    $page['pages'][$pageNo - 1]['selected'] = true;
    $page['btn'] = getPageButtons($page['pages'], $mod);
    $page['mod'] = $mod;
    $page['config'] = $config;

    if(!$jsonOutput) {
        echo Element('index.html', $page);
    }
    else {
        $api = new Api();
        $catalog = array();

        $threads = $page['threads'];
        $json = json_encode($api->translatePage($threads));

        outputJson($json);
    }
}
else { // thread page
    renderThread($threadId, $mod, $jsonOutput);
}



?>
