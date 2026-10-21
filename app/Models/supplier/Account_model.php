<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model {

	function __construct()
    {
        parent::__construct();
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

    public function promo_supplier_user($userId) {

        $query = $this->db->select('password, username')
                            ->get_where('promo_supplier_user', array('id' => $userId));
        return $query->row_array();
    }

    public function update_password($userId, $password) {

        $password =  password_hash($password,PASSWORD_DEFAULT);

        $data = array(
                'password'  => $password
        );

        $this->db->where('id', $userId);
        return $this->db->update('promo_supplier_user', $data);
    }

    public function update_username($userId, $username) {

        $data = array(
                'username'      => $username
        );

        $this->db->where('id', $userId);
        return $this->db->update('promo_supplier_user', $data);
    }

    public function update_profile($data) {

        $update = array(
                'address'       => $data['address'],
                'email_address' => $data['email'],
                'telephone'     => $data['telephone_no'],
                'cellphone'     => $data['cellphone_no']
        );

        $this->db->where('id', $data['userId']);
        return $this->db->update('promo_supplier_user', $update);
    }

    public function company_basic_info($userId) {

        $query = $this->db->select('photo, address, email_address, telephone, cellphone')
                            ->get_where('promo_supplier_user', array('id' => $userId));
        return $query->row_array();
    }

    public function update_photo($userId, $path) {

        $update = array(
                'photo'  => $path
        );
        
        $this->db->where('id', $userId);
        return $this->db->update('promo_supplier_user', $update);
    }
}