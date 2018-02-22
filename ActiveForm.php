<?php

namespace shirase\form;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ActiveForm
 * @package shirase\form
 */
class ActiveForm extends \kartik\widgets\ActiveForm {

    public function field($model, $attribute, $options = [])
    {
        if(strpos($attribute, ']')!==false) {
            if ($attribute[strlen($attribute)-1]==']') {
                // relation[relation_attribute]
                $path = explode('[', str_replace(']', '', $attribute));
            } else {
                // [group]attribute
                $path = [substr($attribute, strrpos($attribute, ']')+1)];
            }
        } else {
            $path = [$attribute];
        }

        /**
         * @var $last ActiveRecord|Model
         */
        $last = $model;
        $prevAttr = null;
        foreach($path as $attr) {
            if (!$attr)
                continue;

            if ($prevAttr) {
                $relationFound = false;

                if ($last instanceof ActiveRecord) {
                    $relation = $last->getRelation($prevAttr, false);
                    if ($relation instanceof ActiveQuery) {
                        $relationFound = true;
                        $modelClass = $relation->modelClass;
                        $last = new $modelClass();
                    }
                }

                if (!$relationFound) {
                    if (!isset($last->{$prevAttr})) {
                        break;
                    }

                    $last = $last->{$prevAttr};
                }

                if (!($last instanceof Model)) {
                    break;
                }
            }

            if ($last && method_exists($last, 'isAttributeActive') && !$last->isAttributeActive($attr)) {
                return Yii::createObject([
                    'class' => 'shirase\form\FakeActiveField',
                    'model' => $model,
                    'attribute' => $attribute,
                    'form' => $this,
                ]);
            }

            $prevAttr = $attr;
        }

        if ($last instanceof Model && sizeof($path)>1 && !isset($options['labelOptions']['label'])) {
            $options['labelOptions']['label'] = $last->getAttributeLabel($path[sizeof($path)-1]);
        }

        return parent::field($model, $attribute, $options);
    }
}
