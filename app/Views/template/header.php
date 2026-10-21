<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <title>altsportal.com </title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets/img/logo1.png') ?>" type="image/png" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="<?= base_url('../assets/main/css/e-commerce/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/main/css/e-commerce/app.min.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('../assets/main/js/e-commerce/vendor.min.js') ?>"></script>
    <script src="<?= base_url('../assets/main/js/e-commerce/app.min.js') ?>"></script>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <!-- ================== END core-css ================== -->

    <!-- ================== BEGIN page-css ================== -->
    <link href="<?= base_url('../assets/plugins/jvectormap-next/jquery-jvectormap.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/gritter/css/jquery.gritter.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/bootstrap-icons/font/bootstrap-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/switcher/css/switcher.css'); ?>" rel="stylesheet">
    <!-- ================== END page-css ================== -->
</head>
<style>
    .message_badge {
        position: absolute;
        top: 30px;
        display: block;
        background: var(--bs-app-theme);
        line-height: 12px;
        font-weight: 600;
        color: var(--bs-app-theme-color);
        padding: 3px 6px;
        font-size: 10.5px;
        border-radius: 30px;
        inset-inline-end: -5px;
    }
</style>

<body class="theme-orange">
    <!-- BEGIN #app -->
    <div id="app" class="fade show">
        <!-- BEGIN #top-nav -->
        <div id="top-nav" class="top-nav">
            <!-- BEGIN container -->
            <div class="container">
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <span class="text-warning"><?= strtoupper($supplierName) ?></span>
                    </ul>
                    <ul class="nav navbar-nav navbar-end">
                        <li><a href="<?= base_url('page/menu/masterfile/masterfile'); ?>">Masterfile </a></li>
                        <li><a href="<?= base_url('page/menu/contact/contact'); ?>">Contact Us </a></li>
                        <li><a href="<?= base_url('page/menu/account/user'); ?>">My Account </a></li>
                        <!-- <li><a href="<?= base_url('assets/user_guide/portal.pdf'); ?>" target="_blank">User Guide </a></li> -->
                        <!-- <li><a href="mailto:corporatehrd@alturasbohol.com"><i class="fa-regular fa-envelope f-s-14"></i></a></li> -->
                        <!-- <li><a href="mailto:corporatehrd@alturasbohol.com"><i class="fa-regular fa-envelope f-s-14"></i></li> -->
                        <!-- <li><a href="#"><i class="fab fa-twitter f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-dribbble f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-google f-s-14"></i></a></li> -->
                    </ul>
                </div>
            </div>
            <!-- <div class="container">
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown dropdown-hover">
                            <a href="#" data-bs-toggle="dropdown"><img src="<? base_url('../assets/img/flag/flag-english.png') ?>" class="flag-img" alt=""> English <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="dropdown-item"><img src="<? base_url('../assets/img/flag/flag-english.png') ?>" class="flag-img" alt=""> English</a></li>
                                <li><a href="#" class="dropdown-item"><img src="<? base_url('../assets/img/flag/flag-german.png') ?>" class="flag-img" alt=""> German</a></li>
                                <li><a href="#" class="dropdown-item"><img src="<? base_url('../assets/img/flag/flag-spanish.png') ?>" class="flag-img" alt=""> Spanish</a></li>
                                <li><a href="#" class="dropdown-item"><img src="<? base_url('../assets/img/flag/flag-french.png') ?>" class="flag-img" alt=""> French</a></li>
                                <li><a href="#" class="dropdown-item"><img src="<? base_url('../assets/img/flag/flag-chinese.png') ?>" class="flag-img" alt=""> Chinese</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Customer Care</a></li>
                        <li><a href="#">Order Tracker</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-end">
                        <li><a href="#">Career</a></li>
                        <li><a href="#">Our Forum</a></li>
                        <li><a href="#">Newsletter</a></li>
                        <li><a href="#"><i class="fab fa-facebook-f f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-dribbble f-s-14"></i></a></li>
                        <li><a href="#"><i class="fab fa-google f-s-14"></i></a></li>
                    </ul>
                </div>
            </div> -->
            <!-- END container -->


        </div>
        <!-- END #top-nav -->