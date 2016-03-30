<?php

namespace shirase\form;

use Yii;

/**
 * Class ActiveForm
 * @package shirase\form
 */
class ActiveForm extends \kartik\widgets\ActiveForm {

    public function field($model, $attribute, $options = [])
    {
        if (!$model->isAttributeSafe($attribute)) {
            return Yii::createObject([
                'class' => 'shirase\form\FakeActiveField',
                'model' => $model,
                'attribute' => $attribute,
                'form' => $this,
            ]);
        }
        return parent::field($model, $attribute, $options);
    }
}
