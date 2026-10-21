<?php

namespace App\Models\admins;

use App\Models\BaseModel;

class Portal_model extends BaseModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function userAdmin($server, $table, $string)
    {
        $db = $this->$server;
        $query = $db->table($table)
            ->groupStart()
            ->like('name', $string)
            ->orLike('emp_id', $string)
            ->groupEnd()
            ->orderBy('name')
            ->limit(10)
            ->get();

        return $query->getResultArray();
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
            ->limit(12)
            ->get();

        return $query->getResultArray();
    }

    public function getCompanys($server, $table, $agency_code)
    {
        $db = $this->$server;
        $query = $db->table($table)
            ->select('company_name')
            ->where('agency_code', $agency_code)
            ->groupBy('company_name')
            ->orderBy('company_name', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    public function getDepartments($server, $table, $ids)
    {
        $db = $this->$server;
        $query = $db->table($table)
            ->select('dept_name')
            ->whereIn('bunit_id', $ids)
            ->groupBy('dept_name')
            ->orderBy('dept_name', 'ASC')
            ->get();

        return $query->getResultArray();
    }




    // public function selectAll_tcA($server, $table, $array)
    // {
    //     if ($server == 'cebu') {
    //         $query = $this->pis_cebu->get_where($table, $array);
    //     } else {
    //         $query = $this->pis_bohol->get_where($table, $array);
    //     }

    //     return $query->result_array();
    // }

    // public function selectAll_tcR($server, $table, $array)
    // {
    //     if ($server == 'cebu') {
    //         $query = $this->pis_cebu->get_where($table, $array);
    //     } else {
    //         $query = $this->pis_bohol->get_where($table, $array);
    //     }

    //     return $query->row_array();
    // }

    // public function selectAll_tcR_tk($server, $table, $array)
    // {
    //     if ($server == 'cebu') {
    //         $query = $this->tk_cebu->get_where($table, $array);
    //     } else if ($server == 'tagbilaran') {
    //         $query = $this->tk_tagbi->get_where($table, $array);
    //     } else if ($server == 'talibon') {
    //         $query = $this->tk_talibon->get_where($table, $array);
    //     } else if ($server == 'tubigon') {
    //         $query = $this->tk_tubigon->get_where($table, $array);
    //     }

    //     return $query->row_array();
    // }

    // public function selectAll_tcA_tk($server, $table, $array)
    // {
    //     if ($server == 'cebu') {
    //         $query = $this->tk_cebu->get_where($table, $array);
    //     } else if ($server == 'tagbilaran') {
    //         $query = $this->tk_tagbi->get_where($table, $array);
    //     } else if ($server == 'talibon') {
    //         $query = $this->tk_talibon->get_where($table, $array);
    //     } else if ($server == 'tubigon') {
    //         $query = $this->tk_tubigon->get_where($table, $array);
    //     }

    //     return $query->result_array();
    // }

    // public function join_tkR($server, $select, $tb1, $tb2, $join, $condition)
    // {
    //     if ($server == 'cebu') {
    //         $database = $this->tk_cebu;
    //     } else if ($server == 'tagbilaran') {
    //         $database = $this->tk_tagbi;
    //     } else if ($server == 'talibon') {
    //         $database = $this->tk_talibon;
    //     } else if ($server == 'tubigon') {
    //         $database = $this->tk_tubigon;
    //     }
    //     $query =  $database->select($select)
    //         ->from($tb1)
    //         ->join($tb2, $join)
    //         ->where($condition)
    //         ->get();
    //     return $query->row_array();
    // }

    // public function join_tkA($server, $select, $tb1, $tb2, $join, $condition)
    // {
    //     if ($server == 'cebu') {
    //         $database = $this->tk_cebu;
    //     } else if ($server == 'tagbilaran') {
    //         $database = $this->tk_tagbi;
    //     } else if ($server == 'talibon') {
    //         $database = $this->tk_talibon;
    //     } else if ($server == 'tubigon') {
    //         $database = $this->tk_tubigon;
    //     }
    //     $query =  $database->select($select)
    //         ->from($tb1)
    //         ->join($tb2, $join)
    //         ->where($condition)
    //         ->get();
    //     return $query->result_array();
    // }



    // public function select_pis($return, $server, $table, $condition)
    // {
    //     $database   = ($server == 'cebu') ? $this->pis_cebu : $this->pis_bohol;
    //     $query      = $database->get_where($table, $condition);

    //     return ($return == 'array') ? $query->result_array() : $query->row_array();
    // }

    // public function select_tk($return, $server, $table, $condition = null)
    // {
    //     if ($server == 'tagbilaran') {
    //         $query = is_null($condition) ? $this->tk_tagbi->get($table) : $this->tk_tagbi->get_where($table, $condition);
    //     } else if ($server == 'cebu') {
    //         $query = is_null($condition) ? $this->tk_cebu->get($table) : $this->tk_cebu->get_where($table, $condition);
    //     } else if ($server == 'talibon') {
    //         $query = is_null($condition) ? $this->tk_talibon->get($table) : $this->tk_talibon->get_where($table, $condition);
    //     } else if ($server == 'tubigon') {
    //         $query = is_null($condition) ? $this->tk_tubigon->get($table) : $this->tk_tubigon->get_where($table, $condition);
    //     }

    //     return ($return == 'array') ? $query->result_array() : $query->row_array();
    // }

    // public function update_pis($server, $table, $data, $condition)
    // {
    //     if ($server == 'cebu') {
    //         return $this->pis_cebu->where($condition)
    //             ->update($table, $data);
    //     } else {
    //         return $this->pis_bohol->where($condition)
    //             ->update($table, $data);
    //     }
    // }

    // public function update_tk($server, $table, $data, $condition)
    // {
    //     if ($server == 'tagbilaran') {
    //         return $this->tk_tagbi->where($condition)
    //             ->update($table, $data);
    //     } else if ($server == 'cebu') {
    //         return $this->tk_cebu->where($condition)
    //             ->update($table, $data);
    //     } else if ($server == 'talibon') {
    //         return $this->tk_talibon->where($condition)
    //             ->update($table, $data);
    //     } else if ($server == 'tubigon') {
    //         return $this->tk_tubigon->where($condition)
    //             ->update($table, $data);
    //     }
    // }

    // public function insert_pis($server, $table, $data)
    // {
    //     if ($server == 'cebu') {
    //         return $this->pis_cebu->insert($table, $data);
    //     } else {
    //         return $this->pis_bohol->insert($table, $data);
    //     }
    // }

    // public function insert_tk($server, $table, $data)
    // {
    //     if ($server == 'tagbilaran') {
    //         return $this->tk_tagbi->insert($table, $data);
    //     } else if ($server == 'cebu') {
    //         return $this->tk_cebu->insert($table, $data);
    //     } else if ($server == 'talibon') {
    //         return $this->tk_talibon->insert($table, $data);
    //     } else if ($server == 'tubigon') {
    //         return $this->tk_tubigon->insert($table, $data);
    //     }
    // }

    // public function delete_pis($server, $table, $condition)
    // {
    //     if ($server == 'cebu') {
    //         return $this->pis_cebu->delete($table, $condition);
    //     } else {
    //         return $this->pis_bohol->delete($table, $condition);
    //     }
    // }

    // public function delete_tk($server, $table, $condition)
    // {
    //     if ($server == 'tagbilaran') {
    //         return $this->tk_tagbi->delete($table, $condition);
    //     } else if ($server == 'cebu') {
    //         return $this->tk_cebu->delete($table, $condition);
    //     } else if ($server == 'talibon') {
    //         return $this->tk_talibon->delete($table, $condition);
    //     } else if ($server == 'tubigon') {
    //         return $this->tk_tubigon->delete($table, $condition);
    //     }
    // }

    // public function select2_pis($return, $server, $table, $select, $condition)
    // {
    //     $database   = ($server == 'cebu') ? $this->pis_cebu : $this->pis_bohol;
    //     $query      = $database->select($select)
    //         ->get_where($table, $condition);

    //     return ($return == 'array') ? $query->result_array() : $query->row_array();
    // }

    // public function select2_tk($return, $server, $table, $select, $condition)
    // {

    //     if ($server == 'tagbilaran') {
    //         $query = $this->tk_tagbi->select($select)
    //             ->get_where($table, $condition);
    //     } else if ($server == 'cebu') {
    //         $query = $this->tk_cebu->select($select)
    //             ->get_where($table, $condition);
    //     } else if ($server == 'talibon') {
    //         $query = $this->tk_talibon->select($select)
    //             ->get_where($table, $condition);
    //     } else if ($server == 'tubigon') {
    //         $query = $this->tk_tubigon->select($select)
    //             ->get_where($table, $condition);
    //     }
    //     return ($return == 'array') ? $query->result_array() : $query->row_array();
    // }

    // public function pis_query($server, $sql)
    // {
    //     $database   = ($server == 'cebu') ? $this->pis_cebu : $this->pis_bohol;
    //     $query      = $database->query($sql);

    //     if (strpos(strtoupper($sql), 'INSERT') === 0) {
    //         return $database->insert_id(); // Return the last inserted ID
    //     }

    //     return $query;
    // }
}
