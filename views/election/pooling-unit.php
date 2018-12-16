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
    <select name="pooling_unit_uniqueid" class="form-control">
        <option value="">--select a polling unit--</option>
        <?php foreach ($pollingUnits as $val):?>
            <option value="<?= $val->uniqueid ?>"><?= $val->polling_unit_name ?></option>
        <?php endforeach;?>
    </select>
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
