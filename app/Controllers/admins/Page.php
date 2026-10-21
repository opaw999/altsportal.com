<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Page extends BaseController
{
    public function menu($menu = 'email', $submenu = 'email')
    {
        if (!session('isUserLoggedIn')) {
            return redirect()->to('admins/login');
        }

        if (session('login_on') != 'admins') {
            return redirect()->to('login');
        }

        if (!file_exists(APPPATH . "views/admins/body/$menu/$submenu.php")) {
            return redirect()->to('admins/show_404');
        }

        $data['unread']     = $this->email_model->count_unread_msg();
        $data['userInfo']   = $this->login_model->adminUserInfo(session('adminId'));

        $access     = explode('|', $data['userInfo']['access_menu']);
        $access[]   = 'email';
        $access[]   = 'admin';
        if (!in_array($menu, $access)) {
            return redirect()->to('/show_404');
        }

        $data['menu']       = $menu;
        $data['submenu']    = $submenu;

        if ($menu == 'violation') {
            $data['cutoffs'] = $this->violation_model->list_of_cutoffs();
        }

        echo view('admins/template/header', $data);
        echo view('admins/template/menu', $data);
        echo view("admins/body/$menu/$submenu", $data);
        echo view('admins/template/script', $data);
        echo view("admins/body/$menu/" . $menu . "_js", $data);
    }

    public function show_404()
    {
        return view('admins/body/show_404');
    }
}
