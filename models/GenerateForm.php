<?php

namespace app\models;

use yii\base\Model;

/**
 * Class TreeForm
 * @property integer $numberOfNodes Количество узлов для генерации
 *
 * @package app\models
 */
class GenerateForm extends Model
{
    public $numberOfNodes = 20;

    public function attributeLabels()
    {
        return [
            'numberOfNodes' => 'Количество узлов для генерации',
        ];
    }

    public function rules()
    {
        return [
            ['numberOfNodes', 'required'],
            ['numberOfNodes', 'integer', 'min' => 1, 'max' => 5000],
        ];
    }
}
