<style type="text/css">
	.parsley-error {
		border-color: #ff5b57 !important;
		color: var(--bs-component-color) !important;
	}

	.parsley-success:focus {
		border-color: #00acac !important;
		outline: 0;
		box-shadow: 0 0 0 .25rem rgba(0, 172, 172, .3) !important;
	}

	.parsley-errors-list.filled {
		margin-top: .46875rem !important;
	}

	.parsley-errors-list {
		padding: 0 !important;
		list-style-type: none !important;
		margin: 0 !important;
		margin-top: 0px;
		color: #ff5b57;
		font-size: .75rem !important;
		line-height: inherit !important;
	}

	.parsley-errors-list {
		list-style-type: none !important;
		color: #ff5b57;
		font-size: .75rem !important;
		line-height: inherit !important;
	}
</style>
<div id="page-header" class="section-container page-header-container bg-black">
	<div class="page-header-cover">
		<img src="<?= base_url('../assets/main/img/cover/cover-15.jpg') ?>" alt="" />
	</div>
	<div class="container">
		<h1 class="page-header"><b>My Account</b></h1>
	</div>
</div>

<div class="container">
	<div class="checkout mt-10px mb-10px">
		<form id="account_update_form" data-parsley-validate="true" class="form-horizontal">
			<div class="checkout-header">
				<div class="row">
					<div class="col-lg-4">
						<div class="step step_profile active">
							<a href="javascript:;" onclick="get_form('profile')">
								<div class="number">1</div>
								<div class="info">
									<div class="title">Profile Data</div>
									<div class="desc">Update your account details...</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="step step_username">
							<a href="javascript:;" onclick="get_form('username')">
								<div class="number">2</div>
								<div class="info">
									<div class="title">Username</div>
									<div class="desc">Update your username...</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="step step_password">
							<a href="javascript:;" onclick="get_form('password')">
								<div class="number">3</div>
								<div class="info">
									<div class="title">Password</div>
									<div class="desc">Update your password...</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="checkout-body get_form"></div>
		</form>
	</div>
</div>