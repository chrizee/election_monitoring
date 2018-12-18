<?php
/* @var $this yii\web\View */
/* @var $announcedPu app\models\AnnouncedPuResults*/
/* @var $poolingUnit app\models\PollingUnit */
/* @var $pollingUnits app\models\PollingUnit */
/* @var $show bool */

?>
<h1>Result for individual polling unit</h1>

<?php $form = \yii\widgets\ActiveForm::begin()?>

<div class="form-group">
    <div class="form-group">
        <select name="state_id" class="form-control" required>
            <option value="">--select state--</option>
            <?php foreach ($states as $val):?>
                <option value="<?= $val->state_id ?>"><?= $val->state_name ?></option>
            <?php endforeach;?>
        </select>
    </div>

    <div class="form-group">
        <select name="lga_id" class="form-control" required>
            <option value="">--select local govt--</option>
        </select>
    </div>

    <div class="form-group">
        <select name="pooling_unit_uniqueid" class="form-control" required>
            <option value="">--select a polling unit--</option>
            <?php /*foreach ($pollingUnits as $val):*/?><!--
                <option value="<?/*= $val->uniqueid */?>"><?/*= $val->polling_unit_name */?></option>
            --><?php /*endforeach;*/?>
        </select>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-primary" value="Search">
</div>
<?php \yii\widgets\ActiveForm::end() ?>

<div class="row">
    <?php if($show) : ?>
        <div class="table-responsive">
            <?php if(count($announcedPu) > 0): ?>
                <table class="table table-bordered table-striped">
                    <caption class="caption">Unit: <strong><?= $poolingUnit->polling_unit_name?></strong></caption>
                    <thead>
                    <tr>
                        <th>Party</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcedPu as $value): ?>
                        <tr>
                            <td><?= $value->party_abbreviation ?></td>
                            <td><?= $value->party_score?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text text-lg-center">No result for <?= $poolingUnit->polling_unit_name?></p>
            <?php endif;?>
        </div>
    <?php endif; ?>
</div>
<?php $baseUrl =  Yii::$app->request->baseUrl; ?>
<?php
$string = <<< JS
    $("body").on("change", "select[name='state_id']", function() {
        let state = $(this).val();
        $.post("$baseUrl/index.php/election/get-lga",{"state_id" : state}, function(result) {
            if(result !== '') {
                $("select[name='lga_id']").empty().append(result);
            }else {
                alert("No local govt for that state.");
            }
        })
    }).on("change", "select[name='lga_id']", function(e) {
        let lga = $(this).val();
        $.post("$baseUrl/index.php/election/get-pooling-units",{"lga_id" : lga}, function(result) {
            if(result !== '') {
                $("select[name='pooling_unit_uniqueid']").empty().append(result);
            }else {
                alert("No polling unit for that local govt.");
            }
        })
    }).on("change", "select[name='pooling_unit_uniqueid']", function(e) {
        document.forms[0].submit();
    })
JS;
$this->registerJs($string)?>