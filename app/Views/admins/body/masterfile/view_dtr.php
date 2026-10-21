<style>
    ul.list-group a:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .dtr {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .font_size {
        font-size: 80%;
    }

    .font_size3 {
        font-size: 90%;
    }

    .font_size2 {
        font-size: 75%;
    }
</style>
<div id="content" class="app-content">
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
        <li class="breadcrumb-item"><a href="javascript:;">Promo Data</a></li>
        <li class="breadcrumb-item active">DTR</li>
    </ol>
    <h1 class="page-header">Promo Data <small>(Daily Time Record)</small></h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">DTR</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 searchContainer">
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fa fa-server"></i></div>
                        <select name="server" class="form-select" onchange="setServer(this.value)">
                            <option value="">Select Server</option>
                            <option value="tagbilaran">Tagbilaran Server</option>
                            <option value="talibon">Talibon Server</option>
                            <option value="tubigon">Tubigon Server</option>
                            <option value="cebu">Cebu Server</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text"><i class="fa fa-users"></i></div>
                        <input type="text" class="form-control" name="dtr" placeholder="Search Name/EmployeeID here..." autocomplete="off" onkeyup="searchDtr(this.value, this.name)">
                    </div>

                    <div class="dropdown-list dtr"></div>
                    <div class="row">
                        <div class="col-md-12 masterfileData"></div>
                    </div>
                </div>
                <div class="col-md-8 dtrCutoff"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="promoDtr">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Daily Time Record <b>( <span id="cutoff"></span> )</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body promoDtr"></div>
            <form id="data_print_dtr" action="<?= base_url('admins/print_dtr') ?>" method="get" target="_blank">
                <div id="dtrData">
                    <input type="hidden" name="empId" value="">
                    <input type="hidden" name="recordNo" value="">
                    <input type="hidden" name="dateFrom" value="">
                    <input type="hidden" name="dateTo" value="">
                    <input type="hidden" name="cut" value="">
                    <input type="hidden" name="server" value="">
                    <input type="hidden" name="statCut" value="">
                    <input type="hidden" name="bioM" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="print_dtr">Print to PDF</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="viewSched">
    <div class="modal-dialog modal-lg" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Schedule</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body viewSched"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="shiftSched">
    <div class="modal-dialog modal-lg" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Shift Schedule</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body shiftSched"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="show_entry" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Time Entry</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body show_entry"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="requestDetails">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Request Details...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body requestDetails"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>