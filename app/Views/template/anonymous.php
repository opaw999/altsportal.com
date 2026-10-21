<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Original URL: http://seantheme.com/color-admin/admin/html/login_v2.html Date Downloaded: 3/5/2025 11:07:14 AM !-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <title>AltSPortal | Admin</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets/img/logo1.png') ?>" type="image/png" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="<?= base_url('../assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/default/app.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/plugins/gritter/css/jquery.gritter.css') ?>" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>

<body class='pace-done pace-top theme-orange'>
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->


    <!-- BEGIN #app -->
    <div id="app" class="app">
        <!-- BEGIN login -->
        <div class="login login-v2 fw-bold">
            <!-- BEGIN login-cover -->
            <div class="login-cover">
                <div class="login-cover-img" style="background-image: url(../assets/img/login-bg/login-bg-16.jpg)" data-id="login-cover-image"></div>
                <div class="login-cover-bg"></div>
            </div>
            <!-- END login-cover -->

            <!-- BEGIN login-container -->
            <div class="login-container">
                <!-- BEGIN login-header -->
                <div class="login-header">
                    <div class="brand">
                        <div class="d-flex align-items-center">
                            <img src="<?= base_url('assets/img/logo1.png') ?>" width="100%">
                        </div>
                        <small>Alturas Supplier's Portal | Anonymous</small>
                    </div>
                    <!-- <div class="icon">
						<i class="fa fa-lock"></i>
					</div> -->
                </div>
                <!-- END login-header -->

                <!-- BEGIN login-content -->
                <div class="login-content">
                    <form id="adminLogin" data-parsley-validate="true">
                        <div class="form-group mb-10px">
                            <input type="text" name="username" class="form-control fs-13px h-45px border-0" placeholder="Username" data-parsley-required="true">
                        </div>
                        <div class="form-group mb-10px">
                            <input type="password" name="password" class="form-control fs-13px h-45px border-0" placeholder="Password" data-parsley-required="true">
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-theme d-block w-100 h-45px btn-lg loginbutton">Sign me in</button>
                        </div>
                        <div class="text-center text-warning mt-2">
                            <h5 class="error_alert" style="text-shadow: 0 5px 15px rgba(41, 41, 41, 0.95);"></h5>
                        </div>
                    </form>
                </div>
                <!-- END login-content -->
            </div>
            <!-- END login-container -->
        </div>
        <!-- END login -->

        <!-- BEGIN login-bg -->
        <div class="login-bg-list clearfix">
            <!-- <div class="login-bg-list-item active"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-17.jpg" style="background-image: url(../assets/img/login-bg/login-bg-17.jpg)"></a></div> -->
            <!-- <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-17.jpg" style="background-image: url(../assets/img/login-bg/login-bg-17.jpg)"></a></div> -->
            <!-- <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-16.jpg" style="background-image: url(../assets/img/login-bg/login-bg-16.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-15.jpg" style="background-image: url(../assets/img/login-bg/login-bg-15.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-14.jpg" style="background-image: url(../assets/img/login-bg/login-bg-14.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-13.jpg" style="background-image: url(../assets/img/login-bg/login-bg-13.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="../assets/img/login-bg/login-bg-12.jpg" style="background-image: url(../assets/img/login-bg/login-bg-12.jpg)"></a></div> -->
        </div>
        <!-- END login-bg -->
        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        <!-- END scroll-top-btn -->
    </div>
    <!-- END #app -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="<?= base_url('../assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('../assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('../assets/plugins/parsleyjs/dist/parsley.min.js') ?>"></script>
    <script src="<?= base_url('../assets/plugins/gritter/js/jquery.gritter.js') ?>"></script>
    <!-- ================== END core-js ================== -->

    <!-- ================== BEGIN page-js ================== -->
    <!-- <script src="<?= base_url('../assets/js/demo/login-v2.demo.js') ?>"></script> -->
    <!-- ================== END page-js ================== -->
    <!-- <script async="" src="../../../../www.googletagmanager.com/gtag/js_1ef9c450.js"></script> -->
    <script>
        $(document).ready(function() {
            $("form#adminLogin").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $('button.loginbutton').prop('disabled', true);
                $.ajax({
                    url: "<?= site_url('authenticate_anonymous') ?>",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        try {
                            let response = JSON.parse(data); // Attempt to parse JSON
                            if (response.process === 'success') {
                                error_alert('Login Successful!')
                                var msg1 = 'Login Successful!';
                                var msg2 = 'Please wait a moment...';
                                notif(msg1, msg2)
                                setTimeout(() => {
                                    location.reload()
                                }, 800);
                            } else if (response.process === 'failed') {
                                var msg1 = 'Incorrect Password!';
                                var msg2 = 'You have entered an incorrect password...';
                                notif(msg1, msg2)
                                $('button.loginbutton').prop('disabled', false);
                                error_alert('Incorrect Password!')
                            } else if (response.process === 'inactive') {
                                error_alert('Account Inactive!')
                                var msg1 = 'Account Inactive!';
                                var msg2 = 'Your account is inactive...';
                                notif(msg1, msg2)
                                $('button.loginbutton').prop('disabled', false);
                            } else if (response.process === 'miss') {
                                error_alert('Your account does not exist!')
                                var msg1 = 'Account Error!';
                                var msg2 = 'Your account does not exist...';
                                notif(msg1, msg2)
                                $('button.loginbutton').prop('disabled', false);
                            } else if (response.process === 'error contract') {
                                error_alert('Your account has expired!')
                                var msg1 = 'Account Error!';
                                var msg2 = 'Your account has expired...';
                                notif(msg1, msg2)
                                $('button.loginbutton').prop('disabled', false);
                            }
                        } catch (e) {
                            console.error("Error:", data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                    }
                });
            });
        });

        function error_alert(msg) {
            $('h5.error_alert')
                .stop(true, true)
                .html(msg)
                .css('display', 'none')
                .fadeIn(300)
                .delay(2000)
                .fadeOut(500);
        }

        function notif(msg1, msg2) {
            $.gritter.add({
                title: msg1,
                text: msg2,
                sticky: false,
                time: '3000',
                class_name: 'my-sticky-class gritter-warning',
            });
        }
    </script>

</body>

</html>