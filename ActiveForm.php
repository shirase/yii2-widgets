<?php

namespace shirase\widgets;

use Yii;

/**
 * Class ActiveForm
 * @package shirase\widgets
 */
class ActiveForm extends \yii\widgets\ActiveForm {

    public function field($model, $attribute, $options = [])
    {
        if (!$model->isAttributeSafe($attribute)) return '';
        return parent::field($model, $attribute, $options);
    }
}