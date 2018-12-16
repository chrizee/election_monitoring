<?php
/* @var $this yii\web\View */
/* @var $lgas app\models\Lga*/
/* @var $selectedLga app\models\Lga*/
/* @var $announcedPu app\models\AnnouncedPuResults*/
/* @var $show bool */

?>
<h1>Result for Local govts </h1>

<?php $form = \yii\widgets\ActiveForm::begin()?>
<?php $colors = [
        'PDP' => "#dddddd",
        'DPP' => "#ff9988",
        'ACN' => "#88ff99",
        'PPA' => "#9988ff",
        'CDC' => "#ff8899",
        'JP' => "#8899ff",
        'ANPP' => "#99ff88",
        'LABO' => "#998833",
        'CPP' => "#338899"
];?>
<div class="form-group">
    <select name="lga" class="form-control">
        <option value="">--select a local govt--</option>
        <?php foreach ($lgas as $lga):?>
            <option value="<?= $lga->lga_id ?>"><?= $lga->lga_name ?></option>
        <?php endforeach;?>
    </select>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-primary" value="Search">
</div>
<?php \yii\widgets\ActiveForm::end() ?>

<div class="row">
    <?php if($show) : ?>
        <div class="table-responsive col-md-6">
            <?php if(count($announcedPu) > 0): ?>
                <table class="table table-bordered table-striped">
                    <caption class="caption text-lg-center text-center">search result for <strong><?= $selectedLga->lga_name?></strong></caption>
                    <thead>
                    <tr>
                        <th>Party</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcedPu as $value): ?>
                        <tr style="background: <?= $colors[$value->party_abbreviation]?>">
                            <td><?= $value->party_abbreviation ?></td>
                            <td><?= $value->party_score?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text text-lg-center">No search result for <?= $selectedLga->lga_name?></p>
            <?php endif;?>
        </div>

        <div class="table-responsive col-md-6">
            <?php if(count($announcedLga) > 0): ?>
                <table class="table table-bordered table-striped">
                    <caption class="caption text-lg-center text-center">Announced result for <strong><?= $selectedLga->lga_name?></strong></caption>
                    <thead>
                    <tr>
                        <th>Party</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcedLga as $value): ?>
                        <tr style="background: <?= $colors[$value->party_abbreviation]?>">
                            <td><?= $value->party_abbreviation ?></td>
                            <td><?= $value->party_score?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text text-lg-center">No announced for <?= $selectedLga->lga_name?></p>
            <?php endif;?>
        </div>
    <?php endif; ?>
</div>
