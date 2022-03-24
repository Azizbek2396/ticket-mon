<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seats_pivot".
 *
 * @property int $id
 * @property int $seat_id
 * @property int $seat_svg_id
 */
class SeatsPivot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seats_pivot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seat_id', 'seat_svg_id'], 'required'],
            [['seat_id', 'seat_svg_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seat_id' => 'Seat ID',
            'seat_svg_id' => 'Seat Svg ID',
        ];
    }
}
