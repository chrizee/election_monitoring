<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "party".
 *
 * @property int $id
 * @property string $partyid
 * @property string $partyname
 */
class Party extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'party';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partyid', 'partyname'], 'required'],
            [['partyid', 'partyname'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'partyid' => 'Partyid',
            'partyname' => 'Partyname',
        ];
    }
}
