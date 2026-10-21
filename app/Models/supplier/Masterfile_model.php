<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterfile_model extends CI_Model {

    public $db_pis;
    public $db_piscolonnade;
    public $db_tkcolonnade;
    public $db_tktalibon;
    public $db_tktubigon;

	function __construct()
    {
        parent::__construct();

        $this->db_pis = $this->load->database('pis', TRUE);
        $this->db_piscolonnade = $this->load->database('pis_colonnade', TRUE);
        $this->db_tkcolonnade = $this->load->database('tk_colonnade', TRUE);
        $this->db_tktalibon = $this->load->database('tk_talibon', TRUE);
        $this->db_tktubigon = $this->load->database('tk_tubigon', TRUE);
    }

    public function return_result_array($sql) {

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function return_row_array($sql) {

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function return_num_rows($sql) {

        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function return_result($sql) {

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function return_data_result($database, $sql) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->query($sql);
        }
        else if ($database == "pis") {
            
            $query = $this->db_pis->query($sql);
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->query($sql);
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->query($sql);
        }
        else if ($database == "pis_colonnade") {
            
            $query = $this->db_piscolonnade->query($sql);
        } else {

            $query = $this->db->query($sql);
        }

        return $query->result();
    }

    public function return_data_row_array($database, $sql){

        if ($database == "tk_talibon") {

            $this->db_tktalibon->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_tktalibon->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');
            
            $query = $this->db_tktalibon->query($sql);
        }
        else if ($database == "pis") {
            
            $query = $this->db_pis->query($sql);
        }
        else if ($database == "tk_tubigon") {

            $this->db_tktubigon->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_tktubigon->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');
            
            $query = $this->db_tktubigon->query($sql);
        }
        else if ($database == "tk_colonnade") {

            $this->db_tkcolonnade->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_tkcolonnade->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');
            
            $query = $this->db_tkcolonnade->query($sql);
        }
        else if ($database == "pis_colonnade") {
            
            $query = $this->db_piscolonnade->query($sql);
        } else {

            $this->db->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

            $query = $this->db->query($sql);
        }

        return $query->row_array();
    }

    public function return_data_num_rows($database, $sql) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->query($sql);
        }
        else if ($database == "pis") {
            
            $query = $this->db_pis->query($sql);
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->query($sql);
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->query($sql);
        }
        else if ($database == "pis_colonnade") {
            
            $query = $this->db_piscolonnade->query($sql);
        } else {

            $query = $this->db->query($sql);
        }

        return $query->num_rows();
    }

    public function return_data_result_array($database, $sql) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->query($sql);
        }
        else if ($database == "pis") {
            
            $query = $this->db_pis->query($sql);
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->query($sql);
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->query($sql);
        }
        else if ($database == "pis_colonnade") {
            
            $query = $this->db_piscolonnade->query($sql);
        } else {

            $query = $this->db->query($sql);
        }

        return $query->result_array();
    }

    public function get_statCut($database, $empId) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('statCut')
                                            ->get_where("promo_sched_emp", array('empId' => $empId));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('statCut')
                                            ->get_where("promo_sched_emp", array('empId' => $empId));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('statCut')
                                            ->get_where("promo_sched_emp", array('empId' => $empId));
        } else {

            $query = $this->db->select('statCut')
                                            ->get_where("promo_sched_emp", array('empId' => $empId));
        }

        return $query->row_array();
    }

    public function get_cutoff($database, $statcut) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('startFC, endFC, startSC, endSC')
                                            ->get_where("promo_schedule", array('statCut' => $statcut));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('startFC, endFC, startSC, endSC')
                                            ->get_where("promo_schedule", array('statCut' => $statcut));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('startFC, endFC, startSC, endSC')
                                            ->get_where("promo_schedule", array('statCut' => $statcut));
        } else {

            $query = $this->db->select('startFC, endFC, startSC, endSC')
                                            ->get_where("promo_schedule", array('statCut' => $statcut));
        }

        return $query->row_array();
    }

    public function assign($database, $empId) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('shiftcode')
                                            ->get_where("assign", array('empId' => $empId));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('shiftcode')
                                            ->get_where("assign", array('empId' => $empId));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('shiftcode')
                                            ->get_where("assign", array('empId' => $empId));
        } else {

            $query = $this->db->select('shiftcode')
                                            ->get_where("assign", array('empId' => $empId));
        }

        return $query->row_array();
    }

    public function store($database) {

        if ($database == "pis") {
            
            $query = $this->db_pis->select('bunit_field, bunit_name')
                                    ->get("locate_promo_business_unit");
        } else {
            
            $query = $this->db_piscolonnade->select('bunit_field, bunit_name')
                                    ->get("locate_promo_business_unit");
        }

        return $query->result_array();
    }

    public function dayoff($database, $empId, $amsId, $date) {

        $array = array('empId' => $empId, 'amsId' => $amsId, 'date' => $date);

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('amdId')
                                            ->from("ass_mul_do")
                                            ->where($array)
                                            ->order_by('amdId', 'DESC')
                                            ->limit(1)
                                        ->get();
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('amdId')
                                            ->from("ass_mul_do")
                                            ->where($array)
                                            ->order_by('amdId', 'DESC')
                                            ->limit(1)
                                        ->get();
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('amdId')
                                            ->from("ass_mul_do")
                                            ->where($array)
                                            ->order_by('amdId', 'DESC')
                                            ->limit(1)
                                        ->get();
        } else {

            $query = $this->db->select('amdId')
                                ->from("ass_mul_do")
                                ->where($array)
                                ->order_by('amdId', 'DESC')
                                ->limit(1)
                            ->get();
        }

        return $query->num_rows();
    }

    public function default_DO($database, $empId, $date) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->get_where("assign_day_off", array('empId' => $empId, 'day' => $date));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->get_where("assign_day_off", array('empId' => $empId, 'day' => $date));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->get_where("assign_day_off", array('empId' => $empId, 'day' => $date));
        } else {

            $query = $this->db->get_where("assign_day_off", array('empId' => $empId, 'day' => $date));
        }

        return $query->num_rows();
    }

    public function promo_def_sched($database, $empId, $date) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('noStore, store1, store2, shiftCode')
                                            ->get_where("promo_def_sched", array('empId' => $empId, 'day_sched' => $date));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('noStore, store1, store2, shiftCode')
                                            ->get_where("promo_def_sched", array('empId' => $empId, 'day_sched' => $date));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('noStore, store1, store2, shiftCode')
                                            ->get_where("promo_def_sched", array('empId' => $empId, 'day_sched' => $date));
        } else {

            $query = $this->db->select('noStore, store1, store2, shiftCode')
                                ->get_where("promo_def_sched", array('empId' => $empId, 'day_sched' => $date));
        }

        return $query->row_array();
    }

    public function store_name($database, $store_code) {

        if ($database == "tk_talibon") {
            
            $query = $this->db_tktalibon->select('store_name')
                                            ->get_where("storecode", array('store_code' => $store_code));
        }
        else if ($database == "tk_tubigon") {
            
            $query = $this->db_tktubigon->select('store_name')
                                            ->get_where("storecode", array('store_code' => $store_code));
        }
        else if ($database == "tk_colonnade") {
            
            $query = $this->db_tkcolonnade->select('store_name')
                                            ->get_where("storecode", array('store_code' => $store_code));
        } else {

            $query = $this->db->select('store_name')
                                ->get_where("storecode", array('store_code' => $store_code));
        }

        return $query->row_array();
    }

    public function get_promo_type($database, $emp_id) {

        if ($database == "pis") {
            
            $query = $this->db_pis->select('promo_type')
                                    ->get_where("promo_record", array('emp_id' => $emp_id));;
        } else {
            
            $query = $this->db_piscolonnade->select('promo_type')
                                            ->get_where("promo_record", array('emp_id' => $emp_id));;
        }

        return $query->row_array();
    }
}