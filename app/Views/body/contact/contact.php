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
		<h1 class="page-header"><b>Contact Us</b></h1>
	</div>
</div>

<div class="container">

	<div class="row row-space-30 mt-3">
		<div class="col-md-8">
			<h4 class="mt-0">Contact Form</h4>
			<p class="mb-30px fs-13px">
				For further information, clarification, suggestion and concerns,
				please feel free to contact us by sending us message below (Please fill in all required data upon submission).
				All messages, will be accommodated by our authorized HRD Staff and IT Administrator.
			</p>
			<form class="form-horizontal" id="contact_us_form" data-parsley-validate="true" autocomplete="off">
				<div class="mb-2 row">
					<label class="col-form-label col-md-3 text-lg-end">Company/Agency <span class="text-danger">*</span></label>
					<div class="col-md-7">
						<input type="text" class="form-control" name="sender" value="<?= $supplierName; ?>" readonly="" />
					</div>
				</div>
				<div class="mb-2 row">
					<label class="col-form-label col-md-3 text-lg-end">Subject <span class="text-danger">*</span></label>
					<div class="col-md-7">
						<input type="text" class="form-control" name="subject" required />
					</div>
				</div>
				<div class="mb-2 row">
					<label class="col-form-label col-md-3 text-lg-end">Message <span class="text-danger">*</span></label>
					<div class="col-md-7">
						<textarea class="form-control" rows="10" name="message" required></textarea>
					</div>
				</div>
				<div class="mb-2 row">
					<label class="col-form-label col-md-3"></label>
					<div class="col-md-7">
						<button type="submit" class="btn btn-primary btn-theme">SEND MESSAGE</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<h4 class="mt-0">Our Contacts</h4>
			<div class="ratio ratio-16x9 mb-15px">
				<iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3933.300863864775!2d123.86756191435927!3d9.655313393087972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aa4c30923688e3%3A0xbf744e652643049b!2sIsland+City+Mall!5e0!3m2!1sen!2sph!4v1540889503220" allowfullscreen></iframe>
			</div>
			<div><b>Head Office</b></div>
			<p class="m-b-15">
				AGC Corporate Center, North Wing ICM Bldg.,<br />
				Dampas District, Tagbilaran City, Bohol<br />
				Telephone: 501-3000<br />
				Cellphone: 09255701710 / 09082048438<br />
			</p>
			<div><b>Email</b></div>
			<p class="m-b-15">
				<a href="mailto:corporatehrd@alturasbohol.com" class="text-inverse">corporatehrd@alturasbohol.com</a><br />
			</p>
			<!-- <div class="m-b-5"><b>Social Network</b></div>
			<p class="m-b-15">
				<a href="#" class="btn btn-icon btn-white btn-circle"><i class="fab fa-facebook"></i></a>
				<a href="#" class="btn btn-icon btn-white btn-circle"><i class="fab fa-twitter"></i></a>
				<a href="mailto:corporatehrd@alturasbohol.com" target="_blank" class="btn btn-icon btn-white btn-circle"><i class="fab fa-google"></i></a>
				<a href="#" class="btn btn-icon btn-white btn-circle"><i class="fab fa-instagram"></i></a>
			</p> -->
		</div>
	</div>
</div>