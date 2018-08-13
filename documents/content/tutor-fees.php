<?
$currency = $dao->get("Currency", $client->currencyId, "currencyId");
?>
<div class="in-row tutor-fees-wrapper">
    <div class="in-row tutor-fees">
        <form method="post">
            <div class="in-row" style="border-bottom: 1px solid gray;padding: 15px 0 30px 0;">
                <label><?php echo($dic->translate("Standard Hourly Rate")." (".$currency->currencySymbol.")")?></label>
                <input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId)?>"/>
                <input type="text" name="rate" value="<?php echo(money_format('%(#2n', $tutor->tutorRate))?>"/>
            </div>
            <div class="in-row" style="text-align: center;">
                <?php
                    echo $dic->translate("Hourly Rate per assigned class.");
                ?>
            </div>
            <div class="in-row" style="text-align: center;font-size: 12px; font-style: italic;">
                <?php
                    echo "<br>";
                    echo $dic->translate("Leave blank if Standard Hourly Rate applies.");
                ?>
            </div>
            <div class="in-row" style="text-align: center;margin: 45px 0;">
                <?php
                    echo $dic->translate("The tutor has no assigned classes.");
                ?>
            </div>
            <button class="widget" type="submit" name="tutor-rate" value="change"><?php echo($dic->translate("Save"))?></button>
        </form>
    </div>
</div>


