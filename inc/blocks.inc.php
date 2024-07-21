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
    
    if($page == 1) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            console.log('test');
            setInterval(function() {
                let cur = $('.block').length ? '?cur=' + $('.block').first().data('height') : '';
                $.get('/ajax/blocks' + cur, function(data) {
                    $('#blocks').replaceWith(data);
                });
            }, 5000);
        });
    </script>
    <?php
    }
}
?>