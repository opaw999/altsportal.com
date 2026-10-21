	<div id="footer" class="footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h4 class="footer-header">ABOUT US </h4>
					<p style="text-align: justify;">
						Alturas Supplier Portal is designed to allow vendors/agencies to view, monitor and print DTR and uploaded Violations summary report per employee or per supplier.
					</p>
					<p style="text-align: justify;">
						This is also designed to ensure efficiency and control of manipulating data and to provide a contactless and easy monitoring of transactions online, anytime and anywhere with mobile data or Wi-Fi connection.
					</p>
				</div>
				<div class="col-lg-2">
					<h4 class="footer-header">RELATED LINKS </h4>
					<ul class="fa-ul">
						<li><i class="fa fa-li fa-angle-right"></i> <a href="<?= base_url('page/menu/masterfile/masterfile'); ?>">Masterfile</a></li>
						<li><i class="fa fa-li fa-angle-right"></i> <a href="<?= base_url('page/menu/contact/contact'); ?>">Contact Us</a></li>
						<li><i class="fa fa-li fa-angle-right"></i> <a href="<?= base_url('page/menu/account/user'); ?>">My Account</a></li>
						<li><i class="fa fa-li fa-angle-right"></i> <a href="<?= base_url('assets/user_guide/portal.pdf'); ?>" target="_blank">User Guide</a></li>
						<!-- <li><i class="fa fa-li fa-angle-right"></i> <a href="<?= base_url('page/menu/tutorial'); ?>">Tutorial</a></li> -->
					</ul>
				</div>
				<div class="col-lg-4">
					<h4 class="footer-header">OUR CONTACT </h4>
					<address>
						<strong>Head Office</strong><br />
						AGC Corporate Center, North Wing ICM Bldg.,<br />
						Dampas District, Tagbilaran City, Bohol<br /><br />
						<abbr title="Phone">Telephone:</abbr> 501-3000<br />
						<abbr title="Phone">Cellphone:</abbr> 09190794826<br />
						<abbr title="Fax">Fax:</abbr> (038) 501-9245<br />
						<abbr title="Email">Email:</abbr> <a href="mailto:corporatehrd@alturasbohol.com">corporatehrd@alturasbohol.com</a>
					</address>
				</div>
			</div>
		</div>
	</div>
	<div id="footer-copyright" class="footer-copyright">
		<div class="container">
			<div class="copyright">
				Copyright &copy; 2018 AltSPortal. All rights reserved.
			</div>
		</div>
	</div>

	</div>
	<!-- END #app -->


	<!-- ================== BEGIN core-js ================== -->








	<!-- <script src="<?= base_url('../assets/js/vendor.min.js') ?>"></script>
	<script src="<?= base_url('../assets/js/app.min.js') ?>"></script> -->

	<script src="<?= base_url('../assets/main/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') ?>" data-cfasync="false"></script>
	<script src="<?= base_url('../assets/main/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') ?>" data-cf-settings="73464a4a5c9690ef8e55dad4-|49" defer=""></script>
	<!-- <script src="<?= base_url('../assets/main/js/e-commerce/vendor.min.js') ?>"></script>
	<script src="<?= base_url('../assets/main/js/e-commerce/app.min.js') ?>"></script> -->

	<!-- ================== END core-js ================== -->

	<!-- ================== BEGIN page-js ================== -->
	<script src="<?= base_url('../assets/plugins/gritter/js/jquery.gritter.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/jquery-sparkline/jquery.sparkline.min.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/parsleyjs/dist/parsley.min.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
	<link href="<?= base_url('../assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('../assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') ?>" rel="stylesheet" />
	<script src="<?= base_url('../assets/plugins/datatables.net/js/dataTables.min.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') ?>"></script>

	<script src="<?= base_url('../assets/plugins/switcher/js/jquery.switcher.min.js'); ?>"></script>
	<script src="<?= base_url('../assets/plugins/select2/dist/js/select2.min.js') ?>"></script>

	<script src="<?= base_url('../assets/js/demo/dashboard.js') ?>"></script>
	<script src="<?= base_url('../assets/plugins/code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js') ?>"></script>
	<!-- ================== END page-js ================== -->
	<!-- <script async="" src="../../../../www.googletagmanager.com/gtag/js_1ef9c450.js"></script> -->
	<script>
		$(document).ready(function() {
			Pusher.logToConsole = false;

			const pusher = new Pusher('530a8cc03f6febd40e05', {
				cluster: 'ap1',
				forceTLS: true
			});

			const channel = pusher.subscribe('promo-chat');

			channel.bind('new-message', function(data) {
				$('span.unread').html(data.supplier_unread)
			});
		});
	</script>
	</body>

	</html>