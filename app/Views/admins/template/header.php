<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <title>Altsportal | Admin </title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets/img/logo1.png') ?>" type="image/png" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="<?= base_url('../assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/default/app.min.css') ?>" rel="stylesheet" />

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- ================== END page-css ================== -->
</head>

<body class="theme-orange">
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->

    <!-- BEGIN #app -->
    <div id="app" class="app app-header-fixed app-sidebar-fixed app-content-full-height">
        <!-- BEGIN #header -->
        <div id="header" class="app-header" data-bs-theme="dark">
            <!-- BEGIN navbar-header -->
            <div class="navbar-header">
                <a href="<?= base_url('admins') ?>" class="navbar-brand"><b class="me-10px">ALTSPORTAL</b><span class="badge bg-warning me-10px">ADMIN</span></a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- END navbar-header -->
            <!-- BEGIN header-nav -->
            <div class="navbar-nav">
                <!-- <div class="navbar-item navbar-form">
                    <form action="" method="POST" name="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter keyword" />
                            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div> -->
                <div class="navbar-item dropdown">
                    <a href="<?= base_url('admins'); ?>" class="navbar-link dropdown-toggle icon">
                        <i class="fa fa-envelope"></i>
                        <span class="badge unreadMail"><?= $unread; ?> </span>
                    </a>
                    <!-- <div class="dropdown-menu media-list dropdown-menu-end">
                        <div class="dropdown-header">NOTIFICATIONS (5) </div>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <i class="fa fa-bug media-object bg-gray-500"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
                                <div class="text-muted fs-10px">3 minutes ago </div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <img src="../assets/img/user/user-1.jpg" class="media-object" alt="" />
                                <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">John Smith </h6>
                                <p>Quisque pulvinar tellus sit ____ sem scelerisque tincidunt. </p>
                                <div class="text-muted fs-10px">25 minutes ago </div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <img src="../assets/img/user/user-2.jpg" class="media-object" alt="" />
                                <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">Olivia </h6>
                                <p>Quisque pulvinar tellus sit ____ sem scelerisque tincidunt. </p>
                                <div class="text-muted fs-10px">35 minutes ago </div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <i class="fa fa-plus media-object bg-gray-500"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading"> New User Registered </h6>
                                <div class="text-muted fs-10px">1 hour ago </div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <i class="fa fa-envelope media-object bg-gray-500"></i>
                                <i class="fab fa-google text-warning media-object-icon fs-14px"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading"> New Email From ____ </h6>
                                <div class="text-muted fs-10px">2 hour ago </div>
                            </div>
                        </a>
                        <div class="dropdown-footer text-center">
                            <a href="javascript:;" class="text-decoration-none">View more </a>
                        </div>
                    </div> -->
                </div>

                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="<?= base_url('../assets/img/user/admin.png') ?>" alt="" />
                        <span>
                            <span class="d-none d-md-inline"><?= session('admin_username') ?> </span>
                            <b class="caret"></b>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="<?= base_url('admins/page/menu/admin/account'); ?>" class="dropdown-item">Change Account Details </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('admins/logout') ?>" class="dropdown-item">Log Out </a>
                    </div>
                </div>
            </div>
            <!-- END header-nav -->
        </div>
        <!-- END #header -->