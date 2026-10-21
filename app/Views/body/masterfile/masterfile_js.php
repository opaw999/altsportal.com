<script type="text/javascript">
	$(document).ready(function() {

		masterfile_list('tagbilaran')
	});

	function masterfile_list(server) {
		$('div.step.active').removeClass('active');
		$('div.step.step_' + server).addClass('active');
		$('#dt_masterfile').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('view_masterfile'); ?>",
				type: "POST",
				data: {
					server
				},
			},
			"order": [
				[0, "asc"]
			],
			"columnDefs": [{
					"targets": [4, 5, 6],
					"className": "text-center"
				},
				{
					"targets": [1, 2, 3, 4, 5, 6],
					"orderable": false
				}, {
					"targets": [1],
					"width": "30%"
				},

			],
		});
	}

	function schedule_pager(id) {
		$("#view_schedule").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#view_schedule").modal("show");

		let [server, empId, recordNo, promoType, month, year] = id.split('|')

		$.ajax({
			type: "POST",
			url: "<?= site_url('schedule_pager'); ?>",
			data: {
				server,
				empId,
				recordNo,
				promoType,
				month,
				year
			},
			beforeSend: function() {
				loader_circle('view_schedule')
			},
			success: function(data) {
				$("div.view_schedule").html(data);
				view_sched(id)
			}
		});
	}

	function view_sched(id) {
		let [server, empId, recordNo, promoType, month, year] = id.split('|')
		$.ajax({
			type: "POST",
			url: "<?= site_url('view_schedule'); ?>",
			data: {
				server,
				empId,
				recordNo,
				promoType,
				month,
				year
			},
			beforeSend: function() {
				loader_circle('viewSchedPromo')
			},
			success: function(data) {
				$('div.viewSchedPromo').html(data);
				$('#dt_schedule').DataTable({

					"destroy": true,
					"autoWidth": true,
					"searching": false,
					"info": false,
					"ordering": false,
					"paging": false,
					"scrollY": '45vh',
					"scrollCollapse": true
				});
			}
		});
	}

	function month_n(data) {

		var m_ = $("select[name = 'month_']").val();
		var y_ = $("select[name = 'year_']").val();
		var id = data + '|' + m_ + '|' + y_;

		view_sched(id);
	}

	function year_n(data) {

		var m_ = $("select[name = 'month_']").val();
		var y_ = $("select[name = 'year_']").val();
		var id = data + '|' + m_ + '|' + y_;

		view_sched(id);
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