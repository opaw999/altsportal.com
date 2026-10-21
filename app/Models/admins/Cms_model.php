<?php
// defined('BASEPATH') or exit('No direct script access allowed');

// class Cms_model extends CI_Model
// {
//     function __construct()
//     {
//         parent::__construct();
//         $this->loginId = $_SESSION['adminId'];
//         date_default_timezone_set('Asia/Manila');
//         $this->timestamp = date("Y-m-d H:i:s");
//     }
    
//     	//reusable table
// 	public function cDF($changeformatto,$date)
// 	{	
// 		if($date != '0000-00-00'){
// 			$convert_date = new DateTime($date); 		
// 			return $convert_date->format($changeformatto);
// 		}else{
// 			return '';
// 		}
// 	}
	
// 	public function getallfrmtbl($table)
// 	{		
// 		$query = $this->db->get($table);
// 		return $query->result_array();	
// 	}
	
// 	public function getalltblwhere($table,$where)
// 	{		
// 		$this->db->where($where); 
// 		$query = $this->db->get($table);
// 		return $query->result_array();	
// 	}
	
// 	public function getfrmtblwhere($table,$where)
// 	{		
// 		$this->db->where($where); 
// 		$query = $this->db->get($table);
// 		return $query->row();	
// 	}
	
// 	public function getOneField($field,$table,$where)
// 	{
// 		$this->db->select($field);
// 		$this->db->where($where); 
// 		$query = $this->db->get($table);
// 		return $query->row();
// 	}  
	

	
// 	public function getDescription($vcode, $sufid){
// 	    $this->db->select("GROUP_CONCAT(DISTINCT tbl_items.description ORDER BY tbl_items.description SEPARATOR ', ') AS descriptions");
//         $this->db->from('tbl_item_sales');
//         $this->db->join('tbl_items', 'tbl_item_sales.item_code = tbl_items.item_code');
//         $this->db->join('tbl_item_department', 'tbl_items.item_dept_code = tbl_item_department.item_dept_code');
//         $this->db->where('tbl_item_sales.vendor_code', $vcode);
//         $this->db->where('tbl_item_sales.suf_id', $sufid);
        
//         $query = $this->db->get();
//         $result = $query->row(); // use ->row() if expecting one row
        
//         if ($result) {
//             echo $result->descriptions;
//         } else {
//             echo "No data found.";
//         }
// 	}
namespace App\Models\admins;

use App\Models\BaseModel;

class Cms_model extends BaseModel
{
	function __construct()
    {
        parent::__construct();
    }

	public function getall($table)
	{		
		return $this->tk_corp
			->table($table)     
            ->get()
			->getResultArray();
	}

	public function getAllWhere($table, $where = [])
	{
		$builder = $this->tk_corp->table($table);

		if (!empty($where)) {
			$builder->where($where);
		}

		return $builder->get()->getResultArray();
	}

	public function getJoin($tab1, $tab2, $fields, $join)
	{
		return $this->tk_corp
			->table($tab1)
			->select($fields)
			->join($tab2, $join)
			->get()
			->getResultArray();
	}

	public function getJoinTwo($tab1, $tab2, $tab3, $fields, $join1, $join2)
	{
		return $this->tk_corp
			->table($tab1)
			->select($fields)
			->join($tab2, $join1)
			->join($tab3, $join2)
			->get()
			->getResultArray();
	}

	public function getJoinThree($tab1, $tab2, $tab3, $tab4, $fields, $join1, $join2, $join3)
	{
		return $this->tk_corp
			->table($tab1)
			->select($fields)
			->join($tab2, $join1)
			->join($tab3, $join2)
			->join($tab4, $join3)
			->get()
			->getResultArray();
	}

	public function getJoinsAll($table, $select, $joins = [])
	{
		$builder = $this->tk_corp
			->table($table)
			->select($select);

		foreach ($joins as $join) {
			$builder->join(
				$join['table'],
				$join['condition'],
				$join['type'] ?? 'inner'
			);
		}

		return $builder->get()->getResultArray();
	}

	// public function getVName($vendorcode)
	// {
	// 	return $this->tk_corp
	// 		->table('tbl_vis')
	// 		->select('*')
	// 		->where("vendor_code",$vendorcode)
    //         ->get()
	// 		->getRowArray();
	// }
	
}