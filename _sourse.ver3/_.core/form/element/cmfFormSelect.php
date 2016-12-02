<?php


class cmfFormSelect extends cmfFormElement {

    private $multiple = false;
    private $valueOld = null;

    private $options = null;

    public function reset() {
        unset($this->value['sel']);
    }

    function getTypeElement() {
        return 'select';
    }

    public function resetElement() {
        $this->value = null;
    }

    public function serMultiple() {
        $this->multiple = true;
    }

    public function setOptions($key, $name, $value) {
        $this->value['options'][$key] = get2($this->value, 'options', $key) . " $name=\"$value\"";
        $this->value['js_options'][$key][$name] = $value;
    }

    public function getOptions($key) {
        return get2($this->value, 'js_options', $key, array());
    }

    private function getOptionsStr($key) {
        return get2($this->value, 'options', $key);
    }


    // заполнение доп данными для массивов
    public function addElement($key, $name) {
        $values = &$this->getValues();
        if(is_array($key))
             $this->addElementArray($values['array'], $key, $name);
        else $values['array'][$key] = $name;
    }
    // рекурсия добавления в массивах даных
    protected function addElementArray(&$values, $key, $name) {
        if(count($key)==1) $values[current($key)] = $name;
        else {
            $item = array_shift($key);
            $this->addElementArray($values[$item], $key, $name);
        }
    }


    // выбор активного элемента | установка значения
    public function select($value, $data=null) {
        $this->valueOld = $value;
        $value = $this->getValueFormat($value);
        $values = &$this->getValues();

        if(!$this->multiple) {
            $value = $this->reformAll($value);
            unset($values['sel']);
        }
        if(isset($values['array'])) {
            $this->selectElementFind($values['array'], $values['sel'], $value);
            if(!is_array($values['sel']) or !sizeof($values['sel']))
                unset($values['sel']);
        }
    }
    // рекурсивный поиск даннхы в массивах даных
    protected function selectElementFind(&$values, &$sel, $find) {
        foreach($values as $key=>$value) {
            if(is_array($value)) {
                $this->selectElementFind($values[$key], $sel[$key], $find);
                if(!is_array($sel[$key]) or !sizeof($sel[$key])) unset($sel[$key]);
            } else {
                if(is_array($find)) {
                    if(in_array($key, $find, false)) $sel[$key] = 1;
                } elseif($key==$find) {
                    $sel[$key] = 1;
                    return;
                }
            }
        }
        return;
    }


    // возвращает значения элемента (отформатированные)
    public function getValue() {
        $values = &$this->getValues();
        if(!isset($values['sel'])) return null;

        $value = array();
        $this->getValueFind($values['sel'], $value);
        if(!$size = count($value)) return null;
        if($size==1 and !$this->multiple) $value = current($value);
        return $this->getValueFormat($value);
    }
    // поиск установлененного значения в рекурсивном массиве элементов
    protected function getValueFind(&$sel, &$values) {
        foreach($sel as $key=>$value) {
            if(is_array($value)) $this->getValueFind($value, $values);
            else $values[] = $key;
        }
    }

    public function getValuesAll() {
        return $this->getValuesChild('array');
    }
    protected function getValuesSelect() {
        return $this->getValuesChild('sel');
    }

    // возвращение значения элемента
    public function getValueArray() {
        $values = $this->getValues();
        $result = array();
        if(isset($values['array'])) $this->getValueFind($values['array'], $result);
        return $result;
    }

    public function getIsValue($k) {
        $values = $this->getValues();
        return get2($values, 'array', $k);
    }

    // старое значение элемента
    public function getValueOld() {
        return $this->valueOld;
    }


    public function html($param, $style='') {
        $values = $this->getValues();

        $id = $this->getId();
        if($this->multiple) $html = "\n<select name=\"{$id}[]\" id=\"{$id}\" $style  multiple=\"multiple\">";
        else $html = "\n<select name=\"{$id}\" id=\"{$id}\" $style >";

        // выводим массив select - без комментариев
        if(isset($values['array']) and is_array($values['array'])) {
            foreach($values['array'] as $level1=>$value) {
                if(is_array($value)) {
                    $html .= "\n<optgroup label=\"{$level1}\">";
                    foreach($value as $level2=>$value2) {
                        $select = ((isset($values['sel'][$level1][$level2]))?'selected':'');
                        $option = $this->getOptionsStr($level2);
                        $html .= "\n<option value=\"". cmfString::specialchars($level2) ."\" $select $option>". str_replace(' ', '&nbsp;', cmfString::specialchars(strip_tags($value2))) ."</option>";
                    }
                } else {
                    $select=((isset($values['sel'][$level1]))?'selected':'');
                    $option = $this->getOptionsStr($level1);
                    $html .= "\n<option value=\"". cmfString::specialchars($level1) ."\" $select $option>".str_replace(' ', '&nbsp;', cmfString::specialchars(strip_tags($value))) ."</option>";
                }
            }
        }
        return $html .= "\n</select>";
    }

