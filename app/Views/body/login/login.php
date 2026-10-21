<!-- begin #page-loader -->
<div id="page-loader" class="fade show"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin login-cover -->
<div class="login-cover">
	<?php 
		
		// $imagesDir = 'assets/img/login-bg/';
		// $images = glob($imagesDir . '*.{jpg,jpeg,png}', GLOB_BRACE);
		// $randomImage = $images[array_rand($images)];

		$portal_bg = 'assets/img/login-bg/portal.jpg';
	?>
	<div class="login-cover-image" style="background-image: url(<?php echo base_url($portal_bg); ?>)" data-id="login-cover-image"></div>
	<div class="login-cover-bg"></div>
</div>
<!-- end login-cover -->

<!-- begin #page-container -->
<div id="page-container" class="fade">
	<!-- begin login -->
	<div class="login login-v2" data-pageload-addclass="animated fadeIn">
		<!-- begin brand -->
		<div class="login-header">
			<div class="brand">
				<!-- <span class="logo"></span> <b>Promo</b> DTR -->
				<span class=""><img src="<?php echo base_url('assets/img/logo/asp7.png'); ?>" width="70%"></span> 
				<small>Alturas Supplier's Portal</small>
			</div>
			<div class="icon">
				<i class="fa fa-lock"></i>
			</div>
		</div>
		<!-- end brand -->
		<!-- begin login-content -->
		<div class="login-content">
			<form id="login-form" data-parsley-validate="true" action="" method="post" class="margin-bottom-0">
				<div class="form-group m-b-20">
					<input type="text" name="username" class="form-control form-control-lg" placeholder="Username" data-parsley-required="true" />
				</div>
				<div class="form-group m-b-20">
					<input type="password" name="password" class="form-control form-control-lg" placeholder="Password" data-parsley-required="true" />
				</div>
				<div class="login-buttons">
					<button type="submit" class="btn btn-success btn-block btn-lg sign_in"><span class="loading_"> Sign me in</span></button>
				</div>
			</form>
		</div>
		<!-- end login-content -->
	</div>
	<!-- end login -->
</div>
<!-- end page container