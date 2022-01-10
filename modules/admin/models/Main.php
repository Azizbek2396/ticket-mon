<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "main".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $race_id
 * @property int|null $distance_id
 * @property int|null $claster_id
 * @property int|null $dop_distance_id
 * @property int|null $charity_amunt
 * @property string|null $delivery_type
 * @property int|null $delivery_price
 * @property int|null $is_invalid
 * @property int|null $is_feedback
 * @property int|null $is_agree
 * @property int|null $is_paid
 * @property string|null $create_date
 * @property string|null $mod_date
 * @property string|null $promocode
 * @property int|null $type
 *
 * @property ScreenUploads[] $screenUploads
 */
class Main extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'main';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'race_id', 'distance_id', 'claster_id', 'dop_distance_id', 'charity_amunt', 'delivery_price', 'is_invalid', 'is_feedback', 'is_agree', 'is_paid', 'type'], 'integer'],
            [['create_date', 'mod_date'], 'safe'],
            [['delivery_type', 'promocode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('custom', 'ID'),
            'user_id' => Yii::t('custom', 'User ID'),
            'race_id' => Yii::t('custom', 'Race ID'),
            'distance_id' => Yii::t('custom', 'Distance ID'),
            'claster_id' => Yii::t('custom', 'Claster ID'),
            'dop_distance_id' => Yii::t('custom', 'Dop Distance ID'),
            'charity_amunt' => Yii::t('custom', 'Charity Amunt'),
            'delivery_type' => Yii::t('custom', 'Delivery Type'),
            'delivery_price' => Yii::t('custom', 'Delivery Price'),
            'is_invalid' => Yii::t('custom', 'Is Invalid'),
            'is_feedback' => Yii::t('custom', 'Is Feedback'),
            'is_agree' => Yii::t('custom', 'Is Agree'),
            'is_paid' => Yii::t('custom', 'Is Paid'),
            'create_date' => Yii::t('custom', 'Create Date'),
            'mod_date' => Yii::t('custom', 'Mod Date'),
            'promocode' => Yii::t('custom', 'Promocode'),
            'type' => Yii::t('custom', 'Type'),
        ];
    }

    /**
     * Gets query for [[ScreenUploads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScreenUploads()
    {
        return $this->hasMany(ScreenUploads::className(), ['main_id' => 'id']);
    }
}
