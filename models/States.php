<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "states".
 *
 * @property int $state_id
 * @property string $state_name
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['state_id', 'state_name'], 'required'],
            [['state_id'], 'integer'],
            [['state_name'], 'string', 'max' => 50],
            [['state_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'state_id' => 'State ID',
            'state_name' => 'State Name',
        ];
    }
}
