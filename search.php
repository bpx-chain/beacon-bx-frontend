<?php

include_once __DIR__.'/config.inc.php';
include_once __DIR__.'/inc/main.inc.php';

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$blocks = [];

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET['page']))
    $intPage = intval($_GET['page']);
    if($intPage > 0)
        $page = $_GET['page'] - 1;

if(!empty($_GET['q'])) {
    $task = [
        ':offset' => $page * 50,
        ':q' => $_GET['q'],
        ':q2' => '%' + strtolower($_GET['q']) + '%'
    ];

    $sql = 'SELECT *
            FROM blocks
            WHERE height = :q
            OR LOWER(hash) LIKE :q2
            ORDER BY height DESC
            LIMIT 50 OFFSET :offset';

    $q = $pdo -> prepare($sql);
    $q -> execute($task);
    $blocks = $q -> fetchAll();
}

if(count($blocks) == 1) {
    header('Location: /blocks/' + $blocks[0]['hash']);
}

else {
    getHeader('Search | BPX Beacon Chain explorer');
    getBlocks('Search Results', $blocks);
    getFooter();
}

unset($pdo);
?>