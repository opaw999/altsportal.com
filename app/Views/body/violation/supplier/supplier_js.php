<script type="text/javascript">
	$(document).ready(function() {
		var cutoff = $('a.btn.btn-danger.cutoff.active').attr('id');
		dtr_cutoffs(cutoff)

		$('a.cutoff').click(function() {
			$('a.cutoff').removeClass('btn-danger active').addClass('btn-primary');
			$(this).addClass('btn-danger active');

			var cutoff = this.id;
			dtr_cutoffs(cutoff)
		});

		$('table#dt_dtr').on('click', 'a.record', function() {
			$('#view_company').modal({
				backdrop: 'static',
				keyboard: false
			});

			$('#view_company').modal('show');

			let [cutoff, cut, dateFrom, dateTo] = this.id.split('|');
			server_code_sup(cutoff, cut, dateFrom, dateTo);
			$("span#emp_cutoff").html(dateFrom + ' - ' + dateTo);
		});

	});

	function dtr_cutoffs(cutoff) {
		$('#dt_dtr').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('display_supplier_cutoff'); ?>",
				type: "POST",
				data: {
					cutoff
				},
			},
			"order": [
				[0, "desc"]
			],
			"columnDefs": [{
				"targets": [1, 2],
				"orderable": false,

			}, {
				"targets": [2],
				"className": "text-center"
			}]
		});
	}

	function server_code_sup(cutoff, cut, dateFrom, dateTo) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('server_code_sup'); ?>",
			data: {
				cutoff,
				cut,
				dateFrom,
				dateTo
			},
			beforeSend: function() {
				loader_circle('view_company')
			},
			success: function(data) {
				$("div.view_company").html(data);
				$('input[name="server"]').val('tagbilaran');
				company_list(cutoff, 'tagbilaran', dateFrom, dateTo)

			}
		});
	}

	function company_list(cutoff, server, dateFrom, dateTo) {
		console.log(server)
		$('div.step.active').removeClass('active');
		$('div.step.step_' + server).addClass('active');

		$('#company_list').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('company_list'); ?>",
				type: "POST",
				data: {
					cutoff,
					server,
					dateFrom,
					dateTo
				},
			},
			"order": [
				[0, "asc"]
			],
			"columnDefs": [{
					"targets": [1],
					"width": "12%",
					"className": "text-center"
				},
				{
					"targets": [0],
					"orderable": false,
					"width": "20%"
				}
			]
		});
	}

	function viewViolations(server, agency, company, dateFrom, dateTo) {
		console.log(server, agency, company, dateFrom, dateTo);
		$('#view_violation').modal({
			backdrop: 'static',
			keyboard: false
		});

		$('#view_violation').modal('show');

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('viewViolations'); ?>",
			data: {
				server,
				agency,
				company,
				dateFrom,
				dateTo
			},
			beforeSend: function() {
				loader_circle('view_violation')
			},
			success: function(data) {
				$("div.view_violation").html(data);
			}
		});

	}

	function view_image(id, server) {
		$.ajax({
			type: "POST",
			url: "<?= site_url('viewViolations_img'); ?>",
			data: {
				id,
				server
			},
			beforeSend: function() {
				loader_circle('violation')
			},
			success: function(data) {
				var img = JSON.parse(data);
				$("div.violation_uploaded").html('<label>Date Uploaded: <strong>' + img.created_at + '</strong>');
				$("div.violation").html('<img src="<?php echo base_url(); ?>' + img.image_path + '" loading="lazy" width="100%">');
			}
		});
	}

	function alertRequired(message) {
		Swal.fire({
			title: 'Required!',
			text: message,
			icon: 'warning',
			iconColor: '#ffc107',
			confirmButtonText: 'Ok',
			confirmButtonColor: '#0d6efd',
			allowOutsideClick: false,
		});
	}

	function errorAlert(message) {
		Swal.fire({
			title: 'Error!',
			text: message,
			icon: 'error',
			iconColor: '#dc3545',
			confirmButtonText: 'Ok',
			confirmButtonColor: '#dc3545',
			allowOutsideClick: false,
		})
	}

	function alertInfo(message) {
		Swal.fire({
			title: 'Note!',
			text: message,
			icon: 'info',
			confirmButtonText: 'Ok',
			confirmButtonColor: '#0d6efd',
			allowOutsideClick: false,
		});
	}

	function loader_circle(page) {
		$('div.' + page).html(
			'<div style="text-align: center;">' +
			'<img src="<?= base_url('../assets/img/loader_seq.gif') ?>">' +
			'</div>'
		);
	}
</script>