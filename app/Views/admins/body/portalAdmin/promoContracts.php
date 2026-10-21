<style>
    ul.list-group a:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .names {
        position: absolute;
        z-index: 999;
        max-height: 200px;
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
        <li class="breadcrumb-item"><a href="javascript:;">Portal Admin</a></li>
        <li class="breadcrumb-item active">Promo Contracts</li>
    </ol>
    <h1 class="page-header">Portal Admin <small>(Promo Contracts)</small></h1>
    <div class="panel panel-inverse">
        <div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
            <h4 class="panel-title">Promo Contract List</h4>
            <!-- <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-success" onclick="uploadContract()"><i class="fa fa-plus"></i> Upload Contract</a>
            </div> -->
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fa fa-server"></i></div>
                        <select name="server" class="form-select" onchange="selectServer(this.value)">
                            <option value="">Select Server</option>
                            <option value="tagbilaran">Tagbilaran Server</option>
                            <option value="talibon">Talibon Server</option>
                            <option value="tubigon">Tubigon Server</option>
                            <option value="cebu">Cebu Server</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 searchContainer">
                    <div class="input-group">
                        <div class="input-group-text"><i class="fa fa-users"></i></div>
                        <input type="text" class="form-control" name="promoName" placeholder="Search Name/EmployeeID here..." autocomplete="off" onkeyup="promoSearch(this.value, this.name)">
                    </div>
                    <div class="dropdown-list names"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 contracts"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="editContractForm">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Contract Data...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="editContractSubmit" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body editContractForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="addCutoffForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add statCut data...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="addCutoffSubmit" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body addCutoffForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="addPromorecordForm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Promo Record data...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="addPromorecordSubmit" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body addPromorecordForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="uploadPromorecordForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Promo Record data...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="uploadPromorecordSubmit" autocomplete="off" data-parsley-validate="true" enctype="multipart/form-data">
                <div class="modal-body uploadPromorecordForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="modal fade show" id="uploadContractForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Promo Contract data...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="uploadPromorecordSubmit" autocomplete="off" data-parsley-validate="true" enctype="multipart/form-data">
                <div class="modal-body uploadContractForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div> -->