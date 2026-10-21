<?php

namespace App\Controllers;

class Dtr extends BaseController
{
    public function display_cutoff()
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

    public function server_code()
    {
        $input = $this->request->getPost();
        $df = date('Y-m-d', strtotime($input['dateFrom']));
        $dt = date('Y-m-d', strtotime($input['dateTo']));

        echo '<div class="alert alert-info" style="border-left: 4px solid; border-left-color: currentcolor;" role="alert">
                <h4 class="alert-heading"><b>Reminders!</b></h4>
                <p>Updating and uploading of entries, time schedule, request will be posted a day after the current date.</p>';

        $day_before = date('Y-m-d', strtotime($input['dateTo'] . ' -1 day'));
        if (strtotime($this->today) >= strtotime($input['dateFrom']) && strtotime($this->today) <= strtotime($input['dateTo'])) {
            if ($this->today == $day_before) {
                echo '<p class="mb-0">DTR entries are still up to final posting, two(2) days after cut-off.</p>';
            }
        }
        echo '</div>';

        echo '<div class="checkout">';
        echo '<div class="checkout-header">';
        echo '<div class="row">';

        $alturas = $this->dtr_model->supplier_ac_code('tk_corp');
        if ($alturas) {
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'' . $input['cut'] . '\', \'tagbilaran\', \'' . $df . '\', \'' . $dt . '\')"';
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
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'' . $input['cut'] . '\', \'tubigon\', \'' . $df . '\', \'' . $dt . '\')"';
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
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'' . $input['cut'] . '\',\'talibon\', \'' . $df . '\', \'' . $dt . '\')"';
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
            $clk = 'onclick="employee_list(\'' . $input['cutoff'] . '\',\'' . $input['cut'] . '\',\'cebu\', \'' . $df . '\', \'' . $dt . '\')"';
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
                <table id="dt_emp_list" class="table table-bordered table-hover table-sm align-middle" width="100%">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Company</th>
                            <th>Store(s)</th>
                            <th>Position</th>
                            <th>Promo Type</th>
                            <th>Action</th>
                            <th><input type="checkbox" class="form-check-input chkA chk_0" name="selectAll" onclick="chk(0)"></th>
                        </tr>
                    </thead>
                </table>';
        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

    public function employee_list()
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

                $bioId = $assign['allowId'];
                if ($bioId == 'Logbox ID') {

                    $id = $assign['barcodeId'];
                } else {

                    $id = @$assign['biometricId'];
                }

                $load = $empId . '|' . $recordNo . '|' . $id . '|' . $input['cutoff'] . '|' . $input['cut'] . '|' . $server . '|' . $df . '|' . $dt;
                $click = 'onclick="view_dtr(\'' . $load . '\')"';
                $action = '<a href="javascript:;" ' . $click . ' title="View DTR"><i class="fa-solid fa-list fs-20px text-primary"></i></a>';
                $cb = '<input class="form-check-input dtrBox chkC chk_' . $input['cutoff'] . '" name="ids[]" value="' . $empId . '|' . $recordNo . '" type="checkbox" onclick="chk(' . $input['cutoff'] . ')">';
                $sub_array = array();
                $sub_array[] = ucwords(strtolower($emp['name']));
                $sub_array[] = $emp['promo_company'];
                $sub_array[] = $stores;
                $sub_array[] = ucwords(strtolower($emp['position']));
                $sub_array[] = $emp['promo_type'];
                $sub_array[] = $action;
                $sub_array[] = $cb;
                $data[] = $sub_array;
            } else {

                $sub_array = array();
                $sub_array[] = ucwords(strtolower($emp['name']));
                $sub_array[] = $emp['promo_company'];
                $sub_array[] = $stores;
                $sub_array[] = ucwords(strtolower($emp['position']));
                $sub_array[] = $emp['promo_type'];
                $sub_array[] = '';
                $sub_array[] = '';
                $data[] = $sub_array;
            }
        }

