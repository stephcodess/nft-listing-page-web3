<?php
require('./functions.php');
if (isset($_COOKIE['userid'])) {
    $select = selectOne('accounts', ['id' => $_COOKIE['userid']]);
} else {
    $select = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="vendors/bootstrap-icons/bootstrap-icons.css" />
    <script src="https://unpkg.com/moralis/dist/moralis.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="background-color: #000;">
    <header>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark static-top">
            <div class="container">
                <a class="navbar-brand" style="font-weight: bolder; font-size: 30px" href="#">
                    MYNFTS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex flex-column flex-md-row justify-content-center ms-auto">
                        <li class="nav-item mt-3 px-3">
                            <button class="btn" style="width:100%;height: 50px; border-color:#fff; color: #fff; border-radius: 30px;" id="metamask-login" type="button">
                                <?php if ($select && $select['metamask'] !== "") {
                                    echo "connected to metamask";
                                } else {
                                    echo "connect metamask";
                                } ?>
                            </button>
                        </li>
                        <li class="nav-item mt-3 px-3" id="login-phantom">
                            <button class="btn" style="height: 50px; width:100%; border-color:#fff; color: #fff; border-radius: 30px;" id="metamask-login" type="button">
                                <?php if ($select && $select['phantom_wallet'] !== "") {
                                    echo "connected to phantom";
                                } else {
                                    echo "connect phantom";
                                } ?>
                            </button>
                        </li>
                        <li class="nav-item mt-3 px-3">
                            <button class="btn" style="height: 50px; width:100%; border-color:#fff; color: #fff; border-radius: 30px;" type="button">connect Terra</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- Background image -->
        <div class="p-lg-5 text-center text-white">
            <div class="container" style="width:100%;height:100%;position: relative;">
                <div style="width:100%;height: 500px;border-radius: 30px;">
                    <img style="width:100%;height: 500px;border-radius: 30px;" src="./image.jpg" alt="img" />
                </div>
                <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-start px-2 py-2 px-lg-5 py-lg-5" style="position: absolute; height: 100%; width:100%; top:0; background: rgba(0,0,0,0.4);">
                    <div style="text-align: left;" class="col-12 col-lg-6">
                        <h3 class="d-none d-lg-block">Connections</h3>
                        <div class="mt-5">
                            <h6><b>Connected Wallets</b></h6>

                            <?php
                            if ($select === []) {
                            ?>
                                <hr />
                                <h4>No wallet connected</h4>
                            <?php
                            }
                            ?>
                            <div class="d-flex flex-column justify-content-between">
                                <?php if ($select && $select['metamask'] !== NULL) {
                                ?>
                                    <span>Metamask: <b>
                                            <?php echo ($select['metamask']) ?></b><i class="btn btn-danger mx-5 bi bi-trash" onclick="deleteAction({delete_metamsk: 'delete'})" data-toggle="tooltip" data-placement="top" title="remove wallet"></i> </span>
                                    <hr />
                                <?php } ?>
                                <?php
                                if ($select && $select['phantom_wallet'] !== NULL) {
                                ?>
                                    <span>Phantom: <b>
                                            <?php echo ($select['phantom_wallet']) ?></b><i class="btn btn-danger mx-5 bi bi-trash" onclick="deleteAction({delete_phantom: 'delete'})" data-toggle="tooltip" data-placement="top" title="remove wallet"></i> </span>
                                    <hr />
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <form style="text-align: left;" class="col-12 mt-5 mt-lg-0 col-lg-6">
                        <h3 class="d-none d-lg-block">Connect Your Emails</h3>
                        <div class="form-group d-flex flex-column flex-lg-row mt-3">
                            <input type="email" placeholder="Enter Email Address" style="height: 50px; border-color:#fff;background: #000; color: #fff; border-radius: 30px;" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <button type="submit" style="height: 50px; width: 100%; border-color:#fff; color: #fff; border-radius: 30px;" class="btn px-lg-2 mt-3 mt-lg-0 mx-lg-3" id="submitemail">verify</button>
                        </div>
                        <div class="mt-3">
                            <h6><b>Connected Emails</b></h6>
                            <div class="d-flex flex-column justify-content-between">
                                <?php
                                if ($select && $select['email'] !== NULL) {
                                ?>
                                    <span><?php echo $select['email'] ?><i class="btn btn-danger mx-5 bi bi-trash" onclick="deleteAction({delete_email: 'delete'})" data-toggle="tooltip" data-placement="top" title="remove email"></i> </span>
                                    <hr />
                                <?php } else { ?>
                                    <span>No email connected</>
                                    <?php
                                } ?>

                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- Background image -->
    </header>
    <hr />

    <div class="container mt-4 text-white" style="border-top: 1px solid #fff ;">
        <div class="heading mb-5 mt-5">
            <h3 class="">Available NFTS (BY COLLECTIONS) </h3>

        </div>
        <div class="the-spinner text-center" class="width: 100%;"></div>
        <div class="d-flex justify-content-between nft-box flex-wrap">

        </div>
    </div>
    </div>
    <!-- Footer -->
    <footer class="mt-5 page-footer font-small bg-dark text-white">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <!-- <a href="/"> MDBootstrap.com</a> -->
        </div>
        <!-- Copyright -->

    </footer>
    <script src="./vendors/bootstrap/js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="./main.js"></script>
</body>


</html>