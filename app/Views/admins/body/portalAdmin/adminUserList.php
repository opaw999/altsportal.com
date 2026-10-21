<style>
    ul.list-group a:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .list {
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
        <li class="breadcrumb-item"><a href="javascript:;">Portal Admin</a></li>
        <li class="breadcrumb-item active">Admin User</li>
    </ol>
    <h1 class="page-header">Portal Admin <small>(Admin User)</small></h1>
    <div class="panel panel-inverse">
        <div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
            <h4 class="panel-title">Admin User List Table</h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-success" onclick="addUserForm()"><i class="fa fa-plus"></i> Add User</a>
            </div>
        </div>
        <div class="panel-body">
            <table id="dt_adminUserList" class="table table-hover align-middle text-nowrap" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Server</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade show" id="addUserForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add User Account...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="addUserSubmit" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body addUserForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="editUserForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update User Account...</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="editUserSubmit" autocomplete="off" data-parsley-validate="true">
                <div class="modal-body editUserForm"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>