<?php

if($request == "view_item_sales"){
    
    echo "
    <table id='reportDatatable' class='table table-bordered table-sm dt-responsive nowrap' width='100%'>
        <thead>
            <tr align='center'>                                
                <th  width='2%'> ISR No. </th>
                <th  width='2%'> SUF ID </th>
                <th  width='5%'> Item Code </th>
                <th  width='35%'> Description </th>
                <th> Qty </th>
                <th> Net Sale <br> w/ VAT </th>
                <th> Comm <br> Rate </th>
            </tr>
        </thead>
        <tbody>";
        
            foreach($sales as $row):

                echo "
                    <tr>
                        <td> ".$row['isr_no']. "</td>
                        <td> ".$row['suf_id']. "</td>
                        <td align='right'> ".$row['item_code']. "</td>
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
?>