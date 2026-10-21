<style>
    .count_badge {
        position: absolute;
        top: 30px;
        display: block;
        background: var(--bs-app-theme);
        line-height: 12px;
        font-weight: 600;
        color: var(--bs-app-theme-color);
        padding: 3px 6px;
        font-size: 10.5px;
        border-radius: 30px;
        inset-inline-end: -5px;
    }
</style>

<div id="content" class="app-content">
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
        <li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
        <li class="breadcrumb-item active">Upload File</small></li>
    </ol>
    <h1 class="page-header">Violation <small>(Upload File)</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading ">List of Cut-offs</div>
        <div class="panel-body">
            <div class="btn-group w-100 d-flex justify-content-center mb-3">
                <?php
                $i = 1;
                foreach ($cutoffs as $row) {

                    if ($row['endFC'] == '') {
                        $endFC = 'Last';
                    } else {
                        $endFC = $row['endFC'];
                    }

                    $statCut = $row['statCut'];
                    $cutOff = $row['startFC'] . ' - ' . $endFC . ' / ' . $row['startSC'] . ' - ' . $row['endSC'];
                    if ($i == 1) {
                        $class = 'btn btn-primary cutoff btn-sm active';
                    } else {
                        $class = 'btn btn-secondary cutoff btn-sm';
                    }
                    echo '<a href="javascript:;" id="' . $statCut . '" class="' . $class . '">' . $cutOff . '</a>';
                    $i++;
                }
                ?>
            </div>
            <table id="dt_dtr" class="table table-bordered table-hover align-middle" width="100%">
                <thead>
                    <tr>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="view_employee">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Employee's Violation <b>( <span id="emp_cutoff"></span> )</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body view_employee">
                <div class="table-responsive">
                    <table id="dt_sup_violation_list" class="table table-bordered table-hover table-sm" width="100%">
                        <thead>
                            <tr>
                                <th> Company</th>
                                <th> Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload_violation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Employee's Violation <b>( <span id="emp_cutoff"></span> )</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="upload_pv" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body upload_violation"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary uploadViolation">Upload File(s)</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="view_violation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Employee's Violation <b>( <span id="emp_cutoff"></span> )</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="delete_pv" action="" method="post">
                <div class="modal-body view_violation"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger delete_violation">Delete Uploaded File</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>