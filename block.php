<?php

include_once __DIR__.'/config.inc.php';
include_once __DIR__.'/inc/main.inc.php';

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if(!isset($_GET['hash']) || strlen($_GET['hash']) != 66) {
    header('Location: /404');
    die();
}

$task = [
    ':hash' => $_GET['hash']
];

$sql = 'SELECT *
        FROM blocks
        WHERE hash = :hash';

$q = $pdo -> prepare($sql);
$q -> execute($task);
$block = $q -> fetch();

if(!$block) {
    header('Location: /404');
    die();
}

getHeader('Block '.$block['height'].' | BPX Beacon Chain explorer');
?>
<pre id="json-renderer"></pre>
<script type="text/javascript">
$(document).ready(function() {
    $('#json-renderer').jsonViewer(<?php echo $block['body']; ?>);
});
</script>
<?php
getFooter();
unset($pdo);
?>