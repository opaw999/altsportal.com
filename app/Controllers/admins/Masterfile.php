<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Masterfile extends BaseController
{
    public function searchPromo()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        echo '<ul class="list-group">';
        $query = $this->masterfile_model->searchPromo($pis, $input['str']);
        if ($query) {
            foreach ($query as $row) {
                $empId      = $row['emp_id'];
                $cond       = "emp_id = '$empId' ORDER BY date_added DESC LIMIT 1";
                $emp        = $this->masterfile_model->getRowArray($pis, 'employee3', $cond);
                $name       = htmlspecialchars($emp['name']);
                $recordNo   = $emp['record_no'];

                $cond       = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
                $assign     = $this->masterfile_model->getRowArray($tk, 'assign',  $cond);
                $chk        = $this->masterfile_model->promoDetails($pis, $empId, $recordNo);
                if ($chk && $assign) {
                    $data = $name . '|' . $empId . '|' . $recordNo;
                    $click = 'onclick="selectPromo(\'' . $data . '\')"';
                    echo '<a href="javascript:;" class="list-group-item" ' . $click . '>' . $name . '</a>';
                } else {
                    echo '<span class="list-group-item text-muted">' . $name . '</span>';
                }
            }
        } else {
            echo '<span class="list-group-item">No Results Found!</span>';
        }
        echo '</ul>';
    }

    public function promoData()
    {
        $input      = $this->request->getPost();
        $month      = date('m');
        $year       = date('Y');
        $empId      = $input['emp_id'];
        $recordNo   = $input['record_no'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $i = 0;
        $stores = '';
        $bus = $this->masterfile_model->locate_promo_bu($pis);
        foreach ($bus as $bu) {
            $field = $bu['bunit_field'];
            $name = '<span class="badge bg-success me-1">' . $bu['bunit_acronym'] . '</span>';
            $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
            $check = $this->masterfile_model->getRowArray($pis, 'promo_record', $cond);
            if ($check) {
                $i++;
                $stores = ($i == 1) ? $name : $stores . $name;
            }
        }

        $row = $this->masterfile_model->promoDetails($pis, $empId, $recordNo);
        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $data = $this->masterfile_model->getRowArray($tk, 'assign', $cond);

        $dayoff = $this->masterfile_model->getRowArray($tk, 'assign_day_off', ['empId' => $empId]);
        $do = ($dayoff) ? $dayoff['day'] : $row['dayoff'];
        if ($do == '') {
            $do = 'No Day Off';
        }

        $sched = '';
        $sc = $this->masterfile_model->getRowArray($tk_loc, 'shiftcodes', ['shiftCode' => $data['shiftCode']]);
        if ($sc) {
            $in1    = $sc['1stIn'];
            $out1   = $sc['1stOut'];
            $in2    = $sc['2ndIn'];
            $out2   = $sc['2ndOut'];

            $sched = "$in1 $out1";
            if ($in2) {
                $sched = "$in1 $out1 - $in2 $out2";
            }
        }

        echo '<table class="table table-sm font_size3 mt-1">
                <tr>
                    <th>HRMS ID</th>
                    <td colspan="3">: ' . $row['emp_id'] . '</td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td colspan="3">: ' . $row['promo_company'] . '</td>
                </tr>
                <tr>
                    <th>Store(s)</th>
                    <td colspan="3">: ' . $stores . '</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td colspan="3">: ' . $row['promo_department'] . '</td>
                </tr>
                <tr>
                    <th>Biometric ID</th>
                    <td colspan="3">: ' . $data['bioMetricId'] . '</td>
                </tr>
                    <th>Logbox ID</th>
                    <td colspan="3">: ' . $data['barcodeId'] . '</td>
                </tr>
                <tr>
                    <th>Default ID</th>
                    <td>: ' . $data['allowId'] . '</td>
                    <th>Day Off</th>
                    <td>: ' . $do . '</td>
                </tr>';
        if ($row['promo_type'] == 'STATION') {
            echo    '<tr>
                        <th>Default Shift Code</th>
                        <td colspan="3">: ' . $data['shiftCode'] . '</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td colspan="3">: ' . $sched . '</td>
                    </tr>';
        }
        echo '</table>';
        echo '<div class="button-container d-flex justify-content-center">';
        if ($row['promo_type'] == 'ROVING') {
            echo '<button class="btn btn-primary mx-1" onclick="shiftSched(\'' . $empId . '\')">Default Schedule</button>';
        }

        $click = 'onclick="viewSched(\'' . $empId . '\',\'' . $recordNo . '\',\'' . $row['promo_type'] . '\',\'' . $month . '\',\'' . $year . '\')"';
        echo    '<button class="btn btn-primary mx-1" ' . $click . '>View Schedule</button>';
        echo '</div>';
    }

    public function multSched()
    {
        $input      = $this->request->getPost();
        $empId      = $input['emp_id'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        echo '<h5 class="text-center">Multiple Shift Schedule</h5>';
        echo '<table class="table table-hover table-sm font_size3" width="100%" id="multScheds">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>SC</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Request</th>
                        <th>Request Date</th>
                        <th>Dayoff</th>
                    </tr>
                <thead>
                <tbody>';
        $cond = "empId ='$empId' ORDER BY dateFrom DESC";
        $query = $this->masterfile_model->getResultArray($tk, 'assign_mul_sc', $cond);
        foreach ($query as $row) {

            $amsId  = $row['amsId'];
            $mSC    = $row['shiftCode'];
            $dF     = $row['dateFrom'];
            $dT     = $row['dateTo'];

            $status = '<span class="badge bg-warning">' . $row['status'] . '</span>';
            if ($row['status'] == 'active') {
                $status = '<span class="badge bg-green">' . $row['status'] . '</span>';
            }

            $dFrom  = date('m/d/Y', strtotime($dF));
            $dTo    = date('m/d/Y', strtotime($dT));

            $sched = '';
            $sc = $this->masterfile_model->getRowArray($tk_loc, 'shiftcodes', ['shiftCode' => $mSC]);
            if ($sc) {
                $in1    = $sc['1stIn'];
                $out1   = $sc['1stOut'];
                $in2    = $sc['2ndIn'];
                $out2   = $sc['2ndOut'];

                $sched = "$in1 $out1";
                if ($in2) {
                    $sched = "$in1 $out1 - $in2 $out2";
                }
            }

            $reqStat = '';
            $dateReq = '';
            $num_odaf = $this->masterfile_model->getRowArray($tk, 'request_odaf', ['amsId' => $amsId]);
            if ($num_odaf) {

                $dateReq = $num_odaf['dateReq'];
                $timeReq = $num_odaf['timeReq'];
                $dateReq = date("m/d/Y g:i A", strtotime("$dateReq $timeReq"));
                $reqStat = "<b>O.D.A.</b>";
            }

            $num_nsc = $this->masterfile_model->getRowArray($tk, 'request_new_shift', ['amsId' => $amsId]);
            $action = '';
            if ($num_nsc) {
                $dateReq    = $num_nsc['dateReq'];
                $timeReq    = $num_nsc['timeReq'];
                $dateReq    = date("m/d/Y g:i A", strtotime("$dateReq $timeReq"));
                $reqStat    = "<b>C.S.</b>";
                $click      = 'onclick="viewDayoff(\'' . $empId . '\',\'' . $amsId . '\')"';
                $action     = '<a href="javascript:;" title="View Dayoff" ' . $click . '><i class="fa-solid fa-calendar-day fs-20px"></i></a>';
            }

            echo "<tr>
                    <td>$dFrom - $dTo</td>
                    <td>$mSC</td>
                    <td>$sched </td>
                    <td>$status</td>
                    <td>$reqStat</td>
                    <td>$dateReq</td>
                    <td>$action</td>";
            echo "</tr>";
        }

        echo '</tbody>';
        echo '</table>';
    }

    public function promoDefSched()
    {
        $input      = $this->request->getPost();
        $empId      = $input['emp_id'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        echo '<div class="table-responsive">';
        echo '<table class="table table-hover table-sm font_size3" width="100%" id="promoDefSched">
                <thead>
                    <tr>
                    	<th>Day</th>
                    	<th>Shift Code</th>
                    	<th>Time Schedule</th>
                    	<th>Store1</th>
                    	<th>Store2</th>
                    </tr>
                </thead>
                <tbody>';
        $query = $this->masterfile_model->getResultArray($server2, 'promo_def_sched', ['empId' => $empId]);
        foreach ($query as $row) {
            $dayV       = $row['day_sched'];
            $shiftCode  = $row['shiftCode'];
            $storea     = $row['store1'];
            $storeb     = $row['store2'];

            $storea = str_replace(',', '', $row['store1']);
            $storeb = str_replace(',', '', $row['store2']);


            $s_a = '';
            $s1 = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storea]);
            if ($s1) {
                $s_a = $s1['bunit_name'];
            }

            $s_b = '';
            $s2 = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storeb]);
            if ($s2) {
                $s_b = $s2['bunit_name'];
            }

            $sched = '';
            $sc     = $this->masterfile_model->getRowArray($tk_loc, 'shiftcodes', ['shiftCode' => $shiftCode]);
            if ($sc) {
                $in1    = $sc['1stIn'];
                $out1   = $sc['1stOut'];
                $in2    = $sc['2ndIn'];
                $out2   = $sc['2ndOut'];

                $sched = "$in1 $out1";
                if ($in2) {
                    $sched = "$in1 - $out1 | $in2 - $out2";
                }
            }

            echo "<tr>
	            	<td width='5%'>$dayV</td>
	            	<td width='10%'>$shiftCode</td>
	            	<td width='35%'>$sched</td>
	            	<td>$s_a</td>
	            	<td>$s_b</td>";
            echo "</tr>";
        }
        echo '<tbody>';
        echo '</table>';
        echo '</div>';
    }

    public function viewSched()
    {
        $input          = $this->request->getPost();
        $empId          = $input['emp_id'];
        $recordNo       = $input['record_no'];
        $promo_type     = $input['promo_type'];
        $month          = $input['month'];
        $year           = $input['year'];
        $data           = $empId . '*' . $recordNo . '*' . $promo_type;
        $future_year    = ($year * 1) + 3;

        $temp_date  = date("Y-m-d", strtotime("$year-$month-01"));

        $back_month = date("m", strtotime($temp_date . "-1 month"));
        $back_year  = date("Y", strtotime($temp_date . "-1 month"));

        $add_month  = date("m", strtotime($temp_date . "+1 month"));
        $add_year   = date("Y", strtotime($temp_date . "+1 month"));

        echo '<div class="d-flex justify-content-center align-items-center">';
        echo '<div class="col-md-4 text-end">
                <a href="javascript:;" onclick="view_sched2(\'' . $back_month . '\',\'' . $back_year . '\',\'' . $data . '\')">
                    <i class="fa-solid fa-backward fs-20px me-3"></i>
                </a>';
        echo '</div>';
        echo '<div class="col-md-2">
                <select name="month_" class="form-select" onchange="month_n(\'' . $data . '\')">';
        for ($x = 1; $x <= 12; $x++) {
            $sel = ($month  == $x) ? 'selected' : '';
            echo '<option value="' . $x . '" ' . $sel . '>' . date('F', strtotime("$year-$x-01")) . '</option>';
        }
        echo    '</select>';
        echo '</div>';
        echo '<div class="col-md-2">
                <select name="year_" class="form-select ms-1" onchange="year_n(\'' . $data . '\')">';
        for ($y = $future_year; 2017 <= $y; $y--) {
            $sel = ($y == $year) ? 'selected' : '';
            echo '<option value="' . $y . '" ' . $sel . '>' . $y . '</option>';
        }
        echo    '</select>';
        echo '</div>';
        echo '<div class="col-md-4">
                <a href="javascript:;" onclick="view_sched2(\'' . $add_month . '\',\'' . $add_year . '\',\'' . $data . '\')">
                    <i class="fa-solid fa-forward fs-20px ms-3"></i>
                </a>';
        echo '</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-md-12">
                <div class="viewSchedPromo"></div>';
        echo '</div>';
        echo '</div>';
    }

    public function viewSchedPromo()
    {
        $input = $this->request->getPost();
        $empId = $input['emp_id'];
        $recordNo = $input['record_no'];
        $promo_type = $input['promo_type'];
        $month = $input['month'];
        $year = $input['year'];
        // $server1 = ($input['server'] == 'cebu') ? 'pis_cebo' : 'pis_corp';
        // if ($input['server'] == 'cebu') {
        //     $server2 = 'tk_cebo';
        // } else if ($input['server']  == 'tagbilaran') {
        //     $server2 = 'tk_corp';
        // } else if ($input['server']  == 'talibon') {
        //     $server2 = 'tk_talibon';
        // } else if ($input['server']  == 'tubigon') {
        //     $server2 = 'tk_tubigon';
        // }

        $getServers = getServers($input['server']);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $last = date('t', strtotime("$year-$month-01"));
        echo '<table id="dt_schedule" class="table table-sm table-hover font_size3" width="100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Schedule</th>
                        <th>Store(s)</th>
                    </tr>
                </thead>
                <tbody>';
        $i = 0;
        $stores = '';
        $bus = $this->masterfile_model->locate_promo_bu($server1);
        foreach ($bus as $bu) {
            $field = $bu['bunit_field'];
            $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
            $check = $this->masterfile_model->getRowArray($server1, 'promo_record', $cond);
            if ($check) {
                $i++;
                $stores = ($i == 1) ? $bu['bunit_name'] : $stores . ', ' . $bu['bunit_name'];
            }
        }

        if ($promo_type == 'STATION') {
            for ($i = 1; $i <= $last; $i++) {

                $wholeDate  = date("M. d, Y", strtotime("$year-$month-$i"));
                $n_day      = date("Y-m-d", strtotime("$year-$month-$i"));
                $dayS       = date("l", strtotime($n_day));
                $schedule   = '';
                $storeSched = $stores;

                $cond  = "empId= '$empId' AND '$n_day' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
                $co = $this->masterfile_model->getRowArray($server2, 'assign_mul_sc', $cond);
                if ($co) {
                    $shiftCode = $co['shiftCode'];
                    $amsId = $co['amsId'];

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $no_sched = $this->masterfile_model->getResultArray($server2, 'promo_nosched', $cond);

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $dayoff = $this->masterfile_model->getResultArray($server2, 'ass_mul_do', $cond);

                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else if ($no_sched) {
                        $schedule = '<font color="red">NO SCHEDULE</font>';
                    } else {

                        $sched = '';
                        $sc = $this->masterfile_model->getRowArray($server2, 'shiftcodes', ['shiftCode' => $shiftCode]);
                        if ($sc) {
                            $fIn    = $sc['1stIn'];
                            $fOut   = $sc['1stOut'];
                            $sIn    = $sc['2ndIn'];
                            $sOut   = $sc['2ndOut'];
                            $sched = "<span style='font-size: smaller;'>$fIn - $fOut</span>";
                            if ($sIn && $sOut) {
                                $sched = "<span style='font-size: smaller;'>$fIn - $fOut | $sIn - $sOut</span>";
                            }
                        }
                        $sq = $this->masterfile_model->getRowArray($server2, 'request_new_shift', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($server2, 'req_new_shift_emp', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($server2, 'request_odaf', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'red'>$shiftCode</font> = $sched</span> ]";
                        }
                    }
                } else {
                    $day = date('l', strtotime($n_day));
                    $cond = "empId= '$empId' AND day= '$day'";
                    $dayoff = $this->masterfile_model->getResultArray($server2, 'assign_day_off', $cond);
                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else {

                        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
                        $row = $this->masterfile_model->getRowArray($server2, 'assign', $cond);
                        if ($row) {
                            $sched = '';
                            $shiftCode = $row['shiftCode'];
                            $sc = $this->masterfile_model->getRowArray($server2, 'shiftcodes', ['shiftCode' => $shiftCode]);
                            if ($sc) {
                                $fIn    = $sc['1stIn'];
                                $fOut   = $sc['1stOut'];
                                $sIn    = $sc['2ndIn'];
                                $sOut   = $sc['2ndOut'];
                                $sched = "<span style='font-size: smaller;'>$fIn - $fOut</span>";
                                if ($sIn && $sOut) {
                                    $sched = "<span style='font-size: smaller;'>$fIn - $fOut | $sIn - $sOut</span>";
                                }

                                $schedule = "[ <span><font color = 'gray'>$shiftCode</font> = $sched</span> ]";
                            }
                        }
                    }
                }

                echo "<tr>
                        <td>$wholeDate</td>
                        <td>$dayS</td>
                        <td>$schedule</td>
                        <td>$storeSched</td>";
                echo "</tr>";
            }
        } else {
            for ($i = 1; $i <= $last; $i++) {
                $wholeDate  = date("M. d, Y", strtotime("$year-$month-$i"));
                $n_day      = date("Y-m-d", strtotime("$year-$month-$i"));
                $dayS       = date("l", strtotime($n_day));
                $schedule   = '';
                $storeSched = '';

                $cond = "empId = '$empId' AND dateRendered = '$n_day' ORDER BY pId DESC LIMIT 1";
                $co = $this->masterfile_model->getRowArray($server2, 'promo_mul_sc', $cond);
                if ($co) {
                    $shiftCode  = $co['shiftCode'];
                    $amsId      = $co['amsId'];
                    $no_store   = $co['noStore'];

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $dayoff = $this->masterfile_model->getResultArray($server2, 'ass_mul_do', $cond);
                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else if ($no_store == 0) {
                        $schedule = '<font color="red">NO SCHEDULE</font>';
                    } else {
                        $sched = '';
                        $sg = $this->masterfile_model->getRowArray($server2, 'shiftcodes', ['shiftCode' => $shiftCode]);
                        if ($sg) {
                            $fIn    = $sg['1stIn'];
                            $fOut   = $sg['1stOut'];
                            $sIn    = $sg['2ndIn'];
                            $sOut   = $sg['2ndOut'];
                            $sched = "<span style='font-size: smaller;'>$fIn - $fOut</span>";
                            if ($sIn && $sOut) {
                                $sched = "<span style='font-size: smaller;'>$fIn - $fOut | $sIn - $sOut</span>";
                            }
                        }

                        $sq = $this->masterfile_model->getRowArray($server2, 'request_new_shift', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($server2, 'request_odaf', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'red'>$shiftCode </font> = $sched</span> ]";
                        }
                    }

                    if ($co['noStore'] > 0) {
                        $storea = str_replace(',', '', $co['store1']);
                        $storeb = str_replace(',', '', $co['store2']);
                        if ($co['noStore'] == '1') {
                            $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storea]);
                            if ($row) {
                                $storeSched = $row['bunit_name'];
                            }
                        } else {
                            $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storea]);
                            if ($row) {
                                $storeSched = $row['bunit_name'];
                            }

                            $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storeb]);
                            if ($row) {
                                $storeSched .= ' / ' . $row['bunit_name'];
                            }
                        }
                    }
                } else {

                    $day = date('l', strtotime($n_day));
                    $cond = "empId = '$empId' AND day_sched = '$day'";
                    $ds = $this->masterfile_model->getRowArray($server2, 'promo_def_sched', $cond);
                    if ($ds) {
                        $shiftCode  = $ds['shiftCode'];
                        $no_store   = $ds['noStore'];
                        if ($no_store == '0') {
                            $schedule = '<font color="red">DAY-OFF</font>';
                        } else {
                            $sched = '';
                            $sg = $this->masterfile_model->getRowArray($server2, 'shiftcodes', ['shiftCode' => $shiftCode]);
                            if ($sg) {
                                $fIn    = $sg['1stIn'];
                                $fOut   = $sg['1stOut'];
                                $sIn    = $sg['2ndIn'];
                                $sOut   = $sg['2ndOut'];
                                $sched = "<span style='font-size: smaller;'>$fIn - $fOut</span>";
                                if ($sIn && $sOut) {
                                    $sched = "<span style='font-size: smaller;'>$fIn - $fOut | $sIn - $sOut</span>";
                                }

                                $schedule = "[ <span><font color = 'gray'>$shiftCode</font> = $sched</span> ]";
                            }
                        }

                        if ($ds['noStore'] > 0) {
                            $storea = str_replace(',', '', $ds['store1']);
                            $storeb = str_replace(',', '', $ds['store2']);
                            if ($ds['noStore'] == '1') {
                                $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storea]);
                                if ($row) {
                                    $storeSched = $row['bunit_name'];
                                }
                            } else {
                                $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storea]);
                                if ($row) {
                                    $storeSched = $row['bunit_name'];
                                }

                                $row = $this->masterfile_model->getRowArray($server1, 'locate_promo_business_unit', ['bu' => $storeb]);
                                if ($row) {
                                    $storeSched .= ' / ' . $row['bunit_name'];
                                }
                            }
                        }
                    }
                }
                echo "<tr>
                        <td>$wholeDate</td>
                        <td>$dayS</td>
                        <td>$schedule</td>
                        <td>$storeSched</td>";
                echo "</tr>";
            }
        }
        echo '</tbody>';
        echo '</table>';
    }

    public function dayoff()
    {
        $input = $this->request->getPost();
        $empId = $input['emp_id'];
        $amsId = $input['amsId'];
        // if ($input['server'] == 'cebu') {
        //     $server2 = 'tk_cebo';
        // } else if ($input['server']  == 'tagbilaran') {
        //     $server2 = 'tk_corp';
        // } else if ($input['server']  == 'talibon') {
        //     $server2 = 'tk_talibon';
        // } else if ($input['server']  == 'tubigon') {
        //     $server2 = 'tk_tubigon';
        // }

        $getServers = getServers($input['server']);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];



        $cond = array('empId' => $empId, 'amsId' => $amsId);
        $query = $this->masterfile_model->getResultArray($server2, 'ass_mul_do', $cond);
        if ($query) {
            echo '<table class="table table-hover table-sm">
                    <tr>
                        <th>Date</th>
                        <th>Dayoff</th>
                    </tr>';
            foreach ($query as $row) {

                $dayOff = $row['dayOff'];
                $date   = date("M. d, Y", strtotime($row['date']));
                echo '<tr>
                        <td>' . $date . '</td>
                        <td>' . $dayOff . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<h4 class="text-danger">No Results Found!</h4>';
        }
    }

    public function dtrSearch()
    {
        $input = $this->request->getPost();
        echo '<ul class="list-group">';
        // $server1 = ($input['server'] == 'cebu') ? 'pis_cebo' : 'pis_corp';
        // if ($input['server'] == 'cebu') {
        //     $server2 = 'tk_cebo';
        // } else if ($input['server']  == 'tagbilaran') {
        //     $server2 = 'tk_corp';
        // } else if ($input['server']  == 'talibon') {
        //     $server2 = 'tk_talibon';
        // } else if ($input['server']  == 'tubigon') {
        //     $server2 = 'tk_tubigon';
        // }

        $getServers = getServers($input['server']);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $query = $this->masterfile_model->searchPromo($server1, $input['str']);
        if ($query) {
            foreach ($query as $row) {
                $empId      = $row['emp_id'];
                $cond       = "emp_id='$empId' ORDER BY date_added DESC LIMIT 1";
                $emp        = $this->masterfile_model->getRowArray($server1, 'employee3', $cond);
                $name       = htmlspecialchars($emp['name']);
                $recordNo   = $emp['record_no'];
                $cond       = "empId='$empId' AND recordNo='$recordNo' LIMIT 1";
                $statCut    = $this->masterfile_model->getRowArray($server2, 'promo_sched_emp', $cond)['statCut'] ?? '';
                $cond       = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
                $assign     = $this->masterfile_model->getRowArray($server2, 'assign', $cond);
                $chk        = $this->masterfile_model->promoDetails($server1, $empId, $recordNo);
                if ($chk && $assign && $statCut) {
                    $data = $name . '|' . $empId . '|' . $recordNo . '|' . $statCut;
                    $click = 'onclick="selectDtr(\'' . $data . '\')"';
                    echo '<a href="javascript:;" class="list-group-item" ' . $click . '>' . $name . '</a>';
                } else {
                    echo '<span class="list-group-item text-muted">' . $name . '</span>';
                }
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
        }
        echo '</ul>';
    }

    public function dtrCutoff()
    {
        $input      = $this->request->getPost();
        $statCut    = $input['statCut'];
        $empId      = $input['emp_id'];
        $recordNo   = $input['record_no'];
        $server     = $input['server'];
        $getServers = getServers($server);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];


        $cond = "empId = '$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $assign = $this->masterfile_model->getRowArray($server2, 'assign', $cond);
        if ($assign) {
            $allowId = $assign['allowId'];
            if ($allowId == "Biometric ID") {
                $bioM = $assign['bioMetricId'];
            }
            if ($allowId == "Logbox ID") {
                $bioM = $assign['barcodeId'];
            }
        }

        $sched = $this->masterfile_model->getRowArray($tk_loc, 'promo_schedule', ['statCut' => $statCut]);
        $startFC    = $sched['startFC'];
        $endFC      = $sched['endFC'];
        $startSC    = $sched['startSC'];
        $endSC      = $sched['endSC'];

        if ($endFC == '') {
            $endFC = 'last';
        }

        echo '<h5 class="text-center">List of Cut Off [<font color="red"> ' . $startFC . ' - ' . $endFC . ' | ' . $startSC . ' - ' . $endSC . ' </font>]</h5>';
        echo '<table class="table table-hover table-bordered table-sm" id="dtrCutoffs">
                <thead>
                    <tr>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th class="text-center">Action</th>
                    </tr>
                <thead>
                <tbody>';

        for ($i = 1; $i <= 2; $i++) {
            $table = ($i == 1) ? '1stcutoff' : '2ndcutoff';
            $query = $this->masterfile_model->promoCutoffs($server2, $table, $statCut);
            foreach ($query as $c) {
                $order  = strtotime($c['dateFrom']);
                $df     = date('M. d, Y', strtotime($c['dateFrom']));
                $dt     = date('M. d, Y', strtotime($c['dateTo']));
                $click  = 'onclick="viewDTR(\'' . $empId . '\',\'' . $recordNo . '\',\'' . $df . '\',\'' . $dt . '\',\'' . $bioM . '\',\'' . $i . '\',\'' . $statCut . '\',\'' . $server . '\')"';
                $action = '<a href="javascript:;" ' . $click . '><i class="fa-solid fa-folder-open fs-20px text-primary"></i></a>';
                echo '<tr>';
                echo '<td><span style="display:none;">' . $order . '</span>' . $df . '</td>';
                echo '<td>' . $dt . '</td>';
                echo '<td>' . $action . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
    }

    public function promoDtr()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $empId      = $input['empId'];
        $recordNo   = $input['recordNo'];
        $bioM       = $input['bioM'];
        $dateFrom   = date('Y-m-d', strtotime($input['dateFrom']));
        $dateTo     = date('Y-m-d', strtotime($input['dateTo']));

        $cond       = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $allowId    = $this->masterfile_model->getRowArray($server2, 'assign', $cond)['allowId'];

        $promo      = $this->masterfile_model->promoDetails($server1, $empId, $recordNo);
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
        $bus = $this->masterfile_model->locate_promo_bu($server1);
        foreach ($bus as $bu) {
            $field = $bu['bunit_field'];
            $buName = '<span class="badge bg-success me-1">' . $bu['bunit_acronym'] . '</span>';
            $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
            $check = $this->masterfile_model->getRowArray($server1, 'promo_record', $cond);
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
        $co = $this->masterfile_model->getRowArray($server2, $tbl, $cond);
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
            $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
            $dutyId = $dtr['dutyId'] ?? '';
            $in1    = $dtr['in1'] ?? '';
            $out1   = $dtr['out1'] ?? '';
            $in2    = $dtr['in2'] ?? '';
            $out2   = $dtr['out2'] ?? '';
            $in3    = $dtr['in3'] ?? '';
            $out3   = $dtr['out3'] ?? '';

            $in1_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in1, "I") ?? '';
            $out1_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out1, "O") ?? '';
            $in2_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in2, "I") ?? '';
            $out2_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out2, "O") ?? '';
            $in3_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in3, "I") ?? '';
            $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out3, "O") ?? '';

            if (empty($out3_store)) {
                $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, date('Y-m-d', strtotime($tempDate . " +1 day")), $out3, "O") ?? '';
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
            $dutyl  = $this->masterfile_model->getRowArray($server2, 'duty_logbox', $cond);

            $dw = 0;
            $hw = 0;
            $cond = array('dutyId' => $dutyId);
            $perday = $this->masterfile_model->getRowArray($server2, 'perday', $cond);
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
            $otApp = $this->masterfile_model->getRowArray($server2, 'otforpayment', $cond)['otApp'] ?? '';
            if ($otApp) {
                $ot = floatval($otApp) * 1;
            }
            // overtime ********************************************************************

            // holiday	********************************************************************
            $hol = '';
            $holiday = false;
            if ($server == 'cebu') {
                $cond  = "status='active' AND hrd_location='cebo'";
                $strs  = $this->masterfile_model->getResultArray($server1, 'locate_promo_business_unit', $cond);
                if ($promo_type == 'STATION') {
                    foreach ($strs as $key => $str) {
                        $field = $str['bunit_field'];
                        if ($promo[$field] == 'T') {
                            if ($field == 'colc') {
                                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            } else {
                                $cond = "(store='' OR store='$field') AND (date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo_outside', $cond);
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
                    $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo_roving', $cond);
                    if ($ch) {
                        $holiday = true;
                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                        $hol    = $nH . ' [ ' . $cat . ' ]';
                    }
                }
            } else {
                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                $chk = $this->masterfile_model->getRowArray($server2, 'holidays_promo', $cond);
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
                $sql   = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = ['amsId' => $amsId];
                    $sql    = $this->masterfile_model->getRowArray($server2, 'request_odaf', $cond);
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
            $sql = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
            if ($sql) {
                foreach ($sql as $ams) {

                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId, 'empId' => $empId, 'date' => $tempDate);
                    $amd    = $this->masterfile_model->getRowArray($server2, 'ass_mul_do', $cond);

                    $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);

                    if ($amd && !$acd) {
                        $show_do = true;
                    }

                    if ($amd && $acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                        $appby  = $this->masterfile_model->getRowArray($server2, 'approveby', $cond);
                        if ($appby) {

                            $dateReq = $appby['date'];
                            $timeReq = $appby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                            $cond   = ['amsId' => $amsId];
                            $rns    = $this->masterfile_model->getRowArray($server2, 'request_new_shift', $cond);
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

                    $cond   = ['empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved'];
                    $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                    if ($acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                        $aprby  = $this->masterfile_model->getRowArray($server2, 'approveby', $cond);
                        if ($aprby) {
                            $dateReq    = $aprby['date'];
                            $timeReq    = $aprby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));

                            $cond   = ['amsId' => $amsId];
                            $rns    = $this->masterfile_model->getRowArray($server2, 'request_new_shift', $cond);
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

                $cond   = ['empId' => $empId, 'dateNew' => $tempDate, 'status' => 'Approved'];
                $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                if ($acd) {
                    $show_do = true;
                }

                $cond   = ['empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved'];
                $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                if ($acd) {
                    $old_do = true;
                }
            }

            // default day-off if $show_do is false
            if (!$sql && !$show_do && !$old_do) {
                $days = date('l', strtotime($tempDate));
                $cond = ['empId' => $empId, 'day' => date('l', strtotime($tempDate))];
                $sql = $this->masterfile_model->getRowArray($server2, 'assign_day_off', $cond);
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
                $sql   = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = ['amsId' => $amsId];
                    $sql    = $this->masterfile_model->getRowArray($server2, 'request_odaf', $cond);
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
                $pms    = $this->masterfile_model->getRowArray($server2, 'promo_mul_sc', $cond);
                if ($pms) {
                    $nStore = $pms['noStore'];
                    if ($nStore == 0) {
                        $no_schedule = true;
                    }
                } else {
                    $cond   = "empId='$empId' AND day_sched='$day' ORDER BY pdId DESC";
                    $pds    = $this->masterfile_model->getRowArray($server2, 'promo_def_sched', $cond);
                    if ($pds) {
                        $nStore = $pds['noStore'];
                        if ($nStore == 0) {
                            $no_schedule = true;
                        }
                    }
                }
            } else {

                $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC";
                $amsId = $this->masterfile_model->getRowArray($server2, 'assign_mul_sc', $cond)['amsId'] ?? '';
                if ($amsId) {
                    $cond  = array('empId' => $empId, 'amsId' => $amsId, 'date' => $tempDate);
                    $pns   = $this->masterfile_model->getRowArray($server2, 'promo_nosched', $cond);
                    if ($pns) {
                        $no_schedule = true;
                    }
                }
            }
            // no schedule ********************************************************************

            // request checking ********************************************************************
            $cond = array('empId' => $empId, 'effectiveDate' => $tempDate);
            $sqlcdol = $this->masterfile_model->getRowArray($server2, 'ass_dayoff_long', $cond) ?? [];
            $numCDOL = count($sqlcdol);

            $cond = array('empId' => $empId, 'dateToChange' => $tempDate);
            $sqlcdos = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond) ?? [];
            $numCDOS = count($sqlcdos);

            //loa
            $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
            $sqlloa = $this->masterfile_model->getRowArray($server2, 'r_l_absence, rla_day', $cond) ?? [];
            $numLOA = count($sqlloa);
            //end of loa

            //dwa
            $cond   = "d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$tempDate' 
                AND '$tempDate' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
            $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment, off_date', $cond) ?? [];
            $numDWA = count($sqldwa);

            if ($numDWA == 0) {
                $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') 
                    AND '$tempDate' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment', $cond) ?? [];
                $numDWA = count($sqldwa);
            }
            //end of dwa

            //eoa
            // $cond   = array('empId' => $empId, 'dateOvertime' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND dateOvertime='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleoa = $this->masterfile_model->getRowArray($server2, 'e_o_authorization', $cond) ?? [];
            $numEOA = count($sqleoa);
            //end of eoa

            //EOP
            // $cond   = array('emp_id' => $empId, 'otDate' => $tempDate, 'status' => 'Approved');
            $cond   = "emp_id='$empId' AND otDate='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleop = $this->masterfile_model->getRowArray($server2, 'otforpayment', $cond) ?? [];
            $numEOP = count($sqleop);
            //end of EOP

            //ECA
            // $cond   = array('empId' => $empId, 'cuDate' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND cuDate='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleca = $this->masterfile_model->getRowArray($server2, 'coverup_auth', $cond) ?? [];
            $numECA = count($sqleca);
            //end of ECA

            //EUA
            // $cond   = array('empId' => $empId, 'dateUndertime' => $tempDate, 'status' => 'Approved');
            $cond   = "empId='$empId' AND dateUndertime='$tempDate' AND (status = 'Approved' OR status = 'Pending')";
            $sqleua = $this->masterfile_model->getRowArray($server2, 'undertime_auth', $cond) ?? [];
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
                $CDOL = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'CDOL\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }

            $CDOS = '';
            if ($numCDOS != 0) {
                $span = ($sqlcdos['status'] == 'Approved') ? '<span class="badge bg-primary" >CDOS</span>' : '<span class="badge bg-warning" >CDOS</span>';
                $CDOS = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'CDOS\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }

            $LOA = '';
            if ($numLOA != 0) {
                $span = ($sqlloa['status'] == 'Approved') ? '<span class="badge bg-primary" >LOA</span>' : '<span class="badge bg-warning" >LOA</span>';
                $LOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }

            $numSIL = 0;
            if ($numLOA != 0) {
                //sil
                $cpnd   = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                    AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') 
                    AND (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
                $sqlsil = $this->masterfile_model->getRowArray($server2, 'r_l_absence, rla_day', $cpnd) ?? [];
                $numSIL = count($sqlsil);
                //end of sil
                if ($numSIL != 0) {
                    $span = ($sqlsil['status'] == 'Approved') ? '<span class="badge bg-primary" >SIL</span>' : '<span class="badge bg-warning" >SIL</span>';
                    $LOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
                }
            }

            $DWA = '';
            if ($numDWA != 0) {
                $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }
            $EOA = '';
            if ($numEOA != 0) {
                $span = ($sqleoa['status'] == 'Approved') ? '<span class="badge bg-primary" >EOA</span>' : '<span class="badge bg-warning" >EOA</span>';
                $EOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }
            $EOP = '';
            if ($numEOP != 0) {
                $span = ($sqleop['status'] == 'Approved') ? '<span class="badge bg-primary" >EOP</span>' : '<span class="badge bg-warning" >EOP</span>';
                $EOP = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOP\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }
            $ECA = '';
            if ($numECA != 0) {
                $span = ($sqleca['status'] == 'Approved') ? '<span class="badge bg-primary" >ECA</span>' : '<span class="badge bg-warning" >ECA</span>';
                $ECA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'ECA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }
            $EUA = '';
            if ($numEUA != 0) {
                $span = ($sqleua['status'] == 'Approved') ? '<span class="badge bg-primary" >EUA</span>' : '<span class="badge bg-warning" >EUA</span>';
                $EUA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EUA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server2 . '\')">' . $span . '</a>';
            }
            // request checking ********************************************************************


            $tableDate = date('M. d, Y', strtotime($dateFrom));

            if ($holiday && !$odaHoliday) {
                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center">' . $hol . '</td>';
                echo    '<td class="text-center">' . $ODAHD . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            } else if ($show_do && !$odaDayoff) {

                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center text-danger">DAY-OFF</td>';
                echo    '<td class="text-center">' . $CDOS . ' ' . $CDOL . ' ' . $ODADO . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            } else if ($no_schedule && !$dutyl) {

                echo '<tr>';
                echo    '<td>' . $tableDate . '</td>';
                echo    '<td>' . $day . '</td>';
                echo    '<td colspan="9" class="text-center text-danger">NO SCHEDULE</td>';
                echo    '<td class="text-center">' . $ODAHD . ' ' . $ODADO . '</td>';
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
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
                echo    '<td class="text-center"><a href="javascript:;" onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $tempDate . '\')"><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a></td>';
                echo '</tr>';
            }

            $dateFrom = date('M. d, Y', strtotime($dateFrom . "+1 day"));
        }

        // Previous Cut Off
        $df     = date('Y-m-d', strtotime($input['dateFrom']));
        $dt     = date('Y-m-d', strtotime($input['dateTo']));
        $cond   = array('empId' => $empId, 'coDF1' => $df, 'coDF2' => $dt);
        $pdp    = $this->masterfile_model->getResultArray($server2, 'perday_previous', $cond);
        $cond   = array('empId' => $empId, 'dateFrom' => $df, 'dateTo' => $dt);
        $pdpa   = $this->masterfile_model->getRowArray($server2, 'perday_previous_allow', $cond);
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
                $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                $cond   = "d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$dateDuty' AND '$dateDuty' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
                $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment, off_date', $cond) ?? [];
                $numDWA = count($sqldwa);

                if ($numDWA == 0) {
                    $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') AND '$dateDuty' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                    $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment', $cond) ?? [];
                    $numDWA = count($sqldwa);
                }

                $DWA = '';
                if ($numDWA != 0) {
                    $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                    $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }


                $onclick = 'onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $dateFrom . '\')"';
                $action = '<a href="javascript:;" ' . $onclick . '><i class="fa-regular fa-eye fs-20px text-primary"></i></i></a>';

                $in1_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
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
                $od = $this->masterfile_model->getRowArray($server2, 'off_date', $cond);
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
        $otp  = $this->masterfile_model->getResultArray($server2, 'otforpayment', $cond);
        if ($otp) {
            echo '<tr>';
            echo '<th colspan="13"><span class="text-success">Previous Cutoff (Over Time)</span></th>';
            echo '</tr>';
            foreach ($otp as $nf) {

                $dateDuty   = $nf['otDate'];
                $dateFrom   = date('M. d, Y', strtotime($nf['otDate']));
                $otApp  = $nf['otApp'] * 1;
                $dayS   = date("D", strtotime($dateDuty));
                $dateD  = date("M. d, Y", strtotime($dateDuty));

                $cond   = array('bioMetricId' => $bioM, 'date' => $dateDuty);
                $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                $in1_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                $onclick = 'onclick="viewTime(\'' . $server2 . '\',\'' . $empId . '\',\'' . $dateFrom . '\')"';
                $action = '<a href="javascript:;" ' . $onclick . '><i class="fa-solid fa-eye fs-20px text-primary"></i></i></a>';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
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
                $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment, off_date', $cond) ?? [];
                $numDWA = count($sqldwa);

                if ($numDWA == 0) {
                    $cond   = "empId = '$empId' AND (type = 'Failure' OR type = 'System Error' OR type = 'Wrong Mode') AND '$dateDuty' BETWEEN dateFrom AND dateTo AND (status = 'Approved' OR status = 'Pending')";
                    $sqldwa = $this->masterfile_model->getRowArray($server2, 'd_w_adjustment', $cond) ?? [];
                    $numDWA = count($sqldwa);
                }

                $DWA = '';
                if ($numDWA != 0) {
                    $span = ($sqldwa['status'] == 'Approved') ? '<span class="badge bg-primary" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
                    $DWA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                $cond   = "empId='$empId' AND dateOvertime='$dateDuty' AND (status = 'Approved' OR status = 'Pending')";
                $sqleoa = $this->masterfile_model->getRowArray($server2, 'e_o_authorization', $cond) ?? [];
                $numEOA = count($sqleoa);

                $EOA = '';
                if ($numEOA != 0) {
                    $span = ($sqleoa['status'] == 'Approved') ? '<span class="badge bg-primary" >EOA</span>' : '<span class="badge bg-warning" >EOA</span>';
                    $EOA = '<a href="javascript:;" class="text-decoration-none" onclick="viewRequest(\'EOA\',\'' . $empId . '\',\'' . $dateDuty . '\')">' . $span . '</a>';
                }

                $cond   = "emp_id='$empId' AND otDate='$dateDuty' AND (status = 'Approved' OR status = 'Pending')";
                $sqleop = $this->masterfile_model->getRowArray($server2, 'otforpayment', $cond) ?? [];
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
        $c = $this->masterfile_model->getRowArray($server, 'assign_mul_sc', $cond);
        if ($c) {

            $mSC    = $c['shiftCode'];
            $cond   = "shiftCode = '$mSC'";
            $e      = $this->masterfile_model->getRowArray($server, 'shiftcodes', $cond);
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
        $assign = $this->masterfile_model->getRowArray($server, 'assign', $cond);
        $allowId = $assign['allowId'];
        if ($allowId == "Biometric ID") {

            $bio = $assign['bioMetricId'];
            $cond = "bioMetricId = '$bio' AND date = '$nDate' ORDER BY ADDTIME(timeIn,timeOut) ASC";
            $con = $this->masterfile_model->getResultArray($server, 'duty_biometric', $cond);
        }

        if ($allowId == "Logbox ID") {

            $bio = $assign['barcodeId'];
            $cond = "bioMetricId = '$bio' AND date = '$nDate' ORDER BY ADDTIME(timeIn,timeOut) ASC";
            $con = $this->masterfile_model->getResultArray($server, 'duty_logbox', $cond);
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
            $store = $this->masterfile_model->getRowArray($server, 'duty_logbox', $cond)['store'] ?? '';

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
            $loa    = $this->masterfile_model->getRowArray($server, 'r_l_absence, rla_day', $cond);
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $loa    = $this->masterfile_model->getRowArray($server, 'd_w_adjustment, off_date', $cond);
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
                $loa        = $this->masterfile_model->getRowArray($server, 'd_w_adjustment', $cond);
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
                $f      = $this->masterfile_model->getRowArray($server, 'dwa_error_failed', $cond);

                $in1    = $f['in1'];
                $out1   = $f['out1'];
                $in2    = $f['in2'];
                $out2   = $f['out2'];


                $in1_V  = '';
                $out1_V = '';
                $in2_V  = '';
                $out2_V = '';

                $cond   = array('err_fai_id' => $errId);
                $v      = $this->masterfile_model->getRowArray($server, 'dwa_err_failed_value', $cond) ?? [];

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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $eoa    = $this->masterfile_model->getRowArray($server, 'e_o_authorization', $cond);
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $eop        = $this->masterfile_model->getRowArray($server, 'otforpayment', $cond);
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $sql        = $this->masterfile_model->getRowArray($server, 'coverup_auth', $cond);
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
            $cov = $this->masterfile_model->getResultArray($server, 'coverup_auth_ext', $cond);
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
                    $eoa    = $this->masterfile_model->getRowArray($server, 'e_o_authorization', $cond);
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $eua        = $this->masterfile_model->getRowArray($server, 'undertime_auth', $cond) ?? [];
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $cdos       = $this->masterfile_model->getRowArray($server, 'ass_change_do', $cond);
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
            $cdol       = $this->masterfile_model->getRowArray($server, 'ass_dayoff_long', $cond) ?? [];
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
            $query = $this->masterfile_model->getResultArray($server, 'approveby', $cond);
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
        $server1    = $getServers['pis'];
        $server2    = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $dF = date('Y-m-d', strtotime($dateFrom));
        $dT = date('Y-m-d', strtotime($dateTo));

        $emp    = $this->masterfile_model->promoDetails($server1, $empId, $recordNo);
        $name   = ucwords(strtolower($emp['name']));
        $comp   = $emp['promo_company'];
        $dept   = $emp['promo_department'];
        $ptype  = $emp['promo_type'];

        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
        $allowId = $this->masterfile_model->getRowArray($server2, 'assign', $cond)['allowId'];

        $dw = 0;
        $ot = 0;
        $lh = 0;
        $sh = 0;
        $tbl = ($cut == 1) ? '1stcutoff' : '2ndcutoff';
        $cond = array('bioMetricId' => $bioM, 'dateFrom' => $dF, 'dateTo' => $dT, 'statCut' => $statCut);
        $co = $this->masterfile_model->getRowArray($server2, $tbl, $cond);
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
            $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
            $dutyId = $dtr['dutyId'] ?? '';
            $in1    = $dtr['in1'] ?? '';
            $out1   = $dtr['out1'] ?? '';
            $in2    = $dtr['in2'] ?? '';
            $out2   = $dtr['out2'] ?? '';
            $in3    = $dtr['in3'] ?? '';
            $out3   = $dtr['out3'] ?? '';


            $in1_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in1, "I") ?? '';
            $out1_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out1, "O") ?? '';
            $in2_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in2, "I") ?? '';
            $out2_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out2, "O") ?? '';
            $in3_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $in3, "I") ?? '';
            $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $tempDate, $out3, "O") ?? '';

            if (empty($out3_store)) {
                $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, date('Y-m-d', strtotime($tempDate . " +1 day")), $out3, "O") ?? '';
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
            $dutyl  = $this->masterfile_model->getRowArray($server2, 'duty_logbox', $cond);

            $dw = 0;
            $hw = 0;
            $cond = array('dutyId' => $dutyId);
            $perday = $this->masterfile_model->getRowArray($server2, 'perday', $cond);
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
            $otApp = $this->masterfile_model->getRowArray($server2, 'otforpayment', $cond)['otApp'] ?? '';
            if ($otApp) {
                $ot = floatval($otApp) * 1;
            }
            // overtime ********************************************************************

            // holiday	********************************************************************
            $hol = '';
            $holiday = false;
            if ($server == 'cebu') {
                $cond  = "status='active' AND hrd_location='cebo'";
                $strs  = $this->masterfile_model->getResultArray($server1, 'locate_promo_business_unit', $cond);
                if ($ptype == 'STATION') {
                    foreach ($strs as $key => $str) {
                        $field = $str['bunit_field'];
                        if ($emp[$field] == 'T') {
                            if ($field == 'colc') {
                                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo', $cond);
                                if ($ch) {
                                    $holiday = true;
                                    $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                                    $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                                    $hol    = $nH . ' [ ' . $cat . ' ]';
                                }
                            } else {
                                $cond = "(store='' OR store='$field') AND (date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                                $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo_outside', $cond);
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
                    $ch = $this->masterfile_model->getRowArray($server2, 'holidays_promo_roving', $cond);
                    if ($ch) {
                        $holiday = true;
                        $nH     = '<span class="text-danger">' . $ch['nameHoliday'] . '</span>';
                        $cat    = '<span class="text-danger">' . $ch['category'] . '</span>';
                        $hol    = $nH . ' [ ' . $cat . ' ]';
                    }
                }
            } else {
                $cond = "(date='$tempDate' AND moveDate='') OR moveDate='$tempDate' LIMIT 1";
                $chk = $this->masterfile_model->getRowArray($server2, 'holidays_promo', $cond);
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
                $sql   = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->masterfile_model->getRowArray($server2, 'request_odaf', $cond);
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
            $sql = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
            if ($sql) {
                foreach ($sql as $ams) {

                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId, 'empId' => $empId, 'date' => $tempDate);
                    $amd    = $this->masterfile_model->getRowArray($server2, 'ass_mul_do', $cond);

                    $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                    $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);

                    if ($amd && !$acd) {
                        $show_do = true;
                    }

                    if ($amd && $acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = ['requestId' => $acdId, 'requestType' => 'CDOS'];
                        $appby  = $this->masterfile_model->getRowArray($server2, 'approveby', $cond);
                        if ($appby) {

                            $dateReq = $appby['date'];
                            $timeReq = $appby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
                            $cond   = ['amsId' => $amsId];
                            $rns    = $this->masterfile_model->getRowArray($server2, 'request_new_shift', $cond);
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
                    $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                    if ($acd) {
                        $acdId  = $acd['acdId'];
                        $cond   = array('requestId' => $acdId, 'requestType' => 'CDOS');
                        $aprby  = $this->masterfile_model->getRowArray($server2, 'approveby', $cond);
                        if ($aprby) {
                            $dateReq    = $aprby['date'];
                            $timeReq    = $aprby['time'];
                            $app_details = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));

                            $cond   = array('amsId' => $amsId);
                            $rns    = $this->masterfile_model->getRowArray($server2, 'request_new_shift', $cond);
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
                $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                if ($acd) {
                    $show_do = true;
                }

                $cond   = array('empId' => $empId, 'dateToChange' => $tempDate, 'status' => 'Approved');
                $acd    = $this->masterfile_model->getRowArray($server2, 'ass_change_do', $cond);
                if ($acd) {
                    $old_do = true;
                }
            }

            // default day-off if $show_do is false
            if (!$sql &&  !$show_do &&  !$old_do) {
                $days = date('l', strtotime($tempDate));
                $cond = array('empId' => $empId, 'day' => date('l', strtotime($tempDate)));
                $sql = $this->masterfile_model->getRowArray($server2, 'assign_day_off', $cond);
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
                $sql   = $this->masterfile_model->getResultArray($server2, 'assign_mul_sc', $cond);
                foreach ($sql as $ams) {
                    $amsId  = $ams['amsId'];
                    $cond   = array('amsId' => $amsId);
                    $sql    = $this->masterfile_model->getRowArray($server2, 'request_odaf', $cond);
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
                $pms    = $this->masterfile_model->getRowArray($server2, 'promo_mul_sc', $cond);
                if ($pms) {
                    $nStore = $pms['noStore'];
                    if ($nStore == 0) {
                        $no_schedule = true;
                    }
                } else {
                    $cond   = "empId='$empId' AND day_sched='$day' ORDER BY pdId DESC";
                    $pds    = $this->masterfile_model->getRowArray($server2, 'promo_def_sched', $cond);
                    if ($pds) {
                        $nStore = $pds['noStore'];
                        if ($nStore == 0) {
                            $no_schedule = true;
                        }
                    }
                }
            } else {

                $cond = "empId = '$empId' AND status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo ORDER BY id DESC";
                $amsId = $this->masterfile_model->getRowArray($server2, 'assign_mul_sc', $cond)['amsId'] ?? '';
                if ($amsId) {
                    $cond  = array('empId' => $empId, 'amsId' => $amsId, 'date' => $tempDate);
                    $pns   = $this->masterfile_model->getRowArray($server2, 'promo_nosched', $cond);
                    if ($pns) {
                        $no_schedule = true;
                    }
                }
            }
            // no schedule ********************************************************************

            $loa = false;
            $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
            AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
            $sqlloa = $this->masterfile_model->getResultArray($server2, 'r_l_absence, rla_day', $cond) ?? [];
            if ($sqlloa) {
                $loa = true;
            }

            $sil = false;
            if ($loa) {
                $cond = "r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' 
                AND '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') 
                AND (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
                $sqlsil = $this->masterfile_model->getRowArray($server2, 'r_l_absence, rla_day', $cond) ?? [];
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
        $pdp    = $this->masterfile_model->getResultArray($server2, 'perday_previous', $cond);
        $cond   = array('empId' => $empId, 'dateFrom' => $dF, 'dateTo' => $dT);
        $allow  = $this->masterfile_model->getResultArray($server2, 'perday_previous_allow', $cond);
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
                $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
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
                $od = $this->masterfile_model->getRowArray($server2, 'off_date', $cond);
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
        $otp    = $this->masterfile_model->getResultArray($server2, 'otforpayment', $cond);
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
                $dtr    = $this->masterfile_model->getRowArray($server2, 'duty', $cond);
                $dutyId = $dtr['dutyId'] ?? '';
                $in1    = $dtr['in1'] ?? '';
                $out1   = $dtr['out1'] ?? '';
                $in2    = $dtr['in2'] ?? '';
                $out2   = $dtr['out2'] ?? '';
                $in3    = $dtr['in3'] ?? '';
                $out3   = $dtr['out3'] ?? '';

                // store entry
                $in1_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in1, "I") ?? '';
                $out1_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out1, "O") ?? '';
                $in2_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in2, "I") ?? '';
                $out2_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out2, "O") ?? '';
                $in3_store  = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $in3, "I") ?? '';
                $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, $dateDuty, $out3, "O") ?? '';

                if (empty($out3_store)) {
                    $out3_store = $this->masterfile_model->store_entry($server2, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O") ?? '';
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
        $legend = $this->masterfile_model->dtr_store_code();
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
}
