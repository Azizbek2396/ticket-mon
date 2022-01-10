<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "saver".
 *
 * @property int $id
 * @property int $event_id
 * @property string|null $seat_id
 * @property string|null $comment
 */
class Saver extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $cnt;
    public static function tableName()
    {
        return 'saver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'required'],
            [['event_id'], 'integer'],
            [['seat_id'], 'string', 'max' => 50],
            [['comment','place_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('custom', 'ID'),
            'event_id' => Yii::t('custom', 'Мероприятие'),
            'seat_id' => Yii::t('custom', 'Месте'),
            'comment' => Yii::t('custom', 'Комментарий'),
            'place_title' => Yii::t('custom', 'Место'),
        ];
    }
}
