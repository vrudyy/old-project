<?php

    //var_dump($dao);
    //$dao = new $DAO;
    $text = "";
    $invoiceText = $dao->getWhere("TextTemplate", "WHERE `clientId` = $client->clientId AND `textTemplateName` = 'Invoice';");
    if($invoiceText->textTemplateId != null){
        $text = $invoiceText->textTemplateText;
    }
    if(isset($_POST["submit"])){
        $text = $_POST["invoiceText"];
        if($invoiceText->textTemplateId != null){
            $invoiceText->textTemplateText = $text;
            $dao->update($invoiceText);
        }else{
            $invoiceText = new TextTemplate;
            $invoiceText->textTemplateName = "Invoice";
            $invoiceText->textTemplateText = $text;
            $invoiceText->clientId = $client->clientId;
            $dao->add($invoiceText);
        }
    }
?>



<div>
    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-beta.3/classic/ckeditor.js"></script>
    <form method="post">
        <textarea name="invoiceText" id="in-editor"><?php echo($text)?></textarea>
        <button style="margin: 20px 0 0 0;" class="widget" type="submit" name="submit"><?echo $dic->translate("Save")?></button>
    </form>
    
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
</div>
