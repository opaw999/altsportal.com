<div id="content" class="app-content">
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
        <li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
        <li class="breadcrumb-item active">Consignor (User's Account) </li>
    </ol>
    <h1 class="page-header">Consignor <small>(User's Account)</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="javascript:;" class="btn btn-primary" onclick="add_account()">Create Account</a>
            <!-- <button class="btn btn-green ms-2 generate_excel"><i class="fa fa-file-excel"></i> Generate Excel</button> -->
        </div>
        <div class="panel-body table-responsive">
            <table id="dt_consignor_account" class="table table-bordered table-hover align-middle" width="100%">
                <thead>
                    <tr>
                        <th width="35%">Consignor</th>
                        <th width="15%">Username</th>
                        <th>Contract Date</th>
                        <th>Status</th>
                        <th>isLogin</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- #modal-dialog -->
<div class="modal fade" id="supplier_account">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Supplier Account Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="consignor_account_form" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body supplier_account"> </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="renew_contract">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Renew Contract</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="renew_contract_form" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body renew_contract"> </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_tag_ac">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Supplier (Vendors)</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="edit_tag_vendor_form">
                <div class="modal-body edit_tag_ac"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="supplier_user_details">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Supplier Account Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body supplier_user_details"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
    function setup_supplier(id) {
        $.ajax({
            url: "<?= site_url('admins/setup_consignor'); ?>",
            method: "POST",
            data: {
                id
            },
            beforeSend: function() {
                loader_circle('setup_supplier')
            },
            success: function(result) {
                $("div.setup_supplier").html(result);
                $('#dt-vendors').DataTable({
                    "order": [2, "asc"],
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                        "className": "text-center",
                    }]
                });

                $('.datePicker').attr('placeholder', 'mm/dd/yyyy')
                $('.datePicker').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose: true
                });

                var dateToday = new Date();
                $('#dateFrom').datepicker('setStartDate', dateToday).on('changeDate', function(e) {
                    var selectedDate = $(this).val();
                    $('#dateTo').val('').datepicker('setStartDate', selectedDate);
                    $(this).parsley().validate();
                });

                $('#dateTo').datepicker().on('changeDate', function(e) {
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
    }
</script>