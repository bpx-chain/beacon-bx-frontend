<?php

function getHeader($title) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title><?php echo $title; ?></title>
        
        <!-- Font Awesome -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            rel="stylesheet"
        />
        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
            rel="stylesheet"
        />
        <!-- MDB -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css"
            rel="stylesheet"
        />
        <!-- jQuery -->
        <script
            src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
            crossorigin="anonymous">
        </script>
        <!-- JSON Viewer -->
        <script src="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.css">
    </head>

    <body>
        <header>
            <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
                <div class="container-fluid flex-nowrap">
                    <a class="navbar-brand" href="/">
                        <img src="/img/logo.svg" height="32" alt="" loading="lazy" />
                        <h4 class="ms-2 my-auto d-none d-md-block">Beacon Chain explorer</h4>
                        <h4 class="ms-2 my-auto d-md-none">BC</h4>
                    </a>

                    <form method="GET" action="/search" class="input-group w-100 my-auto">
                        <input autocomplete="off" type="search" class="form-control rounded"
                            placeholder='Search: height / hash' name="q" />
                        <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
                    </form>
                </div>
            </nav>
        </header>

        <main style="margin-top: 62px">
            <div class="container pt-4">
<?php
}

function getFooter() {
?>
            </div>
        </main>
    </body>
</html>
<?php
}

function getBlocks($title, $blocks) {
?>
<section class="mb-4">
    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong><?php echo $title; ?></strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">Height</th>
                            <th scope="col">Block hash</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($blocks as $b) {
                            $b['body'] = json_decode($b['body']);
                        ?>
                        <tr>
                            <td>
                                <a href="/block/<?php echo $b['hash']; ?>">
                                    <?php echo $b['height']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="/block/<?php echo $b['hash']; ?>">
                                    <i class="fas fa-cube"></i>
                                    <span><?php echo $b['hash']; ?></span>
                                </a>
                            </td>
                            <td>
                                <?php echo date("Y-m-d H:i:s", $b['body'] -> foliage -> foliage_block_data -> timestamp); ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
}
?>