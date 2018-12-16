<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lga".
 *
 * @property int $uniqueid
 * @property int $lga_id
 * @property string $lga_name
 * @property int $state_id
 * @property string $lga_description
 * @property string $entered_by_user
 * @property string $date_entered
 * @property string $user_ip_address
 */
class Lga extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lga';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lga_id', 'lga_name', 'state_id', 'entered_by_user', 'date_entered', 'user_ip_address'], 'required'],
            [['lga_id', 'state_id'], 'integer'],
            [['lga_description'], 'string'],
            [['date_entered'], 'safe'],
            [['lga_name', 'entered_by_user', 'user_ip_address'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uniqueid' => 'Uniqueid',
            'lga_id' => 'Lga ID',
            'lga_name' => 'Lga Name',
            'state_id' => 'State ID',
            'lga_description' => 'Lga Description',
            'entered_by_user' => 'Entered By User',
            'date_entered' => 'Date Entered',
            'user_ip_address' => 'User Ip Address',
        ];
    }
}
