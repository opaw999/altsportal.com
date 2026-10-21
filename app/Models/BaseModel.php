<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class BaseModel extends Model
{
    protected $pis;
    protected $pis_corp;
    protected $pis_cebo;
    protected $tk_corp;
    protected $tk_tubigon;
    protected $tk_talibon;
    protected $tk_cebo;

    public function __construct()
    {
        parent::__construct();

        $this->pis = Database::connect('pis');
        $this->pis_corp = Database::connect('pis_corp');
        $this->pis_cebo = Database::connect('pis_cebo');
        $this->tk_corp = Database::connect('tk_corp');
        $this->tk_tubigon = Database::connect('tk_tubigon');
        $this->tk_talibon = Database::connect('tk_talibon');
        $this->tk_cebo = Database::connect('tk_cebo');
    }

    public function getRowArray($server, $table, $condition)
    {
        $db = $this->$server;
        return $db->table($table)->where($condition)->get()->getRowArray();
    }

    public function getResultArray($server, $table, $condition)
    {
        $db = $this->$server;
        return $db->table($table)->where($condition)->get()->getResultArray();
    }

    public function insertData($server, $table, $data)
    {
        $db = $this->$server;
        return $db->table($table)->insert($data) ? $db->insertID() : false;
    }

    public function updateData($server, $table, $condition, $data)
    {
        $db = $this->$server;
        return $db->table($table)->where($condition)->update($data);
    }

    public function deleteData($server, $table, $condition)
    {
        $db = $this->$server;
        $db->table($table)->where($condition)->delete();
        return $db->affectedRows();
    }

    public function insertQuery($server, $sql)
    {
        $db = $this->$server;
        return $db->query($sql) ? $db->insertID() : false;
    }

    public function import_query($server, $sql)
    {
        $db = $this->$server;
        return $db->query($sql);
    }

    public function admin_user()
    {
        return $this->tk_corp
            ->table('admin_user')
            ->get()
            ->getResultArray();
    }

    public function agencyList($server, $table)
    {
        $db = $this->$server;
        return $db->table($table)->get()->getResultArray();
    }

    public function companyList($server, $table)
    {
        $db = $this->$server;
        return $db->table($table)->get()->getResultArray();
    }

    public function locate_promo_bu($server)
    {
        $db = $this->$server;
        return  $db->table('locate_promo_business_unit')
            ->where('status', 'active')
            ->orderBy('bunit_name')
            ->get()
            ->getResultArray();
    }

    public function portal_servers()
    {
        return  [
            'server_tag'        => 'Corporate',
            'server_tal'        => 'Alturas Talibon',
            'server_tub'        => 'Alturas Tubigon',
            'server_colonnade'  => 'Colonnade',
        ];
    }

    public function user_access_menu()
    {
        return  [
            'setup'             => 'Setup',
            'violation'         => 'Violation',
            'masterfile'        => 'Promo Data',
            'portalAdmin'       => 'Portal Admin',
            'import'            => 'Import',
            'import_violation'  => 'Import Violation',
            'cms'               => 'CMS',
        ];
    }

    public function dtr_store_code()
    {
        $letters = range('A', 'Z');
        $stores = [
            'Alturas Mall',
            'Alturas Talibon',
            'Alturas Tubigon',
            'Alturas Panglao',
            'Alta Citta',
            'Island City Mall',
            'Plaza Marcela',
            'Colonnade Colon',
            'Colonnade Mandaue',
            'Colonnade Mohon',
        ];

        $code = [];
        foreach ($stores as $key => $store) {
            $code[$letters[$key]] = $store;
        }

        return $code;
    }

    public function display_1stcutoff($cut_off)
    {
        $query = $this->tk_corp->table('1stcutoff')
            ->select('DISTINCT(dateTo) AS dateTo, dateFrom')
            ->where('statCut', $cut_off)
            ->orderBy('dateFrom', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    public function display_2ndcutoff($cut_off)
    {
        $query = $this->tk_corp->table('2ndcutoff')
            ->select('DISTINCT(dateTo) AS dateTo, dateFrom')
            ->where('statCut', $cut_off)
            ->orderBy('dateFrom', 'DESC')
            ->get();

        return $query->getResultArray();
    }
}
