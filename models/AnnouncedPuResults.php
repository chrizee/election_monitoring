<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "announced_pu_results".
 *
 * @property int $result_id
 * @property string $polling_unit_uniqueid
 * @property string $party_abbreviation
 * @property int $party_score
 * @property int score
 * @property string $entered_by_user
 * @property string $date_entered
 * @property string $user_ip_address
 */
class AnnouncedPuResults extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'announced_pu_results';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['polling_unit_uniqueid', 'party_abbreviation', 'party_score', 'entered_by_user', 'date_entered', 'user_ip_address'], 'required'],
            [['party_score'], 'integer'],
            [['date_entered'], 'safe'],
            [['polling_unit_uniqueid', 'entered_by_user', 'user_ip_address'], 'string', 'max' => 50],
            [['party_abbreviation'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'result_id' => 'Result ID',
            'polling_unit_uniqueid' => 'Polling Unit Uniqueid',
            'party_abbreviation' => 'Party Abbreviation',
            'party_score' => 'Party Score',
            'entered_by_user' => 'Entered By User',
            'date_entered' => 'Date Entered',
            'user_ip_address' => 'User Ip Address',
        ];
    }

    public function formName()
    {
        return '';
    }

    public function getPoolingUnit()
    {
        return $this->hasOne(PollingUnit::class, ['uniqueid' => 'polling_unit_uniqueid']);
    }
}
