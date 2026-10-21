<?php

namespace App\Controllers;

class Violation extends BaseController
{
    public function display_supplier_cutoff()
    {
        $cutoff = $this->request->getPost('cutoff');
        $data = [];
        $query = $this->dtr_model->display_1stcutoff($cutoff);
        foreach ($query as $row) {
            $dateFrom = strtotime($row['dateFrom']);
            $df = date('M. d, Y', strtotime($row['dateFrom']));
            $dt = date('M. d, Y', strtotime($row['dateTo']));
            $id = $cutoff . '|1|' . $df . '|' . $dt;

            $sub_array = array();
            $sub_array[] = '<span style="display:none;">' . $dateFrom . '</span>' . $df;
            $sub_array[] = $dt;
            $sub_array[] = '<a href="javascript:;" id="' . $id . '" class="record"><i class="fa-solid fa-list fs-20px text-primary"></i></a>';
            $data[] = $sub_array;
        }

        $query = $this->dtr_model->display_2ndcutoff($cutoff);
        foreach ($query as $row) {
            $dateFrom = strtotime($row['dateFrom']);
            $df = date('M. d, Y', strtotime($row['dateFrom']));
            $dt = date('M. d, Y', strtotime($row['dateTo']));
            $id = $cutoff . '|2|' . $df . '|' . $dt;

            $sub_array = array();
            $sub_array[] = '<span style="display:none;">' . $dateFrom . '</span>' . $df;
            $sub_array[] = $dt;
            $sub_array[] = '<a href="javascript:;" id="' . $id . '" class="record"><i class="fa-solid fa-list fs-20px text-primary"></i></a>';
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function server_code_sup()
    {
        $input = $this->request->getPost();
        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));

        echo '<div class="checkout">';
        echo '<div class="checkout-header">';
        echo '<div class="row">';

        $alturas = $this->dtr_model->supplier_ac_code('tk_corp');
        if ($alturas) {
            $clk = 'onclick="company_list(\'' . $input['cutoff'] . '\', \'tagbilaran\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-6 col-sm-6">
                    <div class="step active step_tagbilaran">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">1</div>
                            <div class="info">
                                <div class="title">Tagbilaran, Bohol</div>
                                <div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta, Alturas Panglao, Alturas Tubigon, Alturas Talibon</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-6 col-sm-6">
                    <div class="step step_tagbilaran" style="color: #6c757d;">
                        <div class="number">1</div>
                        <div class="info">
                            <div class="title">Tagbilaran, Bohol</div>
                             <div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta, Alturas Panglao, Alturas Tubigon, Alturas Talibon</div>
                        </div>
                    </div>';
            echo '</div>';
        }

        $colonnade = $this->dtr_model->supplier_ac_code('tk_cebo');
        if ($colonnade) {
            $clk = 'onclick="company_list(\'' . $input['cutoff'] . '\',\'cebu\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-6 col-sm-6">
                    <div class="step step_cebu">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">2</div>
                            <div class="info">
                                <div class="title">Cebu</div>
                                <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-6 col-sm-6">
                    <div class="step step_cebu" style="color: #6c757d;">
                            <div class="number">2</div>
                            <div class="info">
                                <div class="title">Cebu</div>
                                <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                            </div>
                    </div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        echo '<input type="hidden" name="dateFrom" value="' . $df . '">';
        echo '<input type="hidden" name="dateTo" value="' . $dt . '">';
        echo '<input type="hidden" name="cut" value="' . $input['cut'] . '">';
        echo '<input type="hidden" name="statCut" value="' . $input['cutoff'] . '">';

        echo '<div class="checkout-body" style="padding: 10px 0px;">';
        echo '<div class="table-responsive">
                <table id="company_list" class="table table-bordered table-hover table-sm align-middle" width="100%">
                    <thead>
                        <tr>
                            <th> Company</th>
							<th> Action</th>
                        </tr>
                    </thead>
                </table>';
        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

    public function company_list()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));

        $supplierType = session('supplierType');

