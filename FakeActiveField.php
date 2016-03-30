<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 30.03.16
 * Time: 12:44
 */

namespace shirase\form;


use kartik\form\ActiveField;

class FakeActiveField extends ActiveField {

    public function render($content = null) {
        return '';
    }
} 