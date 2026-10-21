<style>
    ul.list-group a:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .masterfile {
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
        <li class="breadcrumb-item active">Masterfile</li>
    </ol>
    <h1 class="page-header">Promo Data <small>(Masterfile)</small></h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">Masterfile</div>
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
                        <input type="text" class="form-control" name="masterfile" placeholder="Search Name/EmployeeID here..." autocomplete="off" onkeyup="searchPromo(this.value, this.name)">
                    </div>

                    <div class="dropdown-list masterfile"></div>
                    <div class="row">
                        <div class="col-md-12 masterfileData"></div>
                    </div>
                </div>
                <div class="col-md-8 schedules"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="dayoff">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Assigned Day Off/No Duty</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body dayoff">

            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="viewSched">
    <div class="modal-dialog modal-lg">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Schedule</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body shiftSched"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>