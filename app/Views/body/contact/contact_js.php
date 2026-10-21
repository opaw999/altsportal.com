<script type="text/javascript">
	$(document).ready(function() {
		$('form#contact_us_form').submit(function(e) {

			e.preventDefault();
			let formData = $(this).serialize();
			Swal.fire({
				icon: 'info',
				title: 'Please wait...',
				text: 'Sending message.',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});
			setTimeout(() => {
				$.ajax({
					url: "<?= site_url('submit_message'); ?>",
					type: "POST",
					data: formData,
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
							Swal.close();
							console.error("Error:", data);
							errorAlert(data)
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						Swal.close();
						console.error("AJAX Error:", textStatus, errorThrown);
						errorAlert(errorThrown)
					}
				});
			}, 2000);
		});
	});
</script>