<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar" data-bs-theme="dark">
	<!-- BEGIN scrollbar -->
	<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
		<!-- BEGIN menu -->
		<div class="menu">
			<div class="menu-profile">
				<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
					<div class="menu-profile-cover with-shadow"></div>
					<div class="menu-profile-image" style="width: 100px; height:100%">
						<img src="<?= base_url('assets/img/logo1.png') ?>" width="100%">
					</div>
					<div class="menu-profile-info">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								<?= $userInfo['fullname'] ?>
							</div>
						</div>
						<small><?= $userInfo['server_loc'] ?> </small>
					</div>
				</a>
			</div>
			<div class="menu-header">Navigation </div>
			<div class="menu-item <?= ($menu == 'email' ? 'active' : '') ?>">
				<a href="<?= base_url('admins'); ?>" class="menu-link">
					<div class="menu-icon">
						<i class="fas fa-lg fa-fw fa-envelope"></i>
					</div>
					<div class="menu-text">Email </div>
					<div class="menu-badge unreadMail"><?= $unread; ?> </div>
				</a>
			</div>
			<?php
			$user_type = $userInfo['user_type'];
			$user_no = $userInfo['user_no'];
			$access = $userInfo['access_menu'];
			$access_menu = explode('|', $access);

			if (in_array('setup', $access_menu)) {
			?>
				<div class="menu-item has-sub <?= ($menu == 'setup' ? 'active' : '') ?>">
					<a href="javascript:;" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-cog"></i>
						</div>
						<div class="menu-text">Setup </div>
						<div class="menu-caret"></div>
					</a>
					<div class="menu-submenu">
						<div class="menu-item <?= ($submenu == 'supplier' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/setup/supplier'); ?>" class="menu-link">
								<div class="menu-text">Supplier </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'supplier_account' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/setup/supplier_account'); ?>" class="menu-link">
								<div class="menu-text">Supplier Account </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'consignor_account' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/setup/consignor_account'); ?>" class="menu-link">
								<div class="menu-text">Consignor Account </div>
							</a>
						</div>
						<?php if ($user_type == "superadmin") : ?>
							<div class="menu-item <?= ($submenu == 'user' ? 'active' : '') ?>">
								<a href="<?= base_url('admins/page/menu/setup/user'); ?>" class="menu-link">
									<div class="menu-text">User Account </div>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php
			}
			?>

			<?php
			$servers = array('server_tag', 'server_colonnade');
			if (in_array('violation', $access_menu)) {
				if (in_array(session('server'), $servers)) {
			?>
					<div class="menu-item has-sub <?= ($menu == 'violation' ? 'active' : '') ?>">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fas fa-lg fa-fw fa-file-archive"></i>
							</div>
							<div class="menu-text">Violation </div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
							<div class="menu-item <?= ($submenu == 'upload_file' ? 'active' : '') ?>">
								<a href="<?= base_url('admins/page/menu/violation/upload_file'); ?>" class="menu-link">
									<div class="menu-text">Upload File </div>
								</a>
							</div>
						</div>
					</div>
			<?php
				}
			}
			?>

			<?php
			if (in_array('masterfile', $access_menu)) {
			?>
				<div class="menu-item has-sub <?= ($menu == 'masterfile' ? 'active' : '') ?>">
					<a href="javascript:;" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-tags"></i>
						</div>
						<div class="menu-text">Promo Data </div>
						<div class="menu-caret"></div>
					</a>
					<div class="menu-submenu">
						<div class="menu-item <?= ($submenu == 'view_masterfile' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/masterfile/view_masterfile'); ?>" class="menu-link">
								<div class="menu-text">Masterfile </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'view_dtr' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/masterfile/view_dtr'); ?>" class="menu-link">
								<div class="menu-text">DTR </div>
							</a>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			<!-- Admin Menu for Setup Admin User, fixing data, etc. (start) -->
			<!-- Note: For superadmin and admin with portalAdmin access users only! -->
			<?php
			if (in_array('portalAdmin', $access_menu)) {
			?>
				<div class="menu-item has-sub <?= ($menu == 'portalAdmin' ? 'active' : '') ?>">
					<a href="javascript:;" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-user-ninja"></i>
						</div>
						<div class="menu-text">Portal Admin </div>
						<div class="menu-caret"></div>
					</a>
					<div class="menu-submenu">
						<?php if ($user_type == 'superadmin') : ?>
							<div class="menu-item <?= ($submenu == 'adminUserList' ? 'active' : '') ?>">
								<a href="<?= base_url('admins/page/menu/portalAdmin/adminUserList'); ?>" class="menu-link">
									<div class="menu-text">Admin User List </div>
								</a>
							</div>
						<?php endif; ?>
						<div class="menu-item <?= ($submenu == 'promoContracts' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/portalAdmin/promoContracts'); ?>" class="menu-link">
								<div class="menu-text">Promo Contracts </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'assignData' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/portalAdmin/assignData'); ?>" class="menu-link">
								<div class="menu-text">Assign Data </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'previousPerday' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/portalAdmin/previousPerday'); ?>" class="menu-link">
								<div class="menu-text">Previous DTR </div>
							</a>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			<!-- Admin Menu for fixing data (end) -->

			<?php
			if (in_array('import', $access_menu)) {
			?>
				<div class="menu-item <?= ($menu == 'import' ? 'active' : '') ?>">
					<a href="<?= base_url('admins/page/menu/import/data'); ?>" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-file-import"></i>
						</div>
						<div class="menu-text">Import </div>
					</a>
				</div>
			<?php
			}
			?>
			<?php
			if (in_array('import_violation', $access_menu)) {
			?>
				<div class="menu-item <?= ($menu == 'import_violation' ? 'active' : '') ?>">
					<a href="<?= base_url('admins/page/menu/import_violation/data'); ?>" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-file-import"></i>
						</div>
						<div class="menu-text">Import Violation </div>
					</a>
				</div>
			<?php
			}
			?>
			<?php
			if (in_array('cms', $access_menu) && $user_type == 'superadmin') {
			?>
				<div class="menu-item has-sub <?= ($menu == 'cms' ? 'active' : '') ?>">
					<a href="javascript:;" class="menu-link">
						<div class="menu-icon">
							<i class="fas fa-lg fa-fw fa-cog"></i>
						</div>
						<div class="menu-text">CMS </div>
						<div class="menu-caret"></div>
					</a>
					<div class="menu-submenu">
						
						<div class="menu-item <?= ($submenu == 'vendor' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/vendor'); ?>" class="menu-link">
								<div class="menu-text">Vendor </div>
							</a>
						</div>

						<div class="menu-item <?= ($submenu == 'vis' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/vis'); ?>" class="menu-link">
								<div class="menu-text">VIS </div>
							</a>
						</div>
						
						<div class="menu-item <?= ($submenu == 'cas' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/cas'); ?>" class="menu-link">
								<div class="menu-text">CAS </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'items' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/items'); ?>" class="menu-link">
								<div class="menu-text">Items </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'po' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/po'); ?>" class="menu-link">
								<div class="menu-text">P.O. </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'sales_summary' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/sales_summary'); ?>" class="menu-link">
								<div class="menu-text">Sales Summary </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'sales_uploaded' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/sales_uploaded'); ?>" class="menu-link">
								<div class="menu-text">Sales Uploaded File </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'deduction' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/deduction'); ?>" class="menu-link">
								<div class="menu-text">Deduction </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'checks' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/checks'); ?>" class="menu-link">
								<div class="menu-text">Checks Monitoring </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'check_voucher' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/check_voucher'); ?>" class="menu-link">
								<div class="menu-text">Checks Voucher </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'check_details' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/check_details'); ?>" class="menu-link">
								<div class="menu-text">Checks Details </div>
							</a>
						</div>
						<div class="menu-item <?= ($submenu == 'upload_cms' ? 'active' : '') ?>">
							<a href="<?= base_url('admins/page/menu/cms/upload_cms'); ?>" class="menu-link">
								<div class="menu-text"> Upload CMS</div>
							</a>
						</div>

					</div>
				</div>
			<?php
			}
			?>


			<!-- BEGIN minify-button -->
			<div class="menu-item d-flex">
				<a href="javascript:;" class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
			</div>
			<!-- END minify-button -->
		</div>
		<!-- END menu -->
	</div>
	<!-- END scrollbar -->
</div>
<div class="app-sidebar-bg" data-bs-theme="dark"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
<!-- END #sidebar -->