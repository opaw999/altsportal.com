<?php

namespace App\Models\admins;

use App\Models\BaseModel;

class Masterfile_model extends BaseModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function searchPromo($server, $string)
    {
        $db = $this->$server;
        $query = $db->table('employee3')
            ->select('emp_id')
            ->groupStart()
            ->like('name', $string)
            ->orLike('emp_id', $string)
            ->groupEnd()
            ->whereIn('emp_type', ['Promo', 'Promo-NESCO'])
            ->groupBy('emp_id')
            ->limit(10)
            ->get();

        return $query->getResultArray();
    }

    public function promoDetails($server, $empId, $recordNo)
    {
        $db = $this->$server;
        $query = $db->table('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where('e.emp_id', $empId)
            ->where('e.record_no', $recordNo)
            ->get();
        return $query->getRowArray();
    }

    public function promoCutoffs($server, $table, $statCut)
    {
        $db = $this->$server;
        $query = $db->table($table)
            ->select('dateFrom, MIN(dateTo) AS dateTo')
            ->where('statCut', $statCut)
            ->groupBy(['dateFrom'])
            ->orderBy('dateFrom', 'DESC')
            ->limit(24)
            ->get();

        return $query->getResultArray();
    }

    public function no_sil_current($data)
    {
        $server1 = ($data['server'] == 'cebu') ? 'pis_cebo' : 'pis_corp';
        if ($data['server'] == 'cebu') {
            $server2 = 'tk_cebo';
        } else if ($data['server']  == 'tagbilaran') {
            $server2 = 'tk_corp';
        } else if ($data['server']  == 'talibon') {
            $server2 = 'tk_talibon';
        } else if ($data['server']  == 'tubigon') {
            $server2 = 'tk_tubigon';
        } else {
            $server2 = 'tk_corp';
        }

        $tk = $this->$server2;
        $pis = $this->$server1;

        $df = date('Y-m-d', strtotime($data['dateFrom']));
        $dt = date('Y-m-d', strtotime($data['dateTo']));
        $empId = $data['empId'];

        $sql = "SELECT COUNT(id) as no_rows FROM r_l_absence WHERE 
                            r_l_absence.typeLeave = 'Personal' AND 
                            (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%') AND 
                            r_l_absence.empId = '" . $empId . "' AND 
                            fromDate = '" . $df . "' AND toDate = '" . $dt . "' AND r_l_absence.supStatus != 'Previous' AND
                            r_l_absence.status = 'Approved'";
        $r_l_absence = $tk->query($sql);
        $r_l_absence_count = $r_l_absence->getRow();

        $sql = "SELECT COUNT(id) as no_rows FROM r_l_absence, rla_day WHERE 
                            r_l_absence.typeLeave = 'Personal' AND 
                            (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%') AND 
                            r_l_absence.empId = '" . $empId . "' AND 
                            rla_day.empId = '" . $empId . "' AND 
                            rla_day.date BETWEEN '" . $df . "' AND '" . $dt . "' AND r_l_absence.supStatus != 'Previous' AND
                            r_l_absence.rLAId = rla_day.rLAId AND r_l_absence.empId = rla_day.empId AND r_l_absence.status = 'Approved'";
        $rla_day = $tk->query($sql);
        $rla_day_count = $rla_day->getRow();

        if ($r_l_absence_count->no_rows != $rla_day_count->no_rows) {

            $sql = "SELECT COUNT(noDays) as sil FROM r_l_absence, rla_day WHERE 
                                      r_l_absence.typeLeave = 'Personal' AND 
                                      (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%') AND 
                                      r_l_absence.empId = '" . $empId . "' AND 
                                      rla_day.empId = '" . $empId . "' AND 
                                      rla_day.date BETWEEN '" . $df . "' AND '" . $dt . "' AND r_l_absence.supStatus != 'Previous' AND
                                      r_l_absence.rLAId = rla_day.rLAId AND r_l_absence.empId = rla_day.empId AND r_l_absence.status = 'Approved'";
            $query = $tk->query($sql);
        } else {

            $sql = "SELECT SUM(noDays) as sil FROM r_l_absence, rla_day WHERE 
                                      r_l_absence.typeLeave = 'Personal' AND 
                                      (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%') AND 
                                      r_l_absence.empId = '" . $empId . "' AND 
                                      rla_day.empId = '" . $empId . "' AND 
                                      rla_day.date BETWEEN '" . $df . "' AND '" . $dt . "' AND r_l_absence.supStatus != 'Previous' AND
                                      r_l_absence.rLAId = rla_day.rLAId AND r_l_absence.empId = rla_day.empId AND r_l_absence.status = 'Approved'
                                      LIMIT 1";
            $query = $tk->query($sql);
        }

        return $query->getRowArray();
    }

    public function no_sil_previous($data)
    {
        $server1 = ($data['server'] == 'cebu') ? 'pis_cebo' : 'pis_corp';
        if ($data['server'] == 'cebu') {
            $server2 = 'tk_cebo';
        } else if ($data['server']  == 'tagbilaran') {
            $server2 = 'tk_corp';
        } else if ($data['server']  == 'talibon') {
            $server2 = 'tk_talibon';
        } else if ($data['server']  == 'tubigon') {
            $server2 = 'tk_tubigon';
        } else {
            $server2 = 'tk_corp';
        }

        $tk = $this->$server2;
        $pis = $this->$server1;

        $df = date('Y-m-d', strtotime($data['dateFrom']));
        $dt = date('Y-m-d', strtotime($data['dateTo']));
        $empId = $data['empId'];



        // $dateFrom   = date('Y-m-d', strtotime($data['dateFrom']));
        // $dateTo     = date('Y-m-d', strtotime($data['dateTo']));
        // $this->tk->query('SET SESSION sql_mode = ""');

        // // ONLY_FULL_GROUP_BY
        // $this->tk->query('SET SESSION sql_mode =
        //                       REPLACE(REPLACE(REPLACE(
        //                       @@sql_mode,
        //                       "ONLY_FULL_GROUP_BY,", ""),
        //                       ",ONLY_FULL_GROUP_BY", ""),
        //                       "ONLY_FULL_GROUP_BY", "")');

        $query = "SELECT COUNT(r_l_absence.id) as sil FROM r_l_absence, rla_day WHERE 
                                  r_l_absence.typeLeave = 'Personal' AND 
                                  (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%') AND 
                                  r_l_absence.empId = '" . $empId . "' AND 
                                  rla_day.empId = '" . $empId . "' AND 
                                  r_l_absence.supDF = '" . $df . "' AND r_l_absence.supDT = '" . $dt . "' AND r_l_absence.supStatus = 'Previous' AND
                                  r_l_absence.rLAId = rla_day.rLAId AND r_l_absence.empId = rla_day.empId AND r_l_absence.status = 'Approved' AND 
                                  rla_day.date BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT
                                  GROUP BY rla_day.date";
        $query = $tk->query($query);

        return $query->getRowArray();
    }

    public function store_entry($server, $bioM, $allowId, $dateFrom, $entry, $mode)
    {
        $table = '';

        if ($allowId == 'Biometric ID') {
            $table = 'duty_biometric';
        }

        if ($allowId == 'Logbox ID') {
            $table = 'duty_logbox';
        }

        $time = ($mode == 'I') ? 'timeIn' : 'timeOut';

        $store = '';
        $cond = "bioMetricId = '$bioM' AND dateDuty = '$dateFrom' AND $time = '$entry' AND mode = '$mode'";
        $row = $this->getRowArray($server, $table, $cond);
        if ($row) {
            $store = $row['store'];
        }

        $code = '';
        if ($store == 'alturas') {
            $code = 'A';
        }
        if ($store == 'alturas_tal') {
            $code = 'B';
        }
        if ($store == 'al_tub') {
            $code = 'C';
        }
        if ($store == 'al_panglao' || $store == 'alturas_panglao' || $store == 'fixrite_panglao') {
            $code = 'D';
        }
        if ($store == 'alta_cita') {
            $code = 'E';
        }
        if ($store == 'icm') {
            $code = 'F';
        }
        if ($store == 'plaza_marcela') {
            $code = 'G';
        }
        if ($store == 'col_c') {
            $code = 'H';
        }
        if ($store == 'col_m') {
            $code = 'I';
        }
        if ($store == 'col_mh') {
            $code = 'J';
        }
        return $code;
    }
}
