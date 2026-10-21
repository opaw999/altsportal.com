<script type="text/javascript">
	$(document).ready(function() {
		$("form#data_import_violation").submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			Swal.fire({
				title: 'Confirm!',
				text: 'Import violation data.',
				icon: 'question',
				iconColor: '#ffc107',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						icon: 'info',
						title: 'Please wait...',
						text: 'Importing data.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/import_violation_data'); ?>",
							type: "POST",
							data: formData,
							contentType: false,
							processData: false,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											location.reload();
										});
									} else {
										errorAlert(response.message)
									}
								} catch (e) {
									console.error("Error:", data);
									errorAlert(data)
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {
								console.error("AJAX Error:", textStatus, errorThrown);
								errorAlert(errorThrown)
							}
						});
					}, 2000);
				}
			})
		});
	});

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
</script>