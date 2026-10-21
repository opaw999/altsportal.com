<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Violation_model extends CI_Model {

    public $db_pis;
    public $db_piscolonnade;
    public $db_tkcolonnade;
    public $db_tktalibon;
    public $db_tktubigon;
    public $supplier_id;

	function __construct()
    {
        parent::__construct();

        $this->db_pis = $this->load->database('pis', TRUE);
        $this->db_piscolonnade = $this->load->database('pis_colonnade', TRUE);
        $this->db_tkcolonnade = $this->load->database('tk_colonnade', TRUE);
        $this->db_tktalibon = $this->load->database('tk_talibon', TRUE);
        $this->db_tktubigon = $this->load->database('tk_tubigon', TRUE);
        $this->supplier_id = $this->session->userdata('supplier_id');
        date_default_timezone_set('Asia/Manila');
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

    public function promo_details($pis, $emp_id, $record_no) {

        if ($pis == "pis_colonnade") {
            $query = $this->db_piscolonnade->select('promo_company, promo_type')
                                        ->get_where('promo_record', array('emp_id' => $emp_id, 'record_no' => $record_no));
        } else {
            $query = $this->db_pis->select('promo_company, promo_type')
                                        ->get_where('promo_record', array('emp_id' => $emp_id, 'record_no' => $record_no));
        }
        return $query->row();
    }

    public function promo_violation($emp_id, $data)
    {
        $date_from = date("Y-m-d", strtotime($data['dateFrom']));
        $date_to = date("Y-m-d", strtotime($data['dateTo']));
        $dateRange = "$date_from|$date_to";

        if ($data['server'] == "colonnade") {
            
            $query = $this->db_tkcolonnade->select('COUNT(id) AS exist')
                                            ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
        } 
        else if ($data['server'] == "talibon") {
            
             $query = $this->db_tktalibon->select('COUNT(id) AS exist')
                                            ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
        }
        else if ($data['server'] == "tubigon") {
            
             $query = $this->db_tktubigon->select('COUNT(id) AS exist')
                                            ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
        }
        else {

             $query = $this->db->select('COUNT(id) AS exist')
                                            ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
        }

        return $query->row();
    }

    public function promo_violation_agency($agency_code, $company, $data) {

        $date_from = date("Y-m-d", strtotime($data['dateFrom']));
        $date_to = date("Y-m-d", strtotime($data['dateTo']));

        if ($data['server'] == "colonnade") {
            $query = $this->db_piscolonnade->select('COUNT(id) AS exist')
                                        ->get_where('promo_violation', array('agency_code' => $agency_code, 'company_name' => base64_decode($company), 'date_from' => $date_from, 'date_to' => $date_to));
        } else {
            $query = $this->db_pis->select('COUNT(id) AS exist')
                                        ->get_where('promo_violation', array('agency_code' => $agency_code, 'company_name' => base64_decode($company), 'date_from' => $date_from, 'date_to' => $date_to));
        }
        return $query->row();
    }

    public function promo_violation_company($company, $data) {

        $date_from = date("Y-m-d", strtotime($data['dateFrom']));
        $date_to = date("Y-m-d", strtotime($data['dateTo']));

        if ($data['server'] == "colonnade") {
            $query = $this->db_piscolonnade->select('COUNT(id) AS exist')
                                        ->get_where('promo_violation', array('company_name' => base64_decode($company), 'date_from' => $date_from, 'date_to' => $date_to));
        } else {
            $query = $this->db_pis->select('COUNT(id) AS exist')
                                        ->get_where('promo_violation', array('company_name' => base64_decode($company), 'date_from' => $date_from, 'date_to' => $date_to));
        }
        return $query->row();
    }

    public function promo_violation_details($data)
    {
        $date_from = date("Y-m-d", strtotime($data['dateFrom']));
        $date_to = date("Y-m-d", strtotime($data['dateTo']));
        $dateRange = "$date_from|$date_to";
        $emp_id = $data['empId'];

        if ($data['server'] == "colonnade") {
            
            $query = $this->db_tkcolonnade->select('COUNT(id) AS exist')
                                            ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
        } else {

            $employee = $this->db_pis->select('name')
                                        ->get_where('employee3', array('emp_id' => $emp_id));
            $emp = $employee->row();
            $fullname = $emp->name;
            
            if ($data['server'] == "talibon") {
                
                $query = $this->db_tktalibon->select('COUNT(id) AS exist')
                                                ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
            }
            else if ($data['server'] == "tubigon") {
                
                $query = $this->db_tktubigon->select('COUNT(id) AS exist')
                                                ->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
            }
            else {

                $query = $this->db->get_where('promo_portal_emp_list', array('empId' => $emp_id, 'dateRange' => $dateRange));
                $row = $query->row();

                $agency = '';
                if (!empty($row->agency)) {

                    $supplier = $this->db->select('agency_name')
                                            ->get_where('promo_locate_agency', array('agency_code' => $row->agency));
                    $sup = $supplier->row();
                    $agency = $sup->agency_name;
                }

                $company = $row->company_name;
                
                $promo_portal_detail = $this->db->get_where('promo_portal_detail', array('emp_violation_id' => $row->id));
                $details = $promo_portal_detail->result();
                $violation_detail = array();
                foreach ($details as $detail) {
                
                    $violation = $this->db->select('violation_type')
                                            ->get_where('houserules', array('violation_id' => $detail->violation_id));
                    $v = $violation->row();

                    $promo_portal_violation_detail = $this->db->get_where('promo_portal_violation_detail', array('detail_id' => $detail->id));
                    $violation_details = $promo_portal_violation_detail->result();
                    
                    $promo_prev_violation_detail = $this->db->get_where('promo_portal_violation_prev_detail', array('detail_id' => $detail->id));
                    $violation_prev_details = $promo_prev_violation_detail->result();

                    $violation_detail[] = [
                        'violation' => $v->violation_type,
                        'suspension' => $detail->suspension,
                        'store' => $detail->store,
                        'department' => $detail->department,
                        'violation_details' => $violation_details,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                        'violation_prev_details' => $violation_prev_details                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                    ];
                }
            }

            return compact(
                'fullname',
                'agency',
                'company',
                'violation_detail'
            );
        }
    }

    public function get_promo_violation($data) {

        $id = base64_decode($data['id']);
        $row = explode('|', $id);
        $company = addslashes($row[0]);
        $agency_code = $row[1];

        if ($data['server'] != "colonnade") {
            
            $query = $this->db_pis->select('id, image_path, created_at')
                                    ->get_where('promo_violation', array('agency_code' => $agency_code, 'company_name' => $company, 'date_from' => $data['dateFrom'], 'date_to' => $data['dateTo']));
        } else {

            $query = $this->db_piscolonnade->select('id, image_path, created_at')
                                            ->get_where('promo_violation', array('agency_code' => $agency_code, 'company_name' => $company, 'date_from' => $data['dateFrom'], 'date_to' => $data['dateTo']));
        }

        return $query->result();
    }

    public function view_image($data) {

        if ($data['server'] != "colonnade") {
            
            $query = $this->db_pis->select('image_path, created_at')
                                    ->get_where('promo_violation', array('id' => $data['id']));
        } else {

            $query = $this->db_piscolonnade->select('image_path, created_at')
                                    ->get_where('promo_violation', array('id' => $data['id']));
        }

        return $query->row();
    }

    public function promo_supplier_ac_code($server) {

        if ($server == "colonnade") {
            
            $query = $this->db_tkcolonnade->get_where('promo_supplier_ac_code', array('supplier_id' => $this->supplier_id));
        } else {

            $query = $this->db->get_where('promo_supplier_ac_code', array('supplier_id' => $this->supplier_id));
        }

        return $query->row();
    }

    public function violation_under_agency($server, $cutoff, $date_from, $date_to, $companies) {

        $store = "";
        $companys = "";
        $i = 0;

        foreach ($companies as $company) {

            if ($i == 0) {
                
                $companys = "p.promo_company = '".addslashes($company->company_name)."'";
            } else {

                $companys .= " OR p.promo_company = '".addslashes($company->company_name)."'";
            }
            
            $i++;
        }

        $total_violation = 0;
        if ($server == "colonnade") {
            
            $store = "(p.colm = 'T' OR p.colc = 'T')";
            $this->db_piscolonnade->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_piscolonnade->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

            $query = "SELECT p.emp_id
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN boholonl_dbtkcolon.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                ($companys) AND
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_piscolonnade->query($query);
            $tal_emps = $sql->result();

            foreach ($tal_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }

        } else {

            $this->db_pis->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');
                
            $tk = "boholonl_dbtalibon";
            $store = "(p.al_tal = 'T')";

            $query = "SELECT p.emp_id
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_type
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                ($companys) AND
                                (e.emp_type like 'Promo%') AND 
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tal_emps = $sql->result();

            foreach ($tal_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }

                
            $tk = "boholonl_dbtubigon";
            $store = "(p.al_tub = 'T')";

            $query = "SELECT p.emp_id
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_type
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                ($companys) AND
                                (e.emp_type like 'Promo%') AND 
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tub_emps = $sql->result();

            foreach ($tub_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }


            $tk = "boholonl_altstk";
            $store = "(p.al_tag = 'T' OR p.icm = 'T' OR p.pm = 'T' OR p.alta_citta = 'T')";
        

            $query = "SELECT p.emp_id
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_type
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                ($companys) AND
                                (e.emp_type like 'Promo%') AND 
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tagb_emps = $sql->result();

            foreach ($tagb_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;

                }
            }

        }   
        
        return $total_violation;
    }

    private function check_promo_violation($emp_id, $server, $cutoff, $date_from, $date_to) {
        
        $array = array(
            'emp_id' => $emp_id,
            'statCut' => $cutoff,
            'date_from' => $date_from,
            'date_to' => $date_to
        );

        if ($server == "colonnade") {
            
            $query = $this->db_piscolonnade->select('COUNT(id) AS exist')
                                                ->from('promo_violation')
                                                    ->where($array)
                                                ->get();
        } else {

            $query = $this->db_pis->select('COUNT(id) AS exist')
                                        ->from('promo_violation')
                                            ->where($array)
                                        ->get();
        }

        return $query->row();
    }

    public function violation_under_company($server, $cutoff, $date_from, $date_to, $company) {

        $before= date("Y-m-d",strtotime("- 2 week"));
        $date  = date('Y-m-d');

        $company = addslashes($company);

        $total_violation = 0;
        if ($server == "colonnade") {
            
            $this->db_piscolonnade->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_piscolonnade->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

            $store = "(p.colm = 'T' OR p.colc = 'T')";
            $query = "SELECT p.record_no, p.emp_id, e.name, e.position
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_id, name, position
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN boholonl_dbtkcolon.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                p.promo_company = '".$company."' AND
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_piscolonnade->query($query);
            $col_emps = $sql->result();

            foreach ($col_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }

        } else {

            $this->db_pis->query('SET SESSION sql_mode = ""');

            // ONLY_FULL_GROUP_BY
            $this->db_pis->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

            $tk = "boholonl_dbtalibon";
            $store = "(p.al_tal = 'T')";

            $query = "SELECT p.record_no, p.emp_id, e.name, e.position
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_id, name, position
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                p.promo_company = '".$company."' AND
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tal_emps = $sql->result();

            foreach ($tal_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }

            $tk = "boholonl_dbtubigon";
            $store = "(p.al_tub = 'T')";

            $query = "SELECT p.record_no, p.emp_id, e.name, e.position
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_id, name, position
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                p.promo_company = '".$company."' AND
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tub_emps = $sql->result();

            foreach ($tub_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }

            $tk = "boholonl_altstk";
            $store = "(p.al_tag = 'T' OR p.icm = 'T' OR p.pm = 'T' OR p.alta_citta = 'T')";

            $query = "SELECT p.record_no, p.emp_id, e.name, e.position
                        FROM promo_record p
                            INNER JOIN (
                                SELECT max(record_no) AS record_no, emp_id, name, position
                                FROM employee3
                                GROUP BY emp_id
                            ) e
                            ON  p.record_no = e.record_no
                            INNER JOIN $tk.promo_sched_emp t ON t.empId = p.emp_id
                            WHERE
                                t.statCut = '".$cutoff."' AND
                                p.promo_company = '".$company."' AND
                                $store
                            ORDER BY p.record_no DESC";
            $sql = $this->db_pis->query($query);
            $tagb_emps = $sql->result();

            foreach ($tagb_emps as $emp) {

                $violation = $this->check_promo_violation($emp->emp_id, $server, $cutoff, $date_from, $date_to);
                if ($violation->exist > 0) {
                    
                    $total_violation += 1;
                }
            }
        }

        return $total_violation;   
    }
}