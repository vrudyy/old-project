<?php
require_once './mpdf60/mpdf.php';

?>

<div class="in-row">
    <?PHP
        $sow_table = '<div class="in-row" id="sow-table">';
        #$sow_table .= '<div class="in-row in-class-head-title" style="text-align: center;">'.($dic->translate("Scheme of Work")).'</div>';
        $sow_table .= '<div id="class-record-wrapper" class="in-row in-class-heading-wrapper">';
        $sow_table .= '<div class="in-fl-l in-class col2 otk">'.$dic->translate("Date").'</div>';
        $sow_table .= '<div class="in-fl-l in-class col3 otk">'.$dic->translate("Planned Syllabus").'</div>';
        $sow_table .= '<div class="in-fl-l in-class col4 otk">'.$dic->translate("Notes").'</div>';
        $sow_table .= '<div class="in-fl-l in-class col3 otk">'.$dic->translate("Teaching Resources").'</div>';
        $sow_table .= '</div>';
        $sow_table .= '<form method="post">';
        $sow_table .= '<input type="hidden" name="classId" value="'.$class->classId.'"/>';
      
        $sow_pdf = '<div style="padding: w20px 5px; background: white; color: black; text-align: center; border: 1px solid black; font-size: 22px; font-style:italic;">'.$dic->translate("Scheme Of Work").'</div>';
        $sow_pdf .= '<div style="box-sizing: border-box;padding: 10px 0;width: 20%;float: left;font-style: italic; color: white; background: #2c89ba; text-align: center; font-size: 16px;">'.$dic->translate("Date").'</div>';
        $sow_pdf .= '<div style="box-sizing: border-box;padding: 10px 0;width: 40%;float: left;font-style: italic; color: white; background: #2c89ba; text-align: center; font-size: 16px;">'.$dic->translate("Planned Syllabus").'</div>';
        $sow_pdf .= '<div style="box-sizing: border-box;padding: 10px 0;width: 40%;float: left;font-style: italic; color: white; background: #2c89ba; text-align: center; font-size: 16px;">'.$dic->translate("Notes").'</div>';
        $sow_pdf .= '<div style="border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;">';
        foreach($sows as $key => $s){
            $files = [];
            $filesIds = explode(";", $s->sowResources);

            foreach($filesIds as $value){
                if(strcmp($value, "")!=0){
                    $file = $dao->get("File", $value, "fileId");
                    array_push($files, $file);
                }
            } 
            if($key % 2 == 0){
                $sow_table .= '<div style="background:#e8f0ff;" class="in-row in-class-row-wrapper">';
            }else{
                $sow_table .= '<div style="background:white;" class="in-row in-class-row-wrapper">';
            }
            $sow_pdf .= '<div style="border-bottom: 1px solid black;">';
            $sow_pdf .= '<div style="padding: 10px 1%; width: 17.85%;float:left;">'.$s->sowWeekDate.'</div>';
            $sow_pdf .= '<div style="padding: 10px 1%;width: 37.85%;float:left;">'.$s->sowPlannedSyllabus.'</div>';
            $sow_pdf .= '<div style="padding: 10px 1%;width: 37.6%;float:left;">'.$s->sowNotes.'</div>';
            $sow_pdf .= '</div>';
            $sow_table .= '<div style="" class="in-fl-l in-class col2">'.$s->sowWeekDate.'</div>';
            $sow_table .= '<div style="text-align: left;padding: 5px;" class="in-fl-l in-class col3">'.$s->sowPlannedSyllabus.'</div>';
            $sow_table .= '<div style="text-align: left;padding: 5px;%;" class="in-fl-l in-class col4">'.$s->sowNotes.'</div>';
            $sow_table .= '<div style="padding: 0;border-right: 1px solid gray;" class="in-fl-l in-class col3">';
            $sow_table .= '<div style="text-align: left;padding: 5px;">';
            foreach($files as $f){
                $sow_table .= '<div style="overflow:hidden;" title="'.$f->fileName.'"><a download href="'.substr($f->fileLocation.$f->fileName, 23).'">'.$f->fileName.'</a></div>';
            }
            $sow_table .= '</div></div></div>';
        }
        $sow_pdf .= '</div>';
        $sow_table .= '</form>';
        $sow_table .= '</div>';
        echo $sow_table;
        
        
?>
              
            
        
    
    <script>
     var test = document.getElementById("test");
   
     test.addEventListener("click", function(e){
         console.log(e.target);
     });
    </script>
</div>