    public function jsUpdateValue() {
        return $this->jsUpdateSelect($this->getId());
    }

    public function jsUpdateSelect($id) {
        $js ="\nvar selectObj = cmf.form.select.newSelect('". $id ."'); var opt1, optgroup;";
        $values = $this->getValues();
        if(!isset($values['array'])
            or !is_array($values['array'])
            or !sizeof($values['array'])) return $js;


        reset($values['array']);
        while(list($level1, $text) = each($values['array'])) {
            if(is_array($text)) {
                $js .= "\n optgroup = cmf.form.select.optgroup(selectObj, '". cmfToJsString($level1)."');";
                reset($text);
                while(list($level2, $text2) = each($text)) {
                    $select = isset($values['sel'][$level1][$level2]) ? 'true' : 'false';
                    $js .= "\n opt1 = cmf.form.select.option(optgroup, '". cmfToJsString(strip_tags($text2)) ."', '". cmfToJsString($level2). "', $select);";
                    foreach($this->getOptions($level2) as $opt=>$value)
                        $js .= "\n \$(opt1).attr('{$opt}', '{$value}');";
                }
            } else {
                $select = isset($values['sel'][$level1]) ? 'true' : 'false';
                $js .= "\n opt1 = cmf.form.select.option(selectObj, '". cmfToJsString(strip_tags($text)) ."', '". cmfToJsString($level1). "', $select);";
                foreach($this->getOptions($level1) as $opt=>$value)
                    $js .= "\n \$(opt1).attr('{$opt}', '{$value}');";
            }
        }
        return $js;
    }

    public function processing($data, $old, $upload) {
        $value = get($data, $this->getId());
        if($this->multiple) {
            $value = (array)$value;
            $inValue = $this->getValueArray();
            foreach($value as $k=>$v) {
                if(!in_array($v, $inValue)) {
                    unset($value[$k]);
                }
            }
        } else {
            $inValue = $this->getValueArray();
            if($inValue and $value and !in_array($value, $inValue)) {
                cmfFormError::set("недопустимое значение");
            }
        }
        $data[$this->getId()] = $value;
        return parent::processing($data, $old, $upload);
    }

}



class cmfFormSelectInt extends cmfFormSelect {
    function init($o) {
        $this->setReform('cmfReformInt', false, true);
        parent::init($o);
    }
}


class cmfFormSelectFloat extends cmfFormSelect {
    protected function init($o) {
        $this->setReform('cmfReformFloatSelect', array('in'=>false, 'out'=>false));
        parent::init($o);
    }
}


class cmfFormSelectMultiple extends cmfFormSelect {
    protected function init($o) {
        $this->serMultiple();
        parent::init($o);
        $this->initMultiple();
    }
    protected function initMultiple() {
        $this->setReform('cmfReformSerialize', 'unserialize', 'serialize');
    }
}


class cmfFormSelectMultipleInt extends cmfFormSelectMultiple {
    protected function initMultiple() {
        $this->setReform('cmfReformSerializeInt', 'unserialize', 'serialize');
    }
}

class cmfFormSelectCheckbox extends cmfFormSelectMultiple {
    protected function initMultiple() {
        $this->setReform('cmfReformArrayPath', 'unserialize', 'serialize');
    }

    public function htmlError($s=null, $s2=null) {
        return $s . $s2;
    }

    public function html($param, $sep='') {
        $id = $this->getId();
        $values = $this->getValues();
        $html = '';
        if(isset($values['array']) and is_array($values['array'])) {
            foreach($values['array'] as $k=>$v) {
                $select = isset($values['sel'][$k]) ? 'checked' : '';
                $html .= "<span class=\"formCheckbox\"><label><input type=\"checkbox\" name=\"{$id}[{$k}]\" id=\"{$id}[{$k}]\" {$select} />"
                        ."&nbsp;". cmfString::specialchars($v) ."&nbsp;</label>" .'</span>'. $sep;

            }
        }
        return $html;
    }


    public function processing($data, $old, $upload) {
        $value = (array)get($data, $this->getId());
        $inValue = $this->getValueArray();
        foreach($value as $k=>$v) {
            if(!in_array($k, $inValue)) {
                unset($value[$k]);
            } else {
                $value[$k] = $k;
            }
        }
        $data[$this->getId()] = $value;
        return parent::processing($data, $old, $upload);
    }

    public function jsUpdateValue() {
        $id = $this->getId();
        $js = '';
        $values = $this->getValues();
        if(!isset($values['array'])
            or !is_array($values['array'])
            or !sizeof($values['array'])) return $js;

        foreach($values['array'] as $k=>$v) {
            $js .= "\ncmf.getId('{$id}[{$k}]').checked = ". (isset($values['sel'][$k]) ? 'true' : 'false') .";";
        }
        return $js;
    }

}

?>