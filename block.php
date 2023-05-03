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
<section class="mb-4">
    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>Block <?php echo $block['height']; ?></strong>
            </h5>
        </div>
        <div class="card-body">
            <pre id="json-renderer"></pre>
        </div>
    </div>
</section>
<script type="text/javascript">
$(document).ready(function() {
    var json = <?php echo $block['body']; ?>;
    $('#json-renderer').jsonViewer(json, {
        collapsed: true
    });
});
</script>
<?php
getFooter();
unset($pdo);
?>