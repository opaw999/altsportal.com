<script type="text/javascript">
	$(document).ready(function() {

		var dt_supplier = $('table#dt_supplier').DataTable({
			"destroy": true,
			"ajax": {
				url: "<?= site_url('admins/supplier_list'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
			"columnDefs": [{
				"targets": [2, 4],
				"orderable": false,
				"className": "text-center",
			}]
		});

		$('table#dt_supplier').on('click', 'i.action', function() {
			let [action, id] = this.id.split('|');
			if (action == "update") {
				$("div#supplier_details").modal({
					backdrop: 'static',
					keyboard: false
				});
				$("div#supplier_details").modal("show");

				$.ajax({
					url: "<?php echo site_url('admins/edit_supplier_details'); ?>",
					type: "POST",
					data: {
						action,
						id
					},
					beforeSend: function() {
						loader_circle('supplier_details')
					},
					success: function(result) {
						$("div.supplier_details").html(result);
					}
				});

			} else {

				if (action == 'activate') {
					var msg1 = 'Activate supplier status.';
					var msg2 = 'Activating supplier...';
				} else {
					var msg1 = 'Deactivate supplier status.';
					var msg2 = 'Deactivating supplier...';
				}

				Swal.fire({
					title: 'Confirm!',
					text: msg1,
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
							text: msg2,
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/create_update_supplier'); ?>",
								type: "POST",
								data: {
									action,
									id
								},
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
												dt_supplier.ajax.reload();
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
			}
		});

		$("form#supplier_form").submit(function(e) {
			e.preventDefault();

			let formData = $(this).serialize();

			var action = $('input[name="action"]').val();

			if (action == 'create') {
				var msg1 = 'Add supplier data.';
				var msg2 = 'Adding supplier...';
			} else {
				var msg1 = 'Update supplier data.';
				var msg2 = 'Updating supplier...';
			}

			Swal.fire({
				title: 'Confirm!',
				text: msg1,
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
						text: msg2,
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/create_update_supplier'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "create") {
										$("div#supplier_details").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier.ajax.reload();
										});
									} else if (response.message === "update") {
										$("div#supplier_details").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier.ajax.reload();
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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


		var dt_supplier_account = $("table#dt_supplier_account").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?= site_url('admins/supplier_account_list'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
			"columnDefs": [{
					"targets": [2, 3, 4, 5],
					"className": "text-center",
				},
				{
					"targets": [1, 2, 3, 4, 5],
					"orderable": false
				}
			]
		});

		$('button.generate_excel').click(function() {
			Swal.fire({
				title: 'Confirm!',
				text: 'Generate Supplier Users report.',
				icon: 'question',
				iconColor: '#ffc107',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				allowOutsideClick: false,
			}).then((result) => {
				if (result.isConfirmed) {
					window.location = "<?= base_url('suppliers_xls'); ?>";
				}
			});
		});

		$('table#dt_supplier_account').on('click', 'i.action', function() {
			let [action, supplier_id, user_id] = this.id.split('|');
			console.log(action)

			if (action == 'update') {
				$('div#update_ac_code').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#update_ac_code').modal('show');

				$.ajax({
					url: "<?= site_url('admins/update_ac_code'); ?>",
					method: "POST",
					data: {
						action,
						supplier_id,
						user_id
					},
					beforeSend: function() {
						loader_circle('update_ac_code')
					},
					success: function(result) {
						$("div.update_ac_code").html(result);
						$('.select2default').select2({
							allowClear: true,
							dropdownParent: $('#update_ac_code')
						});
					}
				});
			} else if (action == 'extend') {
				$('div#renew_contract').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#renew_contract').modal('show');

				$.ajax({
					type: "POST",
					url: "<?php echo site_url('admins/renew_contract'); ?>",
					data: {
						action,
						supplier_id,
						user_id
					},
					beforeSend: function() {
						loader_circle('renew_contract')
					},
					success: function(result) {
						$("div.renew_contract").html(result);

						$('.datePicker').attr('placeholder', 'mm/dd/yyyy')
						$('.datePicker').datepicker({
							format: "yyyy-mm-dd",
							todayHighlight: true,
							autoclose: true
						});

						var monthStart = "<?= date('Y-m-1'); ?>";
						var min = new Date(monthStart);

						var monthEnd = "<?= date('Y-m-t'); ?>";
						var max = new Date(monthEnd);

						$('input[name="dateFrom"]').datepicker('setEndDate', monthEnd)
						$('input[name="dateFrom"]').datepicker('setStartDate', monthStart).on('changeDate', function(e) {
							var selectedDate = $(this).val();
							$('input[name="dateTo"]').val('').datepicker('setStartDate', selectedDate);
							$(this).parsley().validate();
						});

						$('input[name="dateTo"]').datepicker().on('changeDate', function(e) {
							var dateFrom = $("input[name = 'dateFrom']").val();
							if (dateFrom == '') {
								$(this).val('');
								errorAlert('Please select Date From field first.')
							} else {
								$(this).parsley().validate();
							}
						});
					}
				});

			} else if (action == 'cutoff') {
				$('div#setup_cutoff').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#setup_cutoff').modal('show');

				$.ajax({
					type: "POST",
					url: "<?= site_url('admins/setup_cutoff'); ?>",
					data: {
						supplier_id
					},
					beforeSend: function() {
						loader_circle('setup_cutoff')
					},
					success: function(result) {
						$("div.setup_cutoff").html(result);
						$.switcher();
						var x = $("input.cutoff").length;
						var i = $("input.cutoff:checked").length;
						if (x == i) {
							$('.ui-switcher').attr('aria-checked', 'true');
							$('input.selected_0').prop('checked', true);
						}
					}
				});
			} else if (action == 'reset') {
				Swal.fire({
					title: 'Confirm!',
					text: 'Reset Supplier user password.',
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
							text: 'Resseting user password.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/supplier_reset_password'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_supplier_account.ajax.reload();
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
			} else if (action == 'details') {
				$('div#supplier_user_details').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#supplier_user_details').modal('show');

				$.ajax({
					url: "<?= site_url('admins/supplier_user_details'); ?>",
					method: "POST",
					data: {
						action,
						supplier_id,
						user_id
					},
					beforeSend: function() {
						loader_circle('supplier_user_details')
					},
					success: function(result) {
						$("div.supplier_user_details").html(result);
					}
				});
			} else {
				Swal.fire({
					title: 'Confirm!',
					text: 'Update Supplier user account status.',
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
							text: 'Updating user status.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/supplier_user_status'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_supplier_account.ajax.reload();
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
			}
		});

		$("form#supplier_account_form").submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			let supplier = $("select[name = 'supplier']").val();
			if (supplier === "") {
				alertRequired('Please select a supplier.')
				return
			}

			let supplier_type = $("input[name = 'supplier_type']").val();
			if (supplier_type == 1) {
				let alturas_agency = $("select[name = 'alturas_agency']").val();
				let colonnade_agency = $("select[name = 'colonnade_agency']").val();
				if (alturas_agency === "" && colonnade_agency === "") {
					alertRequired('Please select Agency either Bohol or Colonnade Server or both.')
					return
				}
			} else {
				let colonnade_company = $("select[name = 'colonnade_company']").val();
				let alturas_company = $("select[name = 'alturas_company']").val();
				if (colonnade_company === "" && alturas_company === "") {
					alertRequired('Please select Company either Bohol or Colonnade Server or both.')
					return
				}
			}

			Swal.fire({
				title: 'Confirm!',
				text: 'Add supplier user account.',
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
						text: 'Adding user account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/create_supplier_account'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#supplier_account").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier_account.ajax.reload();
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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

		$("form#renew_contract_form").submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			Swal.fire({
				title: 'Confirm!',
				text: 'Update supplier contract.',
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
						text: 'Updating supplier contract.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/renew_supplier_contract'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#renew_contract").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier_account.ajax.reload();
											dt_consignor_account.ajax.reload();
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

		$("form#update_ac_code_form").submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			let supplier_type = $("input[name = 'supplier_type']").val();
			if (supplier_type == 1) {
				let alturas_agency = $("select[name = 'alturas_agency']").val();
				let colonnade_agency = $("select[name = 'colonnade_agency']").val();
				if (alturas_agency === "" && colonnade_agency === "") {
					alertRequired('Please select Agency either Bohol or Colonnade Server or both.')
					return
				}
			} else {
				let colonnade_company = $("select[name = 'colonnade_company']").val();
				let alturas_company = $("select[name = 'alturas_company']").val();
				if (colonnade_company === "" && alturas_company === "") {
					alertRequired('Please select Company either Bohol or Colonnade Server or both.')
					return
				}
			}

			Swal.fire({
				title: 'Confirm!',
				text: 'Update Agency/Company server tagging.',
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
						text: 'Updating user account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/update_supplier_account'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#update_ac_code").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier_account.ajax.reload();
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


		var dt_consignor_account = $("table#dt_consignor_account").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/consignor_account_list'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
			"columnDefs": [{
					"targets": [3, 4, 5],
					"className": "text-center",
				},
				{
					"targets": [1, 2, 3, 4, 5],
					"orderable": false
				}
			]
		});

		$('table#dt_consignor_account').on('click', 'i.action', function() {
			let [action, supplier_id, user_id] = this.id.split('|');
			console.log(action, supplier_id, user_id)

			if (action == 'update') {
				$('div#edit_tag_ac').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#edit_tag_ac').modal('show');

				$.ajax({
					url: "<?= site_url('admins/edit_tag_vendor'); ?>",
					method: "POST",
					data: {
						supplier_id
					},
					beforeSend: function() {
						loader_circle('edit_tag_ac')
					},
					success: function(result) {
						$("div.edit_tag_ac").html(result);
						$('#dt-vendors').DataTable({
							"order": [],
							"columnDefs": [{
								"targets": [0],
								"orderable": false,
								"className": "text-center",
							}, {
								"targets": [1, 2],
								"orderable": false,
							}]
						});
					}
				});
			} else if (action == 'extend') {
				$('div#renew_contract').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#renew_contract').modal('show');

				$.ajax({
					type: "POST",
					url: "<?php echo site_url('admins/renew_contract'); ?>",
					data: {
						action,
						supplier_id,
						user_id
					},
					beforeSend: function() {
						loader_circle('renew_contract')
					},
					success: function(result) {
						$("div.renew_contract").html(result);

						$('.datePicker').attr('placeholder', 'mm/dd/yyyy')
						$('.datePicker').datepicker({
							format: "yyyy-mm-dd",
							todayHighlight: true,
							autoclose: true
						});

						var monthStart = "<?= date('Y-m-1'); ?>";
						var min = new Date(monthStart);

						var monthEnd = "<?= date('Y-m-t'); ?>";
						var max = new Date(monthEnd);

						$('input[name="dateFrom"]').datepicker('setEndDate', monthEnd)
						$('input[name="dateFrom"]').datepicker('setStartDate', monthStart).on('changeDate', function(e) {
							var selectedDate = $(this).val();
							$('input[name="dateTo"]').val('').datepicker('setStartDate', selectedDate);
							$(this).parsley().validate();
						});

						$('input[name="dateTo"]').datepicker().on('changeDate', function(e) {
							var dateFrom = $("input[name = 'dateFrom']").val();
							if (dateFrom == '') {
								$(this).val('');
								errorAlert('Please select Date From field first.')
							} else {
								$(this).parsley().validate();
							}
						});
					}
				});
			} else if (action == 'reset') {
				Swal.fire({
					title: 'Confirm!',
					text: 'Reset Supplier user password.',
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
							text: 'Resseting user password.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/supplier_reset_password'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_consignor_account.ajax.reload();
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
			} else if (action == 'details') {
				$('div#supplier_user_details').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#supplier_user_details').modal('show');

				$.ajax({
					url: "<?= site_url('admins/supplier_user_details'); ?>",
					method: "POST",
					data: {
						action,
						supplier_id,
						user_id
					},
					beforeSend: function() {
						loader_circle('supplier_user_details')
					},
					success: function(result) {
						$("div.supplier_user_details").html(result);
					}
				});
			} else {
				Swal.fire({
					title: 'Confirm!',
					text: 'Update Supplier user account status.',
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
							text: 'Updating user status.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/supplier_user_status'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_consignor_account.ajax.reload();
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
			}
		});

		$("form#consignor_account_form").submit(function(e) {

			e.preventDefault();
			let formData = $(this).find(':input[name!="vendor_id[]"]').serialize();

			var vCodes = [];
			var table = $('table#dt-vendors').DataTable();
			table.rows().nodes().to$().find('input[name="vendor_id[]"]:checked').each(function() {
				var code = $(this).val();
				vCodes.push(code);
				formData += '&vendor_id[]=' + encodeURIComponent(code);
			});

			if (vCodes.length === 0) {
				alertRequired('Please select atleast one vendor.')
				return
			}

			Swal.fire({
				title: 'Confirm!',
				text: 'Add consignor user account.',
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
						text: 'Adding consignor user account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/create_consignor_account'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#supplier_account").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_supplier_account.ajax.reload();
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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

		$("form#edit_tag_vendor_form").submit(function(e) {
			e.preventDefault();
			let formData = $(this).find(':input[name!="vendor_id[]"]').serialize();
			var supplier_id = $('input[name="supplier_id"]').val();
			var vCodes = [];
			var table = $('table#dt-vendors').DataTable();
			table.rows().nodes().to$().find('input[name="vendor_id[]"]:checked').each(function() {
				var code = $(this).val();
				vCodes.push(code);
				formData += '&vendor_id[]=' + encodeURIComponent(code);
			});

			if (vCodes.length === 0) {
				alertRequired('Please select atleast one vendor.')
				return
			}

			Swal.fire({
				title: 'Confirm!',
				text: 'Update supplier vendor.',
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
						text: 'Updating supplier vendor.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/update_consignor_account'); ?>",
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
											vendor_tag(supplier_id)
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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

		var dt_users = $("table#dt_users").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?= site_url('admins/fetch_users'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
			"columnDefs": [{
					"targets": [3, 4, 5],
					"className": "text-center",
				},
				{
					"targets": [1, 2, 3, 4, 5],
					"orderable": false
				}
			]
		});

		$('table#dt_users').on('click', 'i.action', function() {
			let [action, user_id] = this.id.split('|');
			console.log(action, user_id)

			if (action == 'update') {
				$('div#update_adminUser').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('div#update_adminUser').modal('show');

				$.ajax({
					url: "<?= site_url('admins/update_adminUser'); ?>",
					method: "POST",
					data: {
						user_id
					},
					beforeSend: function() {
						loader_circle('update_adminUser')
					},
					success: function(result) {
						$("div.update_adminUser").html(result);
					}
				});
			} else if (action == 'reset') {
				Swal.fire({
					title: 'Confirm!',
					text: 'Reset Admin user password.',
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
							text: 'Resseting user password.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/reset_password'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_users.ajax.reload();
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

			} else {
				Swal.fire({
					title: 'Confirm!',
					text: 'Update Admin user account status.',
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
							text: 'Updating user status.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						setTimeout(() => {
							$.ajax({
								url: "<?= site_url('admins/user_status'); ?>",
								type: "POST",
								data: {
									user_id
								},
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
												dt_users.ajax.reload();
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
			}
		});

		$('a.add_user_btn').click(function() {
			$('div#add_adminUser').modal({
				backdrop: 'static',
				keyboard: false
			});
			$('div#add_adminUser').modal('show');

			$.ajax({
				url: "<?= site_url('admins/add_adminUser'); ?>",
				beforeSend: function() {
					loader_circle('add_adminUser')
				},
				success: function(result) {
					$("div.add_adminUser").html(result);
				}
			});
		});

		$("form#create_admin_user").submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			Swal.fire({
				title: 'Confirm!',
				text: 'Add admin user account.',
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
						text: 'Adding admin user account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/submit_adminUser'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#add_adminUser").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_users.ajax.reload();
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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

		$("form#update_admin_user").submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			Swal.fire({
				title: 'Confirm!',
				text: 'Update admin user account.',
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
						text: 'Updating admin user account.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					setTimeout(() => {
						$.ajax({
							url: "<?= site_url('admins/submit_updated_userAdmin'); ?>",
							type: "POST",
							data: formData,
							success: function(data) {
								try {
									let response = JSON.parse(data);
									if (response.message === "success") {
										$("div#update_adminUser").modal("hide");
										Swal.fire({
											title: 'Success!',
											text: response.feedback,
											icon: 'success',
											iconColor: '#15ca20',
											confirmButtonText: 'Ok',
											confirmButtonColor: '#0d6efd',
											allowOutsideClick: false,
										}).then(() => {
											dt_users.ajax.reload();
										});
									} else if (response.message === "exist") {
										errorAlert(response.feedback)
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

	function add_details() {
		$("div#supplier_details").modal({
			backdrop: 'static',
			keyboard: false
		});
		$("div#supplier_details").modal("show");

		$.ajax({
			url: "<?= site_url('admins/add_supplier_details'); ?>",
			beforeSend: function() {
				loader_circle('supplier_details')
			},
			success: function(result) {
				$("div.supplier_details").html(result);
			}
		});
	}

	function add_account() {
		$("div#supplier_account").modal({
			backdrop: 'static',
			keyboard: false
		});
		$("div#supplier_account").modal("show");
		$.ajax({
			url: "<?= site_url('admins/add_supplier_account'); ?>",
			beforeSend: function() {
				loader_circle('supplier_account')
			},
			success: function(result) {
				$("div.supplier_account").html(result);
				$('.select2default').select2({
					dropdownParent: $('#supplier_account')
				});
			}
		});
	}

	function generateUsername() {
		var username = random_username(6);
		$("input[name = 'username']").val(username).parsley().validate();
	}

	function send_email() {
		alert('send email')
		var id = 1;
		$.ajax({
			method: "POST",
			data: {
				id: id
			},
			url: "<?php echo site_url('admins/send_email_user_account'); ?>",
			success: function(result) {
				console.log(result);
			}
		});
	}

	function setCutoff(i) {
		var supplier_id = $("input[name = 'supplier_id']").val();

		if (i == 0) {
			$('input[name="trigger"]').val('all');
			var count = $("input.cutoff").length;
			if ($('input.selected_0').is(':checked')) {
				$.ajax({
					url: "<?php echo site_url('admins/insertAll_com_cut'); ?>",
					method: "POST",
					data: {
						supplier_id
					},
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
									for (var x = 1; x <= count; x++) {
										if (!$('input.selected_' + x).is(':checked')) {
											$("input.selected_" + x).trigger("click");
										}
									}
									$('input[name="trigger"]').val('');
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
			} else {
				$.ajax({
					url: "<?php echo site_url('admins/deleteAll_com_cut'); ?>",
					method: "POST",
					data: {
						supplier_id
					},
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
									for (var x = 1; x <= count; x++) {
										if ($("input.selected_" + x).is(":checked")) {
											$("input.selected_" + x).trigger("click");
										}
									}
									$('input[name="trigger"]').val('');
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
			}
		} else {
			var trigger = $('input[name="trigger"]').val();
			if (!trigger) {
				if ($('input.selected_' + i).is(':checked')) {
					var statCut = $("input.selected_" + i).val();
					$.ajax({
						url: "<?php echo site_url('admins/insert_com_cut'); ?>",
						method: "POST",
						data: {
							supplier_id,
							statCut
						},
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
									})
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
				} else {
					var statCut = $("input.selected_" + i).val();
					$.ajax({
						url: "<?php echo site_url('admins/delete_com_cut'); ?>",
						method: "POST",
						data: {
							supplier_id,
							statCut
						},
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
									})
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
				}
			}
		}
	}

	function vendor_tag(supplier_id) {
		$.ajax({
			url: "<?= site_url('admins/edit_tag_vendor'); ?>",
			method: "POST",
			data: {
				supplier_id
			},
			beforeSend: function() {
				loader_circle('edit_tag_ac')
			},
			success: function(result) {
				$("div.edit_tag_ac").html(result);
				$('#dt-vendors').DataTable({
					"order": [],
					"columnDefs": [{
						"targets": [0],
						"orderable": false,
						"className": "text-center",
					}, {
						"targets": [1, 2],
						"orderable": false,
					}]
				});
			}
		});
	}

	function random_username(str_length) {

		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		// random string for username
		for (var i = 0; i < str_length; i++) {

			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}

		return text;
	}

	function random_password(str_length) {

		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		// random string for password
		for (var i = 0; i < str_length; i++) {

			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}

		return text;
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