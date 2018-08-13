<?php
if(isset($_POST["newinvoice"])){
require_once './mpdf60/mpdf.php';




$invoice = "";

$address = new Address();
$currency = $dao->get("Currency", $client->currencyId, "currencyId");

$size = floor((sizeof($_POST)/4));


$text = "";
$invoiceText = $dao->getWhere("TextTemplate", "WHERE `clientId` = $client->clientId AND `textTemplateName` = 'Invoice';");
if($invoiceText->textTemplateId != null){
    $text = $invoiceText->textTemplateText;
}
if(isset($_POST["invoiceText"])){
    $text = $_POST["invoiceText"];
}



if(isset($_POST["delete-data"])){
    $data = $_POST["delete-data"];
    unset($_POST["quantity".$data]);
    unset($_POST["details".$data]);
    unset($_POST["vat".$data]);
    unset($_POST["unitprice".$data]);
}

if(isset($_POST["add-data"])){
    $_POST["quantity".$_POST["size"]] = 0;
    $_POST["details".$_POST["size"]] = "";
    $_POST["vat".$_POST["size"]] = $client->clientVatVal;
    $_POST["unitprice".$_POST["size"]] = 0;
}

$content = "";
$content .= '<form class="col9 in-invoice-wrapper" method="post" action="newinvoice.php#in-invoice">';
if(isset($_POST["invoiceText"])){
    $content .= '<input type="hidden" name="invoiceText" value="'.$text.'"/>';
}
if(isset($_POST['changeFooterText'])){    
    $content .= '<div class="in-row in-invoice-editText-wrapper">';
        $content .= '<div class="in-row in-invoice-editText">';
            $content .= '<script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-beta.3/classic/ckeditor.js"></script>';
            $content .= '<textarea name="invoiceText" id="in-editor">'.$text.'</textarea>';
            $content .= '<button style="margin: 20px 0 0 0;" class="widget" type="submit">'.$dic->translate("Change").'</button>';
        $content .= '</div>';
    $content .= '</div>';
}

    $invoice = $content;
    $invoice .= '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'"/>';
    $invoice .= '<input type="hidden" name="newinvoice" value="newinvoice" />';
        $invoice .= '<div class="in-row in-invoice">';
            // START OF THE COMPANY DETAILS
            $invoice .= '<div class="in-row in-invoice-company">';
                $invoice .= '<div class="col12 in-fl-r">';
                    $invoice .= '<div class="in-row" style="text-align: right;font-size:14px;">';
                        $address->convertToObject($contact->contactAddress);
                        $invoice .= '<div class="" style="text-align:left;">';
                            $invoice .= '<img src="'.$client->clientLogo.'" width="90px" height="90px"/>';
                        $invoice .= '</div>';
                        $invoice .= '<div style="font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $invoice .= $client->clientCompanyName;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->firstline;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->secondline;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->thirdline;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->town." ".$address->zip;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= '<b>'.$dic->translate("Email").'</b>: '.$client->clientEmail;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= '<b>'.$dic->translate("Phone").'</b>: '.$contact->contactPhone;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= '<b>'.$dic->translate("VAT").'</b>: '.$client->clientVAT;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= '<b>'.$dic->translate("Company Registration Number").'</b>: '.$client->clientRegistrationNumber;
                        $invoice .= '</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';
            $invoice .= '</div>';
            // END OF THE COMPANY DETAILS
            // START OF THE PARENT DETAILS
            $invoice .= '<div class="in-row">';
                $invoice .= '<div class="col12">';
                    $invoice .= '<div class="in-row">';
                        $address->convertToObject($parent->contactAddress);
                        $invoice .= '<div style="font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $invoice .= $parent->contactFirstName." ".$parent->contactLastName;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->firstline;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->seconddivne;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->thirdline;
                        $invoice .= '</div>';
                        $invoice .= '<div class="">';
                            $invoice .= $address->town." ".$address->zip;
                        $invoice .= '</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';
            $invoice .= '</div>';
            // END OF THE PARENT DETAILS
            // START OF THE INVOICE DETAILS
            $invoice .= '<div class="in-row in-invoice-num">';
                $invoice .= '<div class="col12 in-fl-r">';
                    $invoice .= '<div class="in-row" style="text-align: right;">';
                        $isize = sizeof($dao->listAll("Invoice", "prospectId", $prospect->prospectId))+1;
                        $invoice .= '<div style="text-align: right;font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $invoice .= $dic->translate("Invoice")." ".$student->contactFirstName." ".$student->contactLastName." 00".$isize;
                        $invoice .= '</div>';
                        $invoice .= '<div>';
                            $invoice .= '<label style="margin: 10px 10px 0 10px;height: 34px;line-height: 34px;">'.$dic->translate("Invoice Date").': </label>';
                            $invoice .= date("D j, F Y");
                        $invoice .= '</div>';
                        $invoice .= '<div>';
                            $invoice .= '<label style="margin: 10px 10px 0 10px;height: 34px;line-height: 34px;">'.$dic->translate("Payment Due By").': </label>';
                            $invoice .= date("D j, F Y");
                        $invoice .= '</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';    
            $invoice .= '</div>';        
            //END OF THE INVOICE DETAILS            
            //START OF THE PAYMENT DETAILS    
            $invoice .= '<div class="in-row in-invoice-table">';
                $invoice .= '<div class="in-row" id="in-invoice">';
                    $invoice .= '<div class="in-row" style="margin-bottom: 15px;">';
                        $invoice .= '<div class="in-row" style="font-size:13px;text-align:left;margin-top: 15px; border-bottom: 1px solid black;">';
                            $invoice .= '<div style="width: 8.33%; float: left;" class="col1">'.$dic->translate("Quantity").'</div>';
                            $invoice .= '<div style="width: 41.66%; float: left;" class="col5">'.$dic->translate("Details").'</div>';
                            $invoice .= '<div style="width: 16.66%; float: left;" class="col2">'.$dic->translate("Unit Price").' ('.$currency->currencySymbol.')'.'</div>';
                            $invoice .= '<div style="width: 8.33%; float: left;" class="col1">'.$dic->translate("VAT").' (%)</div>';
                            $invoice .= '<div style="text-align: center;width: 25%; float: left;" class="col3">'.$dic->translate("Net Sub Total").' ('.$currency->currencySymbol.')'.'</div>';
                        $invoice .= '</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';
                $netTotal = 0;
                $vat = 0;
                $total = 0;
                foreach($_POST as $key => $value){
                    
                    
                    if(strpos($key, "quantity") !== false){
                        $i = substr($key, 8);
                        $size = $i+1;
                        
                        
                        $netTotal += $_POST["quantity".$i] * $_POST["unitprice".$i];
                        $vat += $_POST["quantity".$i] * $_POST["unitprice".$i] * ($_POST["vat".$i]/100);
                       
                        
                        
                        /*
                        $invoice .= '<input style="display:none;" class="in-row in-invoice-quantity" type="text" name="quantity'.$i.'" value="'.$_POST['quantity'.$i].'" />';
                        $invoice .= '<input style="display:none;" class="in-row" type="text" name="details'.$i.'" value="'.$_POST['details'.$i].'"/>';
                        $invoice .= '<input style="display:none;" class="in-row in-invoice-unitprice" type="text" name="unitprice'.$i.'" value="'.$_POST['unitprice'.$i].'"/>';
                        $invoice .= '<input style="display:none;" class="in-row in-invoice-vat" type="text" name="vat'.$i.'" value=';
                        
                            if(strcmp($_POST["vat".$i], "") != 0){
                                $invoice .= '"'.$_POST["vat".$i].'"';
                            }else{
                                $invoice .= '"'.$client->clientVatVal.'"';
                            }
                        $invoice .= '/>';
                         */
                        
                        $invoice .= '<div style="text-align: center;float: left;width: 8.33%;">'.$_POST['quantity'.$i].'</div>';
                        $invoice .= '<div style="float: left;width: 41.66%;">'.$_POST['details'.$i].'</div>';
                        $invoice .= '<div style="text-align: center;float: left;width: 16.66%;">'.money_format("%.2n",$_POST['unitprice'.$i]).'</div>';
                        $invoice .= '<div style="text-align: center;float: left;width: 8.33%;">';
                            if(strcmp($_POST["vat".$i], "") != 0){
                                $invoice .= $_POST["vat".$i];
                            }else{
                                $invoice .= $client->clientVatVal;
                            }
                        $invoice .= '</div>';
                        $invoice .= '<div style="text-align: center;float: left;width: 23%;">'.money_format("%.2n",$_POST['quantity'.$i] * $_POST["unitprice".$i]).'</div>';
                        /*
                        $invoice .= '<div class="in-row in-invoice-table-data">';
                            $invoice .= '<div class="in-row in-invoice-data">';
                                $invoice .= '<div style="border: 1px solid lightgray; float: left; width: 8.33%;" class="col1"><input style="border:none;" class="in-row in-invoice-quantity" type="text" name="quantity'.$i.'" value="'.$_POST['quantity'.$i].'" /></div>';
                                $invoice .= '<div style="border: 1px solid lightgray;float: left; width: 41.66%;" class="col5"><input style="border:none;" class="in-row" type="text" name="details'.$i.'" value="'.$_POST['details'.$i].'"/></div>';
                                $invoice .= '<div style="border: 1px solid lightgray;float: left; width: 16.66%;" class="col2"><input style="border:none;" class="in-row in-invoice-unitprice" type="text" name="unitprice'.$i.'" value="'.$_POST['unitprice'.$i].'"/></div>';
                                $invoice .= '<div style="border: 1px solid lightgray;float: left; width: 8.33%;" class="col1">';
                                    $invoice .= '<input style="border:none;" class="in-row in-invoice-vat" type="text" name="vat'.$i.'" value=';
                                        if(strcmp($_POST["vat".$i], "") != 0){
                                            $invoice .= '"'.$_POST["vat".$i].'"';
                                        }else{
                                            $invoice .= '"'.$client->clientVatVal.'"';
                                        }
                                    $invoice .= '/>';
                                $invoice .= '</div>';
                                $invoice .= '<div style="border: 1px solid lightgray;float: left; width: 24%;" class="col3">';
                                    $invoice .= '<div style="float:left;" class="in-invoice-netSubTotal">'.$_POST['quantity'.$i] * $_POST["unitprice".$i].'</div>';
                                    $invoice .= '<button class="in-fl-r" type="submit" name="delete-data" value="'.$i.'"><i class="fas fa-times"></i></button>';
                                $invoice .= '</div>';
                            $invoice .= '</div>';
                        $invoice .= '</div>';*/
                    }
                }
                $total = $netTotal + $vat;
                $invoice .= '<input type="hidden" name="size" value="'.$size.'" />';
                
                $invoice .= '<div style="margin-top: 15px; float: left; width: 100%;">';
                    $invoice .= '<div style="clear: both;text-align: right; float: left; width: 75%;">'.$dic->translate("Net Total").'</div><div style="float: left; width: 23%;text-align:center;">'.money_format("%.2n",$netTotal).'</div>';
                    $invoice .= '<div style="clear: both;text-align: right; float: left; width: 75%;">'.$dic->translate("VAT").'</div><div style="float: left; width: 23%;text-align:center;">'.money_format("%.2n",$vat).'</div>';
                    $invoice .= '<div style="clear: both;text-align: right; float: left; width: 75%;">'.$currency->currencyShort." ".$dic->translate("Total").'</div><div style="float: left; width: 23%;text-align:center;">'.money_format("%.2n",$total).'</div>';
                $invoice .= '</div>';
                
                /*
                $invoice .= '<div class="in-row in-invoice-table-totals">';
                    $invoice .= '<div class="col12 in-fl-r" style="clear:both;text-align:right;padding:0px;">';
                        $invoice .= '<div id="netTotal" class="col3 in-fl-r">'.$netTotal.'</div>';
                        $invoice .= '<div class="col5 in-fl-r">'.$dic->translate("Net Total").'</div>';
                    $invoice .= '</div>';
                    $invoice .= '<div class="col12 in-fl-r" style="clear:both;text-align:right;padding:0px;">';
                        $invoice .= '<div id="vat" class="col3 in-fl-r">'.$vat.'</div>';
                        $invoice .= '<div class="col5 in-fl-r">'.$dic->translate("VAT").'</div>';
                    $invoice .= '</div>';
                    $invoice .= '<div class="col12 in-fl-r" style="border: 1px sodivd black; clear:both;text-align:right;padding:0px;">';
                        $invoice .= '<div id="total" class="col3 in-fl-r">'.$total.'</div>';
                        $invoice .= '<div class="col5 in-fl-r" style="font-weight: bold;">'.$currency->currencyShort." ".$dic->translate("Total").'</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';
                */
                
                
                
            $invoice .= '</div>';
            // START OF THE BANK DETAILS
            
            $invoice .= '<div style="margin: 10px 0 0 0;font-weight: bold; padding: 0 0 10px 0; font-size: 16px;">'.$dic->translate("Payment Details").'</div>';
            $invoice .= '<div style="width: 25%;clear:both;font-weight: bold; padding: 0 10px 0 0; float:left;">'.$dic->translate("Bank Name").":".'</div><div style="width: 73%;float:left;">'.$client->clientBankName.'</div>';
            $invoice .= '<div style="width: 25%;clear:both;font-weight: bold; padding: 0 10px 0 0; float: left;">'.$dic->translate("Bank Sort Code").":".'</div><div style="width: 73%;float:left;">'.$client->clientSortCode.'</div>';
            $invoice .= '<div style="width: 25%;clear:both;font-weight: bold; padding: 0 10px 0 0; float: left;">'.$dic->translate("Bank Account Number").":".'</div><div style="width: 73%;float:left;">'.$client->clientBankNumber.'</div>';
            $invoice .= '<div style="width: 25%;clear:both;font-weight: bold; padding: 0 10px 0 0; float: left;">'.$dic->translate("Payment Reference").":".'</div><div style="width: 73%;float:left;">'.$dic->translate("Invoice")." ".$student->contactFirstName." ".$student->contactLastName." 00".$isize.'</div>';
            
            /*
            $invoice .= '<div class="in-row">';
                $invoice .= '<div class="in-row">';
                    $invoice .= '<div class="in-row">'.$dic->translate("Payment Details").'</div>';
                    $invoice .= '<div class="in-row in-row-bank-details">';
                        $invoice .= '<div class="in-row">';
                            $invoice .= '<div class="col3">'.$dic->translate("Bank Name").":".'</div>';
                            $invoice .= '<div class="col4">'.$client->clientBankName.'</div>';
                        $invoice .= '</div>';
                        $invoice .= '<div class="in-row">';
                            $invoice .= '<div class="col3">'.$dic->translate("Bank Sort Code").":".'</div>';
                            $invoice .= '<div class="col4">'.$client->clientSortCode.'</div>';
                        $invoice .= '</div>';
                        $invoice .= '<div class="in-row">';
                            $invoice .= '<div class="col3">'.$dic->translate("Bank Account Number").":".'</div>';
                            $invoice .= '<div class="col4">'.$client->clientBankNumber.'</div>';
                        $invoice .= '</div>';
                        $invoice .= '<div class="in-row">';
                            $invoice .= '<div class="col3">'.$dic->translate("Payment Reference").":".'</div>';
                            $invoice .= '<div class="col4">'.$dic->translate("Invoice")." ".$student->contactFirstName." ".$student->contactLastName." 001".'</div>';
                        $invoice .= '</div>';
                    $invoice .= '</div>';
                $invoice .= '</div>';
            $invoice .= '</div>';
            */
            // END OF THE BANK DETAILS
            // START OF THE TEXT AREA
            $invoice .= '<div class="in-row in-invoice-footer-message">';
                $invoice .= '<div class="in-row">';
                    $invoice .= $text;
                $invoice .= '</div>';
            $invoice .= '</div>';
            // END OF THE TEXT AREA
        $invoice .= '</div>';
    $invoice .= '</form>';
    
    
    
    
    
    
    
    
    
    
    
    $content .= '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'"/>';
    $content .= '<input type="hidden" name="newinvoice" value="newinvoice" />';
        $content .= '<div class="in-row in-invoice">';
            // START OF THE COMPANY DETAILS
            $content .= '<div class="in-row in-invoice-company">';
                $content .= '<div class="col12 in-fl-r">';
                    $content .= '<div class="in-row" style="text-align: right;font-size:14px;">';
                        $address->convertToObject($contact->contactAddress);
                        $content .= '<div class="" style="text-align:left;">';
                            $content .= '<img src="'.$client->clientLogo.'" width="90px" height="90px"/>';
                        $content .= '</div>';
                        $content .= '<div style="font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $content .= $client->clientCompanyName;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->firstline;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->secondline;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->thirdline;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->town." ".$address->zip;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= '<b>'.$dic->translate("Email").'</b>: '.$client->clientEmail;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= '<b>'.$dic->translate("Phone").'</b>: '.$contact->contactPhone;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= '<b>'.$dic->translate("VAT").'</b>: '.$client->clientVAT;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= '<b>'.$dic->translate("Company Registration Number").'</b>: '.$client->clientRegistrationNumber;
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            // END OF THE COMPANY DETAILS
            // START OF THE PARENT DETAILS
            $content .= '<div class="in-row">';
                $content .= '<div class="col12">';
                    $content .= '<div class="in-row">';
                        $address->convertToObject($parent->contactAddress);
                        $content .= '<div style="font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $content .= $parent->contactFirstName." ".$parent->contactLastName;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->firstline;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->seconddivne;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->thirdline;
                        $content .= '</div>';
                        $content .= '<div class="">';
                            $content .= $address->town." ".$address->zip;
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            // END OF THE PARENT DETAILS
            // START OF THE INVOICE DETAILS
            $content .= '<div class="in-row in-invoice-num">';
                $content .= '<div class="col12 in-fl-r">';
                    $content .= '<div class="in-row">';
                        $content .= '<div style="text-align: right;font-weight: bold;padding: 25px 0 5px 0; font-size: 16px;">';
                            $content .= $dic->translate("Invoice")." ".$student->contactFirstName." ".$student->contactLastName." 00".$isize;
                        $content .= '</div>';
                        $content .= '<div>';
                            $content .= '<label style="margin: 10px 10px 0 10px;height: 34px;line-height: 34px;">'.$dic->translate("Invoice Date").'</label>';
                            $content .= '<div style="clear: both; width: 30%; float: right;margin: 10px 0 0 0;" class="input-group input-append date" id="datepicker1">';
                                $content .= '<input value="'.date("D j, F Y").'" required type="text" class="form-control col6" name="invoiceDate" />';
                                $content .= '<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>';
                            $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div>';
                            $content .= '<label style="margin: 10px 10px 0 10px;height: 34px;line-height: 34px;">'.$dic->translate("Payment Due By").'</label>';
                            $content .= '<div style="clear: both; width: 30%; float: right;margin: 10px 0 0 0;" class="input-group input-append date" id="datepicker2">';
                                $content .= '<input value="'.date("D j, F Y").'" required type="text" class="form-control col6" name="invoiceSend" />';
                                $content .= '<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';    
            $content .= '</div>';        
            //END OF THE INVOICE DETAILS            
            //START OF THE PAYMENT DETAILS    
            $content .= '<div class="in-row in-invoice-table">';
                $content .= '<div class="in-row" id="in-invoice">';
                    $content .= '<div class="in-row" style="margin-bottom: 15px;">';
                        $content .= '<div class="in-row" style="text-align:left;border-bottom: 1px solid black;">';
                            $content .= '<div class="col1">'.$dic->translate("Quantity").'</div>';
                            $content .= '<div class="col5">'.$dic->translate("Details").'</div>';
                            $content .= '<div class="col2">'.$dic->translate("Unit Price").' ('.$currency->currencySymbol.')'.'</div>';
                            $content .= '<div class="col1">'.$dic->translate("VAT").' (%)</div>';
                            $content .= '<div class="col3">'.$dic->translate("Net Sub Total").' ('.$currency->currencySymbol.')'.'</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
                
                foreach($_POST as $key => $value){
                    if(strpos($key, "quantity") !== false){
                        $i = substr($key, 8);
                        $size = $i+1;
                        $content .= '<div class="in-row in-invoice-table-data">';
                            $content .= '<div class="in-row in-invoice-data">';
                                $content .= '<div class="col1"><input class="in-row in-invoice-quantity" type="text" name="quantity'.$i.'" value="'.$_POST['quantity'.$i].'" /></div>';
                                $content .= '<div class="col5"><input class="in-row" type="text" name="details'.$i.'" value="'.$_POST['details'.$i].'"/></div>';
                                $content .= '<div class="col2"><input class="in-row in-invoice-unitprice" type="text" name="unitprice'.$i.'" value="'.$_POST['unitprice'.$i].'"/></div>';
                                $content .= '<div class="col1">';
                                    $content .= '<input class="in-row in-invoice-vat" type="text" name="vat'.$i.'" value=';
                                        if(strcmp($_POST["vat".$i], "") != 0){
                                            $content .= '"'.$_POST["vat".$i].'"';
                                        }else{
                                            $content .= '"'.$client->clientVatVal.'"';
                                        }
                                    $content .= '/>';
                                $content .= '</div>';
                                $content .= '<div class="col3" style="text-align: left;">';
                                    $content .= '<div style="float:left;" class="in-invoice-netSubTotal">'.$_POST['quantity'.$i] * $_POST["unitprice".$i].'</div>';
                                    $content .= '<button class="in-fl-r" type="submit" name="delete-data" value="'.$i.'"><i class="fas fa-times"></i></button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    }
                }
                $content .= '<input type="hidden" name="size" value="'.$size.'" />';
                
                $content .= '<button name="add-data" class="widget" style="padding: 5px;margin: 0 0 0 10px;">'.$dic->translate("Add").'</button>';
                
                $content .= '<div class="in-row in-invoice-table-totals">';
                    $content .= '<div class="col12 in-fl-r" style="clear:both;text-align:right;padding:0px;">';
                        $content .= '<div id="netTotal" class="col3 in-fl-r">'.$netTotal.'</div>';
                        $content .= '<div class="col5 in-fl-r">'.$dic->translate("Net Total").'</div>';
                    $content .= '</div>';
                    $content .= '<div class="col12 in-fl-r" style="clear:both;text-align:right;padding:0px;">';
                        $content .= '<div id="vat" class="col3 in-fl-r">'.$vat.'</div>';
                        $content .= '<div class="col5 in-fl-r">'.$dic->translate("VAT").'</div>';
                    $content .= '</div>';
                    $content .= '<div class="col12 in-fl-r" style="border: 1px sodivd black; clear:both;text-align:right;padding:0px;">';
                        $content .= '<div id="total" class="col3 in-fl-r">'.$total.'</div>';
                        $content .= '<div class="col5 in-fl-r" style="font-weight: bold;">'.$currency->currencyShort." ".$dic->translate("Total").'</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            // START OF THE BANK DETAILS
            $content .= '<div class="in-row">';
                $content .= '<div class="in-row">';
                    $content .= '<div class="in-row">'.$dic->translate("Payment Details").'</div>';
                    $content .= '<div class="in-row in-row-bank-details" style="font-size: 14px;">';
                        $content .= '<div class="in-row">';
                            $content .= '<div class="col3">'.$dic->translate("Bank Name").":".'</div>';
                            $content .= '<div class="col4">'.$client->clientBankName.'</div>';
                        $content .= '</div>';
                        $content .= '<div class="in-row">';
                            $content .= '<div class="col3">'.$dic->translate("Bank Sort Code").":".'</div>';
                            $content .= '<div class="col4">'.$client->clientSortCode.'</div>';
                        $content .= '</div>';
                        $content .= '<div class="in-row">';
                            $content .= '<div class="col3">'.$dic->translate("Bank Account Number").":".'</div>';
                            $content .= '<div class="col4">'.$client->clientBankNumber.'</div>';
                        $content .= '</div>';
                        $content .= '<div class="in-row">';
                            $content .= '<div class="col3">'.$dic->translate("Payment Reference").":".'</div>';
                            $content .= '<div class="col4">'.$dic->translate("Invoice")." ".$student->contactFirstName." ".$student->contactLastName." 001".'</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            // END OF THE BANK DETAILS
            // START OF THE TEXT AREA
            $content .= '<div class="in-row in-invoice-footer-message">';
                $content .= '<div class="in-row">';
                    $content .= $text;
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<button style="padding: 4px;min-width: 40px;font-size: 12px;margin: auto;float: right;" class="widget" type="submit" name="changeFooterText">'.$dic->translate("Edit").'</button>';
            // END OF THE TEXT AREA
        $content .= '</div>';
        $content .= '<div class="in-row in-invoice-finals">';
            $content .= '<button id="in-invoice-save" type="submit" name="submit" value="save" class="widget">'.$dic->translate("Save").'</button>';
            #echo '<button class="widget">'.$dic->translate("Save & Send").'</button>';
            $content .= '<button class="widget cwidget">'.$dic->translate("Cancel").'</button>';
        $content .= '</div>';
    $content .= '</form>';
    
    if(isset($_POST["submit"]) && strcmp($_POST["submit"], "save") == 0){
        // THIS IS FOR CREATING THE PDF FILE
        $size = sizeof($dao->listAll("Invoice", 'clientId', $client->clientId));
        $timestamp = time();
        $target_dir = "/var/www/i-nucleus.com/main/files/$timestamp/";
        $name = ($size+123456).".pdf";
        mkdir($target_dir);
        $filePath = $target_dir . $name;
        $mpdf = new mPDF("c");
        $mpdf->WriteHTML($invoice);
        $mpdf->Output($filePath, "F");
       
        // CREATING A NEW INVOICE IN THE DATABASE
        $invoice = new Invoice();
        $invoice->invoiceAmount = $total;
        $date = new Date();
        $date->periodToDate($_POST["invoiceDate"]);
        $invoice->invoiceDate = $date->toDB();
        $date->periodToDate($_POST["invoiceSend"]);
        $invoice->invoiceDueDate = $date->toDB();
        $invoice->invoiceDateCreated = date("Y-m-d");
        $invoice->invoiceReminderSent = 0;
        $invoice->invoiceStatus = 0;
        $invoice->invoiceThankYouSent = 0;
        
        // CREATING A NEW FILE IN THE DATABASE
        $file = new File();
        $file->clientId = $client->clientId;
        $file->fileAuthor = $contact->contactId;
        $file->fileIsDir = 0;
        $file->fileLocation = $target_dir;
        $file->fileName = $name;
        $file->fileParentFolder = "none";
        $file->fileStructureId = $prospect->prospectId;
        $file->fileStructureName = "Prospect";
        $file->fileUploadDate = date("Y-m-d");
        $fileId = $dao->add($file);
        $invoice->fileId = $fileId;
        $invoice->clientId = $client->clientId;
        $invoice->prospectId = $prospect->prospectId;
        $dao->add($invoice);
        
        // GOING BACK TO THE PROSPECT
        ob_start();
        header('Location: '.'prospect.php?sec=invoices');
        ob_end_flush();
        die();
    }
    
    echo $content;
    
    
    
}

?>




<script>
    // SCRIPT FOR THE CALCULATION OF THE INVOICE
    
    var eData = document.getElementsByClassName("in-invoice-data");
    
    function calculateInvoice(){
        var eNetTotal = document.getElementById('netTotal');
        var eVatTotal = document.getElementById("vat");
        var eTotal = document.getElementById("total");
        
        var netTotal = 0;
        var vatTotal = 0;
        var Total = 0;
        
        var quantity;
        var unitPrice;
        var vat;
        var netSubTotal;
        
        for(var i = 0; i<eData.length; i++){
            eQuantity = eData[i].getElementsByClassName("in-invoice-quantity")[0];
            eUnitPrice = eData[i].getElementsByClassName("in-invoice-unitprice")[0];
            eVat = eData[i].getElementsByClassName("in-invoice-vat")[0];
            eNetSubTotal = eData[i].getElementsByClassName("in-invoice-netSubTotal")[0];
            
            quantity = eData[i].getElementsByClassName("in-invoice-quantity")[0].value;
            unitPrice = eData[i].getElementsByClassName("in-invoice-unitprice")[0].value;
            vat = eData[i].getElementsByClassName("in-invoice-vat")[0].value;
            netSubTotal = eData[i].getElementsByClassName("in-invoice-netSubTotal")[0].textContent;
            
            eNetSubTotal.textContent = quantity * unitPrice;
            netTotal += quantity * unitPrice;
            vatTotal += quantity * unitPrice * (vat/100);
        }
        Total = netTotal + vatTotal;
        
        eNetTotal.textContent = netTotal;
        eVatTotal.textContent = vatTotal;
        eTotal.textContent = Total;
        
    }
    
    function validate(element){
        if(isNaN(element.value)){
            element.value = 0;
        }
    }
    
    for(var i = 0; i<eData.length; i++){
        eQuantity = eData[i].getElementsByClassName("in-invoice-quantity")[0];
        eUnitPrice = eData[i].getElementsByClassName("in-invoice-unitprice")[0];
        eVat = eData[i].getElementsByClassName("in-invoice-vat")[0];
        
        eQuantity.addEventListener("change", function(e){
            validate(e.target);
            calculateInvoice();
        });
        eUnitPrice.addEventListener("change", function(e){
            validate(e.target);
            calculateInvoice();
        });
        eVat.addEventListener("change", function(e){
            validate(e.target);
            calculateInvoice();
        });
    }
    
    calculateInvoice();
    
    
</script>
<?php 
    if(isset($_POST["changeFooterText"])){
        
?>
<script>
    ClassicEditor
         .create( document.querySelector( '#in-editor' ) )
         .then( editor => {
                 console.log( editor );
         } )
         .catch( error => {
                 console.error( error );
         } );
 </script>
 <?php
 }
 ?>