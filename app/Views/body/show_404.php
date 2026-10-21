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
	<link href="../assets/css/vendor.min.css" rel="stylesheet" />
	<link href="../assets/css/default/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
</head>

<body class='pace-top'>
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<div id="app" class="app">
		<div class="error">
			<div class="error-code">404 </div>
			<div class="error-content">
				<div class="error-message">We couldn't find it... </div>
				<div class="error-desc mb-4">
					The page you're looking for doesn't exist. <br />
					Perhaps, there pages will help find what you're looking for.
				</div>
				<div>
					<a href="<?= base_url('/') ?>" class="btn btn-success px-3">Go Home </a>
				</div>
			</div>
		</div>
	</div>

	<!-- ================== BEGIN core-js ================== -->
	<script src="../assets/js/vendor.min.js"></script>
	<script src="../assets/js/app.min.js"></script>
	<!-- ================== END core-js ================== -->

</body>

</html>