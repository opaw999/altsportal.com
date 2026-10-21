<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function login()
    {
        if (session('isUserLoggedIn')) {
            if (session('login_on') == 'admins') {
                return redirect()->to('admins');
            } else {
                return redirect()->to('/');
            }
        } else {
            return view('admins/template/loginAdmin');
        }
    }

    public function authenticate()
    {
        $input = $this->request->getPost();
        $username = $input['username'];
        $password = $input['password'];
        $check = $this->login_model->checkAdminAccount($username);
        if ($check) {
            if ($check['status'] === 'active') {
                if (password_verify($password, $check['password'])) {
                    $data = array(
                        'isUserLoggedIn'    => TRUE,
                        'login_on'          => 'admins',
                        'adminId'           => $check['user_no'],
                        'adminType'         => $check['user_type'],
                        'admin_username'    => $check['username'],
                        'server'            => $check['server']
                    );
                    session()->set($data);
                    echo json_encode(['process' => 'success']);
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
