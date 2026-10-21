<?php

namespace App\Models\admins;

use App\Models\BaseModel;

class Violation_model extends BaseModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function list_of_cutoffs()
    {
        if (session('server') == 'server_colonnade') {
            return $this->tk_cebo
                ->table('promo_schedule')
                ->where('remark', 'active')
                ->get()
                ->getResultArray();
        } else {
            return $this->tk_corp
                ->table('promo_schedule')
                ->where('remark', 'active')
                ->get()
                ->getResultArray();
        }
    }

    public function getCompanyViolationCount($server, $df, $dt)
    {
        $db = $this->$server;
        $query = $db->table('locate_promo_company p1')
            ->select('p1.pc_code,p1.pc_name, COUNT(p2.id) AS count')
            ->join('promo_violation p2', "p1.pc_name = p2.company_name AND p2.date_from = " . $db->escape($df) . " AND p2.date_to   = " . $db->escape($dt), 'left')
            ->groupBy('p1.pc_code, p1.pc_name')
            ->get();

        return $query->getResultArray();
    }

    public function companyAgency($server, $condition)
    {
        $db = $this->$server;
        $query = $db->table('promo_locate_company')
            ->select('agency_code')
            ->where($condition)
            ->groupBy('agency_code')
            ->get();

        return $query->getResultArray();
    }
}
