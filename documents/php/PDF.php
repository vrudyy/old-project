<?php

class PDF{
    
    private $logo;
    private $companyDetails;
    private $prospectDetails;
    private $invoiceDetails;
    private $invoiceData;
    private $bankDetails;
    private $invoiceText;
    
    private $contentForPDF;
    
    public function __construct($logo, $companyDetails, $prospectDetails, $invoiceDetails,$invoiceData, $bankDetails,$invoiceText) {
        $this->logo = $logo;
    }
    
    public function setLogo(){
        $content = "";
        $content .= "<div>";
        $content .= $this->logo;
        $content .= "</div>";
        $this->logo = $content;
    }
    
    private function generateContent(){
        
    }
    
    public function getContentForPDF(){
        return $this->contentForPDF;
    }
    
}

