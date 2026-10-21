<script type="text/javascript">
	$(document).ready(function() {

		// var first_cutoff = $("input[name = 'first_cutoff']").val();
		// var dataTable = $('#dt_dtr').DataTable({

		// 	"destroy" : true,
		//     "ajax": {  
		//         url: "<?php echo site_url('violation/display_cutoff'); ?>",  
		//         type: "POST",
		//         data: { cutoff:first_cutoff },
		//     },  
		//     "order": [[ 0, "desc" ]],
		//     "columnDefs":[  
		//         {  
		//             "targets":[0, 1, 2],  
		//             "orderable":false, 
		//             "className": "text-center" 
		//         },  
		//     ],
		//     "bPaginate": true,
		//     "bLengthChange": false,
		//     "bFilter": false,
		//     "bInfo": true,
		//     "bAutoWidth": true 
		// });

		var cutoff = $('a.btn.btn-danger.cutoff.active').attr('id');
		dtr_cutoffs(cutoff)

		$('a.cutoff').click(function() {
			$('a.cutoff').removeClass('btn-danger active').addClass('btn-primary');
			$(this).addClass('btn-danger active');

			var cutoff = this.id;
			dtr_cutoffs(cutoff)
		});

		$('table#dt_dtr').on('click', 'a.record', function() {
			$('#view_employee').modal({
				backdrop: 'static',
				keyboard: false
			});

			$('#view_employee').modal('show');

			let [cutoff, cut, dateFrom, dateTo] = this.id.split('|');
			server_code_emp(cutoff, cut, dateFrom, dateTo);
			$("span#emp_cutoff").html(dateFrom + ' - ' + dateTo);
		});

		// $("a.cutoff").click(function() {

		// 	var id = this.id.split("_");
		// 	var cutOff = id[1].trim();

		// 	$("a.cutoff").removeClass('btn-danger').addClass('btn-primary');
		// 	$("a#cutoff_" + cutOff).addClass("btn-danger");

		// 	var dataTable = $('#dt_dtr').DataTable({

		// 		"destroy": true,
		// 		"ajax": {
		// 			url: "<?php echo site_url('violation/display_cutoff'); ?>",
		// 			type: "POST",
		// 			data: {
		// 				cutoff: cutOff
		// 			},
		// 		},
		// 		"order": [
		// 			[0, "desc"]
		// 		],
		// 		"columnDefs": [{
		// 			"targets": [0, 1, 2],
		// 			"orderable": false,
		// 			"className": "text-center"
		// 		}, ],
		// 		"bPaginate": true,
		// 		"bLengthChange": false,
		// 		"bFilter": false,
		// 		"bInfo": true,
		// 		"bAutoWidth": true
		// 	});
		// });

		// $('#dt_dtr').on('click', 'a.record', function() {

		// 	if (!$(this).parents('tr').hasClass('selected')) {

		// 		$('tr.selected').removeClass('selected success');
		// 		$(this).parents('tr').addClass('selected success');
		// 	}

		// 	var id = this.id;
		// 	var cutoff = $("input#cutoff_" + id).val();
		// 	var dateFrom = $("input#dateFrom_" + id).val();
		// 	var dateTo = $("input#dateTo_" + id).val();

		// 	$("#view_employee").modal({
		// 		backdrop: 'static',
		// 		keyboard: false
		// 	});

		// 	$("#view_employee").modal("show");

		// 	$("span#emp_cutoff").html('');
		// 	$("div.view_employee").html('<center><img src="<?php echo base_url('assets/img/gif/loader_seq.gif'); ?>"></center>');
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "<?php echo site_url('cutoff'); ?>",
		// 		data: {
		// 			dateFrom: dateFrom,
		// 			dateTo: dateTo
		// 		},
		// 		success: function(data) {

		// 			$("span#emp_cutoff").html(data);

		// 			$.ajax({
		// 				type: "POST",
		// 				url: "<?php echo site_url('emp_violation_list'); ?>",
		// 				data: {
		// 					cutoff: cutoff,
		// 					dateFrom: dateFrom,
		// 					dateTo: dateTo
		// 				},
		// 				beforeSend: function() {

		// 					$("div.view_employee").html('<center><img src="<?php echo base_url('assets/img/gif/loader_seq.gif'); ?>"></center>');
		// 				},
		// 				success: function(data) {

		// 					$("div.view_employee").html(data);
		// 				}
		// 			});
		// 		}
		// 	});
		// });

		// $('div#view_violation').on('hidden.bs.modal', function(e) {

		// 	$('body').addClass('modal-open');
		// });

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

	function server_code_emp(cutoff, cut, dateFrom, dateTo) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('server_code_emp'); ?>",
			data: {
				cutoff,
				cut,
				dateFrom,
				dateTo
			},
			beforeSend: function() {
				loader_circle('view_employee')
			},
			success: function(data) {
				$("div.view_employee").html(data);
				$('input[name="server"]').val('tagbilaran');
				employee_list(cutoff, 'tagbilaran', dateFrom, dateTo)

			}
		});
	}

	function employee_list(cutoff, server, dateFrom, dateTo) {
		$('div.step.active').removeClass('active');
		$('div.step.step_' + server).addClass('active');
		$('#employee_list').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('violation_employee_list'); ?>",
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
					"targets": [3, 4, 5],
					"className": "text-center"
				},
				{
					"targets": [1],
					"width": "30%"
				},
				{
					"targets": [1, 2, 3, 4, 5],
					"orderable": false
				}
			]
		});
	}

	function viewViolations(server, empId, agency, company, dateFrom, dateTo) {
		console.log(server, agency, company, dateFrom, dateTo);
		$('#view_violation').modal({
			backdrop: 'static',
			keyboard: false
		});

		$('#view_violation').modal('show');

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('viewViolations_emp'); ?>",
			data: {
				server,
				empId,
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