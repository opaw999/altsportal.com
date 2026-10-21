<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

use Config\Services;

class Setup extends BaseController
{
    public function supplier_list()
    {
        $data = [];
        $query = $this->setup_model->supplier_list();
        foreach ($query as $row) {

            $cond = ['id' => $row['supplier_type']];
            $type = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_type', $cond)['type'] ?? '';

            if ($row['status'] == 1) {
                $action = '<a href="javascript:;" title="update status"><i id="deactivate|' . $row['id'] . '" class="fa-solid fa-toggle-on fs-15px text-primary action"></i></a>';
                $status = '<span class="badge bg-green">Active</span>';
            } else {
                $action = '<a href="javascript:;" title="update status"><i id="activate|' . $row['id'] . '" class="fa-solid fa-toggle-off fs-15px text-secondary action"></i></a>';
                $status = '<span class="badge bg-warning">Inactive</span>';
            }
            $action .= '<a href="javascript:;" title="update details"><i id="update|' . $row['id'] . '" class="fa-regular fa-pen-to-square ms-2 fs-15px text-primary action"></i></a>';

            $sub_array = array();
            $sub_array[] = $row['supplier'];
            $sub_array[] = $type;
            $sub_array[] = $status;
            $sub_array[] = date("F d, Y h:i A", strtotime($row['created_at']));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function add_supplier_details()
    {
        echo '<input type="hidden" name="action" value="create">';
        echo '<div class="form-group mb-2">
                <label class="form-label">Supplier</label>
	            <input type="text" name="supplier" class="form-control" placeholder="Supplier name..." required>';
        echo '</div>';
        echo '<div class="form-group">
                <label class="form-label">Supplier Type</label>
	            <select name="supplier_type" class="form-select" required>
                <option value="">Select type</option>';
        $query = $this->setup_model->supplier_types();
        foreach ($query as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }

    public function edit_supplier_details()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['id']];
        $supp = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);

        echo '<input type="hidden" name="action" value="update">';
        echo '<input type="hidden" name="id" value="' . $input['id'] . '">';
        echo '<div class="form-group mb-2">
                <label class="form-label">Supplier</label>
	            <input type="text" name="supplier" value="' . $supp['supplier'] . '" class="form-control" placeholder="Supplier name..." required>';
        echo '</div>';
        echo '<div class="form-group">
                <label class="form-label">Supplier Type</label>
	            <select name="supplier_type" class="form-select" required>
                <option value="">Select type</option>';
        $query = $this->setup_model->supplier_types();
        foreach ($query as $row) {
            $selected = ($row['id'] == $supp['supplier_type']) ? 'selected' : '';
            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['type'] . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }

    public function create_update_supplier()
    {
        $input = $this->request->getPost();
        $tbl1 = 'promo_supplier';
        $tbl2 = 'promo_supplier_user';
        $server = 'tk_corp';

        if ($input['action'] == 'create') {
            $cond = ['supplier' => $input['supplier']];
            $check = $this->setup_model->getRowArray($server, $tbl1, $cond);
            if ($check) {
                echo json_encode(['message' => 'exist', 'feedback' => 'Supplier name already exist.']);
            } else {
                $data = [ // status default is 1
                    'supplier'      => $input['supplier'],
                    'supplier_type' => $input['supplier_type'],
                    'created_at'    => $this->timestamp
                ];
                $insert = $this->setup_model->insertData($server, $tbl1, $data);
                if ($insert) {
                    echo json_encode(['message' => 'create', 'feedback' => 'Supplier has been added.']);
                } else {
                    echo json_encode(['message' => 'failed']);
                }
            }
        } else if ($input['action'] == 'update') {
            $cond = ['supplier' => $input['supplier'], 'id !=' => $input['id']];
            $check = $this->setup_model->getRowArray($server, $tbl1, $cond);
            if ($check) {
                echo json_encode(['message' => 'exist', 'feedback' => 'Supplier name already exist.']);
            } else {
                $cond = ['id' => $input['id']];
                $data = [
                    'supplier'      => $input['supplier'],
                    'supplier_type' => $input['supplier_type'],
                    'updated_at'    => $this->timestamp
                ];
                $update = $this->setup_model->updateData($server, $tbl1, $cond, $data);
                if ($update) {
                    echo json_encode(['message' => 'update', 'feedback' => 'Supplier has been updated.']);
                } else {
                    echo json_encode(['message' => 'failed']);
                }
            }
        } else {

            if ($input['action'] == 'activate') {
                $data = ['status' => 1];
                $fback = 'Supplier has been activated.';
            } else {
                $data = ['status' => 0];
                $fback = 'Supplier has been deactivated.';
            }

            $cond = ['id' => $input['id']];
            $upd1 = $this->setup_model->updateData($server, $tbl1, $cond, $data);
            $cond = ['supplier_id' => $input['id']];
            $upd2 = $this->setup_model->updateData($server, $tbl2, $cond, $data);
            if ($upd1 && $upd2) {
                echo json_encode(['message' => 'success', 'feedback' => $fback]);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }


    public function supplier_account_list()
    {
        $data = [];
        $query = $this->setup_model->supplier_account_list();
        foreach ($query as $row) {

            $action = '<a href="javascript:;" title="extend contract"><i id="extend|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-calendar-plus fs-15px text-primary action"></i></a>';
            $action .= '<a href="javascript:;" title="tag cutoff(s)"><i id="cutoff|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-bookmark ms-2 fs-15px text-primary action"></i></a>';

            if ($row['status'] == 1) {
                $action .= '<a href="javascript:;" title="update status"><i id="deactivate|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-toggle-on ms-2 fs-15px text-primary action"></i></a>';
                $status = '<span class="badge bg-green">Active</span>';
            } else {
                $action .= '<a href="javascript:;" title="update status"><i id="activate|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-toggle-off ms-2 fs-15px text-secondary action"></i></a>';
                $status = '<span class="badge bg-warning">Inactive</span>';
            }

            $action .= '<a href="javascript:;" title="reset password"><i id="reset|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-rotate-right ms-2 fs-15px text-primary action"></i></a>';
            $action .= '<a href="javascript:;" title="update agency/company"><i id="update|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-pen-to-square ms-2 fs-15px text-primary action"></i></a>';
            $action .= '<a href="javascript:;" title="view details"><i id="details|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-folder-open ms-2 fs-15px text-primary action"></i></a>';

            $sub_array = array();
            $sub_array[] = $row['supplier'];
            $sub_array[] = $row['username'];
            $sub_array[] = date('M. d, Y', strtotime($row['date_from'])) . " - " . date('M. d, Y', strtotime($row['date_to']));
            $sub_array[] = $status;
            $sub_array[] = $row['first_login'] != null ? "yes" : "no";
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function add_supplier_account()
    {
        echo '<div class="row">';
        echo '<div class="col-md-12">
	        	<div class="form-group mb-3">
	        		<label class="form-label">Supplier</label>
	        		<select name="supplier" class="form-select select2default" onchange="setup_supplier(this.value)" data-placeholder="Select supplier">
	        			<option value="">Select supplier</option>';
        $cond = "status='1' ORDER BY supplier ASC";
        $query = $this->setup_model->getResultArray('tk_corp', 'promo_supplier', $cond);
        foreach ($query as $row) {

            $cond = ['supplier_id' => $row['id']];
            $check = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
            if (!$check) {
                echo "<option value='" . $row['id'] . "'>" . $row['supplier'] . "</option>";
            }
        }
        echo        '</select>';
        echo    '</div>';
        echo '</div>';
        echo '<div class="setup_supplier"></div>';
    }

    public function setup_supplier()
    {
        $id = $this->request->getPost('id');
        $cond = ['id' => $id];
        $supplier = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        echo '<input type="hidden" name="supplier_type" value="' . $supplier['supplier_type'] . '">';
        echo '<div class="row">';
        echo '<div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">&nbsp;</label>
                    <a href="javascript:;" class="btn btn-primary d-block" onclick="generateUsername()">Generate Username</a>
                </div>';
        echo '</div>';
        echo '<div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" value="Altsportal2019" disabled>
                </div>';
        echo '</div>';
        if ($supplier['supplier_type'] == 1) {
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Agency <small>(Bohol Server)</small></label>
                        <input type="hidden" name="alturas_company" value="0">
                        <select name="alturas_agency" class="form-select select2default" data-placeholder="Select agency">
                            <option value="">Select agency</option>';
            $agencyCorp = $this->setup_model->agencyList('tk_corp', 'promo_locate_agency');
            foreach ($agencyCorp as $row) {
                echo "<option value='" . $row['agency_code'] . "'>" . $row['agency_name'] . "</option>";
            }

            echo        '</select>';
            echo    '</div>';
            echo '</div>';
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Agency <small>(Colonnade Server)</small></label>
                        <input type="hidden" name="colonnade_company" value="0">
                        <select name="colonnade_agency" class="form-select select2default" data-placeholder="Select agency">
                            <option value="">Select agency</option>';
            $agencyCebo = $this->setup_model->agencyList('tk_cebo', 'promo_locate_agency');
            foreach ($agencyCebo as $row) {
                echo "<option value='" . $row['agency_code'] . "'>" . $row['agency_name'] . "</option>";
            }
            echo        '</select>';
            echo    '</div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Company <small>(Bohol Server)</small></label>
                        <input type="hidden" name="alturas_agency" value="0">
                        <select name="alturas_company" class="form-select select2default" data-placeholder="Select company">
                            <option value="">Select company</option>';
            $companyCorp = $this->setup_model->companyList('pis_corp', 'locate_promo_company');
            foreach ($companyCorp as $row) {
                echo "<option value='" . $row['pc_code'] . "'>" . $row['pc_name'] . "</option>";
            }

            echo '</select>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Company <small>(Colonnade Server)</small></label>
                        <input type="hidden" name="colonnade_agency" value="0">
                        <select name="colonnade_company" class="form-select select2default" data-placeholder="Select company">
                            <option value="">Select company</option>';
            $companyCebo = $this->setup_model->companyList('pis_cebo', 'locate_promo_company');
            foreach ($companyCebo as $row) {
                echo "<option value='" . $row['pc_code'] . "'>" . $row['pc_name'] . "</option>";
            }

            echo        '</select>';
            echo    '</div>';
            echo '</div>';
        }
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Date From</label>
                    <input type="text" id="dateFrom" name="dateFrom" class="form-control datePicker" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Date To</label>
                    <input type="text" id="dateTo" name="dateTo" class="form-control datePicker" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>

                </div>';
        echo '</div>';
        echo '</div>';
    }

    public function create_supplier_account()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'promo_supplier_user';

        $cond   = ['username' => $input['username']];
        $check  = $this->setup_model->getRowArray($server, $table, $cond);
        if ($check) {
            echo json_encode(['message' => 'exist', 'feedback' => 'Supplier username already exist.']);
        } else {
            $password = password_hash('Altsportal2019', PASSWORD_DEFAULT);
            $data = [
                'supplier_id'   => $input['supplier'],
                'username'      => $input['username'],
                'email_address' => $input['email'],
                'password'      => $password,
                'date_from'     => date("Y-m-d", strtotime($input['dateFrom'])),
                'date_to'       => date("Y-m-d", strtotime($input['dateTo'])),
                'created_at'    => $this->timestamp
            ];

            $user_id = $this->setup_model->insertData($server, $table, $data);
            $agencies = ['alturas' => 'tk_corp', 'colonnade' => 'tk_cebo'];
            if ($input['supplier_type'] == 1) {
                foreach ($agencies as $key => $db) {
                    if (!empty($input[$key . '_agency']) && $input[$key . '_agency'] != 0) {
                        $data = [
                            'user_id'      => $user_id,
                            'supplier_id'  => $input['supplier'],
                            'agency_code'  => $input[$key . '_agency'],
                            'company_code' => $input[$key . '_company'],
                            'created_at'   => $this->timestamp
                        ];

                        $ac_code = $this->setup_model->insertData($db, 'promo_supplier_ac_code', $data);
                    }
                }
            } else {
                foreach ($agencies as $key => $db) {
                    if (!empty($input[$key . '_company']) && $input[$key . '_company'] != 0) {
                        $data = [
                            'user_id'      => $user_id,
                            'supplier_id'  => $input['supplier'],
                            'agency_code'  => $input[$key . '_agency'],
                            'company_code' => $input[$key . '_company'],
                            'created_at'   => $this->timestamp
                        ];

                        $ac_code = $this->setup_model->insertData($db, 'promo_supplier_ac_code', $data);
                    }
                }
            }

            if ($user_id && $ac_code) {
                $email = $this->sendEmail($input['username'], 'Altsportal2019', $input['email']);
                echo json_encode(['message' => 'success', 'feedback' => 'Supplier user account has been added.', 'email' => $email]);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function sendEmail($username, $password, $email)
    {
        $msg  = "Greetings!\n\nThese are your account details for the Alturas Supplier's Portal:\n\n";
        $msg .= "USERNAME: $username\nPASSWORD: $password\n\n";
        $msg .= "To sign in to your account, please visit URL: https://altsportal.com/\n\n";
        $msg .= "*DISCLAIMER: Please change the default password provided to enhance the security of your account.\n\n";
        $msg .= "If you have any concerns regarding your account, kindly contact Corporate HRD c/o Maam Coney Yelen Cornito ";
        $msg .= "through their company mobile #09190794826 and #09088152975 or email them at corporatehrd@alturasbohol.com ";
        $msg .= "to facilitate your concerns. Thank you.\n\n";
        $msg .= "Regards,\nAlturas Supermarket Corporation";

        $emailService = Services::email();

        $emailService->setFrom('support@altsportal.com', 'Alturas Support');
        $emailService->setTo($email);
        // $emailService->setTo('arnaldoparambitajr@gmail.com');
        $emailService->setSubject('User Account');
        $emailService->setMessage($msg);

        if ($emailService->send()) {

            return true;
        } else {

            log_message('error', $emailService->printDebugger(['headers']));
            return false;
        }
    }

    public function update_ac_code()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['supplier_id']];
        $supplier = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        echo '<input type="hidden" name="supplier_id" value="' . $input['supplier_id'] . '">';
        echo '<input type="hidden" name="user_id" value="' . $input['user_id'] . '">';
        echo '<input type="hidden" name="supplier_type" value="' . $supplier['supplier_type'] . '">';
        echo '<div class="row">';
        echo '<div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-control" value="' . $supplier['supplier'] . '" disabled>
                </div>';
        echo '</div>';
        $cond = ['user_id' => $input['user_id']];
        $corp = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_ac_code', $cond);
        $agency_corp  = $corp['agency_code']  ?? '0';
        $company_corp = $corp['company_code'] ?? '0';

        $cond = ['user_id' => $input['user_id']];
        $cebo = $this->setup_model->getRowArray('tk_cebo', 'promo_supplier_ac_code', $cond);
        $agency_cebo  = $cebo['agency_code']  ?? '0';
        $company_cebo = $cebo['company_code'] ?? '0';
        if ($supplier['supplier_type'] == 1) {
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Agency <small>(Bohol Server)</small></label>
                        <input type="hidden" name="alturas_company" value="0">
                        <select name="alturas_agency" class="form-select select2default" data-placeholder="Select agency">
                            <option value="">Select agency</option>';
            $agencyCorp = $this->setup_model->agencyList('tk_corp', 'promo_locate_agency');
            foreach ($agencyCorp as $row) {
                $selected = ($row['agency_code'] == $agency_corp) ? 'selected' : '';
                echo '<option value="' . $row['agency_code'] . '" ' . $selected . '>' . $row['agency_name'] . '</option>';
            }

            echo        '</select>';
            echo    '</div>';
            echo '</div>';
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Agency <small>(Colonnade Server)</small></label>
                        <input type="hidden" name="colonnade_company" value="0">
                        <select name="colonnade_agency" class="form-select select2default" data-placeholder="Select agency">
                            <option value="">Select agency</option>';
            $agencyCebo = $this->setup_model->agencyList('tk_cebo', 'promo_locate_agency');
            foreach ($agencyCebo as $row) {
                $selected = ($row['agency_code'] == $agency_cebo) ? 'selected' : '';
                echo '<option value="' . $row['agency_code'] . '" ' . $selected . '>' . $row['agency_name'] . '</option>';
            }
            echo        '</select>';
            echo    '</div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Company <small>(Bohol Server)</small></label>
                        <input type="hidden" name="alturas_agency" value="0">
                        <select name="alturas_company" class="form-select select2default" data-placeholder="Select company">
                            <option value="">Select company</option>';
            $companyCorp = $this->setup_model->companyList('pis_corp', 'locate_promo_company');
            foreach ($companyCorp as $row) {
                $selected = ($row['pc_code'] == $company_corp) ? 'selected' : '';
                echo '<option value="' . $row['pc_code'] . '" ' . $selected . '>' . $row['pc_name'] . '</option>';
            }

            echo '</select>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Company <small>(Colonnade Server)</small></label>
                        <input type="hidden" name="colonnade_agency" value="0">
                        <select name="colonnade_company" class="form-select select2default" data-placeholder="Select company">
                            <option value="">Select company</option>';
            $companyCebo = $this->setup_model->companyList('pis_cebo', 'locate_promo_company');
            foreach ($companyCebo as $row) {
                $selected = ($row['pc_code'] == $company_cebo) ? 'selected' : '';
                echo '<option value="' . $row['pc_code'] . '" ' . $selected . '>' . $row['pc_name'] . '</option>';
            }

            echo        '</select>';
            echo    '</div>';
            echo '</div>';
        }
    }

    function update_supplier_account()
    {
        $input    = $this->request->getPost();
        $agencies = ['alturas' => 'tk_corp', 'colonnade' => 'tk_cebo'];
        foreach ($agencies as $key => $db) {
            $field = ($input['supplier_type'] == 1) ? $key . '_agency' : $key . '_company';
            if (!empty($input[$field]) && $input[$field] != 0) {
                $cond  = ['user_id' => $input['user_id']];
                $check = $this->setup_model->getRowArray($db, 'promo_supplier_ac_code', $cond);
                $datetime = ($check) ? 'updated_at' : 'created_at';

                $data = [
                    'user_id'       => $input['user_id'],
                    'supplier_id'   => $input['supplier_id'],
                    'agency_code'   => $input[$key . '_agency'],
                    'company_code'  => $input[$key . '_company'],
                    $datetime       => $this->timestamp
                ];

                if ($check) {
                    $ac_code = $this->setup_model->updateData($db, 'promo_supplier_ac_code', $cond, $data);
                } else {
                    $ac_code = $this->setup_model->insertData($db, 'promo_supplier_ac_code', $data);
                }
            } else {
                $cond = ['user_id' => $input['user_id']];
                $delete = $this->setup_model->deleteData($db, 'promo_supplier_ac_code', $cond);
            }
        }

        if ($ac_code) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier user account has been updated.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function renew_contract()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['supplier_id']];
        $supp = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        $cond = ['supplier_id' => $input['supplier_id'], 'id' => $input['user_id']];
        $user = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
        echo '<input type="hidden" name="supplier_id" value="' . $input['supplier_id'] . '">';
        echo '<input type="hidden" name="user_id" value="' . $input['user_id'] . '">';
        echo '<div class="alert alert-info">
		        <h5>' . $supp['supplier'] . '</h5>
		        <p class="mb-1">Current contract is from <b>' . date('F d, Y', strtotime($user['date_from'])) . '</b> to <b>' . date('F d, Y', strtotime($user['date_to'])) . '</b></p>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="form-group col-md-6">
		        <label class="form-label">Date From</label>
		        <input type="text" name="dateFrom" class="form-control datePicker" required>';
        echo '</div>';
        echo '<div class="form-group col-md-6">
		        <label class="form-label">Date To</label>
		        <input type="text" name="dateTo" class="form-control datePicker" required>';
        echo '</div>';
        echo '</div>';
    }

    public function renew_supplier_contract()
    {
        $input = $this->request->getPost();
        $cond = ['supplier_id' => $input['supplier_id'], 'id' => $input['user_id']];
        $user = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);

        $data = array(
            'supplier_id'   => $input['supplier_id'],
            'date_from'     => $user['date_from'],
            'date_to'       => $user['date_to'],
            'created_at'    => $this->timestamp,
            'updated_at'    => $this->timestamp
        );
        $history = $this->setup_model->insertData('tk_corp', 'promo_supplier_contract_hist', $data);

        $data = array(
            'date_from'     => $input['dateFrom'],
            'date_to'       => $input['dateTo'],
            'updated_at'    => $this->timestamp
        );
        $update = $this->setup_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);

        if ($history && $update) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier contract has been updated.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function setup_cutoff()
    {
        $input = $this->request->getPost();
        $supplier_id = $input['supplier_id'];
        echo '<input type="hidden" name="trigger">';
        echo '<input type="hidden" name="supplier_id" value="' . $supplier_id . '">';
        echo '<table class="table table-bordered" width="100%">';
        echo '<tr>
                <th>Cut-off</th>
                <td class="text-center"><input type="checkbox" class="selected_0" onchange="setCutoff(0)" /></td>';
        echo '</tr>';

        $i = 1;
        $cond = ['remark' => 'active'];
        $query = $this->setup_model->getResultArray('tk_corp', 'promo_schedule', $cond);
        foreach ($query as $row) {

            if ($row['endFC'] == '') {
                $endFC = "Last";
            } else {
                $endFC = $row['endFC'];
            }

            $statCut = $row['statCut'];
            $cutoff = $row['startFC'] . "-" . $endFC . " / " . $row['startSC'] . "-" . $row['endSC'];
            $cond = ['statCut' => $statCut, 'supplier_id' => $supplier_id];
            $co = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_cutoff', $cond);
            $checked = ($co) ? 'checked' : '';
            echo '<tr>
                    <th>' . $cutoff . '</th>
                    <td class="text-center">
                        <input type="checkbox" name="cutoff[]" value="' . $statCut . '" class="cutoff selected_' . $i . '"  onchange="setCutoff(\'' . $i . '\')" ' . $checked . '>
                    </td>';
            echo '</tr>';
            $i++;
        }
        echo '</table>';
    }

    public function insert_com_cut()
    {
        $input = $this->request->getPost();
        $cond = ['supplier_id' => $input['supplier_id'], 'statCut' => $input['statCut']];
        $cutoff = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_cutoff', $cond);
        if (!$cutoff) {
            $data = array(
                'supplier_id'   => $input['supplier_id'],
                'statCut'       => $input['statCut']
            );

            $insert = $this->setup_model->insertData('tk_corp', 'promo_supplier_cutoff', $data);
            if ($insert) {
                echo json_encode(['message' => 'success', 'feedback' => 'Supplier cutoff has been added.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        } else {
            echo json_encode(['message' => 'Cutoff already exists.']);
        }
    }

    public function delete_com_cut()
    {
        $input = $this->request->getPost();
        $cond = ['supplier_id' => $input['supplier_id'], 'statCut' => $input['statCut']];
        $cutoff = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_cutoff', $cond);
        if ($cutoff) {
            $delete = $this->setup_model->deleteData('tk_corp', 'promo_supplier_cutoff', $cond);
            if ($delete) {
                echo json_encode(['message' => 'success', 'feedback' => 'Supplier cutoff has been removed.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        } else {
            echo json_encode(['message' => 'Cutoff does not exists.']);
        }
    }

    public function insertAll_com_cut()
    {
        $input = $this->request->getPost();

        $cond = ['remark' => 'active'];
        $query = $this->setup_model->getResultArray('tk_corp', 'promo_schedule', $cond);
        foreach ($query as $row) {
            $statCut = $row['statCut'];
            $cond = ['supplier_id' => $input['supplier_id'], 'statCut' => $statCut];
            $cutoff = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_cutoff', $cond);
            if (!$cutoff) {
                $data = ['supplier_id' => $input['supplier_id'], 'statCut' => $statCut];
                $insert = $this->setup_model->insertData('tk_corp', 'promo_supplier_cutoff', $data);
            }
        }

        if ($insert) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier cutoff(s) has been added.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function deleteAll_com_cut()
    {
        $input = $this->request->getPost();
        $cond = ['supplier_id' => $input['supplier_id']];
        $delete = $this->setup_model->deleteData('tk_corp', 'promo_supplier_cutoff', $cond);
        if ($delete) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier cutoff(s) has been removed.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function supplier_reset_password()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['user_id']];
        $password = password_hash('Altsportal2019', PASSWORD_DEFAULT);
        $data = ['password' => $password];
        $reset = $this->setup_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
        if ($reset) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier user password has been reset.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function supplier_user_status()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['user_id']];
        $user = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
        $data = ($user['status'] == 1) ? ['status' => 0] : ['status' => 1];
        $status = ($user['status'] == 1) ? 'deactivated' : 'activated';
        $update = $this->setup_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
        if ($update) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier user status has been ' . $status . '.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function supplier_user_details()
    {
        $input = $this->request->getPost();
        $cond = ['id' => $input['supplier_id']];
        $supp = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        $cond = ['supplier_id' => $input['supplier_id'], 'id' => $input['user_id']];
        $user = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
        $cond = ['id' => $supp['supplier_type']];
        $type = $this->setup_model->getRowArray('tk_corp', 'promo_supplier_type', $cond)['type'] ?? '';

        $photo = $user['photo'] ?? 'assets/img/user/admin.png';
        $status = '<span class="badge bg-warning mt-1">Inactive</span>';
        if ($user['status'] == 1) {
            $status = '<span class="badge bg-green mt-1">Active</span>';
        }

        $statSup = ($supp['status']) ? 'Active' : 'Inactive';
        $statUsr = ($user['status']) ? 'Active' : 'Inactive';

        $contract = date('F d, Y', strtotime($user['date_from'])) . ' to ' . date('F d, Y', strtotime($user['date_to']));
        echo '<div class="row">';
        echo '<div class="col-md-2 d-flex flex-column align-items-center text-center">
                <img src="' . base_url($photo) . '" alt="img" class="img-thumbnail" width="120" height="120">
                <span><b>' . $type . '</b></span>' . $status;
        echo '</div>';
        echo '<div class="col-md-10">';
        echo '<table class="table table-sm table-borderless">
                <tbody>
                <tr>
                    <th colspan="3" width="25%">Supplier Name</th>
                    <td>: ' . $supp['supplier'] . '</td>
                </tr>
                 <tr>
                    <th colspan="3" width="25%">Supplier Contract</th>
                    <td>: ' . $contract . '</td>
                </tr>
                </tbody>';
        echo '</table>';
        echo '<table class="table table-sm table-borderless">
                <tbody>
                <tr>
                    <th width="25%">Supplier ID</th>
                    <td>: ' . $supp['id'] . ' (' . $statSup . ')</td>
                    <th width="25%">Supplier User ID</th>
                    <td>: ' . $user['id'] . ' (' . $statUsr . ')</td>
                </tr>
                <tr>
                    <th width="25%">Supplier Address</th>
                    <td>: ' . $user['address'] . '</td>
                    <th width="25%">Supplier Email</th>
                    <td>: ' . $user['email_address'] . '</td>
                </tr>
                <tr>
                    <th width="25%">Supplier Telephone No.</th>
                    <td>: ' . $user['telephone'] . '</td>
                    <th width="25%">Supplier Cellphone No.</th>
                    <td>: ' . $user['cellphone'] . '</td>
                </tr>
                </tbody>';
        echo '</table>';

        echo '</div>';
        echo '</div>';
    }


    public function consignor_account_list()
    {
        $data = [];
        $query = $this->setup_model->consignor_account_list();
        foreach ($query as $row) {
            $action = '<a href="javascript:;" title="extend contract"><i id="extend|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-calendar-plus fs-15px text-primary action"></i></a>';
            // $action .= '<a href="javascript:;"><i id="cutoff|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-bookmark ms-2 fs-15px text-primary action"></i></a>';

            if ($row['status'] == 1) {
                $action .= '<a href="javascript:;" title="update status"><i id="deactivate|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-toggle-on ms-2 fs-15px text-primary action"></i></a>';
                $status = '<span class="badge bg-green">Active</span>';
            } else {
                $action .= '<a href="javascript:;" title="update status"><i id="activate|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-toggle-off ms-2 fs-15px text-secondary action"></i></a>';
                $status = '<span class="badge bg-warning">Inactive</span>';
            }

            $action .= '<a href="javascript:;" title="reset password"><i id="reset|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-solid fa-rotate-right ms-2 fs-15px text-primary action"></i></a>';
            $action .= '<a href="javascript:;" title="tag vendor(s)"><i id="update|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-pen-to-square ms-2 fs-15px text-primary action"></i></a>';
            $action .= '<a href="javascript:;" title="view details"><i id="details|' . $row['supplier_id'] . '|' . $row['id'] . '" class="fa-regular fa-folder-open ms-2 fs-15px text-primary action"></i></a>';

            $sub_array = array();
            $sub_array[] = $row['supplier'];
            $sub_array[] = $row['username'];
            $sub_array[] = date('M. d, Y', strtotime($row['date_from'])) . ' - ' . date('M. d, Y', strtotime($row['date_to']));
            $sub_array[] = $status;
            $sub_array[] = $row['first_login'] != null ? 'yes' : 'no';
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function setup_consignor()
    {
        $id = $this->request->getPost('id');
        $cond = ['id' => $id];
        $supplier = $this->setup_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        $vendor = $this->setup_model->vendor_list();

        echo '<div class="row">';
        echo '<div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">&nbsp;</label>
                    <a href="javascript:;" class="btn btn-primary d-block" onclick="generateUsername()">Generate Username</a>
                </div>';
        echo '</div>';
        echo '<div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" value="Altsportal2019" disabled>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Date From</label>
                    <input type="text" id="dateFrom" name="dateFrom" class="form-control datePicker" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Date To</label>
                    <input type="text" id="dateTo" name="dateTo" class="form-control datePicker" required>
                </div>';
        echo '</div>';
        echo '<div class="col-md-12 table-responsive">';
        echo '<label class="form-label">Select Vendor(s)</label>';
        echo '<table id="dt-vendors" class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Vendor Code</th>
                        <th>Vendor Name</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($vendor as $row) {
            echo    '<tr>
                        <td><input type="checkbox" name="vendor_id[]" value="' . $row['vendor_id'] . '" class="form-check-input"></td>
                        <td>' . $row['vendor_code'] . '</td>
                        <td>' . $row['vendor_name'] . '</td>
                    </tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }

    public function create_consignor_account()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'promo_supplier_user';
        $cond   = ['username' => $input['username']];
        $check  = $this->setup_model->getRowArray($server, $table, $cond);
        if ($check) {
            echo json_encode(['message' => 'exist', 'feedback' => 'Consignor username already exist.']);
        } else {
            $password = password_hash('Altsportal2019', PASSWORD_DEFAULT);
            $data = [
                'supplier_id'   => $input['supplier'],
                'username'      => $input['username'],
                'password'      => $password,
                'date_from'     => date("Y-m-d", strtotime($input['dateFrom'])),
                'date_to'       => date("Y-m-d", strtotime($input['dateTo'])),
                'created_at'    => $this->timestamp
            ];

            $user_id = $this->setup_model->insertData($server, $table, $data);
            $cond = ['vendor_id' => $input['supplier']];
            $delete = $this->setup_model->deleteData($server, 'tbl_supplier_vendor_codes', $cond);
            foreach ($input['vendor_id'] as $vendor_id) {
                $cond = ['vendor_id' => $vendor_id];
                $vendor = $this->setup_model->getRowArray($server, 'tbl_vendor', $cond);
                $data = [
                    'vendor_id'   => $input['supplier'],
                    'vendor_code' => $vendor['vendor_code'],
                    'vendor_name' => $vendor['vendor_name'],
                    'created_at'  => $this->timestamp
                ];
                $insert = $this->setup_model->insertData($server, 'tbl_supplier_vendor_codes', $data);
            }

            if ($user_id && $insert) {
                echo json_encode(['message' => 'success', 'feedback' => 'Consignor user account has been added.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function edit_tag_vendor()
    {
        $input  = $this->request->getPost();
        echo '<div class="row">';
        echo '<input type="hidden" name="supplier_id" value="' . $input['supplier_id'] . '">';
        echo '<div class="col-md-12 table-responsive">';
        echo '<table id="dt-vendors" class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Vendor Code</th>
                        <th>Vendor Name</th>
                    </tr>
                </thead>
                <tbody>';
        $server = 'tk_corp';
        $cond = ['vendor_id' => $input['supplier_id']];
        $vendor = $this->setup_model->getResultArray($server, 'tbl_supplier_vendor_codes', $cond);
        foreach ($vendor as $row) {
            echo    '<tr>
                        <td><input type="checkbox" name="vendor_id[]" value="' . $row['vendor_id'] . '" class="form-check-input" checked></td>
                        <td>' . $row['vendor_code'] . '</td>
                        <td>' . $row['vendor_name'] . '</td>
                    </tr>';
        }

        $vendor = $this->setup_model->vendor_list();
        foreach ($vendor as $row) {
            $cond = ['vendor_id' => $input['supplier_id'], 'vendor_code' => $row['vendor_code']];
            $check = $this->setup_model->getResultArray($server, 'tbl_supplier_vendor_codes', $cond);
            if (!$check) {
                echo    '<tr>
                            <td><input type="checkbox" name="vendor_id[]" value="' . $row['vendor_id'] . '" class="form-check-input"></td>
                            <td>' . $row['vendor_code'] . '</td>
                            <td>' . $row['vendor_name'] . '</td>
                        </tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';

        // $fetch_data = $this->input->get(NULL, TRUE);

        // $supplier_vendors = $this->setup_model->get_supplier_code($fetch_data['supplier_id']);
        // $vendor_code = array();
        // foreach ($supplier_vendors as $sv) {

        //     array_push($vendor_code, $sv->vendor_code);
        // }

        // $data['vendors'] = $this->setup_model->vendor_list();
        // $data['vendor_code'] = $vendor_code;

        // $supplier = $this->setup_model->supplier_details($fetch_data['supplier_id']);
        // $data['supplier'] = $supplier;
        // $data['user_id'] = $fetch_data['id'];
        // $data['request'] = "edit_tag_vendor";

        // $this->load->view('body/modal_response', $data);
    }

    public function update_consignor_account()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $cond   = ['vendor_id' => $input['supplier_id']];
        $delete = $this->setup_model->deleteData($server, 'tbl_supplier_vendor_codes', $cond);
        foreach ($input['vendor_id'] as $vendor_id) {
            $cond = ['vendor_id' => $vendor_id];
            $vendor = $this->setup_model->getRowArray($server, 'tbl_vendor', $cond);
            $data = [
                'vendor_id'   => $input['supplier_id'],
                'vendor_code' => $vendor['vendor_code'],
                'vendor_name' => $vendor['vendor_name'],
                'created_at'  => $this->timestamp
            ];
            $insert = $this->setup_model->insertData($server, 'tbl_supplier_vendor_codes', $data);
        }

        if ($insert) {
            echo json_encode(['message' => 'success', 'feedback' => 'Supplier vendor has been updated.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }


    public function fetch_users()
    {
        $data = [];
        $query = $this->setup_model->admin_user();
        foreach ($query as $row) {

            if ($row['status'] == 'active') {
                $action = '<a href="javascript:;" title="update status"><i id="deactivate|' . $row['user_no'] . '" class="fa-solid fa-toggle-on ms-2 fs-15px text-primary action"></i></a>';
                $status = '<span class="badge bg-green">Active</span>';
            } else {
                $action = '<a href="javascript:;" title="update status"><i id="activate|' . $row['user_no'] . '" class="fa-solid fa-toggle-off ms-2 fs-15px text-secondary action"></i></a>';
                $status = '<span class="badge bg-warning">Inactive</span>';
            }
            $action .= '<a href="javascript:;" title="reset password"><i id="reset|' . $row['user_no'] . '" class="fa-solid fa-rotate-right ms-2 fs-15px text-primary action"></i></a>';
            if (session('adminType') == 'superadmin') {
                $action .= '<a href="javascript:;" title="update details"><i id="update|' . $row['user_no'] . '" class="fa-regular fa-pen-to-square ms-2 fs-15px text-primary action"></i></a>';
            }

            $sub_array = array();
            $sub_array[] = $row['fullname'];
            $sub_array[] = $row['username'];
            $sub_array[] = $row['server_loc'];
            $sub_array[] = $status;
            $sub_array[] = $row['date_added'];
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function add_adminUser()
    {
        echo '<div class="form-group mb-3">
                <label class="form-label">Search Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Lastname, Firstname Middlename" required>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>';
        echo '</div>';
        echo '<div class="col-md-6">
                <label class="form-label">Server Location</label>
                <select name="server_loc" class="form-control" required>
                    <option value="">Select Server Location</option>
                    <option value="server_tag">Corporate</option>
                    <option value="server_colonnade">Colonnade</option>
                    <option value="server_tal">Alturas Talibon</option>
                    <option value="server_tub">Alturas Tubigon</option>
                </select>';
        echo '</div>';
        echo '</div>';
    }

    public function submit_adminUser()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'admin_user';
        $cond   = ['username' => $input['username']];
        $check  = $this->setup_model->getRowArray($server, $table, $cond);
        if ($check) {
            echo json_encode(['message' => 'exist', 'feedback' => 'Admin username already exist.']);
        } else {
            if ($input['server_loc'] == 'server_tal') {
                $location = 'Alturas Talibon';
            } else if ($input['server_loc'] == 'server_tub') {
                $location = 'Alturas Tubigon';
            } else if ($input['server_loc'] == 'server_colonnade') {
                $location = 'Colonnade';
            } else {
                $location = 'Corporate';
            }

            $password = password_hash('Hrms2014', PASSWORD_DEFAULT);
            $data = [
                'username'      => $input['username'],
                'password'      => $password,
                'fullname'      => ucwords(strtolower($input['fullname'])),
                'server'        => $input['server_loc'],
                'server_loc'    => $location,
                'status'        => 'active',
                'date_added'    => $this->timestamp
            ];

            $user_id = $this->setup_model->insertData($server, $table, $data);
            if ($user_id) {
                echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been added.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function update_adminUser()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'admin_user';
        $cond = ['user_no' => $input['user_id']];
        $row  = $this->setup_model->getRowArray($server, $table, $cond);
        echo '<input type="hidden" name="user_no" value="' . $input['user_id'] . '">';
        echo '<div class="form-group mb-3">
                <label class="form-label">Fullname</label>
                <input type="text" name="fullname" value="' . $row['fullname'] . '" class="form-control" placeholder="Lastname, Firstname Middlename" required>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="' . $row['username'] . '" class="form-control" required>';
        echo '</div>';
        echo '<div class="col-md-6">
                <label class="form-label">Server Location</label>
                <select name="server_loc" class="form-control" required>
                    <option value="">Select Server Location</option>';
        echo        '<option value="server_tag" ' . ($row['server'] == 'server_tag' ? 'selected' : '') . '>Corporate</option>';
        echo        '<option value="server_colonnade" ' . ($row['server'] == 'server_colonnade' ? 'selected' : '') . '>Colonnade</option>';
        echo        '<option value="server_tal" ' . ($row['server'] == 'server_tal' ? 'selected' : '') . '>Alturas Talibon</option>';
        echo        '<option value="server_tub" ' . ($row['server'] == 'server_tub' ? 'selected' : '') . '>Alturas Tubigon</option>';
        echo '</select>';
        echo '</div>';
        echo '</div>';




        // $data['user_no'] = $this->input->post('userId', TRUE);
        // $data['request'] = "update_adminUser";

        // $this->load->view('body/modal_response', $data);
    }

    public function submit_updated_userAdmin()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'admin_user';
        $cond   = ['username' => $input['username'], 'user_no !=' => $input['user_no']];
        $check  = $this->setup_model->getRowArray($server, $table, $cond);
        if ($check) {
            echo json_encode(['message' => 'exist', 'feedback' => 'Admin username already exist.']);
        } else {
            if ($input['server_loc'] == 'server_tal') {
                $location = 'Alturas Talibon';
            } else if ($input['server_loc'] == 'server_tub') {
                $location = 'Alturas Tubigon';
            } else if ($input['server_loc'] == 'server_colonnade') {
                $location = 'Colonnade';
            } else {
                $location = 'Corporate';
            }

            $data = [
                'username'      => $input['username'],
                'fullname'      => ucwords(strtolower($input['fullname'])),
                'server'        => $input['server_loc'],
                'server_loc'    => $location,
                'date_updated'  => $this->timestamp
            ];
            $cond = ['user_no' => $input['user_no']];
            $update = $this->setup_model->updateData($server, $table, $cond, $data);
            if ($update) {
                echo json_encode(['message' => 'success', 'feedback' => 'Admin user account has been updated.']);
            } else {
                echo json_encode(['message' => 'failed']);
            }
        }
    }

    public function reset_password()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'admin_user';
        $password = password_hash('Hrms2014', PASSWORD_DEFAULT);
        $data = [
            'password' => $password,
        ];
        $cond = ['user_no' => $input['user_id']];
        $update = $this->setup_model->updateData($server, $table, $cond, $data);
        if ($update) {
            echo json_encode(['message' => 'success', 'feedback' => 'Admin user password has been reset.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function user_status()
    {
        $input  = $this->request->getPost();
        $server = 'tk_corp';
        $table  = 'admin_user';
        $cond   = ['user_no' => $input['user_id']];
        $user   = $this->setup_model->getRowArray($server, $table, $cond);
        $status = ($user['status'] == 'active') ? 'deactivated' : 'activated';
        $data   = ($user['status'] == 'active') ? ['status' => 'inactive'] : ['status' => 'active'];
        $update = $this->setup_model->updateData($server, $table, $cond, $data);
        if ($update) {
            echo json_encode(['message' => 'success', 'feedback' => 'Admin user status has been ' . $status . '.']);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }
}
