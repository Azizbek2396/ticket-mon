<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $img
 * @property int|null $price
 * @property string|null $desc
 * @property string|null $create_date
 * @property string|null $mod_date
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'integer'],
            [['desc'], 'string'],
            [['create_date', 'mod_date'], 'safe'],
            [['title', 'img'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('custom', 'ID'),
            'title' => Yii::t('custom', 'Title'),
            'img' => Yii::t('custom', 'Img'),
            'price' => Yii::t('custom', 'Price'),
            'desc' => Yii::t('custom', 'Desc'),
            'create_date' => Yii::t('custom', 'Create Date'),
            'mod_date' => Yii::t('custom', 'Mod Date'),
        ];
    }
}
