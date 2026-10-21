<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function menu($menu = 'home', $submenu = 'home')
    {
        $segments = $this->request->getUri()->getSegments();
        if ($segments) {
            if (isset($segments[0]) && $segments[0] != 'page' || isset($segments[1]) && $segments[1] != 'menu') {
                return redirect()->to('/');
            }

            if (!isset($segments[2]) || !isset($segments[3])) {
                return redirect()->to('/');
            }
        }

        if (!session('isUserLoggedIn')) {
            return redirect()->to('login');
        }

        if (session('login_on') != 'supplier') {
            return redirect()->to('admins/login');
        }

        $userId                 = session('userId');
        $supplierId             = session('supplierId');
        $cond                   = ['id' => $supplierId];
        $supplierInfo           = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier', $cond);
        $cond                   = ['id' => $userId];
        $userInfo               = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
        $unread                 = $this->message_model->count_unread_msg($supplierId);

        $data['supplierName']   = $supplierInfo['supplier'];
        $data['supplierType']   = $supplierInfo['supplier_type'];
        $data['supplierInfo']   = $supplierInfo;
        $data['userInfo']       = $userInfo;
        $data['unread']         = $unread;
        $data['menu']           = $menu;
        $data['submenu']        = $submenu;

        if ($menu == 'cms' || $menu == 'violation') {

            if (!file_exists(APPPATH . "views/body/$menu/$submenu/$submenu.php")) {
                return redirect()->to('show_404');
            }

            //CMS DATA
            $vcode = @$this->concession_model->getVendorCode(session('supplierId'));
            if (@$vcode) {
                //count vendor code
                $vcodes = [];
                $vcode_count      = $this->concession_model->countVendorCode(session('supplierId'));
                $vendorcodes      = $this->concession_model->getVendorCodes(session('supplierId'));
                foreach ($vendorcodes as $rows) :
                    array_push($vcodes, $rows['vendor_code']);
                endforeach;

                $data['vcodes']  = $vcodes;
                $conditions = ['vendor_code' => $vcode];

                $data['vis_tbl'] = $this->concession_model->getfrmtblwhere('tbl_vis', $conditions);
                $data['cas_tbl'] = $this->concession_model->getalltblwhere('tbl_cas', $conditions);

                //items					
                $data['items'] = @$this->concession_model->getalltblwhere("tbl_items", $conditions);
                $data['po']    = @$this->concession_model->get_po($vcode);
                $data['deductions'] = @$this->concession_model->get_deductions($vcode);
                $data['sales'] = @$this->concession_model->get_sales($vcode);
                $data['check'] = $this->concession_model->get_check($vcode);
            }

            // $monthplus  = date('Y-m-d', strtotime('+1 month')); //date one month from the current date
            // $monthminus = date('Y-m-d', strtotime('-1 month')); //date one month after the current date
            // $where      = " p_start > '$monthminus' and p_end < '$monthplus' and vendor_code = '$vcode'";
            // $data['sales']  = $this->concession_model->getalltblwhere("tbl_item_sales_summary", $where);

            // $data['check']      = $this->concession_model->getalltblwhere("tbl_check_monitoring", "vendor_code='$vcode'");
            // $data['deductions'] = $this->concession_model->getfrmtblwhere('tbl_deductions', "vendor_code = '$vcode' and status !='Void'");
            // //END CMS DATA

            // $data['supplier'] = $this->login_model->supplier_details($_SESSION['userId']);
            // $data['title']  = html_escape($menu);
            // $data['message'] = $this->message_model->count_unread_msg();

            if ($menu == 'violation') {
                $data['cutoffs'] = $this->dtr_model->supplier_cutoffs_sched($supplierId);
            }

            echo view('template/header', $data);
            echo view('template/menu', $data);
            echo view("body/$menu/$submenu/$submenu", $data);
            echo view('template/script', $data);
            echo view("body/$menu/$submenu/" . $submenu . "_js", $data);
        } else {

            if (!file_exists(APPPATH . "views/body/$menu/$submenu.php")) {
                return redirect()->to('/');
            }

            if ($menu == 'dtr') {
                $data['cutoffs'] = $this->dtr_model->supplier_cutoffs_sched($supplierId);
            }
            if ($menu == 'masterfile') {
                $data['alturas']     = $this->dtr_model->supplier_ac_code('tk_corp');
                $data['colonnade']     = $this->dtr_model->supplier_ac_code('tk_cebo');
            }

            echo view('template/header', $data);
            echo view('template/menu', $data);
            echo view("body/$menu/$submenu", $data);
            echo view('template/script', $data);
            echo view("body/$menu/" . $menu . "_js", $data);
        }
    }

    public function show_404()
    {
        return view('body/show_404');
    }
}