        echo json_encode(array('data' => $data, 'server' => $server));
    }

    public function view_dtr()
    {
        $input = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $empId      = $input['empId'];
        $recordNo   = $input['recordNo'];
        $bioM       = $input['bioM'];
        $dateFrom   = date('Y-m-d', strtotime($input['dateFrom']));
        $dateTo     = date('Y-m-d', strtotime($input['dateTo']));

        $cond       = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $allowId    = $this->dtr_model->getRowArray($tk, 'assign', $cond)['allowId'];

        $promo      = $this->masterfile_model->promoDetails($pis, $empId, $recordNo);
        $name       = ucwords(strtolower($promo['name']));
        $company    = $promo['promo_company'];
        $department = $promo['promo_department'];
        $promo_type = $promo['promo_type'];
        $position   = $promo['position'];
        $status     = $promo['current_status'];
        if ($status == 'Active') {
            $stat = '<span class="badge mb-1 bg-green">' . $status . '</span>';
        } else if ($status == 'blacklisted') {
            $stat = '<span class="badge mb-1 bg-danger">' . $status . '</span>';
        } else {
            $stat = '<span class="badge mb-1 bg-warning">' . $status . '</span>';
        }

        $i = 0;
        $stores = '';
        $bus = $this->dtr_model->locate_promo_bu($pis);
        foreach ($bus as $bu) {
            $field = $bu['bunit_field'];
            $buName = '<span class="badge bg-success me-1">' . $bu['bunit_acronym'] . '</span>';
            $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
            $check = $this->dtr_model->getRowArray($pis, 'promo_record', $cond);
            if ($check) {
                $i++;
                $stores = ($i == 1) ? $buName : $stores . $buName;
            }
        }

        $comp   = '<span class="text-truncate d-inline-block" style="max-width: 850px;" title="' . $company . '">: ' . $company . '</span>';
        $link   = base_url('assets/img/user/admin.png');

        $dw = 0;
        $ot = 0;
        $lh = 0;
        $sh = 0;
        $tbl = ($input['i'] == 1) ? '1stcutoff' : '2ndcutoff';
        $cond = array('bioMetricId' => $bioM, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo);
        $co = $this->dtr_model->getRowArray($tk, $tbl, $cond);
        if ($co) {
            if ($co['daysWork']) {
                $dw = $co['daysWork'];
                $decimal = explode('.', (string)$co['daysWork']);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $dw = number_format($co['daysWork'], 3, '.', '');
                }
            }

            if ($co['otForPayment']) {
                $ot = $co['otForPayment'];
            }

            if ($co['legHol']) {
                $lh = $co['legHol'];
            }

            if ($co['speHol']) {
                $sh = $co['speHol'];
            }
        }

        $sil = 0;
        $sil_cu = $this->masterfile_model->no_sil_current($input)['sil'] ?? 0;
        $sil_prev = $this->masterfile_model->no_sil_previous($input)['sil'] ?? 0;
        $sil = $sil_prev + $sil_cu;

        echo '<input type="hidden" name="empId" value="' . $empId . '">';
        echo '<input type="hidden" name="recordNo" value="' . $recordNo . '">';
        echo '<input type="hidden" name="dateFrom" value="' . $dateFrom . '">';
        echo '<input type="hidden" name="dateTo" value="' . $dateTo . '">';
        echo '<input type="hidden" name="cut" value="' . $input['i'] . '">';
        echo '<input type="hidden" name="server" value="' . $server . '">';
        echo '<input type="hidden" name="statCut" value="' . $input['statCut'] . '">';
        echo '<input type="hidden" name="bioM" value="' . $bioM . '">';

        echo '<div class="row">';
        echo '<div class="col-md-12">';
        echo '<div class="d-flex gap-2">';
        echo '<img src="' . $link . '" alt="image error..." class="border rounded shadow p-1 bg-white" width="100" height="100">';
        echo '<table class="table table-sm table-borderless align-middle">
                <tr>
                    <th>Employee ID</th><td>: ' . $empId . '</td>
                    <th>Name</th><td>: ' . $name . '</td>
                    <th>Days Worked</th><td>: ' . $dw . '</td>
                    <th>Legal Holiday</th><td>: ' . $lh . '</td>
                </tr>
                <tr>
                    <th>Status</th><td>: ' . $stat . '</td>
                    <th>Store(s)</th><td>: ' . $stores . '</td>
                    <th>Overtime</th><td>: ' . $ot . '</td>
                    <th>SIL</th><td>: ' . $sil . '</td>
                </tr>
                <tr>
                    <th>Department</th><td>: ' . $department . '</td>
                    <th>Position</th><td>: ' . $position . '</td>
                    <th>Special Holiday</th><td colspan="3">: ' . $sh . '</td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td colspan="7">' . $comp . '</td>
                </tr>';
        echo '</table>';
        echo '</div>';
        echo '</div>';

        echo '<div class="col-md-12">';
        echo '<div class="table-responsive">';
        echo '<table id="dt_dtrEntry" class="table table-hover align-middle" style="width:100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>IN1</th>
                        <th>OUT1</th>
                        <th>IN2</th>
                        <th>OUT2</th>
                        <th>IN3</th>
                        <th>OUT3</th>
                        <th class="text-center">HW</th>
                        <th class="text-center">DW</th>
                        <th class="text-center">OT</th>
                        <th class="text-center">Requests</th>
                        <th class="text-center">Entries</th>
                    </tr>
                </thead>';
        while (strtotime($dateFrom) <= strtotime($dateTo)) {

            $tempDate   = date('Y-m-d', strtotime($dateFrom));
            $day        = date('D', strtotime($dateFrom));

            $cond   = "bioMetricId = '$bioM' AND date = '$tempDate' ORDER BY dutyId DESC";
            $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
            $dutyId = $dtr['dutyId'] ?? '';
            $in1    = $dtr['in1'] ?? '';
            $out1   = $dtr['out1'] ?? '';
            $in2    = $dtr['in2'] ?? '';
            $out2   = $dtr['out2'] ?? '';
            $in3    = $dtr['in3'] ?? '';
            $out3   = $dtr['out3'] ?? '';

            $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in1, "I") ?? '';
            $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out1, "O") ?? '';
            $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in2, "I") ?? '';
            $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out2, "O") ?? '';
            $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in3, "I") ?? '';
            $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out3, "O") ?? '';

            if (empty($out3_store)) {
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($tempDate . " +1 day")), $out3, "O") ?? '';
            }

            if (!empty($in1)) {
                $in1 = date('h:i A', strtotime($in1));
            }

            if (!empty($out1)) {
                $out1 = date('h:i A', strtotime($out1));
            }

            if (!empty($in2)) {
                $in2 = date('h:i A', strtotime($in2));
            }

            if (!empty($out2)) {
                $out2 = date('h:i A', strtotime($out2));
            }

            if (!empty($in3)) {
                $in3 = date('h:i A', strtotime($in3));
            }

            if (!empty($out3)) {

                $out3 = date('h:i A', strtotime($out3));
            }

            $cond   = "bioMetricId = '$bioM' AND dateDuty = '$tempDate'";
            $dutyl  = $this->dtr_model->getRowArray($tk, 'duty_logbox', $cond);

            $dw = 0;
            $hw = 0;
            $cond = array('dutyId' => $dutyId);
            $perday = $this->dtr_model->getRowArray($tk, 'perday', $cond);
            if ($perday) {
                $phw = $perday['hoursWork'];
                $res = explode("&", $phw);
                if ($res[0] == '') {
                    $res[0] = 0;
                }

                if (!isset($res[1])) {
                    $res[1] = 0;
                }

                if ($res[1] == '') {
                    $res[1] = 0;
                }

                $h = floatval($res[0]) * 1;
                $m = floatval($res[1]) * 1;

                $hrs = $h / 8;
                $min = ($m / 60) / 8;

                // days worked ********************************************************************
                $dw = round($hrs + $min, 3);
                $decimal = explode('.', (string)$dw);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $dw = number_format($dw, 3, '.', '');
                }

                // hours worked ********************************************************************
                $min = round(($m / 60), 3);
                $hw =  round($h + $min, 3);
                $decimal = explode('.', (string)$h + $min);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $hw = number_format($h + $min, 3, '.', '');
                }
            }

            // overtime ********************************************************************
            $ot = 0;
            $cond = "emp_id = '$empId' AND otDate = '$tempDate' ORDER BY pay_id DESC";
            $otApp = $this->dtr_model->getRowArray($tk, 'otforpayment', $cond)['otApp'] ?? '';
            if ($otApp) {
                $ot = floatval($otApp) * 1;
            }
            // overtime ********************************************************************

            // holiday	********************************************************************
            $hol = '';
            $holiday = false;
            if ($server == 'cebu') {
                $cond = "status='active' AND hrd_location='cebo'";
                $strs = $this->dtr_model->getResultArray($pis, 'locate_promo_business_unit', $cond);
                if ($promo_type == 'STATION') {
                    foreach ($strs as $key => $str) {
                        $field = $str['bunit_field'];
                        if ($promo[$field] == 'T') {
                            if ($field == 'colc') {
                                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            } else {
                                $cond = "(store='' OR store='$field') AND (date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_outside', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            }
                        }
                    }
                } else {

                    $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                    $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_roving', $cond);
                    if ($ch) {
                        $holiday = true;
                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                        $hol    = $nH . ' [ ' . $cat . ' ]';
                    }
                }
            } else {
                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                $chk = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                if ($chk) {
                    $holiday = true;
                    $nH     = '<span class="text-danger">' . $chk['nameHoliday'] . '</span>';
                    $cat    = '<span class="text-danger">' . $chk['category'] . '</span>';
                    $hol    = $nH . ' [ ' . $cat . ' ]';
                    if ($promo_type == 'STATION' && ($promo['al_panglao'] == 'T' || $promo['cdc'] == 'T') && date('m-d', strtotime($tempDate)) == '07-01') {
                        $holiday = false;
                    }
                }
            }
            // holiday ********************************************************************

            // offduty of holiday ********************************************************************
            $hd = '';
            $odaHoliday = false;
            if ($holiday) {

                $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                    if ($sql) {
                        $hd = $amsId;
                        $odaHoliday = true;
                    }
                }
            }

            // offduty ********************************************************************               

            // dayoff ********************************************************************     
            $show_do = false;
            $old_do = false;
            $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
            $sql = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
            if ($sql) {
                foreach ($sql as $ams) {

                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId, 'empId' => $empId, 'date' => $tempDate);
                    $amd    = $this->dtr_model->getRowArray($tk, 'ass_mul_do', $cond);

                    $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);

                    if ($amd && !$acd) {
                        $show_do = true;
                    }

                    if ($amd && $acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                        $appby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                        if ($appby) {

                            $dateReq = $appby['date'];
                            $timeReq = $appby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                            $cond   = ['amsId' => $amsId];
                            $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                            if ($rns) {

                                $dateReq = $rns['dateReq'];
                                $timeReq = $rns['timeReq'];
                                $req_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                if ($app_details < $req_details) {
                                    $show_do = true;
                                }
                            }
                        }
                    }

                    $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                    if ($acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = array('requestId' => $acdId, 'requestType' => 'CDOS');
                        $aprby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                        if ($aprby) {
                            $dateReq    = $aprby['date'];
                            $timeReq    = $aprby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));

                            $cond   = array('amsId' => $amsId);
                            $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                            if ($rns) {
                                $dateReq = $rns['dateReq'];
                                $timeReq = $rns['timeReq'];

                                $req_details    = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                if ($app_details > $req_details) {
                                    $show_do = true;
                                }
                            }
                        }
                    }
                }
            } else {

                $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                if ($acd) {
                    $show_do = true;
                }

                $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                if ($acd) {
                    $old_do = true;
                }
            }

            // default day-off if $show_do is false
            if (!$sql &&  !$show_do &&  !$old_do) {
                $days = date('l', strtotime($tempDate));
                $cond = array('empId' => $empId, 'day' => date('l', strtotime($tempDate)));
                $sql = $this->dtr_model->getRowArray($tk, 'assign_day_off', $cond);
                if ($sql) {
                    $show_do = true;
                }
            }
            // dayoff ********************************************************************

            // offduty of dayoff ********************************************************************
            $do = '';
            $odaDayoff = false;
            if ($show_do) {
                $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                    if ($sql) {
                        $do = $amsId;
                        $odaDayoff = true;
                    }
                }
            }
            // offduty ********************************************************************   

            // no schedule ********************************************************************
            $no_schedule = false;
            if ($promo_type == 'ROVING') {
                $cond   = "empId = '$empId' AND dateRendered = '$tempDate' ORDER BY pId DESC";
                $pms    = $this->dtr_model->getRowArray($tk, 'promo_mul_sc', $cond);
                if ($pms) {
                    $nStore = $pms['noStore'];
                    if ($nStore == 0) {
                        $no_schedule = true;
                    }
                } else {
                    $cond   = "empId='$empId' AND day_sched='$day' ORDER BY pdId DESC";
                    $pds    = $this->dtr_model->getRowArray($tk, 'promo_def_sched', $cond);
                    if ($pds) {
                        $nStore = $pds['noStore'];
                        if ($nStore == 0) {
                            $no_schedule = true;
                        }
                    }
                }
            } else {

                $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC";
                $amsId = $this->dtr_model->getRowArray($tk, 'assign_mul_sc', $cond)['amsId'] ?? '';
                if ($amsId) {
                    $cond  = array('empId' => $empId, 'amsId' => $amsId, 'date' => $tempDate);
                    $pns   = $this->dtr_model->getRowArray($tk, 'promo_nosched', $cond);
                    if ($pns) {
                        $no_schedule = true;
                    }
                }
            }
            // no schedule ********************************************************************

            // request checking ********************************************************************
            $cond = array('empId' => $empId, 'effectiveDate' => $tempDate);
            $sqlcdol = $this->dtr_model->getRowArray($tk, 'ass_dayoff_long', $cond) ?? [];
            $numCDOL = count($sqlcdol);

            $cond = array('empId' => $empId, 'dateToChange' => $tempDate);
            $sqlcdos = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond) ?? [];
            $numCDOS = count($sqlcdos);

            //loa
            $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
            $sqlloa = $this->dtr_model->getRowArray($tk, 'r_l_absence, rla_day', $cond) ?? [];
            $numLOA = count($sqlloa);
            //end of loa

            //dwa
            $cond   = "d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$tempDate' 
                AND '$tempDate' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
            $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment, off_date', $cond) ?? [];
            $numDWA = count($sqldwa);

            if ($numDWA == 0) {
                $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') 
                    AND '$tempDate' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment', $cond) ?? [];
                $numDWA = count($sqldwa);
            }
            //end of dwa

            //eoa
            // $cond   = array('empId' => $empId, 'dateOvertime' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND dateOvertime='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleoa = $this->dtr_model->getRowArray($tk, 'e_o_authorization', $cond) ?? [];
            $numEOA = count($sqleoa);
            //end of eoa

            //EOP
            // $cond   = array('emp_id' => $empId, 'otDate' => $tempDate, 'status' => 'Approved');
            $cond   = "emp_id='$empId' AND otDate='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleop = $this->dtr_model->getRowArray($tk, 'otforpayment', $cond) ?? [];
            $numEOP = count($sqleop);
            //end of EOP

            //ECA
            // $cond   = array('empId' => $empId, 'cuDate' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND cuDate='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleca = $this->dtr_model->getRowArray($tk, 'coverup_auth', $cond) ?? [];
            $numECA = count($sqleca);
            //end of ECA

            //EUA
            // $cond   = array('empId' => $empId, 'dateUndertime' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND dateUndertime='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleua = $this->dtr_model->getRowArray($tk, 'undertime_auth', $cond) ?? [];
            $numEUA = count($sqleua);
            //end of EUA

            $ODAHD = '';
            if ($hd != '') {
                $ODAHD = '<span class="badge bg-secondary" title="Holiday">ODA</span>';
                // $ODAHD = '<a href="javascript:;" onclick="odaRequest(\'' . $promo_type . '\',\'' . $hd . '\')">' . $span . '</a>';
            }

            $ODADO = '';
            if ($do != '') {
                if ($do == $hd) {
                    $ODAHD = '';
                }
                $ODADO = '<span class="badge bg-secondary" title="Dayoff">ODA</span>';
                // $ODADO = '<a href="javascript:;" onclick="odaRequest(\'' . $promo_type . '\',\'' . $do . '\')">' . $span . '</a>';
            }

            $CDOL = '';
            if ($numCDOL != 0) {
                $span = ($sqlcdol['status'] == 'Approved') ? '<span class="badge bg-primary" >CDOL</span>' : '<span class="badge bg-warning" >CDOL</span>';
                $CDOL = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'CDOL\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }

            $CDOS = '';
            if ($numCDOS != 0) {
                $span = ($sqlcdos['status'] == 'Approved') ? '<span class="badge bg-primary" >CDOS</span>' : '<span class="badge bg-warning" >CDOS</span>';
                $CDOS = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'CDOS\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }

            $LOA = '';
            if ($numLOA != 0) {
                $span = ($sqlloa['status'] == 'Approved') ? '<span class="badge bg-primary" >LOA</span>' : '<span class="badge bg-warning" >LOA</span>';
                $LOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }

            $numSIL = 0;
            if ($numLOA != 0) {
                //sil
                $cpnd   = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                    AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') 
                    AND (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
                $sqlsil = $this->dtr_model->getRowArray($tk, 'r_l_absence, rla_day', $cpnd) ?? [];
                $numSIL = count($sqlsil);
                //end of sil
                if ($numSIL != 0) {
                    $span = ($sqlsil['status'] == 'Approved') ? '<span class="badge bg-primary" >SIL</span>' : '<span class="badge bg-warning" >SIL</span>';
                    $LOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
                }
            }

            $DWA = '';
            if ($numDWA != 0) {
                $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }
            $EOA = '';
            if ($numEOA != 0) {
                $span = ($sqleoa['status'] == 'Approved') ? '<span class="badge bg-primary" >EOA</span>' : '<span class="badge bg-warning" >EOA</span>';
                $EOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }
            $EOP = '';
            if ($numEOP != 0) {
                $span = ($sqleop['status'] == 'Approved') ? '<span class="badge bg-primary" >EOP</span>' : '<span class="badge bg-warning" >EOP</span>';
                $EOP = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOP\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }
            $ECA = '';
            if ($numECA != 0) {
                $span = ($sqleca['status'] == 'Approved') ? '<span class="badge bg-primary" >ECA</span>' : '<span class="badge bg-warning" >ECA</span>';
                $ECA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'ECA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }
            $EUA = '';
            if ($numEUA != 0) {
                $span = ($sqleua['status'] == 'Approved') ? '<span class="badge bg-primary" >EUA</span>' : '<span class="badge bg-warning" >EUA</span>';
                $EUA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EUA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $tk . '\')">' . $span . '</a>';
            }
            // request checking ********************************************************************


            $tableDate = date('M. d, Y', strtotime($dateFrom));

            if ($holiday && !$odaHoliday) {
                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center">' . $hol . '</td>';
                echo    '<td class="text-center">' . $ODAHD . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            } else if ($show_do && !$odaDayoff) {

                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center text-danger">DAY-OFF</td>';
                echo    '<td class="text-center">' . $CDOS . ' ' . $CDOL . ' ' . $ODADO . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            } else if ($no_schedule && !$dutyl) {

                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center text-danger">NO SCHEDULE</td>';
                echo    '<td class="text-center">' . $ODAHD . ' ' . $ODADO . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            } else {

                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td><span class="text-green">' . $in1_store . '</span> ' . $in1 . '</td>';
                echo    '<td><span class="text-green">' . $out1_store . '</span> ' . $out1 . '</td>';
                echo    '<td><span class="text-green">' . $in2_store . '</span> ' . $in2 . '</td>';
                echo    '<td><span class="text-green">' . $out2_store . '</span> ' . $out2 . '</td>';
                echo    '<td><span class="text-green">' . $in3_store . '</span> ' . $in3 . '</td>';
                echo    '<td><span class="text-green">' . $out3_store . '</span> ' . $out3 . '</td>';
                echo    '<td class="text-center">' . $hw . '</td>';
                echo    '<td class="text-center">' . $dw . '</td>';
                echo    '<td class="text-center">' . $ot . '</td>';
                echo    '<td class="text-center">' . $LOA . ' ' . $DWA . ' ' . $EOA . ' ' . $EOP . ' ' . $ECA . ' ' . $EUA . ' ' . $CDOS . ' ' . $CDOL . ' ' . $ODADO . ' ' . $ODAHD . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            }

            $dateFrom = date('M. d, Y', strtotime($dateFrom . "+1 day"));
        }

        // Previous Cut Off
        $df     = date('Y-m-d', strtotime($input['dateFrom']));
        $dt     = date('Y-m-d', strtotime($input['dateTo']));
        $cond   = array('empId' => $empId, 'coDF1' => $df, 'coDF2' => $dt);
        $pdp    = $this->dtr_model->getResultArray($tk, 'perday_previous', $cond);
        $cond   = array('empId' => $empId, 'dateFrom' => $df, 'dateTo' => $dt);
        $pdpa   = $this->dtr_model->getRowArray($tk, 'perday_previous_allow', $cond);
        if ($pdp && $pdpa) {
            echo '<tr>';
            echo '<th colspan="13"><span class="text-success">Previous Cutoff (Late Requested/Approved)</span></th>';
            echo '</tr>';
            foreach ($pdp as $nf) {

                $dateDuty   = $nf['dateDuty'];
                $dateFrom   = date('M. d, Y', strtotime($nf['dateDuty']));

                $hw_new     = floatval($nf['hw_new']);
                $hw_con     = $nf['hw_con'] + 0;
                $request    = $nf['request'];
                $dayS       = date("D", strtotime($dateDuty));
                $dateD      = date("M. d, Y", strtotime($dateDuty));

                $cond   = array('bioMetricId' => $bioM, 'date' => $dateDuty);
                $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                $cond   = "d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$dateDuty' AND '$dateDuty' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
                $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment, off_date', $cond) ?? [];
                $numDWA = count($sqldwa);

                if ($numDWA == 0) {
                    $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') AND '$dateDuty' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                    $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment', $cond) ?? [];
                    $numDWA = count($sqldwa);
                }

                $DWA = '';
                if ($numDWA != 0) {
                    $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                    $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                $onclick = 'onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $dateFrom . '\')"';
                $action = '<a href="javascript:;" ' . $onclick . '><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a>';

                $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
                }

                if (!empty($in1)) {
                    $in1 = date('h:i A', strtotime($in1));
                }

                if (!empty($out1)) {
                    $out1 = date('h:i A', strtotime($out1));
                }

                if (!empty($in2)) {
                    $in2 = date('h:i A', strtotime($in2));
                }

                if (!empty($out2)) {
                    $out2 = date('h:i A', strtotime($out2));
                }

                if (!empty($in3)) {
                    $in3 = date('h:i A', strtotime($in3));
                }

                if (!empty($out3)) {

                    $out3 = date('h:i A', strtotime($out3));
                }

                $inC1 = $in1;
                $inC2 = $in2;
                $inC3 = $in3;
                $outC1 = $out1;
                $outC2 = $out2;
                $outC3 = $out3;
                $cond = array('empId' => $empId, 'date' => $dateDuty);
                $od = $this->dtr_model->getRowArray($tk, 'off_date', $cond);
                if ($od) {

                    $inC1 = '';
                    $inC2 = '';
                    $inC3 = '';
                    $outC1 = '';
                    $outC2 = '';
                    $outC3 = '';

                    $ts = $od['timeStarted'];
                    if ($ts == $in1) {
                        $inC1 = date('h:i A', strtotime($in1));
                    }
                    if ($ts == $in2) {
                        $inC2 = date('h:i A', strtotime($in2));
                    }
                    if ($ts == $in3) {
                        $inC3 = date('h:i A', strtotime($in3));
                    }

                    $te = $od['timeEnded'];
                    if ($te == $out1) {
                        $outC1 = date('h:i A', strtotime($out1));
                    }
                    if ($te == $out2) {
                        $outC2 = date('h:i A', strtotime($out2));
                    }
                    if ($te == $out3) {
                        $outC3 = date('h:i A', strtotime($out3));
                    }
                }

                echo '<tr>';
                echo    '<td>' . $dateD . '</td>';
                echo    '<td>' . $dayS . '</td>';
                echo    '<td><span class="text-green">' . $in1_store . '</span> ' . $inC1 . '</td>';
                echo    '<td><span class="text-green">' . $out1_store . '</span> ' . $outC1 . '</td>';
                echo    '<td><span class="text-green">' . $in2_store . '</span> ' . $inC2 . '</td>';
                echo    '<td><span class="text-green">' . $out2_store . '</span> ' . $outC2 . '</td>';
                echo    '<td><span class="text-green">' . $in3_store . '</span> ' . $inC3 . '</td>';
                echo    '<td><span class="text-green">' . $out3_store . '</span> ' . $outC3 . '</td>';
                echo    '<td class="text-center">' . $hw_new . '</td>';
                echo    '<td class="text-center">' . $hw_con . '</td>';
                echo    '<td class="text-center">0</td>';
                echo    '<td class="text-center">' . $DWA . '</td>';
                echo    '<td class="text-center">' . $action . '</td>';
                echo '</tr>';
            }
        }

        $cond = array('emp_id' => $empId, 'supDF' => $df, 'supDT' => $dt, 'otDate <' => $df);
        $otp  = $this->dtr_model->getResultArray($tk, 'otforpayment', $cond);
        if ($otp) {
            echo '<tr>';
            echo '<th colspan="13"><span class="text-success">Previous Cutoff (Overtime)</span></th>';
            echo '</tr>';
            foreach ($otp as $nf) {

                $dateDuty   = $nf['otDate'];
                $dateFrom   = date('M. d, Y', strtotime($nf['otDate']));
                $otApp  = floatval($nf['otApp']) * 1;
                $dayS   = date("D", strtotime($dateDuty));
                $dateD  = date("M. d, Y", strtotime($dateDuty));

                $cond   = array('bioMetricId' => $bioM, 'date' => $dateDuty);
                $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                $onclick = 'onclick="viewTime(\'' . $tk . '\',\'' . $empId . '\',\'' . $dateFrom . '\')"';
                $action = '<a href="javascript:;" ' . $onclick . '><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a>';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
                }

                if (!empty($in1)) {
                    $in1 = date('h:i A', strtotime($in1));
                }

                if (!empty($out1)) {
                    $out1 = date('h:i A', strtotime($out1));
                }

                if (!empty($in2)) {
                    $in2 = date('h:i A', strtotime($in2));
                }

                if (!empty($out2)) {
                    $out2 = date('h:i A', strtotime($out2));
                }

                if (!empty($in3)) {
                    $in3 = date('h:i A', strtotime($in3));
                }

                if (!empty($out3)) {

                    $out3 = date('h:i A', strtotime($out3));
                }

                $cond   = "d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$dateDuty' AND '$dateDuty' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
                $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment, off_date', $cond) ?? [];
                $numDWA = count($sqldwa);

                if ($numDWA == 0) {
                    $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') AND '$dateDuty' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                    $sqldwa = $this->dtr_model->getRowArray($tk, 'd_w_adjustment', $cond) ?? [];
                    $numDWA = count($sqldwa);
                }

                $DWA = '';
                if ($numDWA != 0) {
                    $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                    $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                $cond   = "empId='$empId' AND dateOvertime='$dateDuty' AND (status = 'Approved' OR status = 'Pending')";
                $sqleoa = $this->dtr_model->getRowArray($tk, 'e_o_authorization', $cond) ?? [];
                $numEOA = count($sqleoa);

                $EOA = '';
                if ($numEOA != 0) {
                    $span = ($sqleoa['status'] == 'Approved') ? '<span class="badge bg-primary" >EOA</span>' : '<span class="badge bg-warning" >EOA</span>';
                    $EOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                $cond   = "emp_id='$empId' AND otDate='$dateDuty' AND (status = 'Approved' OR status = 'Pending')";
                $sqleop = $this->dtr_model->getRowArray($tk, 'otforpayment', $cond) ?? [];
                $numEOP = count($sqleop);

                $EOP = '';
                if ($numEOP != 0) {
                    $span = ($sqleop['status'] == 'Approved') ? '<span class="badge bg-primary" >EOP</span>' : '<span class="badge bg-warning" >EOP</span>';
                    $EOP = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOP\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                echo '<tr>';
                echo    '<td>' . $dateD . '</td>';
                echo    '<td>' . $dayS . '</td>';
                echo    '<td><span class="text-green">' . $in1_store . '</span> ' . $in1 . '</td>';
                echo    '<td><span class="text-green">' . $out1_store . '</span> ' . $out1 . '</td>';
                echo    '<td><span class="text-green">' . $in2_store . '</span> ' . $in2 . '</td>';
                echo    '<td><span class="text-green">' . $out2_store . '</span> ' . $out2 . '</td>';
                echo    '<td><span class="text-green">' . $in3_store . '</span> ' . $in3 . '</td>';
                echo    '<td><span class="text-green">' . $out3_store . '</span> ' . $out3 . '</td>';
                echo    '<td class="text-center">0</td>';
                echo    '<td class="text-center">0</td>';
                echo    '<td class="text-center">' . $otApp . '</td>';
                echo    '<td class="text-center">' . $DWA . ' ' . $EOA . ' ' . $EOP . '</td>';
                echo    '<td class="text-center">' . $action . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="mt-0 mb-0 d-flex flex-wrap">';
        $legend = $this->masterfile_model->dtr_store_code();
        foreach ($legend as $key => $store) {
            echo '<span class="me-3"><span class="text-green">' . $key . '</span> - ' . $store . '</span>';
        }
        echo '</p>';
    }

    public function print_dtr()
    {
        $input      = $this->request->getPostGet();
        $empId      = $input['empId'];
        $recordNo   = $input['recordNo'];
        $dateFrom   = $input['dateFrom'];
        $dateTo     = $input['dateTo'];
        $bioM       = $input['bioM'];
        $cut        = $input['cut'];
        $statCut    = $input['statCut'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $dF = date('Y-m-d', strtotime($dateFrom));
        $dT = date('Y-m-d', strtotime($dateTo));

        $emp    = $this->masterfile_model->promoDetails($pis, $empId, $recordNo);
        $name   = ucwords(strtolower($emp['name']));
        $comp   = $emp['promo_company'];
        $dept   = $emp['promo_department'];
        $ptype  = $emp['promo_type'];

        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $allowId = $this->dtr_model->getRowArray($tk, 'assign', $cond)['allowId'];

        $dw = 0;
        $ot = 0;
        $lh = 0;
        $sh = 0;
        $tbl = ($cut == 1) ? '1stcutoff' : '2ndcutoff';
        $cond = array('bioMetricId' => $bioM, 'dateFrom' => $dF, 'dateTo' => $dT, 'statCut' => $statCut);
        $co = $this->dtr_model->getRowArray($tk, $tbl, $cond);
        if ($co) {

            if ($co['daysWork']) {
                $dw = $co['daysWork'];
                $decimal = explode('.', (string)$co['daysWork']);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $dw = number_format($co['daysWork'], 3, '.', '');
                }
            }

            if ($co['otForPayment']) {
                $ot = $co['otForPayment'];
            }

            if ($co['legHol']) {
                $lh = $co['legHol'];
            }

            if ($co['speHol']) {
                $sh = $co['speHol'];
            }
        }

        $sil = 0;
        $sil_cu = $this->masterfile_model->no_sil_current($input)['sil'] ?? 0;
        $sil_prev = $this->masterfile_model->no_sil_previous($input)['sil'] ?? 0;
        $sil = $sil_prev + $sil_cu;

        $this->pdf->SetMargins(12, 12, 12);
        $html = '<div style="text-align:center;font-family:times;font-size:12px;">';
        $html .= '<p style="margin-bottom:0;"><strong>Integrated Daily Time Record for Promo & Merchandiser</strong></p>';
        $html .= '<p style="margin-top:2px;"><strong>For the Period of ' . date("M. d, Y", strtotime($dateFrom)) . ' to ' . date("M. d, Y", strtotime($dateTo)) . '</strong></p>';
        $html .= '</div>';

        $html .= '<table width="100%" style="font-family:times;font-size:12px;">';
        $html .= '<tr>';
        $html .= '<th width="12%" style="text-align:left;vertical-align: top;">Name</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td width="40%">' . $name . '</td>';
        $html .= '<th width="15%" style="text-align:left;vertical-align: top;">Days Worked</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td width="8%">' . $dw . '</td>';
        $html .= '<th width="13%" style="text-align:left;vertical-align: top;">Legal Holiday</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td width="6%">' . $lh . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th style="text-align:left;vertical-align: top;">Company</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td style="text-align:left;vertical-align: top;font-size:11px">' . $comp . '</td>';
        $html .= '<th style="text-align:left;vertical-align: top;">Overtime</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td style="text-align:left;vertical-align: top;">' . $ot . '</td>';
        $html .= '<th style="text-align:left;vertical-align: top;">SIL</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td style="text-align:left;vertical-align: top;">' . $sil . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th style="text-align:left;vertical-align: top;">Department</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td style="text-align:left;vertical-align: top;">' . $dept . '</td>';
        $html .= '<th style="text-align:left;vertical-align: top;">Special Holiday</th>';
        $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
        $html .= '<td style="text-align:left;vertical-align: top;">' . $sh . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<table width="100%" style="font-family:times;font-size:12px; border-collapse:collapse;margin-top:5px;">';
        $html .= '<tr>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">Date</th>';
        $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">Day</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN1</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT1</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN2</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT2</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN3</th>';
        $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT3</th>';
        $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">HW</th>';
        $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">DW</th>';
        $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OT</th>';
        $html .= '</tr>';

        while (strtotime($dateFrom) <= strtotime($dateTo)) {

            $tempDate   = date('Y-m-d', strtotime($dateFrom));
            $date       = date("m/d/Y", strtotime($dateFrom));
            $day        = date("D", strtotime($dateFrom));

            $cond   = "bioMetricId = '$bioM' AND date = '$tempDate' ORDER BY dutyId DESC";
            $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
            $dutyId = $dtr['dutyId'] ?? '';
            $in1    = $dtr['in1'] ?? '';
            $out1   = $dtr['out1'] ?? '';
            $in2    = $dtr['in2'] ?? '';
            $out2   = $dtr['out2'] ?? '';
            $in3    = $dtr['in3'] ?? '';
            $out3   = $dtr['out3'] ?? '';

            $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in1, "I") ?? '';
            $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out1, "O") ?? '';
            $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in2, "I") ?? '';
            $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out2, "O") ?? '';
            $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in3, "I") ?? '';
            $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out3, "O") ?? '';

            if (empty($out3_store)) {
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($tempDate . " +1 day")), $out3, "O") ?? '';
            }

            if (!empty($in1)) {
                $in1 = date('h:i A', strtotime($in1));
            }

            if (!empty($out1)) {
                $out1 = date('h:i A', strtotime($out1));
            }

            if (!empty($in2)) {
                $in2 = date('h:i A', strtotime($in2));
            }

            if (!empty($out2)) {
                $out2 = date('h:i A', strtotime($out2));
            }

            if (!empty($in3)) {
                $in3 = date('h:i A', strtotime($in3));
            }

            if (!empty($out3)) {

                $out3 = date('h:i A', strtotime($out3));
            }

            $cond   = "bioMetricId = '$bioM' AND dateDuty = '$tempDate'";
            $dutyl  = $this->dtr_model->getRowArray($tk, 'duty_logbox', $cond);

            $dw = 0;
            $hw = 0;
            $cond = array('dutyId' => $dutyId);
            $perday = $this->dtr_model->getRowArray($tk, 'perday', $cond);
            if ($perday) {
                $phw = $perday['hoursWork'];
                $res = explode("&", $phw);
                if ($res[0] == '') {
                    $res[0] = 0;
                }

                if (!isset($res[1])) {
                    $res[1] = 0;
                }

                if ($res[1] == '') {
                    $res[1] = 0;
                }

                $h = floatval($res[0]) * 1;
                $m = floatval($res[1]) * 1;

                $hrs = $h / 8;
                $min = ($m / 60) / 8;

                // days worked ********************************************************************
                $dw = round($hrs + $min, 3);
                $decimal = explode('.', (string)$dw);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $dw = number_format($dw, 3, '.', '');
                }

                // hours worked ********************************************************************
                $min = round(($m / 60), 3);
                $hw =  round($h + $min, 3);
                $decimal = explode('.', (string)$h + $min);
                if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                    $hw = number_format($h + $min, 3, '.', '');
                }
            }

            // overtime ********************************************************************
            $ot = 0;
            $cond = "emp_id = '$empId' AND otDate = '$tempDate' ORDER BY pay_id DESC";
            $otApp = $this->dtr_model->getRowArray($tk, 'otforpayment', $cond)['otApp'] ?? '';
            if ($otApp) {
                $ot = floatval($otApp) * 1;
            }
            // overtime ********************************************************************

            // holiday	********************************************************************
            $hol = '';
            $holiday = false;
            if ($server == 'cebu') {
                $cond  = "status='active' AND hrd_location='cebo'";
                $strs  = $this->dtr_model->getResultArray($pis, 'locate_promo_business_unit', $cond);
                if ($ptype == 'STATION') {
                    foreach ($strs as $key => $str) {
                        $field = $str['bunit_field'];
                        if ($emp[$field] == 'T') {
                            if ($field == 'colc') {
                                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            } else {
                                $cond = "(store='' OR store='$field') AND (date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_outside', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            }
                        }
                    }
                } else {

                    $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                    $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_roving', $cond);
                    if ($ch) {
                        $holiday = true;
                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                        $hol    = $nH . ' [ ' . $cat . ' ]';
                    }
                }
            } else {
                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                $chk = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                if ($chk) {
                    $holiday = true;
                    $nH     = '<span class="text-danger">' . $chk['nameHoliday'] . '</span>';
                    $cat    = '<span class="text-danger">' . $chk['category'] . '</span>';
                    $hol    = $nH . ' [ ' . $cat . ' ]';
                    if ($ptype == 'STATION' && ($emp['al_panglao'] == 'T' || $emp['cdc'] == 'T') && date('m-d', strtotime($tempDate)) == '07-01') {
                        $holiday = false;
                    }
                }
            }
            // holiday ********************************************************************

            // offduty of holiday ********************************************************************
            $hd = '';
            $odaHoliday = false;
            if ($holiday) {


                $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                    if ($sql) {
                        $hd = $amsId;
                        $odaHoliday = true;
                    }
                }
            }
            // offduty ********************************************************************               

            // dayoff ********************************************************************     
            $show_do = false;
            $old_do = false;
            $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
            $sql = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
            if ($sql) {
                foreach ($sql as $ams) {

                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId, 'empId' => $empId, 'date' => $tempDate);
                    $amd    = $this->dtr_model->getRowArray($tk, 'ass_mul_do', $cond);

                    $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);

                    if ($amd && !$acd) {
                        $show_do = true;
                    }

                    if ($amd && $acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                        $appby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                        if ($appby) {

                            $dateReq = $appby['date'];
                            $timeReq = $appby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                            $cond   = ['amsId' => $amsId];
                            $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                            if ($rns) {

                                $dateReq = $rns['dateReq'];
                                $timeReq = $rns['timeReq'];
                                $req_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                if ($app_details < $req_details) {
                                    $show_do = true;
                                }
                            }
                        }
                    }

                    $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                    if ($acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = array('requestId' => $acdId, 'requestType' => 'CDOS');
                        $aprby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                        if ($aprby) {
                            $dateReq    = $aprby['date'];
                            $timeReq    = $aprby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));

                            $cond   = array('amsId' => $amsId);
                            $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                            if ($rns) {
                                $dateReq = $rns['dateReq'];
                                $timeReq = $rns['timeReq'];

                                $req_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                if ($app_details > $req_details) {
                                    $show_do = true;
                                }
                            }
                        }
                    }
                }
            } else {

                $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                if ($acd) {
                    $show_do = true;
                }

                $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                if ($acd) {
                    $old_do = true;
                }
            }

            // default day-off if $show_do is false
            if (!$sql &&  !$show_do &&  !$old_do) {
                $days = date('l', strtotime($tempDate));
                $cond = array('empId' => $empId, 'day' => date('l', strtotime($tempDate)));
                $sql = $this->dtr_model->getRowArray($tk, 'assign_day_off', $cond);
                if ($sql) {
                    $show_do = true;
                }
            }
            // dayoff ********************************************************************

            // offduty of dayoff ********************************************************************
            $do = '';
            $odaDayoff = false;
            if ($show_do) {
                $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                    if ($sql) {
                        $do = $amsId;
                        $odaDayoff = true;
                    }
                }
            }
            // offduty ********************************************************************   

            // no schedule ********************************************************************
            $no_schedule = false;
            if ($ptype == 'ROVING') {
                $cond   = "empId = '$empId' AND dateRendered = '$tempDate' ORDER BY pId DESC";
                $pms    = $this->dtr_model->getRowArray($tk, 'promo_mul_sc', $cond);
                if ($pms) {
                    $nStore = $pms['noStore'];
                    if ($nStore == 0) {
                        $no_schedule = true;
                    }
                } else {
                    $cond   = "empId='$empId' AND day_sched='$day' ORDER BY pdId DESC";
                    $pds    = $this->dtr_model->getRowArray($tk, 'promo_def_sched', $cond);
                    if ($pds) {
                        $nStore = $pds['noStore'];
                        if ($nStore == 0) {
                            $no_schedule = true;
                        }
                    }
                }
            } else {

                $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC";
                $amsId = $this->dtr_model->getRowArray($tk, 'assign_mul_sc', $cond)['amsId'] ?? '';
                if ($amsId) {
                    $cond  = array('empId' => $empId, 'amsId' => $amsId, 'date' => $tempDate);
                    $pns   = $this->dtr_model->getRowArray($tk, 'promo_nosched', $cond);
                    if ($pns) {
                        $no_schedule = true;
                    }
                }
            }
            // no schedule ********************************************************************

            $loa = false;
            $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
            AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
            $sqlloa = $this->dtr_model->getResultArray($tk, 'r_l_absence, rla_day', $cond) ?? [];
            if ($sqlloa) {
                $loa = true;
            }

            $sil = false;
            if ($loa) {
                $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') 
                AND (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
                $sqlsil = $this->dtr_model->getRowArray($tk, 'r_l_absence, rla_day', $cond) ?? [];
                if ($sqlsil) {
                    $sil = true;
                }
            }

            if ($holiday && !$odaHoliday) {

                $html .= '<tr>';
                $html .= '<td style="padding:2px;">' . $date . '</td>';
                $html .= '<td style="text-align:center;">' . $day . '</td>';
                $html .= '<td colspan="6" style="text-align:center;">' . $hol . '</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '</tr>';
            } else if ($show_do && !$odaDayoff) {

                $html .= '<tr>';
                $html .= '<td style="padding:2px;">' . $date . '</td>';
                $html .= '<td style="text-align:center;">' . $day . '</td>';
                $html .= '<td colspan="6" style="text-align:center;">DAYOFF</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '</tr>';
            } else if ($no_schedule && !$dutyl) {

                $html .= '<tr>';
                $html .= '<td style="padding:2px;">' . $date . '</td>';
                $html .= '<td style="text-align:center;">' . $day . '</td>';
                $html .= '<td colspan="6" style="text-align:center;">NO SCHEDULE</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '</tr>';
            } else if ($loa) {

                $html .= '<tr>';
                $html .= '<td style="padding:2px;">' . $date . '</td>';
                $html .= '<td style="text-align:center;">' . $day . '</td>';

                if ($sqlloa['status'] ?? '' == 'Pending') {
                    $leave = ($sil) ? 'SIL (Pending)' : 'LOA (Pending)';
                } else {
                    $leave = ($sil) ? 'SIL' : 'LOA';
                }

                if ($in1 == '' && $out1 == '' && $in2 == '' && $out2 == '' && $in3 == '' && $out3 == '') {

                    $html .= '<td colspan="6" style="text-align:center;">' . $leave . '</td>';
                } else if ($in1 == '' && $out1 == '' && $in2 == '' && $out2 == '') {

                    $html .= '<td colspan="4" style="text-align:center;">' . $leave . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                } else {
                    $check = 0;
                    if ($in1 != '') {

                        $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                    }
                    if ($out1 != '') {

                        $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                    }

                    if ($in1 == '' && $out1 == '') {
                        $check++;

                        $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                    }

                    if ($in2 != '') {

                        $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                    }
                    if ($out2 != '') {

                        $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                    }

                    if ($in2 == '' && $out2 == '') {
                        $check++;

                        $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                    }

                    if ($in3 != '') {

                        $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                    }
                    if ($out3 != '') {

                        $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                    }

                    if ($in3 == '' && $out3 == '' &&  $check == 1) {

                        $html .= '<td></td>';
                        $html .= '<td></td>';
                    } else if ($in3 == '' && $out3 == '' &&  $check == 2) {

                        $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                    } else if ($in3 == '' && $out3 == '') {
                        $html .= '<td></td>';
                        $html .= '<td></td>';
                    }
                }

                $html .= '<td style="text-align:center;">' . $hw . '</td>';
                $html .= '<td style="text-align:center;">' . $dw . '</td>';
                $html .= '<td style="text-align:center;">' . $ot . '</td>';
                $html .= '</tr>';
            } else {

                $html .= '<tr>';
                $html .= '<td style="padding:2px;">' . $date . '</td>';
                $html .= '<td style="text-align:center;">' . $day . '</td>';
                $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                $html .= '<td style="text-align:center;">' . $hw . '</td>';
                $html .= '<td style="text-align:center;">' . $dw . '</td>';
                $html .= '<td style="text-align:center;">' . $ot . '</td>';
                $html .= '</tr>';
            }

            $dateFrom = date("Y-m-d", strtotime($dateFrom . "+1 day"));
        }

        // Previous Cut Off **********************************************************************************
        $dF     = date('Y-m-d', strtotime($input['dateFrom']));
        $dT     = date('Y-m-d', strtotime($input['dateTo']));
        $cond   = "empId = '$empId' AND coDF1 = '$dF' AND coDF2 = '$dT'";
        $pdp    = $this->dtr_model->getResultArray($tk, 'perday_previous', $cond);
        $cond   = array('empId' => $empId, 'dateFrom' => $dF, 'dateTo' => $dT);
        $allow  = $this->dtr_model->getResultArray($tk, 'perday_previous_allow', $cond);
        if ($pdp && $allow) {

            $html .= '<tr>';
            $html .= '<th colspan="11" style="text-align:left; padding-top:10px;">Previous Days Worked</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="11" style="border-bottom:0.5px solid #000;"></th>';
            $html .= '</tr>';

            foreach ($pdp as $nf) {

                $dateDuty   = $nf['dateDuty'];
                $hw_new     = floatval($nf['hw_new']) * 1;
                $hw_con     = $nf['hw_con'] + 0;
                $dayS       = date("D", strtotime($dateDuty));
                $dateD      = date("m/d/Y", strtotime($dateDuty));

                $cond   = "bioMetricId = '$bioM' AND date = '$dateDuty'";
                $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                if (!empty($in1)) {
                    $in1 = date('h:i A', strtotime($in1));
                }

                if (!empty($out1)) {
                    $out1 = date('h:i A', strtotime($out1));
                }

                if (!empty($in2)) {
                    $in2 = date('h:i A', strtotime($in2));
                }

                if (!empty($out2)) {
                    $out2 = date('h:i A', strtotime($out2));
                }

                if (!empty($in3)) {
                    $in3 = date('h:i A', strtotime($in3));
                }

                if (!empty($out3)) {

                    $out3 = date('h:i A', strtotime($out3));
                }

                $inC1 = $in1;
                $inC2 = $in2;
                $inC3 = $in3;
                $outC1 = $out1;
                $outC2 = $out2;
                $outC3 = $out3;
                $cond = array('empId' => $empId, 'date' => $dateDuty);
                $od = $this->dtr_model->getRowArray($tk, 'off_date', $cond);
                if ($od) {

                    $inC1 = '';
                    $inC2 = '';
                    $inC3 = '';
                    $ts = $od['timeStarted'];
                    if ($ts == $in1) {
                        $inC1 = date('h:i A', strtotime($in1));
                    }
                    if ($ts == $in2) {
                        $inC2 = date('h:i A', strtotime($in2));
                    }
                    if ($ts == $in3) {
                        $inC3 = date('h:i A', strtotime($in3));
                    }

                    $outC1 = '';
                    $outC2 = '';
                    $outC3 = '';
                    $te = $od['timeEnded'];
                    if ($te == $out1) {
                        $outC1 = date('h:i A', strtotime($out1));
                    }
                    if ($te == $out2) {
                        $outC2 = date('h:i A', strtotime($out2));
                    }
                    if ($te == $out3) {
                        $outC3 = date('h:i A', strtotime($out3));
                    }
                }

                $html .= '<tr>';
                $html .= '<td style="padding:5px;">' . $dateD . '</td>';
                $html .= '<td style="text-align:center;">' . $dayS . '</td>';
                $html .= '<td>' . $inC1 . '</td>';
                $html .= '<td>' . $outC1 . '</td>';
                $html .= '<td>' . $inC2 . '</td>';
                $html .= '<td>' . $outC2 . '</td>';
                $html .= '<td>' . $inC3 . '</td>';
                $html .= '<td>' . $outC3 . '</td>';
                $html .= '<td style="text-align:center;">' . $hw_new . '</td>';
                $html .= '<td style="text-align:center;">' . $hw_con . '</td>';
                $html .= '<td style="text-align:center;">0</td>';
                $html .= '</tr>';
            }
        }

        // Previous OT **********************************************************************************
        $dF     = date('Y-m-d', strtotime($input['dateFrom']));
        $dT     = date('Y-m-d', strtotime($input['dateTo']));
        $cond   = "emp_id = '$empId' AND supDF = '$dF' AND supDT = '$dT' AND otDate < '$dF'";
        $otp    = $this->dtr_model->getResultArray($tk, 'otforpayment', $cond);
        if ($otp) {

            $html .= '<tr>';
            $html .= '<th colspan="11" style="text-align:left; padding-top:10px;">Previous OT</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="11" style="border-bottom:0.5px solid #000;"></th>';
            $html .= '</tr>';
            foreach ($otp as $ot) {

                $dateDuty   = $ot['otDate'];
                $otApproved = floatval($ot['otApp']) * 1;
                $dayS       = date("D", strtotime($dateDuty));
                $dateD      = date("m/d/Y", strtotime($dateDuty));

                $cond   = "bioMetricId = '$bioM' AND date = '$dateDuty'";
                $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                // store entry
                $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
                }

                if (!empty($in1)) {
                    $in1 = date('h:i A', strtotime($in1));
                }

                if (!empty($out1)) {
                    $out1 = date('h:i A', strtotime($out1));
                }

                if (!empty($in2)) {
                    $in2 = date('h:i A', strtotime($in2));
                }

                if (!empty($out2)) {
                    $out2 = date('h:i A', strtotime($out2));
                }

                if (!empty($in3)) {
                    $in3 = date('h:i A', strtotime($in3));
                }

                if (!empty($out3)) {

                    $out3 = date('h:i A', strtotime($out3));
                }

                $html .= '<tr>';
                $html .= '<td style="padding:5px;">' . $dateD . '</td>';
                $html .= '<td style="text-align:center;">' . $dayS . '</td>';
                $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                $html .= '<td style="text-align:center;">0</td>';
                $html .= '<td style="text-align:center;">0</td>';
                $html .= '<td style="text-align:center;">' . $otApproved . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '<tr>';
        $html .= '<th colspan="11" style="border-top:0.5px solid #000; padding:5px;"></th>';
        $html .= '</tr>';
        $html .= '</table>';

        $i = 0;
        $str = '';
        $legend = $this->dtr_model->dtr_store_code();
        foreach ($legend as $key => $store) {
            $i++;
            if ($i == 9) {
                $str .= '<br>';
            }
            $str .= $key . ' - ' . $store . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $html .= '<p style="margin-top:1px;font-size:8px;">' . $str . '</p>';

        $this->pdf->WriteHTML($html);
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Promo DTR.pdf"')
            ->setBody($this->pdf->Output('', 'S'));
    }

    public function print_dtr_all()
    {
        $input      = $this->request->getPostGet();
        $cut        = $input['cut'];
        $statCut    = $input['statCut'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $ids = $input['ids'];
        foreach ($ids as $key => $row) {
            $trigger = false;
            $id = explode('|', $row);
            $empId = $id[0];
            $recordNo = $id[1];

            $dateFrom   = $input['dateFrom'];
            $dateTo     = $input['dateTo'];
            $dF = date('Y-m-d', strtotime($dateFrom));
            $dT = date('Y-m-d', strtotime($dateTo));


            $emp    = $this->masterfile_model->promoDetails($pis, $empId, $recordNo);
            $name   = ucwords(strtolower($emp['name']));
            $comp   = $emp['promo_company'];
            $dept   = $emp['promo_department'];
            $ptype  = $emp['promo_type'];

            $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
            $assign = $this->dtr_model->getRowArray($tk, 'assign', $cond);
            $allowId = $assign['allowId'];
            if ($allowId == 'Logbox ID') {

                $bioM = $assign['barcodeId'];
            } else {

                $bioM = $assign['biometricId'];
            }


            $dw = 0;
            $ot = 0;
            $lh = 0;
            $sh = 0;
            $tbl = ($cut == 1) ? '1stcutoff' : '2ndcutoff';
            $cond = array('bioMetricId' => $bioM, 'dateFrom' => $dF, 'dateTo' => $dT, 'statCut' => $statCut);
            $co = $this->dtr_model->getRowArray($tk, $tbl, $cond);
            if ($co) {

                if ($co['daysWork']) {
                    $dw = $co['daysWork'];
                    $decimal = explode('.', (string)$co['daysWork']);
                    if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                        $dw = number_format($co['daysWork'], 3, '.', '');
                    }
                }

                if ($co['otForPayment']) {
                    $ot = $co['otForPayment'];
                }

                if ($co['legHol']) {
                    $lh = $co['legHol'];
                }

                if ($co['speHol']) {
                    $sh = $co['speHol'];
                }
            }

            $sil = 0;
            $sil_cu = $this->dtr_model->no_sil_current($input, $empId)['sil'] ?? 0;
            $sil_prev = $this->dtr_model->no_sil_previous($input, $empId)['sil'] ?? 0;
            $sil = $sil_prev + $sil_cu;

            $html = '<div style="text-align:center;font-family:times;font-size:12px;">';
            $html .= '<p style="margin-bottom:0;"><strong>Integrated Daily Time Record for Promo & Merchandiser</strong></p>';
            $html .= '<p style="margin-top:2px;"><strong>For the Period of ' . date("M. d, Y", strtotime($dateFrom)) . ' to ' . date("M. d, Y", strtotime($dateTo)) . '</strong></p>';
            $html .= '</div>';

            $html .= '<table width="100%" style="font-family:times;font-size:12px;">';
            $html .= '<tr>';
            $html .= '<th width="12%" style="text-align:left;vertical-align: top;">Name</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td width="40%">' . $name . '</td>';
            $html .= '<th width="15%" style="text-align:left;vertical-align: top;">Days Worked</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td width="8%">' . $dw . '</td>';
            $html .= '<th width="13%" style="text-align:left;vertical-align: top;">Legal Holiday</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td width="6%">' . $lh . '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th style="text-align:left;vertical-align: top;">Company</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td style="text-align:left;vertical-align: top;font-size:11px">' . $comp . '</td>';
            $html .= '<th style="text-align:left;vertical-align: top;">Overtime</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td style="text-align:left;vertical-align: top;">' . $ot . '</td>';
            $html .= '<th style="text-align:left;vertical-align: top;">SIL</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td style="text-align:left;vertical-align: top;">' . $sil . '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th style="text-align:left;vertical-align: top;">Department</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td style="text-align:left;vertical-align: top;">' . $dept . '</td>';
            $html .= '<th style="text-align:left;vertical-align: top;">Special Holiday</th>';
            $html .= '<td style="text-align:left;vertical-align: top;">:</td>';
            $html .= '<td style="text-align:left;vertical-align: top;">' . $sh . '</td>';
            $html .= '</tr>';
            $html .= '</table>';

            $html .= '<table width="100%" style="font-family:times;font-size:12px; border-collapse:collapse;margin-top:5px;">';
            $html .= '<tr>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">Date</th>';
            $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">Day</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN1</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT1</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN2</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT2</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">IN3</th>';
            $html .= '<th width="10%" style="text-align:left; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OUT3</th>';
            $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">HW</th>';
            $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">DW</th>';
            $html .= '<th width="5%" style="text-align:center; border-bottom:0.5px solid #000;border-top:0.5px solid #000; padding:5px;">OT</th>';
            $html .= '</tr>';

            while (strtotime($dateFrom) <= strtotime($dateTo)) {

                $tempDate   = date('Y-m-d', strtotime($dateFrom));
                $date       = date("m/d/Y", strtotime($dateFrom));
                $day        = date("D", strtotime($dateFrom));

                $cond   = "bioMetricId = '$bioM' AND date = '$tempDate' ORDER BY dutyId DESC";
                $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';


                $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $tempDate, $out3, "O") ?? '';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($tempDate . " +1 day")), $out3, "O") ?? '';
                }

                if (!empty($in1)) {
                    $in1 = date('h:i A', strtotime($in1));
                }

                if (!empty($out1)) {
                    $out1 = date('h:i A', strtotime($out1));
                }

                if (!empty($in2)) {
                    $in2 = date('h:i A', strtotime($in2));
                }

                if (!empty($out2)) {
                    $out2 = date('h:i A', strtotime($out2));
                }

                if (!empty($in3)) {
                    $in3 = date('h:i A', strtotime($in3));
                }

                if (!empty($out3)) {

                    $out3 = date('h:i A', strtotime($out3));
                }

                $cond   = "bioMetricId = '$bioM' AND dateDuty = '$tempDate'";
                $dutyl  = $this->dtr_model->getRowArray($tk, 'duty_logbox', $cond);

                $dw = 0;
                $hw = 0;
                $cond = array('dutyId' => $dutyId);
                $perday = $this->dtr_model->getRowArray($tk, 'perday', $cond);
                if ($perday) {
                    $phw = $perday['hoursWork'];
                    $res = explode("&", $phw);
                    if ($res[0] == '') {
                        $res[0] = 0;
                    }

                    if (!isset($res[1])) {
                        $res[1] = 0;
                    }

                    if ($res[1] == '') {
                        $res[1] = 0;
                    }

                    $h = floatval($res[0]) * 1;
                    $m = floatval($res[1]) * 1;

                    $hrs = $h / 8;
                    $min = ($m / 60) / 8;

                    // days worked ********************************************************************
                    $dw = round($hrs + $min, 3);
                    $decimal = explode('.', (string)$dw);
                    if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                        $dw = number_format($dw, 3, '.', '');
                    }

                    // hours worked ********************************************************************
                    $min = round(($m / 60), 3);
                    $hw =  round($h + $min, 3);
                    $decimal = explode('.', (string)$h + $min);
                    if (isset($decimal[1]) && strlen($decimal[1]) > 3) {
                        $hw = number_format($h + $min, 3, '.', '');
                    }
                }

                // overtime ********************************************************************
                $ot = 0;
                $cond = "emp_id = '$empId' AND otDate = '$tempDate' ORDER BY pay_id DESC";
                $otApp = $this->dtr_model->getRowArray($tk, 'otforpayment', $cond)['otApp'] ?? '';
                if ($otApp) {
                    $ot = floatval($otApp) * 1;
                }
                // overtime ********************************************************************

                // holiday	********************************************************************
                $hol = '';
                $holiday = false;
                if ($server == 'cebu') {
                    $cond  = "status='active' AND hrd_location='cebo'";
                    $strs  = $this->dtr_model->getResultArray($pis, 'locate_promo_business_unit', $cond);
                    if ($ptype == 'STATION') {
                        foreach ($strs as $key => $str) {
                            $field = $str['bunit_field'];
                            if ($emp[$field] == 'T') {
                                if ($field == 'colc') {
                                    $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                    $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                                    if ($ch) {
                                        $holiday = true;
                                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                        $hol    = $nH . ' [ ' . $cat . ' ]';
                                    }
                                } else {
                                    $cond = "(store='' OR store='$field') AND (date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                    $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_outside', $cond);
                                    if ($ch) {
                                        $holiday = true;
                                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                        $hol    = $nH . ' [ ' . $cat . ' ]';
                                    }
                                }
                            }
                        }
                    } else {

                        $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                        $ch = $this->dtr_model->getRowArray($tk, 'holidays_promo_roving', $cond);
                        if ($ch) {
                            $holiday = true;
                            $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                            $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                            $hol    = $nH . ' [ ' . $cat . ' ]';
                        }
                    }
                } else {
                    $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                    $chk = $this->dtr_model->getRowArray($tk, 'holidays_promo', $cond);
                    if ($chk) {
                        $holiday = true;
                        $nH     = '<span class="text-danger">' . $chk['nameHoliday'] . '</span>';
                        $cat    = '<span class="text-danger">' . $chk['category'] . '</span>';
                        $hol    = $nH . ' [ ' . $cat . ' ]';
                        if ($ptype == 'STATION' && ($emp['al_panglao'] == 'T' || $emp['cdc'] == 'T') && date('m-d', strtotime($tempDate)) == '07-01') {
                            $holiday = false;
                        }
                    }
                }
                // holiday ********************************************************************

                // offduty of holiday ********************************************************************
                $hd = '';
                $odaHoliday = false;
                if ($holiday) {


                    $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                    $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                    foreach ($sql as $ams) {
                        $amsId  = $ams['amsId'];
                        $cond   = array('amsId' => $amsId);
                        $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                        if ($sql) {
                            $hd = $amsId;
                            $odaHoliday = true;
                        }
                    }
                }
                // offduty ********************************************************************               

                // dayoff ********************************************************************     
                $show_do = false;
                $old_do = false;
                $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
                $sql = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                if ($sql) {
                    foreach ($sql as $ams) {

                        $amsId  = $ams['amsId'];
                        $cond   = array('amsId' => $amsId, 'empId' => $empId, 'date' => $tempDate);
                        $amd    = $this->dtr_model->getRowArray($tk, 'ass_mul_do', $cond);

                        $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                        $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);

                        if ($amd && !$acd) {
                            $show_do = true;
                        }

                        if ($amd && $acd) {
                            $acdId  = $acd['acdId'];
                            $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                            $appby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                            if ($appby) {

                                $dateReq = $appby['date'];
                                $timeReq = $appby['time'];
                                $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                $cond   = ['amsId' => $amsId];
                                $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                                if ($rns) {

                                    $dateReq = $rns['dateReq'];
                                    $timeReq = $rns['timeReq'];
                                    $req_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                    if ($app_details < $req_details) {
                                        $show_do = true;
                                    }
                                }
                            }
                        }

                        $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                        $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                        if ($acd) {
                            $acdId  = $acd['acdId'];
                            $cond   = array('requestId' => $acdId, 'requestType' => 'CDOS');
                            $aprby  = $this->dtr_model->getRowArray($tk, 'approveby', $cond);
                            if ($aprby) {
                                $dateReq    = $aprby['date'];
                                $timeReq    = $aprby['time'];
                                $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));

                                $cond   = array('amsId' => $amsId);
                                $rns    = $this->dtr_model->getRowArray($tk, 'request_new_shift', $cond);
                                if ($rns) {
                                    $dateReq = $rns['dateReq'];
                                    $timeReq = $rns['timeReq'];

                                    $req_details    = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                                    if ($app_details > $req_details) {
                                        $show_do = true;
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $cond   = array('empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                    if ($acd) {
                        $show_do = true;
                    }

                    $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->dtr_model->getRowArray($tk, 'ass_change_do', $cond);
                    if ($acd) {
                        $old_do = true;
                    }
                }

                // default day-off if $show_do is false
                if (!$sql &&  !$show_do &&  !$old_do) {
                    $days = date('l', strtotime($tempDate));
                    $cond = array('empId' => $empId, 'day' => date('l', strtotime($tempDate)));
                    $sql = $this->dtr_model->getRowArray($tk, 'assign_day_off', $cond);
                    if ($sql) {
                        $show_do = true;
                    }
                }
                // dayoff ********************************************************************

                // offduty of dayoff ********************************************************************
                $do = '';
                $odaDayoff = false;
                if ($show_do) {
                    $cond  = "empId = '$empId' AND '$tempDate' BETWEEN dateFrom AND dateTo AND remark != ''";
                    $sql   = $this->dtr_model->getResultArray($tk, 'assign_mul_sc', $cond);
                    foreach ($sql as $ams) {
                        $amsId  = $ams['amsId'];
                        $cond   = array('amsId' => $amsId);
                        $sql    = $this->dtr_model->getRowArray($tk, 'request_odaf', $cond);
                        if ($sql) {
                            $do = $amsId;
                            $odaDayoff = true;
                        }
                    }
                }
                // offduty ********************************************************************   

                // no schedule ********************************************************************
                $no_schedule = false;
                if ($ptype == 'ROVING') {
                    $cond   = "empId = '$empId' AND dateRendered = '$tempDate' ORDER BY pId DESC";
                    $pms    = $this->dtr_model->getRowArray($tk, 'promo_mul_sc', $cond);
                    if ($pms) {
                        $nStore = $pms['noStore'];
                        if ($nStore == 0) {
                            $no_schedule = true;
                        }
                    } else {
                        $cond   = "empId='$empId' AND day_sched='$day' ORDER BY pdId DESC";
                        $pds    = $this->dtr_model->getRowArray($tk, 'promo_def_sched', $cond);
                        if ($pds) {
                            $nStore = $pds['noStore'];
                            if ($nStore == 0) {
                                $no_schedule = true;
                            }
                        }
                    }
                } else {

                    $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC";
                    $amsId = $this->dtr_model->getRowArray($tk, 'assign_mul_sc', $cond)['amsId'] ?? '';
                    if ($amsId) {
                        $cond  = array('empId' => $empId, 'amsId' => $amsId, 'date' => $tempDate);
                        $pns   = $this->dtr_model->getRowArray($tk, 'promo_nosched', $cond);
                        if ($pns) {
                            $no_schedule = true;
                        }
                    }
                }
                // no schedule ********************************************************************

                $loa = false;
                $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
                $sqlloa = $this->dtr_model->getResultArray($tk, 'r_l_absence, rla_day', $cond) ?? [];
                if ($sqlloa) {
                    $loa = true;
                }

                $sil = false;
                if ($loa) {
                    $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') 
                AND (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
                    $sqlsil = $this->dtr_model->getRowArray($tk, 'r_l_absence, rla_day', $cond) ?? [];
                    if ($sqlsil) {
                        $sil = true;
                    }
                }

                if ($holiday && !$odaHoliday) {

                    $html .= '<tr>';
                    $html .= '<td style="padding:2px;">' . $date . '</td>';
                    $html .= '<td style="text-align:center;">' . $day . '</td>';
                    $html .= '<td colspan="6" style="text-align:center;">' . $hol . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '</tr>';
                } else if ($show_do && !$odaDayoff) {

                    $html .= '<tr>';
                    $html .= '<td style="padding:2px;">' . $date . '</td>';
                    $html .= '<td style="text-align:center;">' . $day . '</td>';
                    $html .= '<td colspan="6" style="text-align:center;">DAYOFF</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '</tr>';
                } else if ($no_schedule && !$dutyl) {

                    $html .= '<tr>';
                    $html .= '<td style="padding:2px;">' . $date . '</td>';
                    $html .= '<td style="text-align:center;">' . $day . '</td>';
                    $html .= '<td colspan="6" style="text-align:center;">NO SCHEDULE</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '</tr>';
                } else if ($loa) {

                    $html .= '<tr>';
                    $html .= '<td style="padding:2px;">' . $date . '</td>';
                    $html .= '<td style="text-align:center;">' . $day . '</td>';

                    if ($sqlloa['status'] ?? '' == 'Pending') {
                        $leave = ($sil) ? 'SIL (Pending)' : 'LOA (Pending)';
                    } else {
                        $leave = ($sil) ? 'SIL' : 'LOA';
                    }

                    if ($in1 == '' && $out1 == '' && $in2 == '' && $out2 == '' && $in3 == '' && $out3 == '') {

                        $html .= '<td colspan="6" style="text-align:center;">' . $leave . '</td>';
                    } else if ($in1 == '' && $out1 == '' && $in2 == '' && $out2 == '') {

                        $html .= '<td colspan="4" style="text-align:center;">' . $leave . '</td>';
                        $html .= '<td></td>';
                        $html .= '<td></td>';
                    } else {
                        $check = 0;
                        if ($in1 != '') {

                            $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                        }
                        if ($out1 != '') {

                            $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                        }

                        if ($in1 == '' && $out1 == '') {
                            $check++;

                            $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                        }

                        if ($in2 != '') {

                            $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                        }
                        if ($out2 != '') {

                            $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                        }

                        if ($in2 == '' && $out2 == '') {
                            $check++;

                            $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                        }

                        if ($in3 != '') {

                            $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                        }
                        if ($out3 != '') {

                            $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                        }

                        if ($in3 == '' && $out3 == '' &&  $check == 1) {

                            $html .= '<td></td>';
                            $html .= '<td></td>';
                        } else if ($in3 == '' && $out3 == '' &&  $check == 2) {

                            $html .= '<td colspan="2" style="text-align:center;">' . $leave . '</td>';
                        } else if ($in3 == '' && $out3 == '') {
                            $html .= '<td></td>';
                            $html .= '<td></td>';
                        }
                    }

                    $html .= '<td style="text-align:center;">' . $hw . '</td>';
                    $html .= '<td style="text-align:center;">' . $dw . '</td>';
                    $html .= '<td style="text-align:center;">' . $ot . '</td>';
                    $html .= '</tr>';
                } else {

                    $html .= '<tr>';
                    $html .= '<td style="padding:2px;">' . $date . '</td>';
                    $html .= '<td style="text-align:center;">' . $day . '</td>';
                    $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                    $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                    $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                    $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                    $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                    $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                    $html .= '<td style="text-align:center;">' . $hw . '</td>';
                    $html .= '<td style="text-align:center;">' . $dw . '</td>';
                    $html .= '<td style="text-align:center;">' . $ot . '</td>';
                    $html .= '</tr>';
                }

                $dateFrom = date("Y-m-d", strtotime($dateFrom . "+1 day"));
            }

            // Previous Cut Off **********************************************************************************
            $dF     = date('Y-m-d', strtotime($input['dateFrom']));
            $dT     = date('Y-m-d', strtotime($input['dateTo']));
            $cond   = "empId = '$empId' AND coDF1 = '$dF' AND coDF2 = '$dT'";
            $pdp    = $this->dtr_model->getResultArray($tk, 'perday_previous', $cond);
            $cond   = array('empId' => $empId, 'dateFrom' => $dF, 'dateTo' => $dT);
            $allow  = $this->dtr_model->getResultArray($tk, 'perday_previous_allow', $cond);
            if ($pdp && $allow) {

                $html .= '<tr>';
                $html .= '<th colspan="11" style="text-align:left; padding-top:10px;">Previous Days Worked</th>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<th colspan="11" style="border-bottom:0.5px solid #000;"></th>';
                $html .= '</tr>';

                foreach ($pdp as $nf) {

                    $dateDuty   = $nf['dateDuty'];
                    $hw_new     = floatval($nf['hw_new']) * 1;
                    $hw_con     = $nf['hw_con'] + 0;
                    $dayS       = date("D", strtotime($dateDuty));
                    $dateD      = date("m/d/Y", strtotime($dateDuty));

                    $cond   = "bioMetricId = '$bioM' AND date = '$dateDuty'";
                    $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                    $dutyId = $dtr['dutyId'] ?? '';
                    $in1    = $dtr['in1'] ?? '';
                    $out1   = $dtr['out1'] ?? '';
                    $in2    = $dtr['in2'] ?? '';
                    $out2   = $dtr['out2'] ?? '';
                    $in3    = $dtr['in3'] ?? '';
                    $out3   = $dtr['out3'] ?? '';

                    if (!empty($in1)) {
                        $in1 = date('h:i A', strtotime($in1));
                    }

                    if (!empty($out1)) {
                        $out1 = date('h:i A', strtotime($out1));
                    }

                    if (!empty($in2)) {
                        $in2 = date('h:i A', strtotime($in2));
                    }

                    if (!empty($out2)) {
                        $out2 = date('h:i A', strtotime($out2));
                    }

                    if (!empty($in3)) {
                        $in3 = date('h:i A', strtotime($in3));
                    }

                    if (!empty($out3)) {

                        $out3 = date('h:i A', strtotime($out3));
                    }

                    $inC1 = $in1;
                    $inC2 = $in2;
                    $inC3 = $in3;
                    $outC1 = $out1;
                    $outC2 = $out2;
                    $outC3 = $out3;
                    $cond = array('empId' => $empId, 'date' => $dateDuty);
                    $od = $this->dtr_model->getRowArray($tk, 'off_date', $cond);
                    if ($od) {

                        $inC1 = '';
                        $inC2 = '';
                        $inC3 = '';
                        $ts = $od['timeStarted'];
                        if ($ts == $in1) {
                            $inC1 = date('h:i A', strtotime($in1));
                        }
                        if ($ts == $in2) {
                            $inC2 = date('h:i A', strtotime($in2));
                        }
                        if ($ts == $in3) {
                            $inC3 = date('h:i A', strtotime($in3));
                        }

                        $outC1 = '';
                        $outC2 = '';
                        $outC3 = '';
                        $te = $od['timeEnded'];
                        if ($te == $out1) {
                            $outC1 = date('h:i A', strtotime($out1));
                        }
                        if ($te == $out2) {
                            $outC2 = date('h:i A', strtotime($out2));
                        }
                        if ($te == $out3) {
                            $outC3 = date('h:i A', strtotime($out3));
                        }
                    }

                    $html .= '<tr>';
                    $html .= '<td style="padding:5px;">' . $dateD . '</td>';
                    $html .= '<td style="text-align:center;">' . $dayS . '</td>';
                    $html .= '<td>' . $inC1 . '</td>';
                    $html .= '<td>' . $outC1 . '</td>';
                    $html .= '<td>' . $inC2 . '</td>';
                    $html .= '<td>' . $outC2 . '</td>';
                    $html .= '<td>' . $inC3 . '</td>';
                    $html .= '<td>' . $outC3 . '</td>';
                    $html .= '<td style="text-align:center;">' . $hw_new . '</td>';
                    $html .= '<td style="text-align:center;">' . $hw_con . '</td>';
                    $html .= '<td style="text-align:center;">0</td>';
                    $html .= '</tr>';
                }
            }

            // Previous OT **********************************************************************************
            $dF     = date('Y-m-d', strtotime($input['dateFrom']));
            $dT     = date('Y-m-d', strtotime($input['dateTo']));
            $cond   = "emp_id = '$empId' AND supDF = '$dF' AND supDT = '$dT' AND otDate < '$dF'";
            $otp    = $this->dtr_model->getResultArray($tk, 'otforpayment', $cond);
            if ($otp) {

                $html .= '<tr>';
                $html .= '<th colspan="11" style="text-align:left; padding-top:10px;">Previous OT</th>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<th colspan="11" style="border-bottom:0.5px solid #000;"></th>';
                $html .= '</tr>';
                foreach ($otp as $ot) {

                    $dateDuty   = $ot['otDate'];
                    $otApproved = floatval($ot['otApp']) * 1;
                    $dayS       = date("D", strtotime($dateDuty));
                    $dateD      = date("m/d/Y", strtotime($dateDuty));

                    $cond   = "bioMetricId = '$bioM' AND date = '$dateDuty'";
                    $dtr    = $this->dtr_model->getRowArray($tk, 'duty', $cond);
                    $dutyId = $dtr['dutyId'] ?? '';
                    $in1    = $dtr['in1'] ?? '';
                    $out1   = $dtr['out1'] ?? '';
                    $in2    = $dtr['in2'] ?? '';
                    $out2   = $dtr['out2'] ?? '';
                    $in3    = $dtr['in3'] ?? '';
                    $out3   = $dtr['out3'] ?? '';

                    // store entry
                    $in1_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                    $out1_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                    $in2_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                    $out2_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                    $in3_store  = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                    $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                    if (empty($out3_store)) {
                        $out3_store = $this->masterfile_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
                    }

                    if (!empty($in1)) {
                        $in1 = date('h:i A', strtotime($in1));
                    }

                    if (!empty($out1)) {
                        $out1 = date('h:i A', strtotime($out1));
                    }

                    if (!empty($in2)) {
                        $in2 = date('h:i A', strtotime($in2));
                    }

                    if (!empty($out2)) {
                        $out2 = date('h:i A', strtotime($out2));
                    }

                    if (!empty($in3)) {
                        $in3 = date('h:i A', strtotime($in3));
                    }

                    if (!empty($out3)) {

                        $out3 = date('h:i A', strtotime($out3));
                    }

                    $html .= '<tr>';
                    $html .= '<td style="padding:5px;">' . $dateD . '</td>';
                    $html .= '<td style="text-align:center;">' . $dayS . '</td>';
                    $html .= '<td>' . $in1_store . '' . $in1 . '</td>';
                    $html .= '<td>' . $out1_store . '' . $out1 . '</td>';
                    $html .= '<td>' . $in2_store . '' . $in2 . '</td>';
                    $html .= '<td>' . $out2_store . '' . $out2 . '</td>';
                    $html .= '<td>' . $in3_store . '' . $in3 . '</td>';
                    $html .= '<td>' . $out3_store . '' . $out3 . '</td>';
                    $html .= '<td style="text-align:center;">0</td>';
                    $html .= '<td style="text-align:center;">0</td>';
                    $html .= '<td style="text-align:center;">' . $otApproved . '</td>';
                    $html .= '</tr>';
                }
            }

            $html .= '<tr>';
            $html .= '<th colspan="11" style="border-top:0.5px solid #000; padding:5px;"></th>';
            $html .= '</tr>';
            $html .= '</table>';

            $i = 0;
            $str = '';
            $legend = $this->dtr_model->dtr_store_code();
            foreach ($legend as $key => $store) {
                $i++;
                if ($i == 9) {
                    $str .= '<br>';
                }
                $str .= $key . ' - ' . $store . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $this->pdf->SetMargins(12, 8, 12);
            $remaining = 147 - $this->pdf->y;

            $html .= '<p style="margin-top:1px;font-size:8px;">' . $str . '</p>';
            $html .= '<br>';

            if ($remaining < 0) {
                $this->pdf->AddPage();
                $this->pdf->y = $this->pdf->tMargin;
            }
            $this->pdf->WriteHTML($html);
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Promo DTR.pdf"')
            ->setBody($this->pdf->Output('', 'S'));
    }

    public function vTEntry()
    {
        $input  = $this->request->getPost();
        $server = $input['server'];
        $empId  = $input['empId'];
        $date   = $input['dateRendered'];
        $nDate  = date('m-d-Y', strtotime($date));
        $df     = date('Y-m-d', strtotime($input['dateRendered']));

        echo '<table class="table table-sm table-bordered">';
        echo    '<tr>
					<th>Shift Code</th>
					<th>Time</th>
                </tr>';
        $cond = "empId='$empId' AND ('$df' BETWEEN dateFrom AND dateTo) ORDER BY amsId DESC LIMIT 1";
        $c = $this->dtr_model->getRowArray($server, 'assign_mul_sc', $cond);
        if ($c) {

            $mSC    = $c['shiftCode'];
            $cond   = "shiftCode = '$mSC'";
            $e      = $this->dtr_model->getRowArray($server, 'shiftcodes', $cond);
            $in1    = $e['1stIn'];
            $out1   = $e['1stOut'];
            $in2    = $e['2ndIn'];
            $out2   = $e['2ndOut'];
            $sched = $in1 . ' - ' . $out1;
            if ($in2 && $out2) {
                $sched = $in1 . ' - ' . $out1 . ' | ' . $in2 . ' - ' . $out2;
            }
            echo    '<tr>
                        <td>' . $mSC . '</td>
                        <td>' . $sched . ' </td>
                    </tr>';
        }
        echo '</table>';
        echo '<table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Mode</th>
                        <th>Terminal</th>
                        <th>Store</th>
                    </tr>
                </thead>
                <tbody>';

        $bio = '';
        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $assign = $this->dtr_model->getRowArray($server, 'assign', $cond);
        $allowId = $assign['allowId'];
        if ($allowId == "Biometric ID") {

            $bio = $assign['bioMetricId'];
            $cond = "bioMetricId = '$bio' AND date = '$nDate' ORDER BY ADDTIME(timeIn,timeOut) ASC";
            $con = $this->dtr_model->getResultArray($server, 'duty_biometric', $cond);
        }

        if ($allowId == "Logbox ID") {

            $bio = $assign['barcodeId'];
            $cond = "bioMetricId = '$bio' AND date = '$nDate' ORDER BY ADDTIME(timeIn,timeOut) ASC";
            $con = $this->dtr_model->getResultArray($server, 'duty_logbox', $cond);
        }

        foreach ($con as $c) {
            $bioM       = $c['bioMetricId'];
            $date       = $c['date'];
            $mode       = $c['mode'];
            $timeIn     = $c['timeIn'];
            $timeOut    = $c['timeOut'];

            $exp        = explode("-", $date);
            $fromDate   = "$exp[2]-$exp[0]-$exp[1]";

            $cond  = "bioMetricId = '$bioM' AND dateDuty = '$fromDate' AND timeIn = '$timeIn' AND timeOut = '$timeOut'";
            $store = $this->dtr_model->getRowArray($server, 'duty_logbox', $cond)['store'] ?? '';

            if ($timeOut == 0) {
                $time = $timeIn;
            }

            if ($timeIn == 0) {
                $time = $timeOut;
            }

            echo "<tr>
                    <td>$date</td>
                    <td>$time</td>
                    <td>$mode</td>
                    <td>$allowId</td>
                    <td>$store</td>";
            echo "</tr>";
        }

        echo '</tbody>';
        echo '</table>';
    }

    public function viewRequest()
    {
        $input  = $this->request->getPost();
        $empId  = $input['empId'];
        $req    = $input['request'];
        $date   = $input['date'];
        $server = $input['server'];
        $render = date('M. d, Y', strtotime($input['date']));
        echo '<div class="row">';
        if ($req == 'LOA') {
            $cond   = "r_l_absence.empId = rla_day.empId AND (status = 'Approved' OR status = 'Pending') AND ";
            $cond   .= "r_l_absence.empId = '$empId' AND rla_day.date = '$date' AND '$date' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT";
            $loa    = $this->dtr_model->getRowArray($server, 'r_l_absence, rla_day', $cond);
            $rLAId  = $loa['rLAId'];
            $type   = $loa['typeLeave'];
            $reason = $loa['if_emer_pers'];
            $reqD   = date("M. d, Y", strtotime($loa['dateReq']));
            $remark = $loa['remark'];
            $status = $loa['status'];
            $class  = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">LOA Details</h5>';
            echo '<table class="table table-sm table-bordered align-middle" style="width:100%;">
                    <tr>
                        <th>Date Requested</th>
                        <td>' . $reqD . '</td>
                        <th>Date Rendered</th>
                        <td>' . $render . '</td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td>' . $remark . '</td>
                        <th>Status</th>
                        <td ' . $class . '>' . $status . '</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td colspan="3">' . $type . '</td>
                    </tr>
                    <tr>
                        <th>Reason</th>
                        <td colspan="3">' . $reason . '</td>
                    </tr>';
            echo '</table>';
            echo '</div>';

            $cond = array('requestId' => $rLAId, 'requestType' => 'RLA');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $row) {

                    $status     = $row['status'];
                    $date       = $row['date'];
                    $time       = $row['time'];
                    $userId     = $row['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'DWA') {
            $cond   = "d_w_adjustment.empId = off_date.empId AND (status = 'Approved' OR status = 'Pending') AND ";
            $cond   .= "d_w_adjustment.empId = '$empId' AND off_date.date = '$date' AND '$date' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo";
            $loa    = $this->dtr_model->getRowArray($server, 'd_w_adjustment, off_date', $cond);
            if ($loa) {
                $dWAId      = $loa['dWAId'];
                $type       = $loa['type'];
                $reqD       = date("M. d, Y", strtotime($loa['dateReq']));
                $remark     = $loa['remark'];
                $status     = $loa['status'];
                $details    = $loa['details'];
                $errId      = $loa['adj_errfai_id'];
                $class  = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">DWA Details</h5>';
                echo '<table class="table table-sm table-bordered align-middle" style="width:100%;">
                        <tr>
                            <th>Date Requested</th>
                            <td>' . $reqD . '</td>
                             <th>Remark</th>
                            <td>' . $remark . '</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>' . $type . '</td>
                            <th>Status</th>
                            <td ' . $class . '>' . $status . '</td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td colspan="3">' . $details . '</td>
                        </tr>';
                echo '</table>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Date Rendered</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Day</th>
                        </tr>';
                $dateO  = $loa['date'];
                $timeS  = $loa['timeStarted'];
                $timeE  = $loa['timeEnded'];
                $day    = $loa['day'];
                $dateO  = date("M. d, Y", strtotime($dateO));
                echo    '<tr>
                            <td>' . $dateO . '</td>
                            <td>' . $timeS . '</td>
                            <td>' . $timeE . '</td>
                            <td>' . $day . '</td>
                        </tr>';
                echo '</table>';
                echo '</div>';
            } else {
                $cond       = "(status = 'Approved' OR status = 'Pending') AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') AND ";
                $cond       .= "empId='$empId'  AND '$date' BETWEEN dateFrom AND dateTo";
                $loa        = $this->dtr_model->getRowArray($server, 'd_w_adjustment', $cond);
                $dWAId      = $loa['dWAId'];
                $type       = $loa['type'];
                $reqD       = date("M. d, Y", strtotime($loa['dateReq']));
                $remark     = $loa['remark'];
                $status     = $loa['status'];
                $class      = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
                $details    = $loa['details'];
                $errId      = $loa['adj_errfai_id'];

                $in1_c  = '';
                $out1_c = '';
                $in2_c  = '';
                $out2_c = '';
                $sched  = '';

                $cond   = array('err_fai_id' => $errId);
                $f      = $this->dtr_model->getRowArray($server, 'dwa_error_failed', $cond);

                $in1    = $f['in1'];
                $out1   = $f['out1'];
                $in2    = $f['in2'];
                $out2   = $f['out2'];


                $in1_V  = '';
                $out1_V = '';
                $in2_V  = '';
                $out2_V = '';

                $cond   = array('err_fai_id' => $errId);
                $v      = $this->dtr_model->getRowArray($server, 'dwa_err_failed_value', $cond) ?? [];

                if (count($v) == 1) {

                    $in1_V  = $v['in1'];
                    $out1_V = $v['out1'];

                    $in2_V  = $v['in2'];
                    $out2_V = $v['out2'];
                }


                if ($in1 == "True") {
                    $in1_c = "In1";
                    $in1_V = "$in1_V";
                }

                if ($out1 == "True") {
                    $out1_c = "Out1";
                    $out1_V = "<font color = 'red'>$out1_V</font>";
                }

                if ($in2 == "True") {
                    $in2_c = "In2";
                    $in1_V = "<font color = 'red'>$in2_V</font>";
                }

                if ($out2 == "True") {
                    $out2_c = "Out2";
                    $out1_V = "<font color = 'red'>$out2_V</font>";
                }

                $sched = '(<b><font color = "red">' . $in1_c . ' ' . $out1_c . ' ' . $in2_c . ' ' . $out2_c . '</font></b>)';

                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">DWA Details</h5>';
                echo '<table class="table table-sm table-bordered align-middle" style="width:100%;">
                        <tr>
                            <th>Date Requested</th>
                            <td>' . $reqD . '</td>
                            <th>Date Rendered</th>
                            <td>' . $render . '</td>
                        </tr>
                        <tr>
                            <th>Remark</th>
                            <td>' . $remark . '</td>
                            <th>Status</th>
                            <td ' . $class . '>' . $status . '</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td colspan="3">' . $type . ' ' . $sched . '</td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td colspan="3">' . $details . '</td>
                        </tr>';
                echo '</table>';
                echo '</div>';
            }

            $cond = array('requestId' => $dWAId, 'requestType' => 'DWA');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'EOA') {
            $cond   = "empId='$empId' AND dateOvertime='$date' AND (status = 'Approved' OR status = 'Pending')";
            $eoa    = $this->dtr_model->getRowArray($server, 'e_o_authorization', $cond);
            $eOAId  = $eoa['eOAId'];
            $reqD   = date("M. d, Y", strtotime($eoa['dateReq']));
            $status = $eoa['status'];
            $in1    = $eoa['ot1st_time'];
            $out1   = $eoa['ot1en_time'];
            $ot1    = $eoa['ot1_length'];
            $in2    = $eoa['ot2st_time'];
            $out2   = $eoa['ot2en_time'];
            $ot2    = $eoa['ot2_length'];

            $otLength   = $eoa['OTLength'];
            $otApp      = $eoa['otApp'];
            $remark     = $eoa['remark'];
            $purpose    = $eoa['purpose'];
            $class = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">EOA Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                    <tr>
                        <th>Date Requested</th>
                        <td>' . $reqD . '</td>
                        <th>Date Overtime</th>
                        <td>' . $render . '</td>
                    </tr>
                    <tr>
                        <th>Time (Before Duty)</th>
                        <td>' . $in1 . ' - ' . $out1 . '</td>
                        <th>O.T. 1 Length</th>
                        <td>' . $ot1 . ' hr(s).</td>
                    </tr>
                    <tr>
                        <th>Time (After Duty)</th>
                        <td>' . $in2 . ' - ' . $out2 . '</td>
                        <th>O.T. 2 Length</th>
                        <td>' . $ot2 . ' hr(s).</td>
                    </tr>
                    <tr>
                        <th>Total OT Requested</th>
                        <td>' . $otLength . '</td>
                        <th>Total OT Approved</th>
                        <td>' . $otApp . '</td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td>' . $remark . '</td>
                        <th>Status</th>
                        <td ' . $class . '>' . $status . '</td>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <td colspan="3">' . $purpose . '</td>
                    </tr>
                </table>';
            echo '</div>';
            $cond = array('requestId' => $eOAId, 'requestType' => 'EOA');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'EOP') {
            $cond       = "emp_id='$empId' AND otDate='$date' AND (status = 'Approved' OR status = 'Pending')";
            $eop        = $this->dtr_model->getRowArray($server, 'otforpayment', $cond);
            $pay_id     = $eop['pay_id'];
            $otLength   = $eop['otLength'];
            $otApp      = $eop['otApp'];
            $otDis      = $eop['otDisapproved'];
            $otDate     = $eop['otDate'];
            $dateReq    = $eop['dateReq'];
            $status     = $eop['status'];
            $approval   = $eop['status2'];
            $otD        = date('M. d, Y', strtotime($otDate));
            $dateR      = date('M. d, Y', strtotime($dateReq));
            $class      = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">EOP Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                        <tr>
                            <th>Date Requested</th>
                            <td>' . $dateR . '</td>
                            <th>Date Overtime</th>
                            <td>' . $otD . '</td>
                        </tr>
                        <tr>
                            <th>Total OT Requested</th>
                            <td>' . $otLength . '</td>
                            <th>Total OT Approved</th>
                            <td>' . $otApp . '</td>
                        </tr>
                        <tr>
                            <th>Total OT Disapproved</th>
                            <td>' . $otDis . '</td>
                            <th>Status</th>
                            <td><span ' . $class . '>' . $status . '</span> [' . $approval . ']</span></td>
                        </tr>
                    </table>';
            echo '</div>';
            $cond = array('requestId' => $pay_id, 'requestType' => 'EOP');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';
                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'ECA') {
            $cond       = "empId='$empId' AND cuDate='$date' AND (status = 'Approved' OR status = 'Pending')";
            $sql        = $this->dtr_model->getRowArray($server, 'coverup_auth', $cond);
            $eCAId      = $sql['eCAId'];
            $empId      = $sql['empId'];
            $cuDate     = $sql['cuDate'];
            $cuPurpose  = $sql['cuPurpose'];
            $apl        = $sql['approval'];
            $dateR      = $sql['dateReq'];
            $status     = $sql['status'];
            $dReq       = date('M. d, Y', strtotime($dateR));
            $cDate      = date('M. d, Y', strtotime($cuDate));
            $class  = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">ECA Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                        <tr>
                            <th>Date Requested</th>
                            <td>' . $dReq . '</td>
                            <th>Date Cover-Up</th>
                            <td>' . $cDate . '</td>
                        </tr>
                        <tr>
                            <th>Approval</th>
                            <td>' . $apl . '</td>
                            <th>Status</th>
                            <td ' . $class . '>' . $status . '</td>
                        </tr>
                        <tr>
                            <th>Purpose</th>
                            <td colspan="3">' . $cuPurpose . '</td>
                        </tr>
                    </table>';
            echo '</div>';
            $cond = array('eCAId' => $eCAId);
            $cov = $this->dtr_model->getResultArray($server, 'coverup_auth_ext', $cond);
            if ($cov) {
                echo    '<table class="table table-bordered table-sm" style="width:100%;">
                            <tr>
                                <th>Date Overtime</th>
                                <th>OT Approved</th>
                                <th>OT used to Cover-Up</th>
                            </tr>';

                foreach ($cov as  $v) {
                    $dateOT = $v['dateOT'];
                    $usedOT = $v['usedOT'];
                    $cond   = array('empId' => $empId, 'dateOvertime' => $dateOT);
                    $eoa    = $this->dtr_model->getRowArray($server, 'e_o_authorization', $cond);
                    if ($eoa) {
                        $otApp  = $eoa['otApp'];
                        $dOT    = date('M. d, Y', strtotime($dateOT));
                        echo    '<tr>
                                    <td>' . $dOT . '</td>
                                    <td>' . $otApp . '</td>
                                    <td>' . $usedOT . ' hour(s)</td>
                                </tr>';
                    }
                }
                echo '</table>';
            }
            echo '</div>';

            $cond = array('requestId' => $eCAId, 'requestType' => 'ECA');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'EUA') {
            $cond       = "empId='$empId' AND dateUndertime='$date' AND (status = 'Approved' OR status = 'Pending')";
            $eua        = $this->dtr_model->getRowArray($server, 'undertime_auth', $cond) ?? [];
            $eUAId      = $eua['eUAId'];
            $dateUT     = $eua['dateUndertime'];
            $utWhen     = $eua['utWhen'];
            $noHours    = $eua['numHours'];
            $reason     = $eua['reason'];
            $remark     = $eua['remark'];
            $dateReq    = $eua['dateReq'];
            $status     = $eua['status'];

            $dateU = date('M. d, Y', strtotime($dateUT));
            $dateR = date('M. d, Y', strtotime($dateReq));
            $class  = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">EUA Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                        <tr>
                            <th>Date Requested</th>
                            <td>' . $dateR . '</td>
                            <th>Date Undertime</th>
                            <td>' . $dateU . '</td>
                        </tr>
                        <tr>
                            <th>Undertime Schedule</th>
                            <td>' . $utWhen . '</td>
                            <th>No. of Hours</th>
                            <td>' . $noHours . ' hour(s)</td>
                        </tr>
                        <tr>
                            <th>Remark</th>
                            <td>' . $remark . '</td>
                            <th>Status</th>
                            <td ' . $class . '>' . $status . '</td>
                        </tr>
                        <tr>
                            <th>Reason</th>
                            <td colspan="3">' . $reason . '</td>
                        </tr>
                    </table>';
            echo '</div>';
            $cond = array('requestId' => $eUAId, 'requestType' => 'EUA');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'CDOS') {
            $cond       = "empId='$empId' AND dateToChange='$date' AND (status = 'Approved' OR status = 'Pending')";
            $cdos       = $this->dtr_model->getRowArray($server, 'ass_change_do', $cond);
            $acdId      = $cdos['acdId'];
            $curDay     = $cdos['dayToChange'];
            $newDay     = $cdos['dayNew'];
            $status     = $cdos['status'];
            $details    = $cdos['details'];
            $curDate    = date("M. d, Y", strtotime($cdos['dateToChange']));
            $newDate    = date("M. d, Y", strtotime($cdos['dateNew']));
            $dateReq    = date("M. d, Y", strtotime($cdos['dateReq']));
            $class      = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">CDOS Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                        <tr>
                            <th>Current Dayoff</th>
                            <td>' . $curDate . ' (' . $curDay . ')</td>
                            <th>Date Requested</th>
                            <td>' . $dateReq . '</td>
                        </tr>
                        <tr>
                            <th>New Dayoff</th>
                            <td>' . $newDate . ' (' . $newDay . ')</td>
                            <th>Status</th>
                            <td ' . $class . '>' . $status . '</td>
                        </tr>
                        <tr>
                            <th>Reason</th>
                            <td colspan="3">' . $details . '</td>
                        </tr>
                    </table>';
            echo '</div>';
            $cond = array('requestId' => $acdId, 'requestType' => 'CDOS');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        } else if ($req == 'CDOL') {
            $cond       = array('empId' => $empId, 'effectiveDate' => $date);
            $cdol       = $this->dtr_model->getRowArray($server, 'ass_dayoff_long', $cond) ?? [];
            $adlid      = $cdol['adlid'];
            $newDay     = $cdol['newDay'];
            $curDay     = $cdol['curDayOff'];
            $eDate      = date("M. d, Y", strtotime($cdol['effectiveDate']));
            $dateReq    = date("M. d, Y", strtotime($cdol['dateRequested']));
            $status     = $cdol['status'];
            $class      = ($status == 'Approved') ? 'class="text-green"' : 'class="text-warning"';
            echo '<div class="col-md-12">';
            echo '<h5 class="text-success">CDOS Details</h5>';
            echo    '<table class="table table-sm table-bordered" style="width:100%;">
                        <tr>
                            <th>Current Default Dayoff</th>
                            <td>' . $curDay . '</td>
                            <th>Date Requested</th>
                            <td>' . $dateReq . '</td>
                        </tr>
                        <tr>
                            <th>New Dayoff</th>
                            <td>' . $newDay . '</td>
                            <th>Effective Date</th>
                            <td>' . $eDate . '</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td colspan="3" ' . $class . '>' . $status . '</td>
                        </tr>
                    </table>';
            echo '</div>';
            $cond = array('requestId' => $adlid, 'requestType' => 'CDOL');
            $query = $this->dtr_model->getResultArray($server, 'approveby', $cond);
            if ($query) {
                echo '<div class="col-md-12">';
                echo '<h5 class="text-success">Supervisors Approval</h5>';
                echo '<table class="table table-bordered table-sm" style="width:100%;">
                        <tr>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Date Approved</th>
                        </tr>';

                foreach ($query as  $val) {

                    $status     = $val['status'];
                    $date       = $val['date'];
                    $time       = $val['time'];
                    $userId     = $val['userId'];
                    @$datime    = "$date | $time";
                    if ($status == 'Approved') {
                        $color = 'text-green';
                    } else if ($status == 'Disapproved') {
                        $color = 'text-danger';
                    } else {
                        $color = '';
                    }
                    echo    '<tr>
                                <td>' . $userId . '</td>
                                <td class="' . $color . '">' . $status . '</td>
                                <td>' . $datime . '</td>
                            </tr>';
                }
                echo '</table>';
                echo '</div>';
            }
        }
        echo '</div>';
    }
}
