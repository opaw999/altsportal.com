<?php

namespace App\Models\supplier;

use App\Models\BaseModel;

class Dtr_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_of_cutoffs()
    {
        return $this->tk_corp
            ->table('promo_schedule')
            ->where('remark', 'active')
            ->get()
            ->getResultArray();
    }

    public function supplier_cutoffs($id)
    {
        $query = $this->tk_corp->table('promo_supplier_cutoff')
            ->select('statCut')
            ->where('supplier_id', $id)
            ->groupBy('statCut')
            ->get()
            ->getResultArray();

        return array_column($query, 'statCut');
    }

    public function supplier_cutoffs_sched($id)
    {
        $statCuts = $this->supplier_cutoffs($id);
        $query = $this->tk_corp->table('promo_schedule')
            ->whereIn('statCut', $statCuts)
            ->get();
        return $query->getResultArray();
    }

    public function supplier_ac_code($server)
    {
        $db = $this->$server;
        $cond = ['user_id' => session('userId'), 'supplier_id' => session('supplierId')];
        return $db->table('promo_supplier_ac_code')
            ->where($cond)
            ->get()
            ->getRowArray();
    }

    public function companies_under_agency($server, $agency_code)
    {
        $db = $this->$server;
        $query = $db->table('promo_locate_company')
            ->select('company_name')
            ->where('agency_code', $agency_code)
            ->groupBy('company_name')
            ->get()
            ->getResultArray();

        return array_column($query, 'company_name');
    }

    public function storeField_Filter($server)
    {

        $getServers = getServers($server);
        $hrcode     = $getServers['hrCode'];

        $pis = ($server == 'cebu') ? $this->pis_cebo : $this->pis_corp;
        $cond = ['status' => 'active', 'hrd_location' => $hrcode];
        $query = $pis->table('locate_promo_business_unit')
            ->where($cond)
            ->get()
            ->getResultArray();

        $i = 0;
        $stores = '';
        foreach ($query as $row) {
            $i++;
            $field  = $row['bunit_field'];
            $str    = "$field = 'T'";
            if ($server == 'talibon') {
                $stores = "al_tal = 'T'";
            } else if ($server == 'tubigon') {
                $stores = "al_tub = 'T' OR fr_tubigon = 'T'";
            } else {
                $stores = ($i == 1) ? $str : $stores . ' OR ' . $str;
            }
        }

        return $stores;
    }

    public function promo_under_agency($data, $agency_code)
    {
        $server     = $data['server'];
        $getServers = getServers($server);
        $tk_loc     = $getServers['tk_loc'];
        $companies  = $this->companies_under_agency($tk_loc, $agency_code);

        $df = date('Y-m-d', strtotime($data['dateFrom']));
        $dt = date('Y-m-d', strtotime($data['dateTo']));
        if ($data['server'] == 'cebu') {
            $tk = $this->tk_cebo;
            $pis = $this->pis_cebo;
        } else {
            $pis = $this->pis_corp;
            if ($data['server'] == 'talibon') {
                $tk = $this->tk_talibon;
            } else if ($data['server'] == "tubigon") {
                $tk = $this->tk_tubigon;
            } else {
                $tk = $this->tk_corp;
            }
        }

        $store = $this->storeField_Filter($server);

        $pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        $query = $pis->table('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id AND p.record_no=e.record_no')
            ->join($tk->getDatabase() . '.promo_sched_emp AS t', 't.empId = e.emp_id')
            ->where('agency_code', $agency_code)
            ->where('statCut', $data['cutoff'])
            ->whereIn('promo_company', $companies)
            ->groupStart()
            ->where($store)
            ->groupEnd()
            ->groupStart()
            ->where("'{$df}' BETWEEN startdate AND eocdate")
            ->orWhere("startdate BETWEEN '{$df}' AND '{$dt}'")
            ->orWhere("'{$dt}' BETWEEN startdate AND eocdate")
            ->orWhere("eocdate BETWEEN '{$df}' AND '{$dt}'")
            ->groupEnd()
            ->groupBy('e.emp_id')
            ->orderBy('e.date_added DESC')
            ->get();

        return $query->getResultArray();
    }

    public function promo_under_company($data, $company)
    {
        $df = date('Y-m-d', strtotime($data['dateFrom']));
        $dt = date('Y-m-d', strtotime($data['dateTo']));
        if ($data['server'] == 'cebu') {
            $tk = $this->tk_cebo;
            $pis = $this->pis_cebo;
        } else {
            $pis = $this->pis_corp;
            if ($data['server'] == 'talibon') {
                $tk = $this->tk_talibon;
            } else if ($data['server'] == "tubigon") {
                $tk = $this->tk_tubigon;
            } else {
                $tk = $this->tk_corp;
            }
        }

        $store = $this->storeField_Filter($data['server']);

        $pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        $query = $pis->table('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->join($tk->getDatabase() . '.promo_sched_emp AS t', 't.empId = e.emp_id')
            ->where('promo_company', $company)
            ->where('statCut', $data['cutoff'])
            ->groupStart()
            ->where($store)
            ->groupEnd()
            ->groupStart()
            ->where("'{$df}' BETWEEN startdate AND eocdate")
            ->orWhere("startdate BETWEEN '{$df}' AND '{$dt}'")
            ->orWhere("'{$dt}' BETWEEN startdate AND eocdate")
            ->orWhere("eocdate BETWEEN '{$df}' AND '{$dt}'")
            ->groupEnd()
            ->groupBy('e.emp_id')
            ->orderBy('e.date_added DESC')
            ->get();
        return $query->getResultArray();
    }

    public function no_sil_current($data, $empId)
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

    public function no_sil_previous($data, $empId)
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

    public function masterfile_under_agency($server, $agency_code)
    {
        $getServers = getServers($server);
        $pis        = $getServers['pis'];
        $tk         = $getServers['tk'];
        $tk_loc     = $getServers['tk_loc'];
        $companies  = $this->companies_under_agency($tk_loc, $agency_code);
        $cutoffs    = $this->supplier_cutoffs(session('supplierId'));

        $df = date('Y-m-d');
        $dt = date('Y-m-d');
        if ($server == 'cebu') {
            $tk = $this->tk_cebo;
            $pis = $this->pis_cebo;
        } else {
            $pis = $this->pis_corp;
            if ($server == 'talibon') {
                $tk = $this->tk_talibon;
            } else if ($server == "tubigon") {
                $tk = $this->tk_tubigon;
            } else {
                $tk = $this->tk_corp;
            }
        }

        $store = $this->storeField_Filter($server);

        $pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        $query = $pis->table('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id AND p.record_no=e.record_no')
            ->join($tk->getDatabase() . '.promo_sched_emp AS t', 't.empId = e.emp_id AND t.recordNo=e.record_no')
            ->where('agency_code', $agency_code)
            ->whereIn('statCut', $cutoffs)
            ->whereIn('promo_company', $companies)
            ->groupStart()
            ->where($store)
            ->groupEnd()
            ->groupStart()
            ->where("'{$df}' BETWEEN startdate AND eocdate")
            ->orWhere("startdate BETWEEN '{$df}' AND '{$dt}'")
            ->orWhere("'{$dt}' BETWEEN startdate AND eocdate")
            ->orWhere("eocdate BETWEEN '{$df}' AND '{$dt}'")
            ->groupEnd()
            ->groupBy('e.emp_id')
            ->orderBy('e.date_added DESC')
            ->get();
        return $query->getResultArray();
    }

    public function masterfile_under_company($server, $company)
    {
        $df = date('Y-m-d');
        $dt = date('Y-m-d');
        $cutoffs = $this->supplier_cutoffs(session('supplierId'));

        if ($server == 'cebu') {
            $tk = $this->tk_cebo;
            $pis = $this->pis_cebo;
        } else {
            $pis = $this->pis_corp;
            if ($server == 'talibon') {
                $tk = $this->tk_talibon;
            } else if ($server == "tubigon") {
                $tk = $this->tk_tubigon;
            } else {
                $tk = $this->tk_corp;
            }
        }

        $store = $this->storeField_Filter($server);

        $pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        $query = $pis->table('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->join($tk->getDatabase() . '.promo_sched_emp AS t', 't.empId = e.emp_id AND t.recordNo=e.record_no')
            ->where('promo_company', $company)
            ->whereIn('statCut', $cutoffs)
            ->groupStart()
            ->where($store)
            ->groupEnd()
            ->groupStart()
            ->where("'{$df}' BETWEEN startdate AND eocdate")
            ->orWhere("startdate BETWEEN '{$df}' AND '{$dt}'")
            ->orWhere("'{$dt}' BETWEEN startdate AND eocdate")
            ->orWhere("eocdate BETWEEN '{$df}' AND '{$dt}'")
            ->groupEnd()
            ->groupBy('e.emp_id')
            ->orderBy('e.date_added DESC')
            ->get();
        return $query->getResultArray();
    }
}
