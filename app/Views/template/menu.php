	<!-- BEGIN #header -->
	<div id="header" class="header">
		<!-- BEGIN container -->
		<div class="container">
			<!-- BEGIN header-container -->
			<div class="header-container">
				<!-- BEGIN navbar-toggle -->
				<button type="button" class="navbar-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- END navbar-toggle -->
				<!-- BEGIN header-logo -->
				<div class="header-logo">
					<a href="<?= base_url() ?>">
						<!-- <span class="brand-logo"></span> -->
						<span class="brand-text">
							<img src="<?= base_url('assets/img/logo2.png') ?>" width="100%">
							<small>Alturas Supplier's Portal </small>
						</span>
					</a>
				</div>
				<!-- END header-logo -->
				<!-- BEGIN header-nav -->
				<div class="header-nav">
					<div class="collapse navbar-collapse" id="navbar-collapse">
						<ul class="nav justify-content-center">
							<li class="<?= ($menu == 'home' ? 'active' : '') ?>">
								<a href="<?= base_url() ?>">Home </a>
							</li>
							<li class="<?= ($menu == 'dtr' ? 'active' : '') ?>">
								<a href="<?= base_url('page/menu/dtr/dtr') ?>">DTR </a>
							</li>
							<li class="<?= ($menu == 'violation' ? 'active' : '') ?> dropdown dropdown-hover">
								<a href="#" data-bs-toggle="dropdown">
									Violation Monitoring
									<b class="caret"></b>
									<span class="arrow top"></span>
								</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="<?= base_url('page/menu/violation/supplier') ?>">Per Supplier </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/violation/employee') ?>">Per Employee </a>
								</div>
							</li>
							<li class="<?= ($menu == 'cms' ? 'active' : '') ?> dropdown dropdown-hover">
								<a href="#" data-bs-toggle="dropdown">
									Supplier's Data
									<b class="caret"></b>
									<span class="arrow top"></span>
								</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/vis') ?>">Vendor's Information </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/cas') ?>">Concessionaires Agreement </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/po') ?>">Purchase Order (P.O.) </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/items') ?>">Items </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/sales') ?>">Sales & Commission </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/deductions'); ?>">Deductions </a>
									<a class="dropdown-item" href="<?= base_url('page/menu/cms/check') ?>">Check Monitoring </a>
								</div>
							</li>
							<li class="<?= ($menu == 'message' ? 'active' : '') ?>">
								<a href="<?php echo base_url('page/menu/message/chats'); ?>">Messages <span class="message_badge unread"><?= $unread ?></span></a>
							</li>
						</ul>
					</div>
				</div>
				<!-- END header-nav -->
				<!-- BEGIN header-nav -->
				<div class="header-nav justify-content-end">
					<ul class="nav">
						<!-- <li>
							<a href="<?php echo base_url('page/menu/message/chats'); ?>" class="header-cart">
								<i class="fa fa-envelope"></i>
								<span class="total unread"><?= $unread ?></span>
							</a>
						</li> -->

						<li class="divider"></li>
						<li class="dropdown dropdown-hover">
							<a href="#" data-toggle="dropdown">
								<?php
								$photo = base_url('assets/img/user/admin.png');
								if ($userInfo['photo']) {
									$photo = base_url($userInfo['photo']);
								}
								?>
								<img src="<?= $photo ?>" class="user-img" alt="">
								<span class="d-none d-xl-inline"><?= session('username') ?></span>
								<b class="caret"></b>
								<span class="arrow top"></span>
							</a>
							<div class="dropdown-menu">
								<a href="<?= base_url('page/menu/account/user'); ?>" class="dropdown-item">My Account</a>
								<a href="<?= base_url('logout'); ?>" class="dropdown-item">Log Out</a>
							</div>
						</li>
					</ul>
				</div>
				<!-- END header-nav -->
			</div>
			<!-- END header-container -->
		</div>
		<!-- END container -->
	</div>
	<!-- END #header -->