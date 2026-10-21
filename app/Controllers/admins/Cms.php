<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

use Config\Services;

class Cms extends BaseController
{
    public function fetch_vis()
    {
        $data       = [];
        $fetch_data = $this->cms_model->getJoin(
                        "tbl_vis vis",
                        "tbl_vendor ven", 
                        "vis.vis_id, vis.vendor_code, vendor_name, mobile_no, email_add, vis.business_name, date_established, date_added, ven.status", 
                        "vis.vendor_code = ven.vendor_code"
                    );

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['vis_id'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['business_name'];
            $sub_array[] = $row['mobile_no'];
            $sub_array[] = $row['email_add'];
            $sub_array[] = $row['date_added']; //$this->cms_model->cDF("m/d/Y H:i:s",$row['date_added']); 
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_vendor()
    {
        $data       = array();
        $fetch_data = $this->cms_model->getall('tbl_vendor');

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['vendor_id'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['address'];
            $sub_array[] = $row['date_created'];// $this->cms_model->cDF("m/d/Y H:i:s", $row['date_created']);
            $sub_array[] = $row['added_by'];
            $sub_array[] = $row['status'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_cas()
    {
        $fetch_data = $this->cms_model->getall('tbl_cas');
        $data       = [];

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['cas_id'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['dated']; //$this->cms_model->cDF("m/d/Y",$row['dated']);  
            $sub_array[] = $row['concessionaire'];
            $sub_array[] = $row['period_start'];
            $sub_array[] = $row['period_end'];
            $sub_array[] = $row['area_covered'];
            $sub_array[] = $row['commission_percentage'];
            $sub_array[] = $row['date_created']; //$this->cms_model->cDF("m/d/Y",$row['date_created']); 
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_po()
    {       
        $fetch_data = $this->cms_model->getJoinThree(
                        "tbl_po_requisition req", 
                        "tbl_vendor ven", 
                        "tbl_code_company comp", 
                        "tbl_code_department dept",
                        "ref_no, req.status, date_requested, deptcode, dept_name, compcode, name, ven.vendor_code, ven.vendor_name", 
                        "req.vendor_code = ven.vendor_code", 
                        "req.compcode = comp.company_code",
                        "req.deptcode = dept.dept_code"
                    );
            
        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['ref_no'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['name'];
            $sub_array[] = $row['dept_name'];
            $sub_array[] = $row['date_requested'];//$this->cms_model->cDF("m/d/Y", $row['date_requested']);
            $sub_array[] = $row['status'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_deduction()
    {
        $fetch_data = $this->cms_model->getJoinThree(
                        "tbl_deductions ded", 
                        "tbl_vendor ven", 
                        "tbl_code_company comp", 
                        "tbl_code_department dept",
                        "ded_no, doc_no, posting_date, deduction_details, amount, ded.dept_code, dept_name, 
                            comp_code, name, ven.vendor_code, `ven.vendor_name", 
                        "ded.vendor_code = ven.vendor_code", 
                        "ded.comp_code = comp.company_code",
                        "ded.dept_code = dept.dept_code"
                    );

        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['ded_no'];
            $sub_array[] = $row['doc_no'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['name'];
            $sub_array[] = $row['dept_name'];
            $sub_array[] = $row['posting_date'];
            $sub_array[] = $row['deduction_details'];
            $sub_array[] = number_format($row['amount'],2);
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_items()
    {
        $fetch_data = $this->cms_model->getJoin(
                    "tbl_items items",
                    "tbl_vendor ven", 
                    "item_no, item_code, ext_desc, comm_rate, date_uploaded, ven.vendor_code, ven.vendor_name", 
                    "items.vendor_code = ven.vendor_code"
                    );

        $data       = array();

        //commission_percentage
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['item_no'];
            $sub_array[] = $row['item_code'];
            $sub_array[] = $row['ext_desc'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['comm_rate'];
            $sub_array[] = $row['date_uploaded'];//$this->cms_model->cDF("m/d/Y H:i:s", $row['date_uploaded']);
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_check_voucher()
    {
        $fetch_data = $this->cms_model->getall('tbl_check_voucher_line');
        $fetch_data = $this->cms_model->getJoin(
                    "tbl_check_voucher_line voucher",
                    "tbl_vendor ven", 
                    "checkv_id, cv_no, line_no, crf_no, doc_no, voucher.vendor_code, ven.vendor_name, invoice_no", 
                    "voucher.vendor_code = ven.vendor_code"
                );
        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['checkv_id'];
            $sub_array[] = $row['cv_no'];
            $sub_array[] = $row['line_no'];
            $sub_array[] = $row['crf_no'];
            $sub_array[] = $row['doc_no'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_check_details()
    {
        $fetch_data = $this->cms_model->getall('tbl_check_details');
       
        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['check_id'];
            $sub_array[] = $row['check_no'];
            $sub_array[] = $row['cv_no'];
            $sub_array[] = $row['check_date'];
            $sub_array[] = $row['bank_acct'];
            $sub_array[] = $row['bank_name'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_check_monitoring()
    {
        $fetch_data = $this->cms_model->getJoin(
                        "tbl_check_monitoring check",
                        "tbl_vendor ven", 
                        "cv_id, cv_no, cv_date, cv_status, collector_name, check.vendor_code, ven.vendor_name, bal_acct_no, total_paid_amt", 
                        "check.vendor_code = ven.vendor_code"
                    );

        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['cv_id'];
            $sub_array[] = $row['cv_no'];
            $sub_array[] = $row['cv_date'];
            $sub_array[] = $row['cv_status'];
            $sub_array[] = number_format($row['total_paid_amt'],2);
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['collector_name'];
            $sub_array[] = $row['bal_acct_no'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_sales_uploaded()
    {
        $fetch_data = $this->cms_model->getall('tbl_item_sales_uploaded_file');
        $data       = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row['suf_id'];
            $sub_array[] = $row['terms'];
            $sub_array[] = $row['cutoff_start'];//$this->cms_model->cDF("m/d/Y", $row['cutoff_start']);
            $sub_array[] = $row['cutoff_end'];//$this->cms_model->cDF("m/d/Y", $row['cutoff_end']);
            $sub_array[] = $row['file_name'];
            $sub_array[] = $row['date_uploaded'];//$this->cms_model->cDF("m/d/Y", $row['date_uploaded']);
            $sub_array[] = $row['uploaded_by'];
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function fetch_sales_summary()
    {
        $fetch_data = $this->cms_model->getJoin(
                        "tbl_item_sales_summary sales",
                        "tbl_vendor ven", 
                        "iss_no, sales_commission, net_sale, commission_percentage, sales.vendor_code, ven.vendor_name, 
                            p_start, p_end, posted_by, date_posted, suf_id", 
                        "sales.vendor_code = ven.vendor_code"
                    );
        $data       = array();

        foreach ($fetch_data as $row) {
           
            $sub_array = array();
            $sub_array[] = $row['iss_no'];
            $sub_array[] = $row['vendor_code'];
            $sub_array[] = $row['vendor_name'];
            $sub_array[] = $row['commission_percentage'];
            $sub_array[] = number_format($row['net_sale'],2);
            $sub_array[] = number_format($row['sales_commission'],2);
            $sub_array[] = $row['p_start']; 
            $sub_array[] = $row['p_end']; 
            $sub_array[] = $row['posted_by'];
            $sub_array[] = $row['date_posted']; 
            $sub_array[] = "<button class='btn btn-primary btn-xs' onclick=view_sales('" . $row['suf_id'] . "')> View Sales</button>";
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }
    
    public function import_csv()
    {
        $tableName = $this->request->getPost('table_name');

        // Get uploaded file
        $file = $this->request->getFile('csvfile');

        if (!$file || !$file->isValid()) {
            return $this->response->setBody("No file uploaded or invalid file.");
        }

        $handle = fopen($file->getTempName(), 'r');

        if (!$handle) {
            return $this->response->setBody("Failed to open file.");
        }

        $db = \Config\Database::connect();

        // Read header row
        $headers = fgetcsv($handle);

        $lineNum  = 2;
        $success  = 0;
        $failures = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) === count($headers)) {

                $rowData = array_combine($headers, $row);

                try {

                    $db->table($tableName)->insert($rowData);

                    if ($db->affectedRows() > 0) {
                        $success++;
                    } else {
                        $failures[] = [
                            'line'  => $lineNum,
                            'data'  => $rowData,
                            'error' => 'Insert failed.'
                        ];
                    }

                } catch (\Exception $e) {

                    $failures[] = [
                        'line'  => $lineNum,
                        'data'  => $rowData,
                        'error' => $e->getMessage()
                    ];
                }
            }

            $lineNum++;
        }

        fclose($handle);

        $output = "<strong>{$success} row(s) successfully imported.</strong><br>";

        if (!empty($failures)) {
            $output .= "<strong>" . count($failures) . " row(s) failed to import:</strong><br><ul>";

            foreach ($failures as $fail) {
                $output .= "<li><strong>Line {$fail['line']}:</strong> "
                    . htmlspecialchars(json_encode($fail['data']))
                    . "<br>Error: <code>{$fail['error']}</code></li>";
            }

            $output .= "</ul>";
        }

        return $this->response->setBody($output);
    }

    public function import_csv_direct()
    {
        if (isset($_FILES['csvfile']) && $_FILES['csvfile']['error'] == 0) {
            $tmp_name = $_FILES['csvfile']['tmp_name'];

            $line_num    = 2;
            $success     = 0;
            $failures    = [];
            $suf_id_map  = []; // 🆕 Mapping old IDs to inserted suf_ids

            if (($handle = fopen($tmp_name, 'r')) !== FALSE) {
                while (($arr = fgetcsv($handle, 0, '|')) !== FALSE) {
                    $column_count = count($arr);

                    if ($column_count == 11) {
                        // 11 columns = tbl_item_sales_uploaded_file
                        $old_id = trim(str_replace('"', "", $arr[0]));

                        $uploaded_file_data = array(
                            'terms'         => trim(str_replace('"', "", $arr[1])),
                            'cutoff_start'  => trim(str_replace('"', "", $arr[2])),
                            'cutoff_end'    => trim(str_replace('"', "", $arr[3])),
                            'store_id'      => trim(str_replace('"', "", $arr[4])),
                            'bu_id'         => trim(str_replace('"', "", $arr[5])),
                            'file_name'     => trim(str_replace('"', "", $arr[6])),
                            'date_uploaded' => trim(str_replace('"', "", $arr[7])),
                            'uploaded_by'   => trim(str_replace('"', "", $arr[8])),
                            'portal_dupdated' => trim(str_replace('"', "", $arr[9])),
                            'portal_status'   => trim(str_replace('"', "", $arr[10]))
                        );

                        //check if existing data
                        $exists = $this->db
                            ->where('cutoff_start', $uploaded_file_data['cutoff_start'])
                            ->where('cutoff_end', $uploaded_file_data['cutoff_end'])
                            ->where('terms', $uploaded_file_data['terms'])
                            ->where('bu_id', $uploaded_file_data['bu_id'])
                            ->where('file_name', $uploaded_file_data['file_name'])
                            ->get('tbl_item_sales_uploaded_file')->num_rows();

                        if ($exists == 0) {
                            $query = $this->db->insert('tbl_item_sales_uploaded_file', $uploaded_file_data);
                            if ($query) {
                                $insert_id = $this->db->insert_id();
                                $suf_id_map[$old_id] = $insert_id; // 🔥 Map old id to new inserted suf_id
                                $success++;
                            } else {
                                $failures[] = ['line' => $line_num, 'data' => $uploaded_file_data, 'error' => $this->db->error()];
                            }
                        }
                        $line_num++;
                    }

                    if ($column_count == 24) {
                        // 24 columns = tbl_item_sales_summary
                        $old_suf_id = trim(str_replace('"', "", $arr[1])); // Get old suf_id from file

                        if (isset($suf_id_map[$old_suf_id])) {
                            $summary_data = array(
                                'suf_id'                => $suf_id_map[$old_suf_id],
                                'vendor_code'           => trim(str_replace('"', "", $arr[2])),
                                'grp'                   => trim(str_replace('"', "", $arr[3])),
                                'commission_percentage' => trim(str_replace('"', "", $arr[4])),
                                'net_sale'              => trim(str_replace('"', "", $arr[5])),
                                'vat_input'             => trim(str_replace('"', "", $arr[6])),
                                'sales_commission'      => trim(str_replace('"', "", $arr[7])),
                                'consignment_payable'   => trim(str_replace('"', "", $arr[8])),
                                'status'                => trim(str_replace('"', "", $arr[9])),
                                'audit_status'          => trim(str_replace('"', "", $arr[10])),
                                'audited_by'            => trim(str_replace('"', "", $arr[11])),
                                'date_audited'          => trim(str_replace('"', "", $arr[12])),
                                'date_posted'           => trim(str_replace('"', "", $arr[13])),
                                'posted_by'             => trim(str_replace('"', "", $arr[14])),
                                'p_start'               => trim(str_replace('"', "", $arr[15])),
                                'p_end'                 => trim(str_replace('"', "", $arr[16])),
                                'store_id'              => trim(str_replace('"', "", $arr[17])),
                                'company_code'          => trim(str_replace('"', "", $arr[18])),
                                'dept_code'             => trim(str_replace('"', "", $arr[19])),
                                'date_generated'        => trim(str_replace('"', "", $arr[20])),
                                'generated_by'          => trim(str_replace('"', "", $arr[21])),
                                'portal_dupdated'       => trim(str_replace('"', "", $arr[22])),
                                'portal_status'         => trim(str_replace('"', "", $arr[23]))
                            );

                            //check existing data
                            $exists = $this->db
                                ->where('vendor_code', $summary_data['vendor_code'])
                                ->where('p_start', $summary_data['p_start'])
                                ->where('p_end', $summary_data['p_end'])
                                ->where('net_sale', $summary_data['net_sale'])
                                ->where('company_code', $summary_data['company_code'])
                                ->where('dept_code', $summary_data['dept_code'])
                                ->get('tbl_item_sales_summary')->num_rows();

                            if ($exists == 0) {
                                $query = $this->db->insert('tbl_item_sales_summary', $summary_data);
                                $query ? $success++ : $failures[] = ['line' => $line_num, 'data' => $summary_data, 'error' => $this->db->error()];
                            }
                        }
                        $line_num++;
                    }

                    if ($column_count == 19) {
                        // 19 columns = tbl_item_sales
                        $old_suf_id = trim(str_replace('"', "", $arr[1])); // Get old suf_id from file

                        if (isset($suf_id_map[$old_suf_id])) {
                            $data = array(
                                'suf_id'                => $suf_id_map[$old_suf_id],
                                'store_id'              => trim(str_replace('"', "", $arr[2])),
                                'company_code'          => trim(str_replace('"', "", $arr[3])),
                                'dept_code'             => trim(str_replace('"', "", $arr[4])),
                                'vendor_code'           => trim(str_replace('"', "", $arr[5])),
                                'period_start'          => trim(str_replace('"', "", $arr[6])),
                                'period_end'            => trim(str_replace('"', "", $arr[7])),
                                'commission_percentage' => trim(str_replace('"', "", $arr[8])),
                                'item_code'             => trim(str_replace('"', "", $arr[9])),
                                'item_desc'             => trim(str_replace('"', "", $arr[10])),
                                'qty'                   => trim(str_replace('"', "", $arr[11])),
                                'net_sale_with_vat'     => trim(str_replace('"', "", $arr[12])),
                                'total_disc'            => trim(str_replace('"', "", $arr[13])),
                                'status'                => trim(str_replace('"', "", $arr[14])),
                                'date_added'            => trim(str_replace('"', "", $arr[15])),
                                'added_by'              => trim(str_replace('"', "", $arr[16])),
                                'portal_dupdated'       => trim(str_replace('"', "", $arr[17])),
                                'portal_status'         => trim(str_replace('"', "", $arr[18]))
                            );

                            //checks duplicate data
                            $exists = $this->db
                                ->where('period_start', $data['period_start'])
                                ->where('period_end', $data['period_end'])
                                ->where('item_code', $data['item_code'])
                                ->where('company_code', $data['company_code'])
                                ->where('dept_code', $data['dept_code'])
                                ->where('vendor_code', $data['vendor_code'])
                                ->where('net_sale_with_vat', $data['net_sale_with_vat'])
                                ->get('tbl_item_sales')->num_rows();

                            if ($exists == 0) {
                                $query = $this->db->insert('tbl_item_sales', $data);
                                $query ? $success++ : $failures[] = ['line' => $line_num, 'data' => $data, 'error' => $this->db->error()];
                            }
                        }
                        $line_num++;
                    }
                }
                fclose($handle);
            } else {
                echo "Failed to open uploaded CSV file.";
                return;
            }
        } else {
            echo "No file uploaded or upload error.";
            return;
        }

        // Output result
        echo "<strong>$success row(s) successfully imported.</strong><br>";
        if (!empty($failures)) {
            echo "<strong>" . count($failures) . " row(s) failed to import:</strong><br><ul>";
            foreach ($failures as $fail) {
                echo "<li><strong>Line {$fail['line']}:</strong> " . json_encode($fail['data']) . "<br>Error: <code>{$fail['error']['message']}</code></li>";
            }
            echo "</ul>";
        }
    }

    public function view_item_sales()
    {
        $sufid = $this->request->getGet('sufid');
        $sales = $this->cms_model->getAllWhere("tbl_item_sales", ['suf_id' => $sufid]);
        
        echo "
            <table id='reportDatatable' class='table table-bordered table-sm dt-responsive nowrap' width='100%'>
                <thead>
                    <tr align='center'>                                
                        <th> No. </th>
                        <th> Item Code </th>
                        <th> Description </th>
                        <th> Qty </th>
                        <th> Net Sale <br> w/ VAT </th>
                        <th> Comm <br> Rate </th>
                    </tr>
                </thead>
                <tbody>";

                    $counter = 0;
                    foreach($sales as $row):

                        $counter++;
                        echo "
                            <tr>
                                <td align='center'> ".$counter. "</td>
                                <td align='center'> ".$row['item_code']. "</td>
                                <td> ".$row['item_desc']. "</td>
                                <td align='right'> ".$row['qty']. "</td>
                                <td align='right'> ".number_format($row['net_sale_with_vat'],2). "</td>
                                <td align='center'> ".$row['commission_percentage']. "</td>
                            </tr>";
                            
                    endforeach;
                    
                    echo "
                </tbody>
            <table>";

    }

    public function testEmail()
    {
        $msg    = "Testing email ni sha.";
        $value  = "mirianncaliao@gmail.com";
        $this->sendMail("CMS - Purchase Order", $msg, $value, "cms@altsportal.com");
    }

    public function sendMail($subject, $message, $email, $source)
    {
        $this->load->library('email');
        $this->email->from($source);
        $this->email->to($email);
        $this->email->set_header('From', 'cms@altsportal.com');
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            return 'Sent';
        } else {
            return 'Error';
        }
    }
}