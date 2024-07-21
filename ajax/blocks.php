<?php

include_once __DIR__.'/../inc/main.inc.php';
include_once __DIR__.'/../inc/blocks.inc.php';

$pdo = pdoConnect();
getRecentBlocks($pdo, @$_GET['page'], @$_GET['cur']);
unset($pdo);
?>