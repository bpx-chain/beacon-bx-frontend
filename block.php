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
                <div class="col-2">
                    Block Height
                </div>
                <div class="col-10">
                    <?php echo $block['height']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    Beacon Block Hash
                </div>
                <div class="col-10">
                    <?php echo $block['hash']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    Execution Block Hash
                </div>
                <div class="col-10">
                    <?php echo $body -> foliage -> foliage_block_data -> execution_block_hash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    Timestamp
                </div>
                <div class="col-10">
                    <?php echo date("Y-m-d H:i:s", $body -> foliage -> foliage_block_data -> timestamp); ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    Weight
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> weight; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-2">
                    Raw Block
                </div>
                <div class="col-10">
                    <pre id="json-raw-block"></pre>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">
                <strong>Proof of Space</strong>
            </h6>
        </div>
        <div class="card-body">
            <div class="row pb-2">
                <div class="col-2">
                    <strong>Challenge</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> challenge; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Public Key</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> plot_public_key; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Pool PH</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_contract_puzzle_hash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Pool PK</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_public_key; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-2">
                    <strong>Plot size</strong>
                </div>
                <div class="col-10">
                    K-<?php echo $body -> reward_chain_block -> proof_of_space -> size; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">
                <strong>Execution Payload</strong>
            </h6>
        </div>
        <div class="card-body">
            <div class="row pb-2">
                <div class="col-2">
                    <strong>Challenge</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> challenge; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Public Key</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> plot_public_key; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Pool PH</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_contract_puzzle_hash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot Pool PK</strong>
                </div>
                <div class="col-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> pool_public_key; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-2">
                    <strong>Plot size</strong>
                </div>
                <div class="col-10">
                    K-<?php echo $body -> reward_chain_block -> proof_of_space -> size; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-2">
                    <strong>Raw Payload</strong>
                </div>
                <div class="col-10">
                    <pre id="json-raw-payload"></pre>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
$(document).ready(function() {
    var jsonBlock = <?php echo $block['body']; ?>;
    var jsonPayload = <?php echo json_encode($body -> execution_payload); ?>;
    var options = {
        collapsed: true
    };
    $('#json-raw-block').jsonViewer(jsonBlock, options);
    $('#json-raw-payload').jsonViewer(jsonPayload, options);
});
</script>
<?php
getFooter();
unset($pdo);
?>