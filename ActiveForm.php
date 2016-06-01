<?php

namespace shirase\form;

use Yii;
use yii\db\ActiveQuery;

/**
 * Class ActiveForm
 * @package shirase\form
 */
class ActiveForm extends \kartik\widgets\ActiveForm {

    public function field($model, $attribute, $options = [])
    {
        $path = explode('[', str_replace(']', '', $attribute));

        /**
         * @var $last \yii\db\ActiveRecord
         */
        $last = null;
        $prevAttr = null;
        foreach($path as $attr) {
            if ($last) {
                $relation = $last->getRelation($prevAttr, false);
                if ($relation instanceof ActiveQuery) {
                    $modelClass = $relation->modelClass;
                    $last = new $modelClass();
                } else {
                    $last = $last->{$prevAttr};
                }
            } else {
                $last = $model;
            }

            if (!$last->isAttributeActive($attr)) {
                return Yii::createObject([
                    'class' => 'shirase\form\FakeActiveField',
                    'model' => $model,
                    'attribute' => $attribute,
                    'form' => $this,
                ]);
            }
            $prevAttr = $attr;
        }

        if (sizeof($path)>1 && !isset($options['labelOptions']['label'])) {
            $options['labelOptions']['label'] = $last->getAttributeLabel($path[sizeof($path)-1]);
        }

        return parent::field($model, $attribute, $options);
    }
}
