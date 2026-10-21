<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Portal extends BaseController
{
    public function portal_users()
    {
        $data = [];
        $query = $this->portal_model->admin_user();
        foreach ($query as $row) {

            $user_no = $row['user_no'];
            $name = ucwords(strtolower($row['fullname']));
            if ($row['user_type'] == 'superadmin') {
                $name = $name . ' <i class="fas fa-lg fa-fw fa-chess-rook" title="' . $row['user_type'] . '"></i>';
            }
            if ($row['user_type'] == 'admin') {
                $name = $name . ' <i class="fas fa-lg fa-fw fa-chess-pawn" title="' . $row['user_type'] . '"></i>';
            }

            $statClass  = ($row['status'] == 'active') ? 'bg-green' : 'bg-danger';
            $status     = '<span class="badge ' . $statClass . '">' . ucfirst($row['status']) . '</span>';
            $updClass   = ($row['status'] == 'active') ? 'btn-icon btn-dark' : 'btn-icon btn-warning';
            $updIcon    = ($row['status'] == 'active') ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
            $updStatus  = ($row['status'] == 'active') ? 'deactivate' : 'activate';

            $action = '<a href="javascript:;" title="' . $updStatus . '" class="btn btn-sm ' . $updClass . '" onclick="userUpdate(\'' . $updStatus . '\',\'' . $user_no . '\')">' . $updIcon . '</a>';
            $action .= '<a href="javascript:;" title="reset password" class="btn btn-sm btn-icon btn-success ms-1" onclick="userUpdate(\'reset\',\'' . $user_no . '\')"><i class="fa fa-redo"></i></a>';
            $action .= '<a href="javascript:;" title="edit user" class="btn btn-sm btn-icon btn-primary ms-1"  onclick="userUpdate(\'edit\',\'' . $user_no . '\')"><i class="fa fa-pen"></i></a>';
            $action .= '<a href="javascript:;" title="remove user" class="btn btn-sm btn-icon btn-danger ms-1"  onclick="userUpdate(\'remove\',\'' . $user_no . '\')"><i class="fa fa-trash"></i></a>';

            $sub_array = [];
            $sub_array[] = $name;
            $sub_array[] = $row['username'];
            $sub_array[] = $row['server_loc'];
            $sub_array[] = $status;
            $sub_array[] = date('M. d, Y', strtotime($row['date_added']));
            $sub_array[] = '<div class="panel-heading-btn" style="float:none;">' . $action . '</div>';
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function add_user_form()
    {
        echo '<div class="row">';
        echo '<input type="hidden" name="process" value="add">';
        echo '<div class="col-md-12 mb-3 searchContainer">
                <label class="form-label">Fullname</label>
                <input type="text" name="fullname" class="form-control" onkeyup="employeeSearch(this.value, this.name)" placeholder="Search Name/EmployeeID here..." required>
                <div class="dropdown-list list"></div>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="user_name" value="" required>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Password (Default: Hrms2014)</label>
                <input type="password" name="password" class="form-control" placeholder="password" value="Hrms2014" readonly>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="">Select User Type...</option>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
                </select>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="active" readonly>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Server Location</label>
                <select name="server_loc" class="form-select" onchange="setServer(this.value)" required>';
        $servers = $this->portal_model->portal_servers();
        foreach ($servers as $key => $row) {
            echo '<option value="' . $key . '">' . $row . '</option>';
        }

        echo '</select>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Server</label>
                <input type="text" name="server" value="server_tag" class="form-control" readonly>';
        echo '</div>';
        echo '<div class="col-md-12 mt-1 mb-0">
                <h5>Menu Access</h5>';
        echo '</div>';

        $menus = $this->portal_model->user_access_menu();
        foreach ($menus as $key => $val) {
            echo '<div class="col-md-4  mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menuAccess[]" value="' . $key . '" id="' . $key . '">
                        <label class="form-check-label" for="' . $key . '">' . $val . '</label>
                    </div>';
            echo '</div>';
        }
        echo '</div>';
    }

    public function edit_user_form()
    {
        $input      = $this->request->getPost();
        $process    = $input['process'];
        $user_no    = $input['user_no'];
        $server2    = 'tk_corp';
        $table      = 'admin_user';

        $cond       = ['user_no' => $user_no];
        $user       = $this->portal_model->getRowArray($server2, $table, $cond);
        $fullname   = $user['fullname'];
        $username   = $user['username'];
        $access     = $user['access_menu'];
        $server     = $user['server'];
        $userType   = $user['user_type'];
        $access     = explode('|', $access);

        echo '<div class="row">';
        echo '<input type="hidden" name="user_no" value="' . $user_no . '">';
        echo '<input type="hidden" name="process" value="' . $process . '">';
        echo '<div class="col-md-12 mb-3" style="position: relative;">
                <label class="form-label">Fullname</label>
                <input type="text" name="fullname" class="form-control" value="' . $fullname . '" onkeyup="employeeSearch(this.value, this.name)" placeholder="Search Name/EmployeeID here..." required>
                <div class="dropdown-list list"></div>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="' . $username . '" placeholder="username" value="" required>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="">Select User Type...</option>
                    <option value="admin" ' . ($userType == 'admin' ? 'selected' : '') . '>Admin</option>
    				<option value="superadmin" ' . ($userType == 'superadmin' ? 'selected' : '') . '>Super Admin</option>
                </select>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="active" readonly>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Server Location</label>
                <select name="server_loc" class="form-select" onchange="setServer(this.value)" required>';
        $servers = $this->portal_model->portal_servers();
        foreach ($servers as $key => $row) {
            $sel = ($key == $server) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $sel . '>' . $row . '</option>';
        }

        echo '</select>';
        echo '</div>';
        echo '<div class="col-md-6 mb-3">
                <label class="form-label">Server</label>
                <input type="text" name="server" value="' . $server . '" class="form-control" readonly>';
        echo '</div>';
        echo '<div class="col-md-12 mt-1 mb-0">
                <h5>Menu Access</h5>';
        echo '</div>';

        $menus = $this->portal_model->user_access_menu();
        foreach ($menus as $key => $val) {
            $chk = (in_array($key, $access)) ? 'checked' : '';
            echo '<div class="col-md-4">
                    <div class="form-check mb-2">
						<input class="form-check-input" type="checkbox" name="menuAccess[]" value="' . $key . '" id="' . $key . '" ' . $chk . '>
						<label class="form-check-label" for="' . $key . '">
							' . $val . '
						</label>
					</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    public function employeeSearch()
    {
        $input  = $this->request->getPost();
        $string = $input['str'];
        $server = 'pis';
        $table  = 'employee3';
        $query  = $this->portal_model->userAdmin($server, $table, $string);
        echo '<ul class="list-group">';
        if (count($query) != 0) {
            foreach ($query as $row) {
                $name = htmlspecialchars($row['name']);
                echo '<a href="javascript:;" class="list-group-item" onclick="selectEmployee(\'' . $name . '\')">' . $name . '</a>';
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
        }
        echo '</ul>';
    }

    public function userUpdate()
    {
        $input      = $this->request->getPost();
        $process    = $input['process'];
        $server2    = 'tk_corp';
        $table      = 'admin_user';

        if ($process != 'add') {
            $user_no = $input['user_no'];
        }

        if ($process == 'add') {
            $cond = ['username' => $input['username']];
            $check = $this->portal_model->getRowArray($server2, $table, $cond);
            if ($check) {
                echo json_encode(['message' => 'exist', 'feedback' => 'Admin username already exist.']);
            } else {
                $menu = '';
                if (isset($input['menuAccess'])) {
                    $menu = implode('|', $input['menuAccess']);
                }

                $servers = $this->portal_model->portal_servers();
                $server_loc = $servers[$input['server_loc']];

                $data = array(
                    'fullname'      => $input['fullname'],
                    'username'      => $input['username'],
                    'password'      => password_hash('Hrms2014', PASSWORD_DEFAULT),
                    'server'        => $input['server'],
                    'server_loc'    => $server_loc,
                    'user_type'     => $input['user_type'],
                    'status'        => $input['status'],
                    'access_menu'   => $menu,
                    'date_added'    => date('Y-m-d H:i:s')
                );
                $insert = $this->portal_model->insertData($server2, $table, $data);
                if ($insert) {
                    echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been added.']);
                } else {
                    echo json_encode(['message' => 'failed']);
                }
            }
        } else if ($process == 'edit') {
            $cond = ['user_no !=' => $user_no, 'username' => $input['username']];
            $check = $this->portal_model->getRowArray($server2, $table, $cond);
            if ($check) {
                echo json_encode(['message' => 'exist', 'feedback' => 'Admin username already exist.']);
            } else {

                $servers    = $this->portal_model->portal_servers();
                $server_loc = $servers[$input['server_loc']];

                $menu = '';
                if (isset($input['menuAccess'])) {
                    $menu = implode('|', $input['menuAccess']);
                }

                $data = array(
                    'fullname'      => $input['fullname'],
                    'username'      => $input['username'],
                    'server'        => $input['server'],
                    'server_loc'    => $server_loc,
                    'user_type'     => $input['user_type'],
                    'access_menu'   => $menu,
                    'date_updated'  => date('Y-m-d H:i:s')
                );

                $cond = ['user_no' => $user_no];
                $update = $this->portal_model->updateData($server2, $table, $cond, $data);
                if ($update) {
                    echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been updated.']);
                } else {
                    echo json_encode(['message' => 'failed']);
                }
            }
        } else if ($process == 'reset') {

            $data   = ['password' => password_hash('Hrms2014', PASSWORD_DEFAULT)];
            $cond   = ['user_no' => $user_no];
            $update = $this->portal_model->updateData($server2, $table, $cond, $data);
            if ($update) {
                echo json_encode(['message' => 'success', 'feedback' => 'Admin user password has been reset.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        } else if ($process == 'remove') {
            $cond   = ['user_no' => $user_no];
            $delete = $this->portal_model->deleteData($server2, $table, $cond);
            if ($delete) {
                echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been deleted.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        } else if ($process == 'activate' || $process == 'deactivate') {

            $status = ($process == 'activate') ? 'active' : 'inactive';
            $data   = ['status' => $status];
            $cond   = ['user_no' => $user_no];
            $update = $this->portal_model->updateData($server2, $table, $cond, $data);
            if ($update) {
                echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been ' . $process . 'd.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function searchContract()
    {
        $input      = $this->request->getPost();
        $string     = $input['str'];
        $getServers = getServers($input['server']);
        $pis        = $getServers['pis'];

        echo '<ul class="list-group">';
        $query = $this->masterfile_model->searchPromo($pis, $string);
        if ($query) {
            foreach ($query as $row) {
                $empId  = $row['emp_id'];
                $cond   = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
                $emp    = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);
                $name   = htmlspecialchars($emp['name']);
                $click  = 'onclick="selectPromo(\'' . $empId . '\',\'' . $name . '\')"';
                echo '<a href="javascript:;" class="list-group-item" ' . $click . '>' . $name . '</a>';
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
        }
        echo '</ul>';
    }

    public function promoContracts()
    {
        $input      = $this->request->getPost();
        $empId      = $input['emp_id'];
        $getServers = getServers($input['server']);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        echo '<table class="table table-bordered">';
        echo '<tr>
                <th class="text-center" colspan="3">employee3 table</th>
                <th class="text-center" colspan="4">promo_record table</th>
                <th class="text-center" rowspan="2" width="10%">CutOff</th>
                <th class="text-center" rowspan="2">Action</th>';
        echo '</tr>';
        echo '<tr>
                <th>record_no</th>
                <th>startdate</th>
                <th>eocdate</th>
                <th>record_no</th>
                <th>agency</th>
                <th>company</th>
                <th class="text-center">store(s)</th>';
        echo '</tr>';

        $cond   = "emp_id='$empId' ORDER BY  startdate DESC, record_no DESC";
        $query  = $this->portal_model->getResultArray($pis, 'employee3', $cond);
        foreach ($query as $row) {

            $record_no  = $row['record_no'];
            $startdate  = date('m/d/Y', strtotime($row['startdate']));
            $eocdate    = date('m/d/Y', strtotime($row['eocdate']));

            echo '<tr>';
            echo    '<td>' . $record_no . '</td>';
            echo    '<td>' . $startdate . '</td>';
            echo    '<td>' . $eocdate . '</td>';

            $cond   = "emp_id = '$empId' AND record_no = '$record_no'";
            $record = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
            if ($record) {

                $agency  = $record['agency_code'];
                $company = $record['promo_company'];

                $i = 0;
                $stores = '';
                $bus = $this->masterfile_model->locate_promo_bu($pis);
                foreach ($bus as $bu) {
                    $field = $bu['bunit_field'];
                    $name = '<span class="badge bg-success me-1">' . $bu['bunit_acronym'] . '</span>';
                    $cond = ['emp_id' => $empId, 'record_no' => $record_no, $field => 'T'];
                    $check = $this->masterfile_model->getRowArray($pis, 'promo_record', $cond);
                    if ($check) {
                        $i++;
                        $stores = ($i == 1) ? $name : $stores . $name;
                    }
                }

                echo '<td>' . $record_no . '</td>';
                echo '<td>' . $agency . '</td>';
                echo '<td>' . $company . '</td>';
                echo '<td class="text-center">' . $stores . '</td>';
            } else {
                $click  = 'onclick="addPromorecordForm(\'' . $input['server'] . '\',\'' . $empId . '\',\'' . $record_no . '\')"';
                $action = '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-green">Add</span></a>';
                $click  = 'onclick="uploadPromorecordForm(\'' . $input['server'] . '\',\'' . $empId . '\',\'' . $record_no . '\')"';
                $action .= '<a href="javascript:;" class="text-decoration-none ms-1" ' . $click . '><span class="badge bg-orange">Upload</span></a>';
                echo '<td class="text-center" colspan="4">' . $action . '</td>';
            }

            $cond = "empId = '$empId' AND recordNo = '$record_no'";
            $cutoff = $this->portal_model->getRowArray($tk, 'promo_sched_emp', $cond);
            if ($cutoff) {
                $statCut = $cutoff['statCut'];
                $cond   = "statCut = '$statCut'";
                $row    = $this->portal_model->getRowArray($tk, 'promo_schedule', $cond);
                $endFC  = ($row['endFC'] != '') ? $row['endFC'] : 'last';
                $sched  = $row['startFC'] . '-' . $endFC . ' / ' . $row['startSC'] . '-' . $row['endSC'];
                echo '<td class="text-center">' . $sched . '</td>';
            } else {
                $click  = 'onclick="addCutoffForm(\'' . $input['server'] . '\',\'' . $empId . '\',\'' . $record_no . '\')"';
                $action = '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-green">Add</span></a>';
                echo '<td class="text-center">' . $action . '</td>';
            }

            if ($record) {
                $click = 'onclick="editContractForm(\'' . $input['server'] . '\',\'' . $empId . '\',\'' . $record_no . '\')"';
                $action = '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-primary">Edit</span></a>';
            } else {
                $action = '<a href="javascript:;" class="text-decoration-none" title="Complete the promo_record table!"><span class="badge bg-secondary">Edit</span></a>';
            }
            echo '<td class="text-center">' . $action . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function editContractForm()
    {
        $input      = $this->request->getPost();
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        echo '<div class="row">';
        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<input type="hidden" name="emp_id" value="' . $emp_id . '">';
        echo '<input type="hidden" name="record_no" value="' . $record_no . '">';
        $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
        $row = $this->portal_model->getRowArray($pis, 'employee3', $cond);
        if ($row) {

            $emp_no     = $row['emp_no'];
            $emp_pins   = $row['emp_pins'];
            $barcodeId  = $row['barcodeId'];
            $biomId     = $row['bioMetricId'];
            $startdate  = $row['startdate'];
            $eocdate    = $row['eocdate'];
            $emp_type   = $row['emp_type'];
            $position   = $row['position'];

            echo '<div class="col-md-4">';
            echo '<div class="row">';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Emp No.</label>';
            echo '<input type="text" class="form-control" name="emp_no" value="' . $emp_no . '">';
            echo '</div>';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Emp Pins</label>';
            echo '<input type="text" class="form-control" name="emp_pins" value="' . $emp_pins . '">';
            echo '</div>';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Barcode Id</label>';
            echo '<input type="text" class="form-control" name="barcodeId" value="' . $barcodeId . '">';
            echo '</div>';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Biometric Id</label>';
            echo '<input type="text" class="form-control" name="bioMetricId" value="' . $biomId . '">';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">Startdate</label>';
            echo '<input type="text" class="form-control datePicker" name="startdate" value="' . $startdate . '" required>';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">EOCdate</label>';
            echo '<input type="text" class="form-control datePicker" name="eocdate" value="' . $eocdate . '" required>';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">Emp Type</label>';
            echo '<input type="text" class="form-control" name="emp_type" value="' . $emp_type . '" required>';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">Position</label>';
            echo '<input type="text" class="form-control" name="position" value="' . $position . '" required>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }

        echo '<div class="col-md-8">';
        echo '<div class="row">';

        $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
        $row = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
        if ($row) {
            $agency     = $row['agency_code'];
            $company    = $row['promo_company'];
            $dept       = $row['promo_department'];
            $type       = $row['type'];
            $hrLoc      = $row['hr_location'];
            if ($row['hr_location'] == '') {
                $hrLoc = ($server == 'cebu') ? 'cebo' : 'asc';
            }

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Select Agency</label>';
            echo '<select class="form-select select2default" name="agency_code" onchange="selectCompany(this.value, \'' . $server . '\')" data-placeholder="---">';
            echo '<option value="">---</option>';

            $cond = "status='1' ORDER BY agency_name";
            $query = $this->portal_model->getResultArray($tk, 'promo_locate_agency', $cond);
            foreach ($query as $ac) {

                $selected = ($ac['agency_code'] == $agency) ? 'selected' : '';
                echo '<option value="' . $ac['agency_code'] . '" ' . $selected . '>' . $ac['agency_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Select Company</label>';
            echo '<select class="form-select select2default" name="promo_company"  required data-placeholder="---">';
            echo '<option value="">---</option>';
            if ($agency == '' || $agency == 0) {

                $cond = "status = '1' ORDER BY pc_name";
                $query = $this->portal_model->getResultArray($pis, 'locate_promo_company', $cond);
                foreach ($query as $pc) {

                    $selected = ($pc['pc_name'] == $company) ? 'selected' : '';
                    echo '<option value="' . $pc['pc_name'] . '" ' . $selected . '>' . $pc['pc_name'] . '</option>';
                }
            } else {

                $query = $this->portal_model->getCompanys($tk, 'promo_locate_company', $agency);
                foreach ($query as $pc) {

                    $selected = ($pc['company_name'] == $company) ? 'selected' : '';
                    echo '<option value="' . $pc['company_name'] . '" ' . $selected . '>' . $pc['company_name'] . '</option>';
                }
            }
            echo '</select>';
            echo '</div>';

            echo '<div class="col-md-12 mb-2">';
            echo '<label class="form-label">Store(s)</label>';
            echo '<div class="row">';

            $ids  = [];
            $table = 'locate_promo_business_unit';
            $cond = ['status' => 'active', 'tk_status' => 'active', 'hrd_location' => $hrLoc];
            $bus = $this->portal_model->getResultArray($pis, $table, $cond);
            foreach ($bus as $bu) {

                $id     = $bu['bunit_id'];
                $field  = $bu['bunit_field'];
                $buName = $bu['bunit_name'];

                $click = 'onclick="selectDepartment(\'' . $server . '\')"';
                $chk = '';
                $cond = "emp_id = '$emp_id' AND record_no = '$record_no' AND $field='T'";
                $check = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
                if ($check) {
                    $ids[] = $id;
                    $chk = 'checked';
                }

                echo '<div class="col-md-4">';
                echo    '<div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="stores[]" value="' . $field . '" id="' . $id . '" ' . $chk . ' ' . $click . '>
                            <label class="form-check-label" for="' . $id . '">' . $buName . '</label>
                        </div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">Select Department</label>';
            echo '<select class="form-select" name="promo_department" required>';
            echo '<option value="">---</option>';

            $query = $this->portal_model->getDepartments($pis, 'locate_promo_department', $ids);
            foreach ($query as $depts) {

                $selected = ($depts['dept_name'] == $dept) ? 'selected' : '';
                echo '<option value="' . $depts['dept_name'] . '" ' . $selected . '>' . $depts['dept_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';

            echo '<div class="col-md-6 mb-2">';
            echo '<label class="form-label">Select Contract Type</label>';
            echo '<select class="form-select" name="type" required>';
            echo '<option value="">---</option>';
            echo '<option value="Contractual" ' . ($type == 'Contractual' ? ' selected' : '') . '>Contractual</option>';
            echo '<option value="Seasonal" ' . ($type == 'Seasonal' ? ' selected' : '') . '>Seasonal</option>';
            echo '</select>';
            echo '</div>';
        }

        $cond = ['empId' => $emp_id, 'recordNo' => $record_no];
        $row = $this->portal_model->getRowArray($tk, 'promo_sched_emp', $cond);

        $statCut = $row['statCut'] ?? '';
        echo '<div class="col-md-6 mb-2">
                    <label class="form-label">Cutoff</label>
                    <select name="statCut" class="form-select" required>
                    <option value="">---</option>';

        $cond = ['remark' => 'active'];
        $query = $this->portal_model->getResultArray($tk, 'promo_schedule', $cond);
        foreach ($query as $row) {

            $endFC = ($row['endFC'] != '') ? $row['endFC'] : 'last';
            $sched = $row['startFC'] . '-' . $endFC . ' / ' . $row['startSC'] . '-' . $row['endSC'];
            $selected = ($statCut == $row['statCut']) ? 'selected' : '';
            echo '<option value="' . $row['statCut'] . '" ' . $selected . '>' . $sched . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function selectCompany()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($input['server']);
        $pis        = $getServers['pis'];
        $tk         = ($server == 'cebu') ? 'tk_cebo' : 'tk_corp';

        $agency_code = $input['agency_code'];
        if ($agency_code) {
            echo '<option value="">---</option>';
            $table  = 'promo_locate_company';
            $query  = $this->portal_model->getCompanys($tk, $table, $agency_code);
            foreach ($query as $row) {

                echo '<option value="' . $row['company_name'] . '">' . $row['company_name'] . '</option>';
            }
        } else {
            echo '<option value="">---</option>';
            $table  = 'locate_promo_company';
            $cond   = "status = '1' ORDER BY pc_name";
            $query  = $this->portal_model->getResultArray($pis, $table, $cond);
            foreach ($query as $row) {

                echo '<option value="' . $row['pc_name'] . '">' . $row['pc_name'] . '</option>';
            }
        }
    }

    public function selectDepartment()
    {
        $input      = $this->request->getPost();
        $ids        = $input['bunit_id'];
        $getServers = getServers($input['server']);
        $pis        = $getServers['pis'];

        echo '<option value="">---</option>';
        $query  = $this->portal_model->getDepartments($pis, 'locate_promo_department', $ids);
        foreach ($query as $row) {

            echo '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . '</option>';
        }
    }

    public function updatePromoContract()
    {
        $input      = $this->request->getPost();
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        $server     = $input['server'];
        $getServers = getServers($input['server']);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
        $chk = $this->portal_model->getRowArray($pis, 'employee3', $cond);
        if ($chk) {
            $data = array(
                'emp_no'        => $input['emp_no'],
                'emp_pins'      => $input['emp_pins'],
                'barcodeId'     => $input['barcodeId'],
                'bioMetricId'   => $input['bioMetricId'],
                'startdate'     => date('Y-m-d', strtotime($input['startdate'])),
                'eocdate'       => date('Y-m-d', strtotime($input['eocdate'])),
                'emp_type'      => $input['emp_type'],
                'position'      => $input['position']
            );

            $employee3 = $this->portal_model->updateData($pis, 'employee3', $cond, $data);
        }

        $record = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
        if ($record) {

            $hrLoc = $record['hr_location'];
            if ($hrLoc == '') {
                $hrLoc = ($server == 'cebu') ? 'cebo' : 'asc';
            }

            $data = array(
                'agency_code'        => $input['agency_code'],
                'promo_company'      => $input['promo_company'],
                'promo_department'   => $input['promo_department'],
                'type'               => $input['type'],
                'hr_location'        => $hrLoc
            );

            $table = 'locate_promo_business_unit';
            $cond = ['status' => 'active', 'tk_status' => 'active', 'hrd_location' => $hrLoc];
            $bus = $this->portal_model->getResultArray($pis, $table, $cond);
            foreach ($bus as $bu) {
                $field = $bu['bunit_field'];
                if (in_array($field, $input['stores'])) {
                    $data[$field] = 'T';
                } else {
                    $data[$field] = '';
                }
            }

            $data['promo_type'] = (count($input['stores']) == 1) ? 'STATION' : 'ROVING';


            $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
            $promo_record = $this->portal_model->updateData($pis, 'promo_record', $cond, $data);
        }

        $cond = ['empId' => $emp_id, 'recordNo' => $record_no];
        $cutoff = $this->portal_model->getRowArray($tk, 'promo_sched_emp', $cond);
        if ($cutoff) {
            $data = ['statCut' => $input['statCut']];
            $promo_sched_emp = $this->portal_model->updateData($tk, 'promo_sched_emp', $cond, $data);
        }

        if ($employee3 && $promo_record) {
            echo json_encode([
                'message' => 'success',
                'feedback' => 'Promo contract has been updated.',
                'emp_id' => $emp_id,
                'server' => $server,
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function addCutoffForm()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<input type="hidden" name="emp_id" value="' . $emp_id . '">';
        echo '<input type="hidden" name="record_no" value="' . $record_no . '">';
        echo '<div class="col-md-12 mb-2">
                <label class="form-label">Select Cutoff</label>
                <select name="statCut" class="form-control">';
        $cond = ['remark' => 'active'];
        $query = $this->portal_model->getResultArray($tk, 'promo_schedule', $cond);
        foreach ($query as $row) {
            $endFC = ($row['endFC'] != '') ? $row['endFC'] : 'last';
            $sched = $row['startFC'] . '-' . $endFC . ' / ' . $row['startSC'] . '-' . $row['endSC'];
            echo '<option value="' . $row['statCut'] . '">' . $sched . '</option>';
        }
        echo    '</select>';
        echo '</div>';
    }

    public function addCutoffSubmit()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];
        $statCut    = $input['statCut'];

        $data = array(
            'empId'         => $emp_id,
            'recordNo'      => $record_no,
            'statCut'       => $statCut,
            'date_setup'    => $this->today
        );
        $insert = $this->portal_model->insertData($tk, 'promo_sched_emp', $data);
        if ($insert) {
            echo json_encode([
                'message'   => 'success',
                'feedback'  => 'Promo cutoff has been added.',
                'emp_id'    => $emp_id,
                'server'    => $server,
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function addPromorecordForm()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $hrLoc      = $getServers['hrCode'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        echo '<div class="row">';
        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<input type="hidden" name="emp_id" value="' . $emp_id . '">';
        echo '<input type="hidden" name="record_no" value="' . $record_no . '">';

        echo '<div class="col-md-12 mb-3">';
        echo '<label class="form-label">Select Agency</label>';
        echo '<select class="form-select select2default" name="agency_code" onchange="selectCompany(this.value, \'' . $server . '\')" data-placeholder="---">';
        echo '<option value="">---</option>';
        $cond = "status='1' ORDER BY agency_name";
        $query = $this->portal_model->getResultArray($tk, 'promo_locate_agency', $cond);
        foreach ($query as $row) {
            echo '<option value="' . $row['agency_code'] . '">' . $row['agency_name'] . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="col-md-12 mb-3">';
        echo '<label class="form-label">Select Company</label>';
        echo '<select class="form-select select2default" name="promo_company" required data-placeholder="---">';
        echo '<option value="">---</option>';
        $cond = "status = '1' ORDER BY pc_name";
        $query = $this->portal_model->getResultArray($pis, 'locate_promo_company', $cond);
        foreach ($query as $row) {
            echo '<option value="' . $row['pc_name'] . '">' . $row['pc_name'] . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="col-md-12 mb-3">';
        echo '<label class="form-label">Store(s)</label>';
        echo '<div class="row">';
        $table = 'locate_promo_business_unit';
        $cond = ['status' => 'active', 'tk_status' => 'active', 'hrd_location' => $hrLoc];
        $bus = $this->portal_model->getResultArray($pis, $table, $cond);
        foreach ($bus as $bu) {
            $id         = $bu['bunit_id'];
            $field      = $bu['bunit_field'];
            $buName     = $bu['bunit_name'];
            $onclick    = 'onclick="selectDepartment(\'' . $server . '\')"';

            echo '<div class="col-md-4">';
            echo    '<div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="' . $id . '" name="stores[]" value="' . $field . '" ' . $onclick . '">
                        <label class="form-check-label" for="' . $id . '">' . $buName . '</label>
                    </div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Select Department</label>';
        echo '<select class="form-select" name="promo_department" required>';
        echo '<option value="">---</option>';
        echo '</select>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Select Contract Type</label>';
        echo '<select class="form-select" name="type" required>';
        echo '<option value="">---</option>';
        echo '<option value="Contractual">Contractual</option>';
        echo '<option value="Seasonal">Seasonal</option>';
        echo '</select>';
        echo '</div>';

        echo '</div>';
    }

    public function addPromorecordSubmit()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $hrLoc      = $getServers['hrCode'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
        $chk = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
        if (!$chk) {

            $data = array(
                'record_no'         => $record_no,
                'emp_id'            => $emp_id,
                'agency_code'       => $input['agency_code'],
                'promo_company'     => $input['promo_company'],
                'promo_department'  => $input['promo_department'],
                'type'              => $input['type'],
                'hr_location'       => $hrLoc
            );

            $table  = 'locate_promo_business_unit';
            $cond = ['status' => 'active', 'tk_status' => 'active', 'hrd_location' => $hrLoc];
            $bus = $this->portal_model->getResultArray($pis, $table, $cond);
            foreach ($bus as $bu) {
                $field = $bu['bunit_field'];
                if (in_array($field, $input['stores'])) {
                    $data[$field] = 'T';
                } else {
                    $data[$field] = '';
                }
            }

            $data['promo_type'] = (count($input['stores']) == 1) ? 'STATION' : 'ROVING';

            $insert = $this->portal_model->insertData($pis, 'promo_record', $data);
            if ($insert) {
                echo json_encode([
                    'message'   => 'success',
                    'feedback'  => 'Promo record data has been added.',
                    'emp_id'    => $emp_id,
                    'server'    => $server,
                ]);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function uploadPromorecordForm()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<input type="hidden" name="emp_id" value="' . $emp_id . '">';
        echo '<input type="hidden" name="record_no" value="' . $record_no . '">';
        echo '<div class="col-md-12 mb-2">';
        echo '<div class="note alert-warning mb-2">
					<div class="note-icon"><i class="fa fa-lightbulb"></i></div>
					<div class="note-content">
						<h4><b>Note:</b></h4>
						<p>Only ZIP and RAR files are allowed.</p>
					</div>';
        echo '</div>';
        echo '</div>';
        echo '<input type="file" class="form-control" name="imported_data" accept=".zip,.rar" required>';
        echo '</div>';
    }

    public function uploadPromorecord()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $emp_id     = $input['emp_id'];
        $record_no  = $input['record_no'];

        $target_dir = 'import/files/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file = $this->request->getFile('imported_data');
        if ($file->isValid()) {
            $name = $file->getClientName();

            $file->move($target_dir, $name);

            $zipPath = $target_dir . $name;

            if ($this->zip->open($zipPath) === TRUE) {
                $this->zip->extractTo($target_dir);
                $this->zip->close();

                $sql_file = $target_dir . 'promo_record.sql';
                $cond = ['emp_id' => $emp_id, 'record_no' => $record_no];
                $chk = $this->portal_model->getRowArray($pis, 'promo_record', $cond);
                if (!$chk) {
                    if (file_exists($sql_file)) {
                        $sql = file_get_contents($sql_file);
                        $id  = $this->portal_model->insertQuery($pis, $sql);
                        if ($id) {
                            $cond   = ['promo_id' => $id];
                            $data   = ['record_no' => $record_no];
                            $update = $this->portal_model->updateData($pis, 'promo_record', $cond, $data);
                            if ($update) {
                                echo json_encode([
                                    'message'   => 'success',
                                    'feedback'  => 'Promo record data has been uploaded.',
                                    'emp_id'    => $emp_id,
                                    'server'    => $server,
                                ]);
                            } else {
                                echo json_encode(['message' => 'failed to update record_no']);
                            }
                        } else {
                            echo json_encode(['message' => 'failed to insert promo record']);
                        }
                    } else {
                        echo json_encode(['message' => 'failed to get extracted file']);
                    }
                }

                unlink($sql_file); // delete extracted file(s)
            } else {
                echo json_encode(['message' => 'failed to open zip file']);
            }
        }
    }

    public function assignSearch()
    {
        $input      = $this->request->getPost();
        $string     = $input['str'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        echo '<ul class="list-group">';
        $query = $this->masterfile_model->searchPromo($pis, $string);
        if ($query) {
            foreach ($query as $row) {
                $empId  = $row['emp_id'];
                $cond   = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
                $emp    = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);
                $name   = htmlspecialchars($emp['name']);
                $click  = 'onclick="selectAssign(\'' . $empId . '\',\'' . $name . '\')"';
                echo '<a href="javascript:;" class="list-group-item" ' . $click . '>' . $name . '</a>';
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
        }
        echo '</ul>';
    }

    public function assignData()
    {
        $input      = $this->request->getPost();
        $empId      = $input['emp_id'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        $cond = "empId LIKE '%$empId%'";
        $query = $this->portal_model->getResultArray($tk, 'assign', $cond);
        if ($query) {
            echo '<table class="table table-bordered">';
            echo    '<tr>';
            echo        '<th>empId</th>';
            echo        '<th>assignId</th>';
            echo        '<th>randAss</th>';
            echo        '<th>bioMetricId</th>';
            echo        '<th>barcodeId</th>';
            echo        '<th>shiftCode</th>';
            echo        '<th>allowId</th>';
            echo        '<th>dateApplied</th>';
            echo        '<th>timeApplied</th>';
            echo        '<th>addedBy</th>';
            echo        '<th class="text-center">Action</th>';
            echo    '</tr>';

            foreach ($query as $row) {
                $emp            = $row['empId'];
                $assignId       = $row['assignId'];
                $randAss        = $row['randAss'];
                $bioMetricId    = $row['bioMetricId'];
                $barcodeId      = $row['barcodeId'];
                $shiftCode      = $row['shiftCode'];
                $allowId        = $row['allowId'];
                $dateApplied    = $row['dateApplied'];
                $timeApplied    = $row['timeApplied'];
                $addedBy        = $row['added_by'];

                $action = '';
                if (strpos($bioMetricId, '_') !== false || strpos($barcodeId, '_') !== false || strpos($emp, '_') !== false) {
                    $process = 'activate';
                    $color  = 'green';
                } else {
                    $process = 'deactivate';
                    $color  = 'red';
                    $click  = 'onclick="editAssignForm(\'' . $server . '\',\'' . $empId . '\',\'' . $assignId . '\')"';
                    $action .= '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-blue">Edit</span></a>';
                }

                $click = 'onclick="updateAssignData(\'' . $process . '\',\'' . $server . '\',\'' . $empId . '\',\'' . $assignId . '\')"';
                $action .= '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-' . $color . ' ms-1">' . ucfirst($process) . '</span></a>';

                echo '<tr>';
                echo    '<td>' . $emp . '</td>';
                echo    '<td>' . $assignId . '</td>';
                echo    '<td>' . $randAss . '</td>';
                echo    '<td>' . $bioMetricId . '</td>';
                echo    '<td>' . $barcodeId . '</td>';
                echo    '<td>' . $shiftCode . '</td>';
                echo    '<td>' . $allowId . '</td>';
                echo    '<td>' . $dateApplied . '</td>';
                echo    '<td>' . $timeApplied . '</td>';
                echo    '<td>' . $addedBy . '</td>';
                echo    '<td class="text-center">' . $action . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<div class="note alert-warning note-with-start-icon mb-2">
    				<div class="note-content">
    					<h4><b>No Assign Data Found!</b></h4>
    				</div>';
            echo '</div>';
        }
    }

    public function editAssignForm()
    {
        $input      = $this->request->getPost();
        $empId      = $input['empId'];
        $assignId   = $input['assignId'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        $cond           = ['assignId' => $assignId];
        $row            = $this->portal_model->getRowArray($tk, 'assign', $cond);
        $assignId       = $row['assignId'];
        $randAss        = $row['randAss'];
        $bioMetricId    = $row['bioMetricId'];
        $barcodeId      = $row['barcodeId'];
        $shiftCode      = $row['shiftCode'];
        $allowId        = $row['allowId'];
        $dateApplied    = $row['dateApplied'];
        $timeApplied    = $row['timeApplied'];
        $addedBy        = $row['added_by'];

        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<div class="row">';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Assign ID</label>';
        echo '<input type="text" class="form-control" name="assignId" value="' . $assignId . '" required readonly>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Employee ID</label>';
        echo '<input type="text" class="form-control" name="empId" value="' . $empId . '" required readonly>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Shift Code</label>';
        echo '<input type="number" class="form-control" name="shiftCode" value="' . $shiftCode . '" required>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Allow ID</label>';
        echo '<select class="form-select" name="allowId" required>';
        echo '<option value="">---</option>';
        echo '<option value="Biometric ID"' . ($allowId == 'Biometric ID' ? ' selected' : '') . '>Biometric ID</option>';
        echo '<option value="Logbox ID"' . ($allowId == 'Logbox ID' ? ' selected' : '') . '>Logbox ID</option>';
        echo '</select>';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Biometric ID</label>';
        echo '<input type="number" class="form-control" name="bioMetricId" value="' . $bioMetricId . '">';
        echo '</div>';

        echo '<div class="col-md-6 mb-2">';
        echo '<label class="form-label">Barcode ID</label>';
        echo '<input type="number" class="form-control" name="barcodeId" value="' . $barcodeId . '">';
        echo '</div>';

        echo '</div>';
    }

    public function editAssignData()
    {
        $input      = $this->request->getPost();
        $empId      = $input['empId'];
        $assignId   = $input['assignId'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];


        $data = array(
            'bioMetricId'   => $input['bioMetricId'],
            'barcodeId'     => $input['barcodeId'],
            'shiftCode'     => $input['shiftCode'],
            'allowId'       => $input['allowId'],
        );

        $cond = ['assignId' => $assignId];
        $update = $this->portal_model->updateData($tk, 'assign', $cond, $data);
        if ($update) {
            echo json_encode([
                'message' => 'success',
                'feedback' => 'Assign data has been updated.',
                'emp_id' => $empId,
                'server' => $server,
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function updateAssignData()
    {
        $input      = $this->request->getPost();
        $process    = $input['process'];
        $empId      = $input['empId'];
        $assignId   = $input['assignId'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        $cond = ['assignId' => $assignId];
        $row = $this->portal_model->getRowArray($tk, 'assign', $cond);
        if ($process == 'activate') {
            $data = [
                'bioMetricId'   => str_replace('_', '', $row['bioMetricId']),
                'barcodeId'     => str_replace('_', '', $row['barcodeId']),
                'empId'         => str_replace('_', '', $row['empId']),
            ];
        } else {
            $data = ['empId' => $row['empId'] . '_'];
            if ($row['bioMetricId'] != '' && preg_match('/^0+$/', $row['bioMetricId']) == 0) {
                $data['bioMetricId'] = $row['bioMetricId'] . '_';
            }
            if ($row['barcodeId'] != '' && preg_match('/^0+$/', $row['barcodeId']) == 0) {
                $data['barcodeId'] = $row['barcodeId'] . '_';
            }
        }

        $update = $this->portal_model->updateData($tk, 'assign', $cond, $data);
        if ($update) {
            echo json_encode([
                'message' => 'success',
                'feedback' => 'Assign data has been ' . $process . 'd.'
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function prevdtrSearch()
    {
        $input      = $this->request->getPost();
        $string     = $input['str'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        echo '<ul class="list-group">';
        $query = $this->masterfile_model->searchPromo($pis, $string);
        if ($query) {
            foreach ($query as $row) {
                $empId  = $row['emp_id'];
                $cond   = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
                $emp    = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);
                $name   = htmlspecialchars($emp['name']);
                $click  = 'onclick="selectPrev(\'' . $empId . '\',\'' . $name . '\')"';
                echo '<a href="javascript:;" class="list-group-item" ' . $click . '>' . $name . '</a>';
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
        }
        echo '</ul>';
    }

    public function prevperDay()
    {
        $input      = $this->request->getPost();
        $empId      = $input['empId'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];

        echo '<h5>Perday Previous (<font class="text-green">Active</font>)</h5>';
        echo '<table class="table table-bordered">';
        echo '<tr>
                <th>empId</th>
                <th>Date Duty</th>
                <th>HW</th>
                <th>DW</th>
                <th>DateFrom</th>
                <th>DateTo</th>
                <th>Request</th>
                <th class="text-center">Action</th>';
        echo '</tr>';

        $cond = "empId='$empId' ORDER BY ppId DESC";
        $query = $this->portal_model->getResultArray($tk, 'perday_previous', $cond);
        foreach ($query as $key => $row) {
            $emp_id     = $row['empId'];
            $ppId       = $row['ppId'];
            $dateDuty   = date('Y-m-d', strtotime($row['dateDuty']));
            $dateFrom   = date('Y-m-d', strtotime($row['coDF1']));
            $dateTo     = date('Y-m-d', strtotime($row['coDF2']));
            $hw_new     = floatval($row['hw_new']);
            $hw_con     = $row['hw_con'] + 0;
            $request    = $row['request'];

            $click = 'onclick="ppDayUpdate(\'deactivate\',\'' . $server . '\',\'' . $empId . '\',\'' . $ppId . '\')"';
            $action = '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-danger">Move</span></a>';

            echo '<tr>
                    <td>' . $emp_id . '</td>
                    <td>' . $dateDuty . '</td>
                    <td>' . $hw_new . '</td>
                    <td>' . $hw_con . '</td>
                    <td>' . $dateFrom . '</td>
                    <td>' . $dateTo . '</td>
                    <td>' . $request . '</td>
                    <td class="text-center">' . $action . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<hr>';

        echo '<h5>Perday Previous (<font class="text-warning">Inactive</font>)</h5>';
        echo '<table class="table table-bordered">';
        echo '<tr>
                <th>empId</th>
                <th>Date Duty</th>
                <th>HW</th>
                <th>DW</th>
                <th>DateFrom</th>
                <th>DateTo</th>
                <th>Request</th>
                <th class="text-center">Action</th>';
        echo '</tr>';

        $cond = "(empId='_" . $empId . "' OR empId='" . $empId . "_') ORDER BY ppId DESC";
        $query = $this->portal_model->getResultArray($tk, 'perday_previous', $cond);
        foreach ($query as $key => $row) {
            $emp_id     = $row['empId'];
            $ppId       = $row['ppId'];
            $dateDuty   = date('Y-m-d', strtotime($row['dateDuty']));
            $dateFrom   = date('Y-m-d', strtotime($row['coDF1']));
            $dateTo     = date('Y-m-d', strtotime($row['coDF2']));
            $hw_new     = floatval($row['hw_new']);
            $hw_con     = $row['hw_con'] + 0;
            $request    = $row['request'];

            $click = 'onclick="ppDayUpdate(\'activate\',\'' . $server . '\',\'' . $empId . '\',\'' . $ppId . '\')"';
            $action = '<a href="javascript:;" class="text-decoration-none" ' . $click . '><span class="badge bg-danger">Move</span></a>';

            echo '<tr>
                    <td>' . $emp_id . '</td>
                    <td>' . $dateDuty . '</td>
                    <td>' . $hw_new . '</td>
                    <td>' . $hw_con . '</td>
                    <td>' . $dateFrom . '</td>
                    <td>' . $dateTo . '</td>
                    <td>' . $request . '</td>
                    <td class="text-center">' . $action . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function ppDayUpdate()
    {
        $input      = $this->request->getPost();
        $process    = $input['process'];
        $ppId       = $input['ppId'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $tk         = $getServers['tk'];

        if ($process == 'deactivate') {
            $empId = $input['empId'] . '_';
            $fb = 'Perday Previous data has been moved to Inactive!';
        } else {
            $empId = ltrim($input['empId'], '_');
            $empId = rtrim($empId, '_');
            $fb = 'Perday Previous data has been moved to Active!';
        }
        $cond = ['ppId' => $ppId];
        $data = ['empId' => $empId];
        $update = $this->portal_model->updateData($tk, 'perday_previous', $cond, $data);
        if ($update) {
            echo json_encode([
                'message' => 'success',
                'feedback' => $fb
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }
}
