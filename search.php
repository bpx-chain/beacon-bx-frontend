<?php

include_once __DIR__.'/config.inc.php';
include_once __DIR__.'/inc/main.inc.php';

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$page = 1;
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $intPage = intval($_GET['page']);
    if($intPage > 1)
        $page = $intPage;
}

$blocks = [];

if(!empty($_GET['q'])) {
    $q = strtolower($_GET['q']);
    $task = null;
    $sql = null;
    
    // Height
    if(preg_match('/^[0-9]+$/', $q)) {
        $task = [
            ':height' => intval($q)
        ];
        
        $sql = 'SELECT *
                FROM blocks
                WHERE height = :height';
    }
    
    else {
        if($q[0] != '0' && $q[1] != 'x')
            $q = '0x'.$q;
        
        // Hash
        if(preg_match('/^0x[0-9a-f]{64}$/', $q)) {
            $task = [
                ':hash' => $q,
                ':execution_block_hash' => $q
            ];
            
            $sql = 'SELECT *
                    FROM blocks
                    WHERE hash = :hash
                    OR execution_block_hash = :hash';
        }
        
        // Address
        else if(preg_match('/^0x[0-9a-f]{40}$/', $q)) {
            $task = [
                ':fee_recipient' => $q,
                ':wd_addresses' => '%'.$q.'%'
            ];
            
            $sql = 'SELECT *
                    FROM blocks
                    WHERE fee_recipient = :fee_recipient
                    OR wd_addresses LIKE :wd_addresses';
        }
    }
    
    if($task) {
        $task[':offset'] = $page * 50;
        $sql .= ' ORDER BY height DESC
                  LIMIT 50 OFFSET :offset';
        $q = $pdo -> prepare($sql);
        $q -> execute($task);
        $blocks = $q -> fetchAll();
    }
}

getHeader('Search | BPX Beacon Chain explorer');
getBlocks('Search Results', $blocks, $page, '&q='.$_GET['q']);
getFooter();

unset($pdo);
?>