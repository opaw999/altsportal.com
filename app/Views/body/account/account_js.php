<script type="text/javascript">
	$(document).ready(function() {

		get_form('profile');

		$("form#account_update_form").submit(function(e) {
			e.preventDefault();

			let formData = $(this).serialize();
			Swal.fire({
				title: 'Confirm!',
				text: 'Update account details.',
				icon: 'question',
				iconColor: '#ffc107',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				allowOutsideClick: false,
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({
						icon: 'info',
						title: 'Please wait...',
						text: 'Updating account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('account_update'); ?>",
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
				}
			})
		});
	});

	function get_form(code) {

		$('div.step.active').removeClass('active');
		$('div.step.step_' + code).addClass('active');

		$.ajax({
			url: "<?php echo site_url('get_form'); ?>",
			type: 'POST',
			data: {
				code
			},
			beforeSend: function() {
				loader_circle('get_form')
			},
			success: function(data) {
				$("div.get_form").html(data);
			}
		});
	}

	function changePhoto(pic) {

		var emp_id = $('input[name="emp_id"]').val();
		Swal.fire({
			html: '<img id="profile-pic" src="' + pic + '" alt="img" class="rounded-circle shadow p-1 border" width="300" height="300">' +
				'<label class="mt-2">Change profile picture?</label>',
			confirmButtonText: 'Yes',
			confirmButtonColor: '#0d6efd',
			cancelButtonColor: '#d33',
			showCancelButton: true,
			cancelButtonText: 'No',
		}).then(async (result) => {
			if (result.isConfirmed) {
				const {
					value: file
				} = await Swal.fire({
					title: 'Select image',
					input: 'file',
					inputAttributes: {
						accept: 'image/*',
						'aria-label': 'Upload your profile picture',
					},
					confirmButtonText: 'Sumbit',
					showCancelButton: true,
					cancelButtonText: 'No',
				})
				if (file) {
					const reader = new FileReader();
					reader.onload = (e) => {
						Swal.fire({
							// title: 'Your uploaded picture',
							html: '<img id="profile-pic" src="' + e.target.result + '" alt="img" class="rounded-circle p-1 border" width="300" height="300">',
							// imageUrl: e.target.result,
							// imageAlt: 'The uploaded picture',
							confirmButtonText: 'Upload',
							showCancelButton: true,
							cancelButtonText: 'Cancel',
							customClass: {
								image: 'rounded-circle shadow p-1 border',
							},
						}).then(async (result) => {
							if (result.isConfirmed) {
								var formData = new FormData();
								formData.append('file', file);

								Swal.fire({
									icon: 'info',
									title: 'Please wait...',
									text: 'Updating photo.',
									allowOutsideClick: false,
									didOpen: () => {
										Swal.showLoading();
									}
								});
								setTimeout(() => {
									$.ajax({
										url: "<?= site_url('change_photo'); ?>",
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

								// $.ajax({
								// 	type: 'POST',
								// 	url: '<?= site_url('change_photo'); ?>',
								// 	data: formData,
								// 	contentType: false,
								// 	processData: false,
								// 	success: function(data) {
								// 		let response = JSON.parse(data);
								// 		if (response.message == 'success') {
								// 			Swal.fire({
								// 				title: 'Success!',
								// 				text: 'Profile picture has been changed.',
								// 				icon: 'success',
								// 				iconColor: '#15ca20',
								// 				confirmButtonText: 'Ok',
								// 				confirmButtonColor: '#0d6efd',
								// 				allowOutsideClick: false,
								// 			}).then((result) => {
								// 				if (result.isConfirmed) {
								// 					get_form('profile');
								// 				}
								// 			});
								// 		} else {

								// 			alert(data)
								// 		}
								// 	}
								// });
							}
						});
					};
					reader.readAsDataURL(file);
				}
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