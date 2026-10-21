<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function login()
    {
        if (session('isUserLoggedIn')) {
            if (session('login_on') == 'supplier') {
                return redirect()->to('/');
            } else {
                return redirect()->to('admins');
            }
        } else {
            return view('template/loginPage');
        }
    }

    public function authenticate()
    {
        $input = $this->request->getPost();
        $username = $input['username'];
        $password = $input['password'];
        $check = $this->login_model->checkSupplierAccount($username);
        if ($check) {
            if ($check['status'] === '1') {

                if (password_verify($password, $check['password'])) {
                    $dateFrom   = $check['date_from'];
                    $dateTo     = $check['date_to'];
                    $dateExt    = date("Y-m-d", strtotime($dateTo . "+3 days"));

                    if ((strtotime($dateFrom) <= strtotime($this->today)) && (strtotime($dateExt) >= strtotime($this->today))) {

                        $data = ['last_login' => $this->timestamp];
                        if (!$check['first_login']) {
                            $data['first_login'] = $this->timestamp;
                        }

                        $cond   = ['id' => $check['id']];
                        $update = $this->login_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);

                        $cond       = ['id' => $check['supplier_id']];
                        $supplier   = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier', $cond);

                        $data = [
                            'isUserLoggedIn'    => TRUE,
                            'login_on'          => 'supplier',
                            'userId'            => $check['id'],
                            'supplierId'        => $check['supplier_id'],
                            'username'          => $check['username'],
                            'supplierType'      => $supplier['supplier_type']
                        ];

                        session()->set($data);

                        echo json_encode(['process' => 'success']);
                    } else {
                        echo json_encode(['process' => 'error contract']);
                    }
                } else {
                    echo json_encode(['process' => 'failed']);
                }
            } else {
                echo json_encode(['process' => 'inactive']);
            }
        } else {
            echo json_encode(['process' => 'miss']);
        }
    }

    public function anonymous()
    {
        if (session('isUserLoggedIn')) {
            if (session('login_on') == 'supplier') {
                return redirect()->to('/');
            } else {
                return redirect()->to('admins');
            }
        } else {
            return view('template/anonymous');
        }
    }

    public function authenticate_anonymous()
    {
        $input = $this->request->getPost();
        $username = $input['username'];
        $password = $input['password'];
        $check = $this->login_model->checkSupplierAccount($username);
        if ($check) {
            if ($check['status'] === '1') {
                if ($password == 'Altsportal2019') {
                    $dateFrom   = $check['date_from'];
                    $dateTo     = $check['date_to'];
                    $dateExt    = date("Y-m-d", strtotime($dateTo . "+3 days"));

                    if ((strtotime($dateFrom) <= strtotime($this->today)) && (strtotime($dateExt) >= strtotime($this->today))) {

                        $data = ['last_login' => $this->timestamp];
                        if (!$check['first_login']) {
                            $data['first_login'] = $this->timestamp;
                        }

                        $cond   = ['id' => $check['id']];
                        $update = $this->login_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);

                        $cond       = ['id' => $check['supplier_id']];
                        $supplier   = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier', $cond);

                        $data = [
                            'isUserLoggedIn'    => TRUE,
                            'login_on'          => 'supplier',
                            'userId'            => $check['id'],
                            'supplierId'        => $check['supplier_id'],
                            'username'          => $check['username'],
                            'supplierType'      => $supplier['supplier_type']
                        ];

                        session()->set($data);

                        echo json_encode(['process' => 'success']);
                    } else {
                        echo json_encode(['process' => 'error contract']);
                    }
                } else {
                    echo json_encode(['process' => 'failed']);
                }
            } else {
                echo json_encode(['process' => 'inactive']);
            }
        } else {
            echo json_encode(['process' => 'miss']);
        }
    }
}
