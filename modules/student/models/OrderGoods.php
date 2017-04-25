<?php

namespace app\modules\student\models;

use Yii;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property string $id
 * @property string $order_sn
 * @property string $name
 * @property string $coverurl
 * @property string $price
 * @property string $promotion_price
 * @property integer $total_count
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_sn', 'name', 'coverurl', 'promotion_price', 'total_count'], 'required'],
            [['price', 'promotion_price'], 'number'],
            [['total_count'], 'integer'],
            [['order_sn'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 100],
            [['coverurl'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '订单编号',
            'name' => '商品/课程名',
            'coverurl' => '商品/课程图片 一张',
            'price' => '商品/课程价格',
            'promotion_price' => '优惠价、最终销售价格',
            'total_count' => '购买的产品/课程数量',
        ];
    }
}
