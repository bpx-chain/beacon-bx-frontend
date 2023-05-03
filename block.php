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

$body = json_decode($block['body']);

getHeader('Block '.$block['height'].' | BPX Beacon Chain explorer');
?>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0">
                <strong>Block <?php echo $block['height']; ?></strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="row pb-2">
                <div class="col">
                    Block Height
                </div>
                <div class="col">
                    <?php echo $block['height']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Beacon Block Hash
                </div>
                <div class="col">
                    <?php echo $block['hash']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Execution Block Hash
                </div>
                <div class="col">
                    <?php echo $body -> foliage -> foliage_block_data -> execution_block_hash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Timestamp
                </div>
                <div class="col">
                    <?php echo date("Y-m-d H:i:s", $body -> foliage -> foliage_block_data -> timestamp); ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Weight
                </div>
                <div class="col">
                    <?php echo $body -> weight; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Signage Point Index
                </div>
                <div class="col">
                    <?php echo $body -> signage_point_index; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col">
                    Total Iterations
                </div>
                <div class="col">
                    <?php echo $body -> total_iters; ?>
                </div>
            </div>
        </div>
        <pre id="json-renderer"></pre>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0">
                <strong>Proof of Space</strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="row pb-2">
                <div class="col">
                    Challenge
                </div>
                <div class="col">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> challenge; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Plot Public Key
                </div>
                <div class="col">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> plot_public_key; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Plot Pool PH
                </div>
                <div class="col">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_contract_puzzle_hash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col">
                    Plot Pool PK
                </div>
                <div class="col">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_public_key; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col">
                    Plot size
                </div>
                <div class="col">
                    K-<?php echo $body -> reward_chain_block -> proof_of_space -> size; ?>
                </div>
            </div>
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