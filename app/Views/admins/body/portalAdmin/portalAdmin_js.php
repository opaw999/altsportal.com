<script type="text/javascript">
    $(document).ready(function() {

        $('#addUserForm').on('hidden.bs.modal', function() {
            $('div.addUserForm').html('')
        });
        $('#editUserForm').on('hidden.bs.modal', function() {
            $('div.editUserForm').html('')
        });

        $('#editContractForm').on('hidden.bs.modal', function() {
            $('div.editContractForm').html('')
        });

        $('#addPromorecordForm').on('hidden.bs.modal', function() {
            $('div.addPromorecordForm').html('')
        });

        adminUserList()

        $("form#addUserSubmit").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            Swal.fire({
                title: 'Confirm!',
                text: 'Add portal admin user account.',
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
                        text: 'Adding user accoount.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/userUpdate'); ?>",
                            type: "POST",
                            data: formData,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#addUserForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            adminUserList()
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
        })

        $("form#editUserSubmit").submit(function(e) {
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
                        text: 'Updating user account.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/userUpdate'); ?>",
                            type: "POST",
                            data: formData,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#editUserForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            adminUserList()
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

        $("form#editContractSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var str = $('input[name="stores[]"]:checked').length;
            console.log(str)
            if (str == 0) {
                errorAlert('Please select store(s)!')
                return;
            }

            Swal.fire({
                title: 'Confirm!',
                text: 'Update promo contract.',
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
                        text: 'Updating contract.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/updatePromoContract'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#editContractForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('admins/promoContracts'); ?>",
                                                data: {
                                                    emp_id: response.emp_id,
                                                    server: response.server
                                                },
                                                beforeSend: function() {
                                                    loader_circle('contracts')
                                                },
                                                success: function(data) {
                                                    $('div.contracts').html(data);
                                                }
                                            });
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
        })

        $("form#addCutoffSubmit").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            Swal.fire({
                title: 'Confirm!',
                text: 'Add promo cutoff.',
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
                        text: 'Adding cutoff.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/addCutoffSubmit'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#addCutoffForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('admins/promoContracts'); ?>",
                                                data: {
                                                    emp_id: response.emp_id,
                                                    server: response.server
                                                },
                                                beforeSend: function() {
                                                    loader_circle('contracts')
                                                },
                                                success: function(data) {
                                                    $('div.contracts').html(data);
                                                }
                                            });
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
        })

        $("form#addPromorecordSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var str = $('input[name="stores[]"]:checked').length;
            console.log(str)
            if (str == 0) {
                errorAlert('Please select store(s)!')
                return;
            }

            Swal.fire({
                title: 'Confirm!',
                text: 'Add promo record data.',
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
                        text: 'Adding promo record data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/addPromorecordSubmit'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#addPromorecordForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('admins/promoContracts'); ?>",
                                                data: {
                                                    emp_id: response.emp_id,
                                                    server: response.server
                                                },
                                                beforeSend: function() {
                                                    loader_circle('contracts')
                                                },
                                                success: function(data) {
                                                    $('div.contracts').html(data);
                                                }
                                            });
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
        })

        $("form#uploadPromorecordSubmit").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: 'Confirm!',
                text: 'Upload promo record data.',
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
                        text: 'Uploading promo record data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/uploadPromorecord'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#uploadPromorecordForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('admins/promoContracts'); ?>",
                                                data: {
                                                    emp_id: response.emp_id,
                                                    server: response.server
                                                },
                                                beforeSend: function() {
                                                    loader_circle('contracts')
                                                },
                                                success: function(data) {
                                                    $('div.contracts').html(data);
                                                }
                                            });
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
        })

        $("form#editAssignData").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            Swal.fire({
                title: 'Confirm!',
                text: 'Update assign data.',
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
                        text: 'Updating assign data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/editAssignData'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $("div#editAssignForm").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('admins/assignData'); ?>",
                                                data: {
                                                    emp_id: response.emp_id,
                                                    server: response.server
                                                },
                                                beforeSend: function() {
                                                    loader_circle('assign')
                                                },
                                                success: function(data) {
                                                    $('div.assign').html(data);
                                                }
                                            });
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
        })


    });

    function adminUserList() {
        $("table#dt_adminUserList").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('admins/portal_users'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                    "targets": [3, 5],
                    "className": "text-center",
                    "orderable": false,
                },
                {
                    "targets": [1, 2, 3, 4, 5],
                    "orderable": false,
                }
            ],
        });
    }

    function addUserForm() {
        $("div#addUserForm").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("div#addUserForm").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/add_user_form'); ?>",
            beforeSend: function() {
                loader_circle('addUserForm')
            },
            success: function(data) {
                $('div.addUserForm').html(data);
            }
        });
    }

    let ajaxCallCount = 0;
    let ajaxRequest;

    function employeeSearch(str) {
        if (str.trim() != '') {
            if (ajaxRequest && ajaxRequest.readyState !== 4) {
                ajaxRequest.abort();
            }

            ajaxCallCount++;

            ajaxRequest = $.ajax({
                type: "POST",
                url: "<?= site_url('admins/employeeSearch'); ?>",
                data: {
                    str
                },
                beforeSend: function() {
                    $(".dropdown-list").show()
                    $('.dropdown-list').html('<ul class="list-group"><span class="list-group-item">' +
                        '<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>' +
                        '</span></ul>');
                },
                success: function(data) {
                    $('.dropdown-list').show()
                    $('.dropdown-list').html(data);
                }
            });
        } else {
            $('.dropdown-list').hide()
        }
    }

    function selectEmployee(name) {
        $('input[name="fullname"]').val(name)
        $('.dropdown-list').hide()
    }

    function setServer(server_loc) {
        $('input[name="server"]').val(server_loc)

    }

    function userUpdate(process, user_no) {
        if (process != '' && user_no != '') {
            if (process == 'activate') {
                var msg1 = 'Activate user account.';
                var msg2 = 'Activating user account.';
            } else if (process == 'deactivate') {
                var msg1 = 'Deactivate user account.';
                var msg2 = 'Deactivating user account.';
            } else if (process == 'reset') {
                var msg1 = 'Reset user password.';
                var msg2 = 'Resetting user password.';
            } else if (process == 'remove') {
                var msg1 = 'Delete user account.';
                var msg2 = 'Deleting user account.';
            }

            if (process == 'edit') {
                $("div#editUserForm").modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $("div#editUserForm").modal("show");

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/edit_user_form'); ?>",
                    data: {
                        process,
                        user_no
                    },
                    beforeSend: function() {
                        loader_circle('editUserForm')
                    },
                    success: function(data) {
                        $('div.editUserForm').html(data)
                    }
                });

            } else {
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
                                url: "<?= site_url('admins/userUpdate'); ?>",
                                type: "POST",
                                data: {
                                    process,
                                    user_no
                                },
                                success: function(data) {
                                    try {
                                        let response = JSON.parse(data);
                                        if (response.message === "success") {
                                            // $("div#editUserForm").modal("hide");
                                            Swal.fire({
                                                title: 'Success!',
                                                text: response.feedback,
                                                icon: 'success',
                                                iconColor: '#15ca20',
                                                confirmButtonText: 'Ok',
                                                confirmButtonColor: '#0d6efd',
                                                allowOutsideClick: false,
                                            }).then(() => {
                                                adminUserList()
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


                // Swal.fire({
                //     title: 'Confirm!',
                //     text: msg1,
                //     icon: 'question',
                //     iconColor: '#ffc107',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes',
                // }).then((result) => {
                //     if (result.value) {
                //         $.ajax({
                //             type: "POST",
                //             url: "<?= site_url('admins/portalAdmin/userUpdate'); ?>",
                //             data: {
                //                 process,
                //                 user_no
                //             },
                //             beforeSend: function() {
                //                 Swal.fire({
                //                     text: msg2,
                //                     allowOutsideClick: false,
                //                     didOpen: () => {
                //                         Swal.showLoading();
                //                     }
                //                 });
                //             },
                //             success: function(data) {
                //                 let response = JSON.parse(data);
                //                 if (response.request == 'success') {
                //                     // $("div#editUserForm").modal("hide");
                //                     Swal.fire({
                //                         title: 'Success!',
                //                         text: msg3,
                //                         icon: 'success',
                //                         iconColor: '#15ca20',
                //                         confirmButtonColor: '#3085d6',
                //                         confirmButtonText: 'Ok',
                //                         allowOutsideClick: false,
                //                     }).then((result) => {
                //                         if (result.isConfirmed) {
                //                             // adminUserList()
                //                             window.location.reload();
                //                         }
                //                     });
                //                 } else if (response.request == 'exist') {
                //                     Swal.fire({
                //                         title: 'Duplicate!',
                //                         text: response.message,
                //                         icon: 'error',
                //                         confirmButtonText: 'Ok',
                //                     })
                //                 } else if (response.request == 'failed') {
                //                     alert(data)
                //                 }
                //             }
                //         });
                //     }
                // });
            }
        }
    }

    function selectServer(server) {
        $('div.contracts,div.assign,div.ppDay').html('');
        $('input[name="promoName"]').val('')
        $('.dropdown-list').hide()
    }

    function promoSearch(str) {
        $('div.contracts').html('');
        if (str.trim() != '') {
            var server = $('select[name="server"]').val();
            if (server.trim() != '') {

                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                ajaxCallCount++;

                ajaxRequest = $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/searchContract'); ?>",
                    data: {
                        str,
                        server
                    },
                    beforeSend: function() {
                        $(".dropdown-list").show()
                        $('.dropdown-list').html('<ul class="list-group"><span class="list-group-item">' +
                            '<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>' +
                            '</span></ul>');
                    },
                    success: function(data) {
                        $('.dropdown-list').show().html(data);
                    }
                });

            } else {
                errorAlert('Please select a Server!')
                $('input[name="promoName"]').val('')
                $('select[name="server"]').css("border-color", "#dd4b39");
                $('select[name="server"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {
            $('.dropdown-list').hide()
        }
    }

    function selectPromo(emp_id, name) {
        var server = $('select[name="server"]').val();
        $('input[name="promoName"]').val('[' + emp_id + '] ' + name)
        $('.dropdown-list').hide()
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/promoContracts'); ?>",
            data: {
                emp_id,
                server
            },
            beforeSend: function() {
                loader_circle('contracts')
            },
            success: function(data) {
                $('div.contracts').html(data);
            }
        });
    }

    function editContractForm(server, emp_id, record_no) {

        $("div#editContractForm").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("div#editContractForm").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/editContractForm'); ?>",
            data: {
                server,
                emp_id,
                record_no
            },
            beforeSend: function() {
                loader_circle('editContractForm')
            },
            success: function(data) {
                $('div.editContractForm').html(data);
                $(".datePicker").datepicker({
                    format: "yyyy-mm-dd",
                    changeYear: true,
                    changeMonth: true,

                });

                $('.select2default').select2({
                    allowClear: true,
                    dropdownParent: $('#editContractForm')
                });
            }
        });
    }

    function selectCompany(agency_code, server) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/selectCompany'); ?>",
            data: {
                agency_code,
                server
            },
            success: function(data) {
                $('select[name="promo_company"]').html(data);
            }
        });
    }

    function selectDepartment(server) {

        var bunit_id = $('input[name="stores[]"]:checked').map(function() {
            return this.id;
        }).get();
        console.log(bunit_id)
        if (bunit_id.length != 0) {
            $.ajax({
                type: "POST",
                url: "<?= site_url('admins/selectDepartment'); ?>",
                data: {
                    bunit_id,
                    server
                },
                success: function(data) {
                    $('select[name="promo_department"]').html(data);
                }
            });
        } else {
            $('select[name="promo_department"]').html('<option value="">---</option>');
        }
    }

    function addCutoffForm(server, emp_id, record_no) {

        $('div#addCutoffForm').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('div#addCutoffForm').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/addCutoffForm'); ?>",
            data: {
                server,
                emp_id,
                record_no
            },
            beforeSend: function() {
                loader_circle('addCutoffForm')
            },
            success: function(data) {
                $('div.addCutoffForm').html(data);
            }
        });
    }

    function addPromorecordForm(server, emp_id, record_no) {

        $('div#addPromorecordForm').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('div#addPromorecordForm').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/addPromorecordForm'); ?>",
            data: {
                server,
                emp_id,
                record_no
            },
            beforeSend: function() {
                loader_circle('addPromorecordForm')
            },
            success: function(data) {
                $('div.addPromorecordForm').html(data);
                $('.select2default').select2({
                    allowClear: true,
                    dropdownParent: $('#addPromorecordForm')
                });
            }
        });
    }

    function uploadPromorecordForm(server, emp_id, record_no) {
        $('div#uploadPromorecordForm').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('div#uploadPromorecordForm').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/uploadPromorecordForm'); ?>",
            data: {
                server,
                emp_id,
                record_no
            },
            beforeSend: function() {
                loader_circle('uploadPromorecordForm')
            },
            success: function(data) {
                $('div.uploadPromorecordForm').html(data);
            }
        });
    }

    function assignSearch(str) {
        $('div.assign').html('');
        if (str.trim() != '') {
            var server = $('select[name="server"]').val();
            if (server.trim() != '') {

                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                ajaxCallCount++;

                ajaxRequest = $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/assignSearch'); ?>",
                    data: {
                        str,
                        server
                    },
                    beforeSend: function() {
                        $(".dropdown-list").show()
                        $('.dropdown-list').html('<ul class="list-group"><span class="list-group-item">' +
                            '<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>' +
                            '</span></ul>');
                    },
                    success: function(data) {
                        $('.dropdown-list').show().html(data);
                    }
                });

            } else {
                errorAlert('Please select a Server!')
                $('input[name="promoName"]').val('')
                $('select[name="server"]').css("border-color", "#dd4b39");
                $('select[name="server"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {
            $('.dropdown-list').hide()
        }
    }

    function selectAssign(emp_id, name) {
        var server = $('select[name="server"]').val();
        $('input[name="promoName"]').val('[' + emp_id + '] ' + name)
        $('.dropdown-list').hide()
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/assignData'); ?>",
            data: {
                emp_id,
                server
            },
            beforeSend: function() {
                loader_circle('assign')
            },
            success: function(data) {
                $('div.assign').html(data);
            }
        });
    }

    function editAssignForm(server, empId, assignId) {
        console.log(server, empId, assignId)
        $("div#editAssignForm").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("div#editAssignForm").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/editAssignForm'); ?>",
            data: {
                server,
                empId,
                assignId
            },
            beforeSend: function() {
                loader_circle('editAssignForm')
            },
            success: function(data) {
                $('div.editAssignForm').html(data);

            }
        });
    }

    function updateAssignData(process, server, empId, assignId) {
        if (process == 'activate') {
            var msg1 = 'Activate assign data.';
            var msg2 = 'Activating assign data.';
        } else {
            var msg1 = 'Deactivate assign data?';
            var msg2 = 'Deactivating assign data.';
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
        }).then((result) => {
            if (result.value) {
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
                        url: "<?= site_url('admins/updateAssignData'); ?>",
                        type: "POST",
                        data: {
                            process,
                            server,
                            empId,
                            assignId
                        },
                        success: function(data) {
                            try {
                                let response = JSON.parse(data);
                                if (response.message === "success") {
                                    $("div#editAssignForm").modal("hide");
                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.feedback,
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonText: 'Ok',
                                        confirmButtonColor: '#0d6efd',
                                        allowOutsideClick: false,
                                    }).then(() => {
                                        $.ajax({
                                            type: "POST",
                                            url: "<?= site_url('admins/assignData'); ?>",
                                            data: {
                                                emp_id: empId,
                                                server: server
                                            },
                                            beforeSend: function() {
                                                loader_circle('assign')
                                            },
                                            success: function(data) {
                                                $('div.assign').html(data);
                                            }
                                        });
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

    function prevdtrSearch(str) {
        $('div.ppDay').html('');
        if (str.trim() != '') {
            var server = $('select[name="server"]').val();
            if (server.trim() != '') {

                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                ajaxCallCount++;

                ajaxRequest = $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/prevdtrSearch'); ?>",
                    data: {
                        str,
                        server
                    },
                    beforeSend: function() {
                        $(".dropdown-list").show()
                        $('.dropdown-list').html('<ul class="list-group"><span class="list-group-item">' +
                            '<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>' +
                            '</span></ul>');
                    },
                    success: function(data) {
                        $('.dropdown-list').show().html(data);
                    }
                });

            } else {
                errorAlert('Please select a Server!')
                $('input[name="promoName"]').val('')
                $('select[name="server"]').css("border-color", "#dd4b39");
                $('select[name="server"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {
            $('.dropdown-list').hide()
        }
    }

    function selectPrev(empId, name) {

        var server = $('select[name="server"]').val();
        $('input[name="promoName"]').val('[' + empId + '] ' + name)
        $('.dropdown-list').hide()

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/prevperDay'); ?>",
            data: {
                empId,
                server
            },
            beforeSend: function() {
                loader_circle('ppDay')
            },
            success: function(data) {
                $('div.ppDay').html(data);
            }
        });
    }

    function ppDayUpdate(process, server, empId, ppId) {
        if (process == 'deactivate') {
            var msg1 = 'Move Perday Previous data to Inactive.';
            var msg2 = 'Moving data to Inactive.';
        } else {
            var msg1 = 'Move Perday Previous data to Active.';
            var msg2 = 'Moving data to Active.';
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
        }).then((result) => {
            if (result.value) {
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
                        url: "<?= site_url('admins/ppDayUpdate'); ?>",
                        type: "POST",
                        data: {
                            process,
                            server,
                            empId,
                            ppId
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
                                        $.ajax({
                                            type: "POST",
                                            url: "<?= site_url('admins/prevperDay'); ?>",
                                            data: {
                                                empId,
                                                server
                                            },
                                            beforeSend: function() {
                                                loader_circle('ppDay')
                                            },
                                            success: function(data) {
                                                $('div.ppDay').html(data);
                                            }
                                        });
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