<?php

class cmfFormTextarea extends cmfFormText {

    protected function init($o) {
        $this->setFilter('cmfFilterLenMax', cmfFormTextareaMax);
        parent::init($o);
    }

    public function html($param, $style = '') {
        return '<textarea name="' . ($name = $this->getId()) . '" id="' . $name . '" ' . $style . '>' . cmfString::specialchars($this->getValue()) . '</textarea>';
    }

}

class cmfFormTextareaMain extends cmfFormTextarea {
    public function htmlError($s=null, $s2=null) {
        if($this->isErrorView()) {
            return '<span class="formwrap_area">'. $s . $s2 .'</span>';
        } else {
            return $s . $s2;
        }
    }
}

class cmfFormTextareaSmall extends cmfFormTextarea {
    public function htmlError($s=null, $s2=null) {
        if($this->isErrorView()) {
            return '<span class="formwrap_area_small">'. $s . $s2 .'</span>';
        } else {
            return $s . $s2;
        }
    }
}

class cmfFormTextareaWysiwyng extends cmfFormText {

    private $path;
    private $number;

    function __construct($path, $number, $o = null) {
        $this->path = $path;
        $this->number = $number;
        parent::__construct($o);
    }

    public function html($param, $height = null) {
        return cmfWysiwyng::html($this->path, $this->number, $this->getId(), $this->getValue(), $height);
    }

    public function jsUpdate() {
        return parent::jsUpdate() . cmfWysiwyng::jsUpdate($this->getId(), $this->getValue());
    }

}

?>