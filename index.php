<?php

include_once __DIR__.'/config.inc.php';
include_once __DIR__.'/inc/main.inc.php';

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $intPage = intval($_GET['page']);
    if($intPage > 0)
        $page = $_GET['page'] - 1;
}

$task = [
    ':offset' => $page * 50
];

$sql = 'SELECT *
        FROM blocks
        ORDER BY height DESC
        LIMIT 50 OFFSET :offset';

$q = $pdo -> prepare($sql);
$q -> execute($task);
$blocks = $q -> fetchAll();

getHeader('BPX Beacon Chain explorer');
?>
<section>
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-network-wired text-info fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>testnet</h3>
                            <p class="mb-0">Network name</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-arrows-up-to-line text-warning fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>156</h3>
                            <p class="mb-0">Peak Height</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-scale-balanced text-success fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>64.89 %</h3>
                            <p class="mb-0">Difficulty</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-hard-drive text-danger fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>423</h3>
                            <p class="mb-0">Netspace</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>         
<?php
getBlocks('Recent Blocks', $blocks, $page);

getFooter();
unset($pdo);
?>