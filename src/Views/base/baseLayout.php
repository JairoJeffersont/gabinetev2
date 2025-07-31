<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $_ENV['APP_NAME'] ?></title>
    <link href="public/vendor/startbootstrap-simple-sidebar-master/dist/css/styles.css" rel="stylesheet" />
    <link href="public/vendor/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="public/css/custom.css" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="57x57" href="public/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="public/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="public/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="public/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="public/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="public/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="public/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="public/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="public/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="public/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="public/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="public/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/icons/favicon-16x16.png">
    <link rel="manifest" href="public/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <script src="public/vendor/tinymce/tinymce.min.js"></script>


</head>

<body>
    <?php include '../src/web.php'; ?>
    <div class="modal fade" id="modalLoading" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center mb-0">
                    <img src="public/img/loading.gif" / width="150" class="img-fluid">
                    <p class="mb-0">Aguarde, processando...</p>
                </div>
            </div>
        </div>
    </div>
    <script src="public/vendor/jquery/jquery.min.js"></script>
    <script src="public/vendor/jquery-mask/jquery.mask.min.js"></script>
    <script src="public/vendor/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/vendor/startbootstrap-simple-sidebar-master/dist/js/scripts.js"></script>
    <script src="public/js/app.js"></script>
</body>

</html>