<?php

namespace shirase\form;

use Yii;

/**
 * Class ActiveForm
 * @package shirase\form
 */
class ActiveForm extends \yii\widgets\ActiveForm {

    public function field($model, $attribute, $options = [])
    {
        if (!$model->isAttributeSafe($attribute)) return '';
        return parent::field($model, $attribute, $options);
    }
}
