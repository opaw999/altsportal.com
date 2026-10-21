<script>
    $(document).ready(function() {

        $('#show_entry').on('hidden.bs.modal', function() {
            $('body').addClass('modal-open');
        });
        $('#requestDetails').on('hidden.bs.modal', function() {
            $('body').addClass('modal-open');
        });
    });

    function setServer(server) {
        $('div.masterfileData').html('');
        $('div.dtrCutoff,div.schedules').html('');
        $('input[name="dtr"],input[name="masterfile"]').val('')
        $('.dropdown-list').hide()
    }

    let ajaxCallCount = 0;
    let ajaxRequest;

    function searchPromo(str) {
        $('div.masterfileData').html('');
        $('div.schedules').html('');
        if (str.trim() != '') {
            var server = $('select[name="server"]').val();
            if (server.trim() != '') {

                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                ajaxCallCount++;

                ajaxRequest = $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/searchPromo'); ?>",
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
                        $('.dropdown-list').show()
                        $('.dropdown-list').html(data);
                    }
                });
            } else {
                errorAlert('Please select a Server!')
                $('input[name="masterfile"]').val('')
                $('select[name="server"]').css("border-color", "#dd4b39");
                $('select[name="server"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {
            $(".dropdown-list").hide();
        }
    }

    function selectPromo(value) {
        var server = $('select[name="server"]').val();
        var [name, emp_id, record_no] = value.split('|');
        $('input[name="masterfile"]').val(name)
        $('.dropdown-list').hide()
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/promoData'); ?>",
            data: {
                emp_id,
                record_no,
                server
            },
            beforeSend: function() {
                loader_circle('masterfileData')
            },
            success: function(data) {
                $('div.masterfileData').html(data);
                getSchedules(emp_id, server)
            }
        });
    }

    function getSchedules(emp_id, server) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/multSched'); ?>",
            data: {
                emp_id,
                server
            },
            beforeSend: function() {
                loader_circle('schedules')
            },
            success: function(data) {
                $('div.schedules').html(data);
                $('#multScheds').DataTable({
                    "ordering": false,
                    "pagingType": "simple",
                    "pagingControl": true,
                    "columnDefs": [{
                        "targets": [1, 3, 4, 6],
                        "className": "text-center"

                    }]
                });
            }
        });
    }

    function shiftSched(emp_id) {
        $('div#shiftSched').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#shiftSched").modal("show");
        var server = $('select[name="server"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/promoDefSched'); ?>",
            data: {
                emp_id,
                server
            },
            beforeSend: function() {
                loader_circle('shiftSched')
            },
            success: function(data) {
                $('div.shiftSched').html(data);
                $('#promoDefSched').DataTable({
                    "ordering": false,
                    "pageLength": 7,
                    "lengthMenu": [7],
                    "columnDefs": [{
                        "targets": [1],
                        "className": "text-center"
                    }]
                });
            }
        });
    }

    function viewSched(emp_id, record_no, promo_type, month, year) {
        $('div#viewSched').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewSched").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/viewSched'); ?>",
            data: {
                emp_id,
                record_no,
                promo_type,
                month,
                year
            },
            beforeSend: function() {
                loader_circle('viewSched')
            },
            success: function(data) {
                $('div.viewSched').html(data);
                view_sched(emp_id, record_no, promo_type, month, year);
            }
        });
    }

    function view_sched(emp_id, record_no, promo_type, month, year) {
        var server = $('select[name="server"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/viewSchedPromo'); ?>",
            data: {
                emp_id,
                record_no,
                promo_type,
                month,
                year,
                server
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

    function month_n(datas) {

        var m_ = $("select[name = 'month_']").val();
        var y_ = $("select[name = 'year_']").val();
        view_sched2(m_, y_, datas);
    }

    function year_n(datas) {

        var m_ = $("select[name = 'month_']").val();
        var y_ = $("select[name = 'year_']").val();
        view_sched2(m_, y_, datas);
    }

    function view_sched2(month, year, data) {
        var server = $('select[name="server"]').val();
        var [emp_id, record_no, promo_type] = data.split('*');
        console.log(month, year)
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/viewSched'); ?>",
            data: {
                emp_id,
                record_no,
                promo_type,
                month,
                year,
                server
            },
            beforeSend: function() {
                loader_circle('viewSched')
            },
            success: function(data) {
                $('div.viewSched').html(data);
                view_sched(emp_id, record_no, promo_type, month, year);
            }
        });
    }

    function viewDayoff(emp_id, amsId) {
        $('div#dayoff').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#dayoff").modal("show");
        var server = $('select[name="server"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/dayoff'); ?>",
            data: {
                emp_id,
                amsId,
                server
            },
            beforeSend: function() {
                loader_circle('dayoff')
            },
            success: function(data) {
                $('div.dayoff').html(data);

            }
        });
    }

    function searchDtr(str) {
        $('div.masterfileData').html('');
        $('div.dtrCutoff').html('');
        if (str.trim() != '') {
            var server = $('select[name="server"]').val();
            if (server.trim() != '') {
                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                ajaxCallCount++;

                ajaxRequest = $.ajax({
                    type: "POST",
                    url: "<?= site_url('admins/dtrSearch'); ?>",
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
                        $('.dropdown-list').show()
                        $('.dropdown-list').html(data);
                    }
                });
            } else {
                errorAlert('Please select a Server!')
                $('input[name="dtr"]').val('')
                $('select[name="server"]').css("border-color", "#dd4b39");
                $('select[name="server"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {
            $(".dropdown-list").hide();
        }
    }

    function selectDtr(value) {
        var server = $('select[name="server"]').val();
        var [name, emp_id, record_no, statCut] = value.split('|');
        $('input[name="dtr"]').val(name)
        $('.dropdown-list').hide()
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/promoData'); ?>",
            data: {
                emp_id,
                record_no,
                server
            },
            beforeSend: function() {
                loader_circle('masterfileData')
            },
            success: function(data) {
                $('div.masterfileData').html(data);
                getDtrCutoff(emp_id, record_no, statCut, server)
            }
        });
    }

    function getDtrCutoff(emp_id, record_no, statCut, server) {
        console.log(emp_id, record_no, statCut)
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/dtrCutoff'); ?>",
            data: {
                emp_id,
                record_no,
                statCut,
                server
            },
            beforeSend: function() {
                loader_circle('dtrCutoff')
            },
            success: function(data) {
                $('div.dtrCutoff').html(data);
                $("#dtrCutoffs").dataTable({
                    "destroy": true,
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
        });
    }

    function viewDTR(empId, recordNo, dateFrom, dateTo, bioM, i, statCut, server) {
        $('div#promoDtr').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $('div#promoDtr').modal('show');
        $('span#cutoff').html(dateFrom + ' - ' + dateTo);
        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/promoDtr'); ?>",
            data: {
                empId,
                recordNo,
                dateFrom,
                dateTo,
                bioM,
                i,
                statCut,
                server
            },
            beforeSend: function() {
                loader_circle('promoDtr')
            },
            success: function(data) {
                $('div.promoDtr').html(data);
                $('input[name="empId"]').val(empId);
                $('input[name="recordNo"]').val(recordNo);
                $('input[name="dateFrom"]').val(dateFrom);
                $('input[name="dateTo"]').val(dateTo);
                $('input[name="bioM"]').val(bioM);
                $('input[name="cut"]').val(i);
                $('input[name="statCut"]').val(statCut);
                $('input[name="server"]').val(server);

            },
        });
    }

    function viewTime(server, empId, dateRendered) {
        $('div#show_entry').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $('#show_entry').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('admins/vTEntry'); ?>",
            data: {
                server,
                empId,
                dateRendered
            },
            beforeSend: function() {
                loader_circle('show_entry')
            },
            success: function(data) {
                $("div.show_entry").html(data);
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
            url: "<?= site_url('admins/viewRequest'); ?>",
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