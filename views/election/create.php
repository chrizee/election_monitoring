<?php
/* @var $this yii\web\View */
/* @var $pollingUnit app\models\PollingUnit */
/* @var $show bool */
/* @var $party app\models\Party */
/* @var $puResult app\models\AnnouncedPuResults */
/* @var $lga array */

?>
<h1>Add new polling unit</h1>

<?php $form = \yii\widgets\ActiveForm::begin()?>
    <div class="col-md-6">
        <?= $form->field($pollingUnit, "polling_unit_name")->input('text', ['autofocus' => true])->label("Name of polling unit") ?>
        <?= $form->field($pollingUnit, "polling_unit_number")?>
        <?= $form->field($pollingUnit, "polling_unit_description") ?>

        <div class="form-group">
            <label for="state_id">State</label>
            <select name="state_id" class="form-control" required>
                <option value="">--select state--</option>
                <?php foreach ($states as $val):?>
                    <option value="<?= $val->state_id ?>"><?= $val->state_name ?></option>
                <?php endforeach;?>
            </select>
        </div>

        <?= $form->field($pollingUnit, "lga_id")->dropDownList([], ['prompt' => "Select local govt"]) ?>
        <?= $form->field($pollingUnit, "ward_id")->dropDownList([], ['prompt' => "Select Ward"]) ?>
    </div>
    <div class="col-md-6 hidden" id="partyScore">
        <h4>Enter score for each party</h4>
        <?php foreach ($party as $pa):?>
            <?= $form->field($puResult, 'party_abbreviation[]')->hiddenInput(['value' => $pa->partyname])->label(false)?>
            <?= $form->field($puResult, 'party_score[]')->label($pa->partyname)?>
        <?php endforeach;?>


        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
<?php \yii\widgets\ActiveForm::end() ?>
<?php $baseUrl =  Yii::$app->request->baseUrl; ?>
<?php
$string = <<< JS
    $("body").on("change", "select[name='state_id']", function() {
        let state = $(this).val();
        $.post("$baseUrl/index.php/election/get-lga",{"state_id" : state}, function(result) {
            if(result !== '') {
                $("select#lga_id").empty().append(result);
            }else {
                alert("No local govt for that state.");
            }
        })
    }).on("change", "select#lga_id", function() {
        let lga = $(this).val();
        $.post("$baseUrl/index.php/election/get-wards",{"lga" : lga}, function(result) {
            if(result !== '') {
                $("select#ward_id").empty().append(result);
            }
        })
    }).on("change", "select#ward_id", function(e) {
        if($(this).val()) $("#partyScore").removeClass("hidden");
        else $("#partyScore").addClass("hidden");
    })
JS;
$this->registerJs($string)?>