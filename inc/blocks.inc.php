<?php

include_once __DIR__.'/main.inc.php';

function getRecentBlocks($pdo, $pageUnsafe, $cur = null) {
    $page = 1;
    if(isset($pageUnsafe) && is_numeric($pageUnsafe)) {
        $intPage = intval($pageUnsafe);
        if($intPage > 1)
            $page = $intPage;
    }
    
    $task = [
        ':offset' => ($page - 1) * 50
    ];
    
    $sql = 'SELECT height, hash, timestamp
            FROM blocks
            ORDER BY height DESC
            LIMIT 50 OFFSET :offset';
    
    $q = $pdo -> prepare($sql);
    $q -> execute($task);
    $blocks = $q -> fetchAll();
    
    getBlocks('Recent Blocks', $blocks, $page, '', $cur);
}
?>