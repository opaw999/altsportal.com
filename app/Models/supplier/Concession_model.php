<?php

namespace App\Models\supplier;

use App\Models\BaseModel;

class Concession_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

	//reusable table
	// public function cDF($changeformatto,$date)
	// {	
	// 	if($date != '0000-00-00'){
	// 		$convert_date = new DateTime($date); 		
	// 		return $convert_date->format($changeformatto);
	// 	}else{
	// 		return '';
	// 	}
	// }

	public function getOneField($field, $table, $where)
	{
		return $this->db->table($table)
			->select($field)
			->where($where)
			->get()
			->getRow();  
	}

	public function getfrmtblwhere($table,$conditions)
	{		
		$query = $this->tk_corp
			->table($table)           
            ->where($conditions)
            ->get();
		return $query->getRowArray();
	}
		
	public function getalltblwhere($table,$conditions)
	{		
		$query = $this->tk_corp
			->table($table)           
            ->where($conditions)
            ->get();
		return $query->getResultArray();
	}

	public function getallfromtablewhereorderby($table,$conditions,$value,$order)
	{
		return $this->tk_corp->table($table)
			->where($conditions)
			->orderBy($value, $order)
			->get()
			->getResultArray();
	}

	public function getVendorCode($supplierId){		
		$query = $this->tk_corp
			->table('tbl_supplier_vendor_codes')           
            ->where('vendor_id', $supplierId)
            ->get();
		return $query->getRowArray()['vendor_code'];
	}

	public function getVendorName($vcode)
	{
		$query = $this->tk_corp
			->table('tbl_supplier_vendor_codes')  
			->select("vendor_name")          
            ->where('vendor_code', $vcode)
            ->get();
		return $query->getRow()->vendor_name;
	}

	public function countVendorCode($supplierId){
		$query = $this->tk_corp
			->table('tbl_supplier_vendor_codes') 
			->select("count(vendor_code) as countcode")          
            ->where('vendor_id', $supplierId)
            ->get();
		return $query->getRowArray()['countcode'];
	}

	public function getVendorCodes($supplierId){
		$query = $this->tk_corp
			->table('tbl_supplier_vendor_codes')           
            ->where('vendor_id', $supplierId)
            ->get();
		return $query->getResultArray();
	}

	public function getstores_concat($vcode)
	{		  
		$query = $this->tk_corp
			->table('tbl_vendor_terms_setup') 
			->select("group_concat(DISTINCT store SEPARATOR ', ') as stores")       
			->join('tbl_stores', 'tbl_vendor_terms_setup.store_id = tbl_stores.store_id', 'inner')   
            ->where("vendor_code",$vcode)
            ->get();
		return $query->getRowArray()['stores'];
	}

	public function getstore_terms($vcode)
	{	
		$query = $this->tk_corp
			->table('tbl_vendor_terms_setup') 
			->select("aname, terms")       
			->join('tbl_stores', 'tbl_vendor_terms_setup.store_id = tbl_stores.store_id', 'inner')   
            ->where("vendor_code",$vcode)
            ->get();
		return $query->getResultArray();
	}
	
	public function get_deductions($vcode)
	{
		$query = $this->db->table('tbl_deductions')
				->select('
					ded_no,
					doc_no, 
					vendor_code, 
					tbl_deductions.comp_code, 
					name, 
					tbl_deductions.dept_code, 
					dept_name, 
					posting_date, 
					deduction_type, 
					description, 
					deduction_details, 
					amount, 
					status
				')
				->join('tbl_deduction_types', 'tbl_deductions.deduction_type = tbl_deduction_types.accountno')
				->join('tbl_code_company', 'tbl_deductions.comp_code = tbl_code_company.company_code')
				->join('tbl_code_department', 'tbl_deductions.dept_code = tbl_code_department.dept_code')
				->where('vendor_code', $vcode)
				->orderBy('posting_date', 'DESC')
				->get();
		return $query->getResultArray();
	}

	public function get_po($vcode){
		$query = $this->db->table('tbl_po_requisition as tbl_req')
				->select('
					po_no, tbl_req.ref_no, tbl_req.vendor_code, tbl_req.compcode, name, tbl_req.deptcode, dept_name,
					DATE_FORMAT(date_requested, \'%Y-%m-%d %h:%i %p\') AS date_requested, requested_by, status, date_received, received_status
				')
				->join('tbl_code_company', 'tbl_req.compcode = tbl_code_company.company_code', 'INNER')
				->join('tbl_code_department', 'tbl_req.deptcode = tbl_code_department.dept_code', 'INNER')
				->join('tbl_po_receiving', 'tbl_req.ref_no = tbl_po_receiving.ref_no', 'LEFT')
				->where('tbl_req.vendor_code', $vcode)
				->orderBy('po_no', 'DESC')
				->get();
		return $query->getResultArray();
	}

	public function get_sales($vcode){
		$query = $this->db->table('tbl_item_sales_summary as tbl_sales')
				->select('tbl_sales.vendor_code, tbl_sales.company_code, tbl_code_company.shortcut, tbl_sales.dept_code, dept_name,
				iss_no, suf_id, tbl_sales.grp, net_sale, vat_input, sales_commission, consignment_payable, status, audit_status, 
				p_start, p_end, DATE_FORMAT(date_posted, \'%Y-%m-%d\') AS date_posted
				')
				->join('tbl_code_company', 'tbl_sales.company_code = tbl_code_company.company_code')
				->join('tbl_code_department', 'tbl_sales.dept_code = tbl_code_department.dept_code')
				->where('tbl_sales.vendor_code', $vcode)
				->orderBy('p_start', 'DESC')
				->get();
		return $query->getResultArray();
	}

	public function get_sales_issno($iss_no){
		$query = $this->db->table('tbl_item_sales_summary as tbl_sales')
				->select('iss_no, suf_id, vendor_code, commission_percentage, net_sale, vat_input, sales_commission, 
				consignment_payable, status, tbl_sales.company_code, name, tbl_sales.dept_code, dept_name,
				DATE_FORMAT(p_start, \'%m/%d/%Y\') AS p_start, DATE_FORMAT(p_end, \'%m/%d/%Y\') AS p_end
				')
				->join('tbl_code_company', 'tbl_sales.company_code = tbl_code_company.company_code')
				->join('tbl_code_department', 'tbl_sales.dept_code = tbl_code_department.dept_code')
				->where('tbl_sales.iss_no', $iss_no)
				->get();
		return $query->getRow();
	}

	public function get_sales_division($type, $vcode)
	{		
		// $this->db->select('distinct(item_division)'); 	
		// $this->db->where($where);		
		// $query = $this->db->get("tbl_items");
		// return $query->result_array();

		if($type =="special"){
			$query = $this->tk_corp
				->table('tbl_items')     
				->select('distinct(item_division), division_name') 
				->join('tbl_item_division', 'tbl_items.item_division = tbl_item_division.division_code') 
				->where('vendor_code', $vcode)
				->like('item_division', 'X', 'after')
                ->like('item_dept_code', 'Y', 'after')
				->get();
			return $query->getResultArray();			 

			//$condition = "vendor_code = '".$vcode."' and item_division like 'X%' and item_dept_code like 'Y%'";
		}else{
        	//$condition = "vendor_code = '".$sales->vendor_code."' and item_division not like 'X%' and item_dept_code not like 'Y%' ";
			$query = $this->tk_corp
				->table('tbl_items')     
				->select('distinct(item_division), division_name')  
				->join('tbl_item_division', 'tbl_items.item_division = tbl_item_division.division_code')
				->where('vendor_code', $vcode)
				->get();
			return $query->getResultArray();
		}	

		// SELECT distinct(item_division), division_name
		// FROM tbl_items
		// INNER JOIN tbl_item_division ON tbl_items.item_division = tbl_item_division.division_code
		// WHERE
		// vendor_code = 'S4607' and item_division not like 'X%' and item_dept_code not like 'Y%'
	}

	public function get_item_sales_report($div,$issno,$vcode)
	{		
		// $this->db->select('tis.vendor_code, tis.qty, tis.item_code, tis.item_desc, tis.net_sale_with_vat, tis.total_disc '); 
		// $this->db->from("tbl_item_sales as tis");
		// $this->db->join("tbl_items", "tbl_items.item_code = tis.item_code", 'inner');	
		// $this->db->where("tis.suf_id = '$issno' and item_division = '$div' and tbl_items.vendor_code = '$vcode' ");		
		// $query = $this->db->get();
		// return $query->result_array();
		$query = $this->db->table('tbl_item_sales as tis')
				->select('tis.vendor_code, tis.qty, tis.item_code, tis.item_desc, tis.net_sale_with_vat, tis.total_disc')				
				->join('tbl_items', 'tbl_items.item_code = tis.item_code', 'INNER')
				->where('tis.suf_id', $issno)
				->where('item_division', $div)
				->where('tbl_items.vendor_code', $vcode)
				->get();
		return $query->getResultArray();
	}

	public function get_po_request($refno){
		$query = $this->db->table('tbl_po_requisition as tbl_req')
				->select('po_no, tbl_req.ref_no, tbl_req.vendor_code, requested_by, status,
					tbl_req.compcode, name, tbl_req.deptcode, dept_name,
					DATE_FORMAT(date_requested, \'%M %d, %Y\') AS date_requested
				')				
				->join('tbl_code_company', 'tbl_req.compcode = tbl_code_company.company_code', 'INNER')
				->join('tbl_code_department', 'tbl_req.deptcode = tbl_code_department.dept_code', 'INNER')
				->where('ref_no', $refno)
				->get();
		return $query->getRow();
		// %h:%i %p
	}

	public function get_po_receive($refno){
		$query = $this->db->table('tbl_po_receiving as tbl_rec')
				->select('tbl_rec.ref_no, tbl_rec.vendor_code, 
					tbl_rec.compcode, name, tbl_rec.deptcode, dept_name,
					DATE_FORMAT(date_received, \'%M %d, %Y\') AS date_received, received_status
				')				
				->join('tbl_code_company', 'tbl_rec.compcode = tbl_code_company.company_code', 'INNER')
				->join('tbl_code_department', 'tbl_rec.deptcode = tbl_code_department.dept_code', 'INNER')
				->where('ref_no', $refno)
				->get();
		return $query->getRow();
	}

	public function get_check($vcode){
		$query = $this->db->table('tbl_check_monitoring as tbl_check')
				->select('tbl_check.cv_no, total_paid_amt, check_status, check_release_date, bank_name, check_date, check_no, vendor_code ')				
				->join('tbl_check_details', 'tbl_check.cv_no = tbl_check_details.cv_no', 'INNER')
				->where('vendor_code', $vcode)
				->orderBy('cv_date', 'DESC')
				->get();
		return $query->getResultArray();
	}
}

//defined('BASEPATH') OR exit('No direct script access allowed');
// class Concession_model extends CI_Model 
// {
//     public $timekeeping2;
//     public $pis2;

// 	function __construct()
//     {
//         parent::__construct();
//         date_default_timezone_set('Asia/Manila');
//     }
	
// 	public function getallfrmtbl($table)
// 	{		
// 		$query = $this->db->get($table);
// 		return $query->result_array();	
// 	}	
	

	

	

	
// 	public function getDivision($code)
// 	{
// 		$this->db->select("division_name");
// 		$this->db->where("division_code",$code); 
// 		$query = $this->db->get("tbl_item_division");
// 		return $query->row()->division_name;
// 	}
	



	

	
// 	public function insert_table($data,$table){
// 		$query = $this->db->insert($table, $data);
// 		if($query){
// 		  	return $this->db->insert_id();
// 		}else{ 
// 			return "FALSE"; 
// 		}  
// 	}
	
// }