        $ac_code = $this->dtr_model->supplier_ac_code($tk_loc);
        if ($supplierType == 1) {
            $agency_code    = $ac_code['agency_code'];
            $companies      = $this->dtr_model->companies_under_agency($tk_loc, $agency_code);
        } else {
            $company_code   = $ac_code['company_code'];
            $cond           = ['pc_code' => $company_code];
            $companies      = $this->dtr_model->getResultArray($pis, 'locate_promo_company', $cond)['pc_name'] ?? [];
        }

        $data = [];
        foreach ($companies as $company) {
            $comp = $company;

            $action = '<i title="No Uploaded Violation" class="fa-solid fa-folder-closed fs-20px text-secondary"></i>';

            if ($supplierType == 1) {
                $cond = ['agency_code' => $agency_code, 'company_name' => $company, 'date_from' => $df, 'date_to' => $dt];
                $check = $this->violation_model->getRowArray($pis, 'promo_violation', $cond);
                if ($check) {
                    $click = 'onclick="viewViolations(\'' . $pis . '\',\'' . $agency_code . '\',\'' . $comp . '\',\'' . $df . '\',\'' . $dt . '\')"';
                    $action = '<a href="javascript:;" ' . $click . ' title="View Violation"><i class="fa-solid fa-folder-closed fs-20px text-primary"></i></a>';
                }
            } else {
                $cond = ['agency_code' => 0, 'company_name' => $company, 'date_from' => $df, 'date_to' => $dt];
                $check = $this->violation_model->getResultArray($pis, 'promo_violation', $cond);
                if ($check) {
                    $click = 'onclick="viewViolations(\'' . $pis . '\',\'0\',\'' . $company_code . '\',\'' . $comp . '\',\'' . $df . '\',\'' . $dt . '\')"';
                    $action = '<a href="javascript:;" ' . $click . ' title="View Violation"><i class="fa-solid fa-folder-closed fs-20px text-primary"></i></a>';
                }
            }

            $sub_array      = [];
            $sub_array[]    = $company;
            $sub_array[]    = $action;
            $data[]         = $sub_array;
        }

