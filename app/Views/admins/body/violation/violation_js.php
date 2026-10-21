<script type="text/javascript">
    $(document).ready(function() {

        var cutOff = $('a.btn.btn-primary.cutoff.active').attr('id');
        dt_dtr(cutOff)

        $("a.cutoff").click(function() {
            $('a.cutoff').removeClass('btn-primary active').addClass('btn-secondary');
            $(this).removeClass('btn-secondary').addClass('btn-primary active');
            var cutOff = this.id;
            dt_dtr(cutOff)
        });

        $('table#dt_dtr').on('click', 'a.record', function() {
            $('#view_employee').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#view_employee').modal('show');

            let [cutoff, dateFrom, dateTo] = this.id.split('|');
            company_list(cutoff, dateFrom, dateTo);
            $("span#emp_cutoff").html(dateFrom + ' - ' + dateTo);
        });

        $("form#upload_pv").submit(function(e) {
            e.preventDefault();
            $("button.uploadViolation").prop('disabled', true);
            var formData = new FormData(this);

            Swal.fire({
                title: 'Confirm!',
                text: 'Upload violation(s).',
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
                        text: 'Uploading violations.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/upload_violation'); ?>",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $('button.uploadViolation').prop('disabled', false);
                                        $('div#upload_violation').modal('hide');
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            company_list(response.cutoff, response.dateFrom, response.dateTo)
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

        $("form#delete_pv").submit(function(e) {
            e.preventDefault();
            $("button.delete_violation").prop('disabled', true);
            let formData = $(this).serialize();
            Swal.fire({
                title: 'Confirm!',
                text: 'Delete uploaded violation.',
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
                        text: 'Deleting violation.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        $.ajax({
                            url: "<?= site_url('admins/delete_violation'); ?>",
                            type: "POST",
                            data: formData,
                            success: function(data) {
                                try {
                                    let response = JSON.parse(data);
                                    if (response.message === "success") {
                                        $('button.delete_violation').prop('disabled', false);
                                        $('div#view_violation').modal('hide');
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.feedback,
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            allowOutsideClick: false,
                                        }).then(() => {
                                            company_list(response.cutoff, response.dateFrom, response.dateTo)
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

        $('#upload_violation, #view_violation').on('hidden.bs.modal', function() {
            $('body').addClass('modal-open');
        });

    });

    function dt_dtr(cutoff) {
        $('#dt_dtr').DataTable({
            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('admins/display_cutoff'); ?>",
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

    function company_list(cutoff, dateFrom, dateTo) {
        $('#dt_sup_violation_list').DataTable({
            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('admins/view_company_list'); ?>",
                type: "POST",
                data: {
                    cutoff,
                    dateFrom,
                    dateTo
                },
            },
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center"
            }]
        });
    }

    function uploadViolations(pc_code, cutoff, dateFrom, dateTo) {
        console.log(pc_code, cutoff, dateFrom, dateTo);
        $('#upload_violation').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#upload_violation').modal('show');

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('admins/upload_violation_form'); ?>",
            data: {
                pc_code,
                dateFrom,
                dateTo,
                cutoff
            },
            beforeSend: function() {
                loader_circle('upload_violation')
            },
            success: function(data) {
                $("div.upload_violation").html(data);
            }
        });
    }

    function viewViolations(company, cutoff, dateFrom, dateTo) {
        console.log(company, cutoff, dateFrom, dateTo);
        $('#view_violation').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#view_violation').modal('show');

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('admins/view_violation'); ?>",
            data: {
                company,
                cutoff,
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

    function view_image(id) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/view_image'); ?>",
            data: {
                id
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