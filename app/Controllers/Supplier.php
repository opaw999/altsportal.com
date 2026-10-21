<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

	function __construct()
    {
        parent::__construct(); 
        $this->load->model('supplier_model');
    }

    public function view_penalty() {

    	$fetch = $this->input->post(NULL, TRUE);
        $vendor_code    = $fetch['vendor_code'];
        $store          = $fetch['store'];
        $category_id    = $fetch['category_id'];
        $date_from      = $fetch['date_from'];
        $date_to        = $fetch['date_to'];

    	$penalty = $this->supplier_model->penalty_details($vendor_code, $store, $category_id, $date_from, $date_to);
     
    	$data['penalty'] = $penalty;
    	$data['penalty_perday'] = $this->supplier_model->penalty_perday($penalty->id);
        $data['vendor'] = $this->supplier_model->supplier_name($penalty->vendor_id);
        $data['store'] = $this->supplier_model->business_unit($penalty->store);
        $data['category'] = $this->supplier_model->category($penalty->category_id);
        $data['request'] = "view_penalty";
        $this->load->view('body/modal_response', $data);
    }

}