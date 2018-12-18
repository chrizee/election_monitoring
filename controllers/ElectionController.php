<?php

namespace app\controllers;

use app\models\AnnouncedLgaResults;
use app\models\AnnouncedPuResults;
use app\models\Lga;
use app\models\Party;
use app\models\PollingUnit;
use app\models\States;
use app\models\Ward;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

class ElectionController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPoolingUnit() {
        $states = States::find()->all();
        if(Yii::$app->request->post("pooling_unit_uniqueid")) {
            $id = Yii::$app->request->post("pooling_unit_uniqueid");
            $announcedPu = AnnouncedPuResults::find()->where(['polling_unit_uniqueid' => $id])->all();
            $pollingUnit = PollingUnit::find()->where(['uniqueid' => $id])->one();

            return $this->render('pooling-unit', [
                'announcedPu' => $announcedPu,
                'show' => true,
                'poolingUnit' => $pollingUnit,
                'states' => $states
            ]);
        }else {
            return $this->render('pooling-unit', [
                'show' => false,
                'states' => $states,
            ]);
        }
    }

    public function actionLocalGovt() {
        $states = States::find()->all();
        //$localGovt = Lga::find()->where(['state_id' => 25])->all(); //get all lga in delta state
        if(Yii::$app->request->post("lga_id")) {
            $lgaId = Yii::$app->request->post("lga_id"); //get lga id
            $selectedLga = Lga::find()->where(["lga_id" => $lgaId])->one();
            //get all polling units uniqueid in that lga
            $pollingUnit = PollingUnit::find()->select('uniqueid')->where(['lga_id' => $lgaId])->all();
            //get all results for that lga
            $arr = [];
            foreach ($pollingUnit as $unit) {
                array_push($arr, $unit->uniqueid);
            }
            //SELECT party_abbreviation, SUM(party_score) FROM announced_pu_results WHERE polling_unit_uniqueid IN (8,9) GROUP BY party_abbreviation
            $announcedPu = AnnouncedPuResults::find()->select(['party_abbreviation', "SUM(party_score) AS party_score"])->where(['polling_unit_uniqueid' => $arr])->groupBy("party_abbreviation")->all();

            //get announced lga resilt to compare with
            $announcedLga = AnnouncedLgaResults::findAll(['lga_name' => $lgaId]);
            //print_r($announcedLga);die;
            return $this->render('lga', [
                'announcedPu' => $announcedPu,
                'announcedLga' => $announcedLga,
                'show' => true,
                //'lgas' => $localGovt,
                'states' => $states,
                'selectedLga' => $selectedLga
            ]);
        }else {
            return $this->render('lga', [
                'show' => false,
                //'lgas' => $localGovt,
                'states' => $states
            ]);
        }
    }

    public function actionCreate() {
        $pollingUnit = new PollingUnit();
        $puResult = new AnnouncedPuResults();
        $states = States::find()->all();
        if($pollingUnit->load(Yii::$app->request->post()) && $pollingUnit->validate()) {
            $time = (new \DateTime())->format("Y-m-d H:i:s");
            $user = "User";
            $pollingUnit->date_entered = $time;
            $pollingUnit->user_ip_address = Yii::$app->request->getUserIP();
            $ward_id = Yii::$app->request->post('ward_id');
            $pollingUnit->uniquewardid = Ward::findOne(['ward_id' => $ward_id])->uniqueid;
            $pollingUnit->polling_unit_id = 10;
            $pollingUnit->entered_by_user = $user;
            if($pollingUnit->save()) {
                $transaction = AnnouncedPuResults::getDb()->beginTransaction();
                try{
                    print_r(Yii::$app->request->post()['party_score']);
                    foreach (Yii::$app->request->post()['party_abbreviation'] as $key => $value) {
                        $obj = new AnnouncedPuResults();
                        $obj->polling_unit_uniqueid = $pollingUnit->uniqueid.'';
                        $obj->party_abbreviation = substr($value,0,4);
                        $obj->party_score = Yii::$app->request->post()['party_score'][$key];
                        $obj->entered_by_user = $user;
                        $obj->date_entered = $time;
                        $obj->user_ip_address = Yii::$app->request->getUserIP();
                        if($obj->validate()) {
                            $obj->save();
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash("success", "Record saved");
                    return $this->redirect(["/index.php/election/create"]);
                }catch (Exception $exception) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash("danger", "Problem saving record");
                    return $this->redirect(["/index.php/election/create"]);
                }catch (Throwable $throwable) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash("danger", "Problem saving record");
                    return $this->redirect(["/index.php/election/create"]);
                }
            }
        }
        $party = Party::find()->all();
        return $this->render("create", [
            'pollingUnit' => $pollingUnit,
            'puResult' => $puResult,
            'states' => $states,
            'party' => $party
        ]);
    }

    //ajax action
    public function actionGetWards() {
        $lga = Yii::$app->request->post('lga');
        $wards = Ward::findAll(['lga_id' => $lga]);
        $str = "<option value=''>--select ward--</option>";
        foreach ($wards as $ward) {
            $str .= "<option value='".$ward->ward_id."'>$ward->ward_name</option>";
        }
        return $str;
    }

    /*
     * get lgas from state id
     * */
    public function actionGetLga() {
        $stateId = Yii::$app->request->post("state_id");
        $lgas = Lga::findAll(['state_id' => $stateId]);
        $str = "<option value=''>--select local govt--</option>";
        foreach ($lgas as $lga) {
            $str .= "<option value='".$lga->lga_id."'>$lga->lga_name</option>";
        }
        return $str;
    }

    public function actionGetPoolingUnits() {
        $lgaId = Yii::$app->request->post("lga_id");
        $poolingUnits = PollingUnit::findAll(['lga_id' => $lgaId]);
        $str = "<option value=''>--select pooling unit--</option>";
        foreach ($poolingUnits as $units) {
            $str .= "<option value='".$units->uniqueid."'>$units->polling_unit_name</option>";
        }
        return $str;
    }

}