        echo json_encode(array('data' => $data));
    }

    public function viewViolations()
    {
        $input = $this->request->getPost();
        $server = $input['server'];
        $cond = ['agency_code' => $input['agency'], 'company_name' => $input['company'], 'date_from' => $input['dateFrom'], 'date_to' => $input['dateTo']];
        $query = $this->violation_model->getResultArray($server, 'promo_violation', $cond);

        echo '<div class="row">';
        echo '<div class="col-md-9 violation_uploaded">
        	    <label class="form-label">Date Uploaded: <strong>' . date('F d, Y h:i A', strtotime($query[0]['created_at'])) . '</strong></label>';
        echo '</div>';
        echo '<div class="col-md-3 mb-2 d-flex text-right">
        	    <label class="form-label me-1 pt-2">Page</label>
        	    <select name="violation_id" class="form-select form-select-sm" onchange="view_image(this.value,\'' . $server . '\')">';

        $i = 1;
        foreach ($query as $row) {
            echo '<option value="' . $row['id'] . '">' . $i . '</option>';
            $i++;
        }

        echo    '</select>';
        echo '</div>';
        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<div class="col-md-12 violation">';
        echo '<img src="' . base_url() . '' . $query[0]['image_path'] . '" width="100%" loading="lazy">';
        echo '</div>';
        echo '</div>';
    }

    public function viewViolations_img()
    {
        $input = $this->request->getPost();
        $server = $input['server'];
        $cond = ['id' => $input['id']];
        $image = $this->violation_model->getRowArray($server, 'promo_violation', $cond);
        if ($image) {
            echo json_encode([
                'image_path' => $image['image_path'],
                'created_at' => date('F d, Y h:i A', strtotime($image['created_at']))
            ]);
        }
    }

    public function print_violation()
    {
        $input  = $this->request->getPostGet();
        $server = $input['server'];
        $cond   = ['id' => $input['violation_id']];
        $image  = $this->violation_model->getRowArray($server, 'promo_violation', $cond);

        $html = '<div style="text-align:center;">';
        $html .= '<img src="' . base_url($image['image_path']) . '" width="100%">';
        $html .= '</div>';

        $this->pdf->SetMargins(12, 12, 12);
        $this->pdf->WriteHTML($html);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Violation.pdf"')
            ->setBody($this->pdf->Output('', 'S'));
    }

    public function server_code_emp()
    {
        $input = $this->request->getPost();
        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));

        echo '<div class="checkout">';
        echo '<div class="checkout-header">';
        echo '<div class="row">';

        $alturas = $this->dtr_model->supplier_ac_code('tk_corp');
        if ($alturas) {
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\', \'tagbilaran\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step active step_tagbilaran">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">1</div>
                            <div class="info">
                                <div class="title">Tagbilaran, Bohol</div>
                                <div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta & Alturas Panglao</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'tubigon\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_tubigon">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">2</div>
                            <div class="info">
                                <div class="title">Tubigon, Bohol</div>
                                <div class="desc">Alturas Tubigon</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'talibon\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_talibon">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">3</div>
                            <div class="info">
                                <div class="title">Talibon, Bohol</div>
                                <div class="desc">Alturas Talibon</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step active step_tagbilaran" style="color: #6c757d;">
                        <div class="number">1</div>
                        <div class="info">
                            <div class="title">Tagbilaran, Bohol</div>
                            <div class="desc">Alturas Mall, ICM , Plaza Marcela & Alturas Panglao</div>
                        </div>
                    </div>';
            echo '</div>';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_tubigon" style="color: #6c757d;">
                        <div class="number">2</div>
                        <div class="info">
                            <div class="title">Tubigon, Bohol</div>
                            <div class="desc">Alturas Tubigon</div>
                        </div>
                    </div>';
            echo '</div>';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_talibon" style="color: #6c757d;">
                        <div class="number">3</div>
                        <div class="info">
                            <div class="title">Talibon, Bohol</div>
                            <div class="desc">Alturas Talibon</div>
                        </div>
                    </div>';
            echo '</div>';
        }

        $colonnade = $this->dtr_model->supplier_ac_code('tk_cebo');
        if ($colonnade) {
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'cebu\', \'' . $df . '\', \'' . $dt . '\')"';
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_cebu">
                        <a href="javascript:;" class="server_code" ' . $clk . '>
                            <div class="number">4</div>
                            <div class="info">
                                <div class="title">Cebu</div>
                                <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                            </div>
                        </a>
                    </div>';
            echo '</div>';
        } else {
            echo '<div class="col-md-3 col-sm-3">
                    <div class="step step_cebu" style="color: #6c757d;">
                        <div class="number">4</div>
                        <div class="info">
                            <div class="title">Cebu</div>
                            <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                        </div>
                    </div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        echo '<input type="hidden" name="dateFrom" value="' . $df . '">';
        echo '<input type="hidden" name="dateTo" value="' . $dt . '">';
        echo '<input type="hidden" name="cut" value="' . $input['cut'] . '">';
        echo '<input type="hidden" name="statCut" value="' . $input['cutoff'] . '">';

        echo '<div class="checkout-body" style="padding: 10px 0px;">';
        echo '<div class="table-responsive">
                <table id="employee_list" class="table table-bordered table-hover table-sm align-middle" width="100%">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Company</th>
                            <th>Store(s)</th>
                            <th>Position</th>
                            <th>Promo Type</th>
							<th>Action</th>
                        </tr>
                    </thead>
                </table>';
        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

    public function violation_employee_list()
    {
        $input = $this->request->getPost();
        $supplierType = session('supplierType');
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));
        $bus = $this->dtr_model->locate_promo_bu($pis);

        $ac_code = $this->dtr_model->supplier_ac_code($tk_loc);
        if ($supplierType == 1) {
            $agency_code    = $ac_code['agency_code'];
            $employees      = $this->dtr_model->promo_under_agency($input, $agency_code);
        } else {
            $company_code   = $ac_code['company_code'];
            $cond           = ['pc_code' => $company_code];
            $company        = $this->dtr_model->getRowArray($pis, 'locate_promo_company', $cond)['pc_name'] ?? '';
            $employees      = $this->dtr_model->promo_under_company($input, $company);
        }

        $data = [];
        foreach ($employees as $emp) {

            $empId      = $emp['emp_id'];
            $recordNo   = $emp['record_no'];
            $comp       = $emp['promo_company'];
            $agency     = $emp['agency_code'];

            $i = 0;
            $stores = '';
            foreach ($bus as $bu) {
                $field = $bu['bunit_field'];
                $str = '<span class="badge bg-success me-1">' . $bu['bunit_acronym'] . '</span>';
                $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
                $check = $this->dtr_model->getRowArray($pis, 'promo_record', $cond);
                if ($check) {
                    $i++;
                    $stores = ($i == 1) ? $str : $stores . $str;
                }
            }

            $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
            $assign = $this->masterfile_model->getRowArray($tk, 'assign',  $cond);
            if ($assign) {

                $action = '<i title="No Uploaded Violation" class="fa-solid fa-folder-closed fs-20px text-secondary"></i>';

                $range = "$df|$dt";
                // $cond = ['empId' => $empId, 'agency' => $agency, 'company_name' => $comp, 'dateRange' => $range];
                $cond = ['empId' => $empId, 'dateRange' => $range];
                $check = $this->violation_model->getRowArray('tk_corp', 'promo_portal_emp_list', $cond);
                if ($check) {
                    $click = 'onclick="viewViolations(\'' . $server . '\',\'' . $empId . '\',\'' . $agency . '\',\'' . addslashes($check['company_name']) . '\',\'' . $df . '\',\'' . $dt . '\')"';
                    $action = '<a href="javascript:;" ' . $click . ' title="View Violation"><i class="fa-solid fa-folder-closed fs-20px text-primary"></i></a>';
                }

                $sub_array = array();
                $sub_array[] = ucwords(strtolower($emp['name']));
                $sub_array[] = $emp['promo_company'];
                $sub_array[] = $stores;
                $sub_array[] = ucwords(strtolower($emp['position']));
                $sub_array[] = $emp['promo_type'];
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
        }

        echo json_encode(array('data' => $data));
    }

    public function viewViolations_emp()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));
        $range = "$df|$dt";

        $empId      = $input['empId'];
        $agency     = $input['agency'];
        $company    = stripslashes($input['company']);

        $cond   = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
        $emp    = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);

        $cond   = ['agency_code' => $agency];
        $agen   = $this->violation_model->getRowArray($tk, 'promo_locate_agency', $cond)['agency_name'] ?? '';
        $header = ($agen) ? $agen : $company;

        echo '<input type="hidden" name="dateFrom" value="' . $df . '">';
        echo '<input type="hidden" name="dateTo" value="' . $dt . '">';
        echo '<input type="hidden" name="empId" value="' . $empId . '">';
        echo '<input type="hidden" name="agency" value="' . $agency . '">';
        echo '<input type="hidden" name="company" value="' . $company . '">';
        echo '<input type="hidden" name="server" value="' . $server . '">';

        echo '<div class="container">';
        echo '<div class="row">
                <div class="col-md-4">
                    <center><img class="align-self-center" src="' . base_url('../assets/agc_logo.jpg') . '" width="60%"></center>
                </div>
                <div class="col-md-6" style="text-align:center;">
                    <h4>Alturas Supermarket Corporation</h4>
                    <p style="margin-top:0px; margin-bottom: 1px;">Dampas District, Tagbilaran City, Philippines</p>
                    <p style="margin-top:0px; margin-bottom: 1px;">Tel. No: (038) 501-3000; Local: 1313; Fax No: 501-9245</p>
                    <p style="margin-top:0px; margin-bottom: 1px;">Email Add: corporatehrd@alturasbohol.com</p>
                </div>
                <div class="col"></div>';
        echo '</div>';
        echo '<div style="padding-top: 50px;">
                <h5>' . $header . '</h5>';
        echo '</div>';
        echo '<div style="padding-top: 30px;">
                <p>Dear Sir/Ma\'am:</p>
                <p style="margin-top:10px;">This is in reference to you merchandiser, <b>MR/MS. ' . mb_strtoupper($emp['name']) . '</b> presently assigned in our establishment handling <b> ' . $company . ' </b> wherein he/she violated our company House Rules and Regulations with suspension/s as a corresponding disciplinary action.</p>';
        echo '</div>';
        echo '<div style="margin-top:10px;">';
        echo '<table class="table table-bordered table-sm align-middle">
                <tr>
                    <th><center>Violation\'s Commited</center></th>
                    <th><center>Area of Assignment</center></th>
                    <th><center>Violation Details</center></th>
                    <th><center>Number of Suspensions</center></th>
                </tr>';

        $range = "$df|$dt";
        // $cond = ['empId' => $empId, 'agency' => $agency, 'company_name' => $company, 'dateRange' => $range];
        $cond = ['empId' => $empId, 'dateRange' => $range];
        $vio = $this->violation_model->getRowArray('tk_corp', 'promo_portal_emp_list', $cond);
        $cond = ['emp_violation_id' => $vio['id']];
        $query = $this->violation_model->getResultArray('tk_corp', 'promo_portal_detail', $cond);
        foreach ($query as $key) {

            $cond = ['violation_id' => $key['violation_id']];
            $v_name = $this->violation_model->getRowArray('tk_corp', 'houserules', $cond)['violation_type'] ?? '';

            $cond = ['detail_id' => $key['id']];
            $pres = $this->violation_model->getResultArray('tk_corp', 'promo_portal_violation_detail', $cond);
            $prev = $this->violation_model->getResultArray('tk_corp', 'promo_portal_violation_prev_detail', $cond);

            echo '<tr>';
            echo '<td class="text-center"><b>' . $v_name . '</b></td>';
            echo '<td class="text-center"><b>' . $key['store'] . ' - ' . $key['department'] . '</b></td>';

            echo '<td>';
            if ($prev) {
                echo '<b>Previous :</b>';
                echo '<ol>';
                foreach ($prev as $value) {
                    echo '<li style="list-style-type: none;">' . $value['detail'] . '</li>';
                }
                echo '</ol>';
            }

            echo '<b>Present :</b>';
            echo '<ol>';
            foreach ($pres as $value) {
                echo '<li style="list-style-type: none;">' . $value['detail'] . '</li>';
            }
            echo '</ol>';
            echo '</td>';
            echo '<td class="text-center"><b>' . $key['suspension'] . ' day(s)</b></td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
        echo '<div style="padding-top: 10px;">
                <p>The above infraction/s forms part of his/her negative performance and attitude at work, hence we are requesting for an immidiate action regarding this matter.</p>
                <p style="margin-top:10px;">Thank you and more power.</p>
                <p style="margin-top:20px;">Respectfully yours,</p>
                <p style="margin-top:30px;margin-bottom:0px;"><b>BERNADETH M. CASTIL</b></p>
                <p>ASSISTANT CORPORATE HR MANAGER</p>';
        echo '</div>';
        echo '</div>';
    }

    public function print_employee_violation()
    {
        $input      = $this->request->getPostGet();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));

        $empId      = $input['empId'];
        $agency     = $input['agency'];
        $company    = $input['company'];

        $cond   = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
        $emp    = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);

        $cond   = ['agency_code' => $agency];
        $agen   = $this->violation_model->getRowArray($tk, 'promo_locate_agency', $cond)['agency_name'] ?? '';
        $header = ($agen) ? $agen : $company;
        $html = '<style>
                    .violation {
                        font-family: "opensans", sans-serif;
                        font-style: normal;
                        font-weight: 300;
                        font-stretch: 100%;
                    }';
        $html .= '</style>';
        $html .= '<div class="violation">';
        $html .= '<table class="header">';
        $html .= '<tr>';
        $html .= '<th width="30%" style="text-align:center">';
        $html .= '<img src="' . base_url('../assets/agc_logo.jpg') . '" alt="AGC Logo" style="width:30%;">';
        $html .= '</th>';
        $html .= '<td style="text-align:center">';
        $html .= '<h2>Alturas Supermarket Corporation</h2>';
        $html .= '<p style="margin-bottom: 1px;">Dampas District, Tagbilaran City, Philippines</p>';
        $html .= '<p style="margin-bottom: 1px;">Tel. No: (038) 501-3000; Local: 1313; Fax No: 501-9245</p>';
        $html .= '<p style="margin-bottom: 1px;">Email Add: corporatehrd@alturasbohol.com</p>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<h3 style="margin-top:50px">' . $header . '</h3>';
        $html .= '<p style="margin-top:30px">Dear Sir/Ma\'am:</p>';
        $html .= '<p>This is in reference to you merchandiser, <b>MR/MS. ' . mb_strtoupper($emp['name']) . '</b> presently assigned in our establishment handling <b>' . $company . '</b> wherein he/she violated our company House Rules and Regulations with suspension/s as a corresponding disciplinary action.</p>';
        $html .= '<style>
                    .violation_table, .violation_table th, .violation_table td {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 5px;
                        font-family: "opensans", sans-serif;
                        font-size: smaller;
                    }';
        $html .=    'li {
                        font-size: 16px;
                    }';
        $html .= '</style>';
        $html .= '<table class="violation_table">';
        $html .= '<tr>';
        $html .= '<th style="text-align:center; width:20%">Violation\'s Commited</th>';
        $html .= '<th style="text-align:center; width:20%">Area of Assignment</th>';
        $html .= '<th style="text-align:center;">Violation Details</th>';
        $html .= '<th style="text-align:center; width:15%">Number of Suspensions</th>';
        $html .= '</tr>';

        $range = "$df|$dt";
        // $cond = ['empId' => $empId, 'agency' => $agency, 'company_name' => $company, 'dateRange' => $range];
        $cond = ['empId' => $empId, 'dateRange' => $range];
        $vio = $this->violation_model->getRowArray('tk_corp', 'promo_portal_emp_list', $cond);
        $cond = ['emp_violation_id' => $vio['id']];
        $query = $this->violation_model->getResultArray('tk_corp', 'promo_portal_detail', $cond);
        foreach ($query as $key) {

            $cond = ['violation_id' => $key['violation_id']];
            $v_name = $this->violation_model->getRowArray('tk_corp', 'houserules', $cond)['violation_type'] ?? '';

            $cond = ['detail_id' => $key['id']];
            $pres = $this->violation_model->getResultArray('tk_corp', 'promo_portal_violation_detail', $cond);
            $prev = $this->violation_model->getResultArray('tk_corp', 'promo_portal_violation_prev_detail', $cond);

            $html .= '<tr>';

            $html .= '<td style="text-align:center"><b>' . $v_name . '</b></td>';
            $html .= '<td style="text-align:center"><b>' . $key['store'] . ' - ' . $key['department'] . '</b></td>';

            $html .= '<td>';
            if ($prev) {
                $html .= '<b>Previous :</b>';
                $html .= '<ol>';
                foreach ($prev as $value) {
                    $html .= '<li style="list-style-type: none;">' . $value['detail'] . '</li>';
                }
                $html .= '</ol>';
            }

            $html .= '<b>Present :</b>';
            $html .= '<ol>';
            foreach ($pres as $value) {
                $html .= '<li style="list-style-type: none;">' . $value['detail'] . '</li>';
            }
            $html .= '</ol>';
            $html .= '</td>';
            $html .= '<td style="text-align:center"><b>' . $key['suspension'] . ' day(s)</b></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '<p>The above infraction/s forms part of his/her negative performance and attitude at work, hence we are requesting for an immidiate action regarding this matter.</p>';
        $html .= '<p style="margin-top:10px;">Thank you and more power.</p>';
        $html .= '<p style="margin-top:20px;">Respectfully yours,</p>';
        $html .= '<p style="margin-top:30px;margin-bottom:0px;"><b>BERNADETH M. CASTIL</b></p>';
        $html .= '<p style="margin-top:0;margin-bottom:0px;">ASSISTANT CORPORATE HR MANAGER</p>';
        $html .= '</div>';

        $this->pdf->SetMargins(12, 12, 12);
        $this->pdf->WriteHTML($html);
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Violation.pdf"')
            ->setBody($this->pdf->Output('', 'S'));
    }
}
