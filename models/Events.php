<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $title
 * @property string $hall
 * @property string $date
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'hall', 'date'], 'required'],
            [['title'], 'string'],
            [['session_id', 'event_id', 'hall', 'is_active'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'hall' => 'Hall',
            'event_id' => 'Event ID',
            'session_id' => 'Sesion ID',
            'date' => 'Date',
            'is_active' => 'Is Active'
        ];
    }
}
