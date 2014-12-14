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
    $query = prepare(sprintf("SELECT * FROM ``posts_%s`` WHERE (`thread` IS NULL AND `id` = :id) OR `thread` = :id ORDER BY `thread`,`id`", $board['uri']));
    $query->bindValue(':id', $threadId, PDO::PARAM_INT);
    $query->execute() or error(db_error($query));

    while ($post = $query->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($thread)) {
            $thread = new Thread($post, $mod ? '?/' : $config['root'], $mod);
        } else {
            $thread->add(new Post($post, $mod ? '?/' : $config['root'], $mod));
        }
    }

    // Check if any posts were found
    if (!isset($thread))
        error($config['error']['nonexistant']);

    $page = array(
        'board' => $board,
        'thread' => $thread,
        'body' => $thread->build(),
        'config' => $config,
        'id' => $threadId,
        'mod' => $mod,
        'antibot' => false,
        'boardlist' => createBoardlist($mod),
        'return' => ($mod ? '?' . $board['url'] . $config['file_index'] : $config['root'] . $board['dir'] . $config['file_index'])
    );

    if(!$jsonOutput) {
        echo Element('thread.html', $page);
    }
    else {
        $api = new Api();
        $json = json_encode($api->translateThread($thread));
        outputJson($json);
    }
}

function outputJson($json) {
    ob_end_clean();

    if(function_exists('ob_gzhandler')) {
        ob_start('ob_gzhandler');
    }
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo $json;

    ob_end_flush();
}

?>
