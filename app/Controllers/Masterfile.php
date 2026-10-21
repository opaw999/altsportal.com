<?php

namespace App\Controllers;

class Masterfile extends BaseController
{
    public function view_masterfile()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];

        $bus        = $this->dtr_model->locate_promo_bu($pis);
        $ac_code    = $this->dtr_model->supplier_ac_code($tk_loc);

        $supplierType = session('supplierType');
        if ($supplierType == 1) {
            $agency_code    = $ac_code['agency_code'];
            $employees      = $this->dtr_model->masterfile_under_agency($server, $agency_code);
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
            $sched = '';
            $cond = ['statCut' => $emp['statCut']];
            $ps = $this->masterfile_model->getRowArray($tk_loc, 'promo_schedule', $cond);
            if ($ps) {
                if ($ps['endFC'] == '') {
                    $endFC = "last";
                } else {
                    $endFC = $ps['endFC'];
                }

                $sched = $ps['startFC'] . '-' . $endFC . ' / ' . $ps['startSC'] . '-' . $ps['endSC'];
            }

            $month = date('m');
            $year = date('Y');
            $promoType = $emp['promo_type'];

            $payload = "$server|$empId|$recordNo|$promoType|$month|$year";
            $click = 'onclick="schedule_pager(\'' . $payload . '\')"';
            $action = '<a href="javascript:;" ' . $click . '><i class="fa-solid fa-list fs-20px text-primary"></i></a>';

            $sub_array = array();
            $sub_array[] = ucwords(strtolower($emp['name']));
            $sub_array[] = $emp['promo_company'];
            $sub_array[] = $stores;
            $sub_array[] = ucwords(strtolower($emp['position']));
            $sub_array[] = $promoType;
            $sub_array[] = $sched;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array('data' => $data, 'server' => $server));
    }

    public function schedule_pager()
    {
        $input      = $this->request->getPost();
        $server     = $input['server'];
        $empId      = $input['empId'];
        $recordNo   = $input['recordNo'];
        $promoType  = $input['promoType'];
        $month      = $input['month'];
        $year       = $input['year'];

        $data           = "$server|$empId|$recordNo|$promoType";
        $future_year    = ($year * 1) + 3;

        $temp_date  = date("Y-m-d", strtotime("$year-$month-01"));

        $back_month = date("m", strtotime($temp_date . "-1 month"));
        $back_year  = date("Y", strtotime($temp_date . "-1 month"));

        $add_month  = date("m", strtotime($temp_date . "+1 month"));
        $add_year   = date("Y", strtotime($temp_date . "+1 month"));


        $prev = "$server|$empId|$recordNo|$promoType|$back_month|$back_year";
        $next = "$server|$empId|$recordNo|$promoType|$add_month|$add_year";

        echo '<div class="d-flex justify-content-center align-items-center">';
        echo '<div class="col-md-4 text-end">
                <a href="javascript:;" onclick="schedule_pager(\'' . $prev . '\')">
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
                <a href="javascript:;" onclick="schedule_pager(\'' . $next . '\')">
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

    public function view_schedule()
    {
        $input      = $this->request->getPost();
        $empId      = $input['empId'];
        $recordNo   = $input['recordNo'];
        $promoType  = $input['promoType'];
        $month      = $input['month'];
        $year       = $input['year'];

        $server     = $input['server'];
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
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
        $bus = $this->masterfile_model->locate_promo_bu($pis);
        foreach ($bus as $bu) {
            $field = $bu['bunit_field'];
            $cond = ['emp_id' => $empId, 'record_no' => $recordNo, $field => 'T'];
            $check = $this->masterfile_model->getRowArray($pis, 'promo_record', $cond);
            if ($check) {
                $i++;
                $stores = ($i == 1) ? $bu['bunit_name'] : $stores . ', ' . $bu['bunit_name'];
            }
        }

        if ($promoType == 'STATION') {
            for ($i = 1; $i <= $last; $i++) {

                $wholeDate  = date("M. d, Y", strtotime("$year-$month-$i"));
                $n_day      = date("Y-m-d", strtotime("$year-$month-$i"));
                $dayS       = date("l", strtotime($n_day));
                $schedule   = '';
                $storeSched = '';

                $cond  = "empId= '$empId' AND '$n_day' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
                $co = $this->masterfile_model->getRowArray($tk, 'assign_mul_sc', $cond);
                if ($co) {
                    $shiftCode = $co['shiftCode'];
                    $amsId = $co['amsId'];

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $no_sched = $this->masterfile_model->getResultArray($tk, 'promo_nosched', $cond);

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $dayoff = $this->masterfile_model->getResultArray($tk, 'ass_mul_do', $cond);

                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else if ($no_sched) {
                        $schedule = '<font color="red">NO SCHEDULE</font>';
                    } else {
                        $storeSched = $stores;
                        $sched = '';
                        $sc = $this->masterfile_model->getRowArray($tk, 'shiftcodes', ['shiftCode' => $shiftCode]);
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
                        $sq = $this->masterfile_model->getRowArray($tk, 'request_new_shift', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($tk, 'req_new_shift_emp', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($tk, 'request_odaf', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'red'>$shiftCode</font> = $sched</span> ]";
                        }
                    }
                } else {
                    $day = date('l', strtotime($n_day));
                    $cond = "empId= '$empId' AND day= '$day'";
                    $dayoff = $this->masterfile_model->getResultArray($tk, 'assign_day_off', $cond);
                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else {

                        $cond = "empId='$empId' AND barcodeId NOT LIKE '%\_%' AND bioMetricId NOT LIKE '%\_%' ORDER BY assignId DESC LIMIT 1";
                        $row = $this->masterfile_model->getRowArray($tk, 'assign', $cond);
                        if ($row) {
                            $sched = '';
                            $shiftCode = $row['shiftCode'];
                            $sc = $this->masterfile_model->getRowArray($tk, 'shiftcodes', ['shiftCode' => $shiftCode]);
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
                $co = $this->masterfile_model->getRowArray($tk, 'promo_mul_sc', $cond);
                if ($co) {
                    $shiftCode  = $co['shiftCode'];
                    $amsId      = $co['amsId'];
                    $no_store   = $co['noStore'];

                    $cond = "empId= '$empId' AND amsId= '$amsId' AND date = '$n_day'";
                    $dayoff = $this->masterfile_model->getResultArray($tk, 'ass_mul_do', $cond);
                    if ($dayoff) {
                        $schedule = '<font color="red">DAY-OFF</font>';
                    } else if ($no_store == 0) {
                        $schedule = '<font color="red">NO SCHEDULE</font>';
                    } else {
                        $sched = '';
                        $sg = $this->masterfile_model->getRowArray($tk, 'shiftcodes', ['shiftCode' => $shiftCode]);
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

                        $sq = $this->masterfile_model->getRowArray($tk, 'request_new_shift', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'blue'>$shiftCode</font> = $sched</span> ]";
                        }

                        $sq = $this->masterfile_model->getRowArray($tk, 'request_odaf', ['amsId' => $amsId]);
                        if ($sq) {
                            $schedule = "[ <span><font color = 'red'>$shiftCode </font> = $sched</span> ]";
                        }
                    }

                    if ($co['noStore'] > 0) {
                        $storea = str_replace(',', '', $co['store1']);
                        $storeb = str_replace(',', '', $co['store2']);
                        if ($co['noStore'] == '1') {
                            $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storea]);
                            if ($row) {
                                $storeSched = $row['bunit_name'];
                            }
                        } else {
                            $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storea]);
                            if ($row) {
                                $storeSched = $row['bunit_name'];
                            }

                            $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storeb]);
                            if ($row) {
                                $storeSched .= ' / ' . $row['bunit_name'];
                            }
                        }
                    }
                } else {

                    $day = date('l', strtotime($n_day));
                    $cond = "empId = '$empId' AND day_sched = '$day'";
                    $ds = $this->masterfile_model->getRowArray($tk, 'promo_def_sched', $cond);
                    if ($ds) {
                        $shiftCode  = $ds['shiftCode'];
                        $no_store   = $ds['noStore'];
                        if ($no_store == '0') {
                            $schedule = '<font color="red">DAY-OFF</font>';
                        } else {
                            $sched = '';
                            $sg = $this->masterfile_model->getRowArray($tk, 'shiftcodes', ['shiftCode' => $shiftCode]);
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
                                $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storea]);
                                if ($row) {
                                    $storeSched = $row['bunit_name'];
                                }
                            } else {
                                $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storea]);
                                if ($row) {
                                    $storeSched = $row['bunit_name'];
                                }

                                $row = $this->masterfile_model->getRowArray($pis, 'locate_promo_business_unit', ['bu' => $storeb]);
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
}
