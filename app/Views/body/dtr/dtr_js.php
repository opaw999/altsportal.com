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
			$('#view_employee').modal({
				backdrop: 'static',
				keyboard: false
			});

			$('#view_employee').modal('show');

			let [cutoff, cut, dateFrom, dateTo] = this.id.split('|');
			server_code(cutoff, cut, dateFrom, dateTo);
			$("span#emp_cutoff").html(dateFrom + ' - ' + dateTo);
		});

		$('#view_dtr').on('hidden.bs.modal', function() {
			$('body').addClass('modal-open').css({
				'overflow': 'hidden',
				'padding-right': '0'
			});
		});

		$('div#show-entry').on('hidden.bs.modal', function(e) {

			$('body').addClass('modal-open');
		});

		$('div#requestDetails').on('hidden.bs.modal', function(e) {

			$('body').addClass('modal-open');
		});
	});

	function dtr_cutoffs(cutoff) {
		$('#dt_dtr').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('display_cutoff'); ?>",
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

	function server_code(cutoff, cut, dateFrom, dateTo) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('server_code'); ?>",
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
				employee_list(cutoff, cut, 'tagbilaran', dateFrom, dateTo)

			}
		});
	}

	function employee_list(cutoff, cut, server, dateFrom, dateTo) {
		$('input[name="server"]').val(server);
		$('div.step.active').removeClass('active');
		$('div.step.step_' + server).addClass('active');
		$('#dt_emp_list').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?= site_url('employee_list'); ?>",
				type: "POST",
				data: {
					cutoff,
					cut,
					server,
					dateFrom,
					dateTo
				},
			},
			"order": [
				[0, "asc"]
			],
			"columnDefs": [{
					"targets": [3, 4, 5, 6],
					"orderable": false,
					"className": "text-center"
				},
				{
					"targets": [1],
					"orderable": false,
					"width": "30%"
				},
				{
					"targets": [3, 4],
					"width": "10%"
				},
				{
					"targets": [2],
					"orderable": false
				}
			],
			"autoWidth": true,
			"paging": false,
			"scrollY": '51vh',
			"scrollCollapse": true,
		});
		$("button#print_dtr").prop("disabled", true);
		$('input[name="selectAll"]').prop("checked", false);
	}

	function view_dtr(payload) {
		$('#view_dtr').modal({
			backdrop: 'static',
			keyboard: false
		});

		$('#view_dtr').modal('show');

		let [empId, recordNo, bioM, statCut, i, server, dateFrom, dateTo] = payload.split('|')

		$.ajax({
			type: "POST",
			url: "<?= site_url('view_dtr'); ?>",
			data: {
				empId,
				recordNo,
				bioM,
				statCut,
				i,
				server,
				dateFrom,
				dateTo
			},
			beforeSend: function() {
				loader_circle('view_dtr')
			},
			success: function(data) {
				$('div.view_dtr').html(data);
			}
		});
	}

	function chk(cut) {
		if (cut == 0) {
			var selectAllChecked = $('input[name="selectAll"]').prop('checked');
			if ($('input[name="selectAll"]').is(':checked')) {
				$(".chkC").prop("checked", true);
				$("button#print_dtr").prop("disabled", false);
			} else {
				$(".chkC").prop("checked", false);
				$("button#print_dtr").prop("disabled", true);
			}
		} else {
			if ($(".chk_" + cut).is(':checked')) {
				$("button#print_dtr").prop("disabled", false);
			} else {
				$("button#print_dtr").prop("disabled", true);
			}
		}
	}

	function viewTime(server, empId, dateRendered) {
		$('div#show-entry').modal({
			backdrop: 'static',
			keyboard: false,
		});
		$("#show-entry").modal("show");
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('vTEntry'); ?>",
			data: {
				server: server,
				empId: empId,
				dateRendered: dateRendered
			},
			beforeSend: function() {
				loader_circle('show-entry')
			},
			success: function(data) {

				$("div.show-entry").html(data);
			}
		});
	}

	function viewRequest(request, empId, date, server) {
		$('div#requestDetails').modal({
			backdrop: 'static',
			keyboard: false,
		});
		$("div#requestDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "<?= site_url('viewRequest'); ?>",
			data: {
				request,
				empId,
				date,
				server
			},
			beforeSend: function(data) {
				loader_circle('requestDetails')
			},
			success: function(data) {
				$("div.requestDetails").html(data)
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