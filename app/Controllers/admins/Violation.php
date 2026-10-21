<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Violation extends BaseController
{

    public function display_cutoff()
    {
        $cutoff = $this->request->getPost('cutoff');
        $data = [];
        $query = $this->violation_model->display_1stcutoff($cutoff);
        foreach ($query as $row) {
            $dateFrom = strtotime($row['dateFrom']);
            $df = date('M. d, Y', strtotime($row['dateFrom']));
            $dt = date('M. d, Y', strtotime($row['dateTo']));
            $id = $cutoff . '|' . $df . '|' . $dt;

            $sub_array = array();
            $sub_array[] = '<span style="display:none;">' . $dateFrom . '</span>' . $df;
            $sub_array[] = $dt;
            $sub_array[] = '<a href="javascript:;" id="' . $id . '" class="record"><i class="fa-solid fa-list fs-20px text-primary"></i></a>';
            $data[] = $sub_array;
        }

        $query = $this->violation_model->display_2ndcutoff($cutoff);
        foreach ($query as $row) {
            $dateFrom = strtotime($row['dateFrom']);
            $df = date('M. d, Y', strtotime($row['dateFrom']));
            $dt = date('M. d, Y', strtotime($row['dateTo']));
            $id = $cutoff . '|' . $df . '|' . $dt;

            $sub_array = array();
            $sub_array[] = '<span style="display:none;">' . $dateFrom . '</span>' . $df;
            $sub_array[] = $dt;
            $sub_array[] = '<a href="javascript:;" id="' . $id . '" class="record"><i class="fa-solid fa-list fs-20px text-primary"></i></a>';
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function view_company_list()
    {
        $input = $this->request->getPost();
        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));
        $data = [];
        $table = 'locate_promo_company';
        $server = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $companies = $this->violation_model->getCompanyViolationCount($server, $df, $dt);
        foreach ($companies as $row) {
            $click = 'onclick="uploadViolations(\'' . $row['pc_code'] . '\',\'' . $input['cutoff'] . '\',\'' . $df . '\',\'' . $dt . '\')"';
            $action = '<a href="javascript:;" ' . $click . ' title="Upload Violation"><i class="fa-solid fa-cloud-arrow-up fs-20px text-primary"></i></a>';
            $check = $row['count'];
            if ($check) {
                $click = 'onclick="viewViolations(\'' . $row['pc_name'] . '\',\'' . $input['cutoff'] . '\',\'' . $df . '\',\'' . $dt . '\')"';
                $action .= '<a href="javascript:;" ' . $click . ' title="View Violation"><i class="fa-solid fa-folder-plus fs-20px ms-2 text-primary"></i></a>';
            } else {
                $action .= '<i title="No Uploaded Violation" class="fa-solid fa-folder-minus fs-20px ms-2 text-secondary"></i>';
            }

            $sub_array = [];
            $sub_array[] = $row['pc_name'];
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function view_violation()
    {
        $input = $this->request->getPost();
        $server = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $cond = ['company_name' => $input['company'], 'date_from' => $input['dateFrom'], 'date_to' => $input['dateTo']];
        $query = $this->violation_model->getResultArray($server, 'promo_violation', $cond);
        echo '<div class="row">';
        echo '<input type="hidden" name="cutoff" value="' . $input['cutoff'] . '">';
        echo '<div class="col-md-9 violation_uploaded">
        	    <label class="form-label">Date Uploaded: <strong>' . date('F d, Y h:i A', strtotime($query[0]['created_at'])) . '</strong></label>';
        echo '</div>';
        echo '<div class="col-md-3 mb-2 d-flex text-right">
        	    <label class="form-label me-1 pt-2">Page</label>
        	    <select name="violation_id" class="form-select form-select-sm" onchange="view_image(this.value)">';

        $i = 1;
        foreach ($query as $row) {
            echo '<option value="' . $row['id'] . '">' . $i . '</option>';
            $i++;
        }

        echo    '</select>';
        echo '</div>';
        echo '<div class="col-md-12 violation">';
        echo '<img src="' . base_url() . '' . $query[0]['image_path'] . '" width="100%" loading="lazy">';
        echo '</div>';
        echo '</div>';
    }

    public function view_image()
    {
        $input = $this->request->getPost();
        $server = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $cond = ['id' => $input['id']];
        $image = $this->violation_model->getRowArray($server, 'promo_violation', $cond);
        if ($image) {
            echo json_encode([
                'image_path' => $image['image_path'],
                'created_at' => date('F d, Y h:i A', strtotime($image['created_at']))
            ]);
        }
    }

    public function upload_violation_form()
    {
        $input = $this->request->getPost();
        $server1 = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $server2 = (session('server') == 'server_colonnade') ? 'tk_cebo' : 'tk_corp';

        $cond = ['pc_code' => $input['pc_code']];
        $pc_name = $this->violation_model->getRowArray($server1, 'locate_promo_company', $cond)['pc_name'];

        $cond = ['company_name' => $pc_name];
        $agency = $this->violation_model->companyAgency($server2, $cond);
        if ($agency) {
            echo '<div class="form-group mb-3">
			        <label class="form-label">Agency</label>
			        <select name="agency" class="form-control" required>
				        <option value=""> --Select-- </option>';
            foreach ($agency as $key => $row) {
                $cond = ['agency_code' => $row['agency_code']];
                $agency_name = $this->violation_model->getRowArray($server2, 'promo_locate_agency', $cond)['agency_name'];
                echo '<option value="' . $row['agency_code'] . '">' . $agency_name . '</option>';
            }
            echo    '</select>';
            echo '</div>';
            echo '<div class="form-group mb-3">
		        	<label class="form-label">Company</label>
		        	<input class="form-control" name="company" value="' . $pc_name . '" readonly required/>';
            echo '</div>';
        } else {

            echo '<div class="form-group mb-3">
			        <label class="form-label">Company</label>
			        <input type="hidden" name="agency" value="0">
			        <input class="form-control" name="company" value="' . $pc_name . '" readonly required/>';
            echo '</div>';
        }

        echo '<div class="form-group">
		        <label class="form-label">Upload Violation(s)</label>
                <input type="hidden" name="pc_code" value="' . $input['pc_code'] . '">
                <input type="hidden" name="cutoff" value="' . $input['cutoff'] . '">
                <input type="hidden" name="dateFrom" value="' . $input['dateFrom'] . '">
                <input type="hidden" name="dateTo" value="' . $input['dateTo'] . '">
		        <input type="file" class="form-control" name="files[]" multiple accept="image/png, image/jpeg, image/jpg" required>';
        echo '</div>';
    }

    public function upload_violation()
    {
        $input = $this->request->getPost();
        $cutoff = $input['cutoff'];
        $server = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $df = $input['dateFrom'];
        $dt = $input['dateTo'];
        $check = true;
        $i = 1;
        $files = $this->request->getFileMultiple('files');
        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                $ext = pathinfo($file->getClientName(), PATHINFO_EXTENSION);
                $path = FCPATH . 'assets/img/violation';
                $name = $input['pc_code'] . '_' . $df . '_' . $dt . '_' . date('YmdHis') . '_' . $i . '.' . $ext;
                if ($file->move($path, $name)) {

                    $data = array(
                        'agency_code'   => $input['agency'],
                        'company_name'  => $input['company'],
                        'date_from'     => $df,
                        'date_to'       => $dt,
                        'image_path'    => 'assets/img/violation/' . $name,
                        'created_at'    => $this->timestamp,
                        'updated_at'    => $this->timestamp
                    );

                    $insert = $this->setup_model->insertData($server, 'promo_violation', $data);
                    if (!$insert) {
                        $check = false;
                    }
                }
            }
            $i++;
        }

        if ($check) {
            echo json_encode([
                'message'   => 'success',
                'feedback'  => 'Violation(s) has been uploaded.',
                'cutoff'    => $cutoff,
                'dateFrom'  => $df,
                'dateTo'    => $dt,
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }

    public function delete_violation()
    {
        $input = $this->request->getPost();
        $cutoff = $input['cutoff'];
        $server = (session('server') == 'server_colonnade') ? 'pis_cebo' : 'pis_corp';
        $cond = ['id' => $input['violation_id']];
        $row = $this->violation_model->getRowArray($server, 'promo_violation', $cond);
        $df = $row['date_from'];
        $dt = $row['date_to'];

        $delete = $this->setup_model->deleteData($server, 'promo_violation', $cond);
        if ($delete) {
            echo json_encode([
                'message' => 'success',
                'feedback' => 'Violation has been removed.',
                'cutoff' => $cutoff,
                'dateFrom' => $df,
                'dateTo' => $dt,
            ]);
        } else {
            echo json_encode(['message' => 'failed']);
        }
    }
}
