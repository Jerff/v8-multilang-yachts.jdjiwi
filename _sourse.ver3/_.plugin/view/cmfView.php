<?php


class cmfView {

	static public function selectOncahge($select, $style='class="width50"') {
		$result='
<select onchange="document.location.href = this.value;" '. $style .'>';
		reset($select);
		while(list(, $v) = each($select)) {
			$result .= '
<option value="'.$v['url'].'" '.((isset($v['sel']))?'selected':'').'>'.$v['name'].'</option>';
		}
		$result .= '
</select>';
		return $result;
	}


	static public function select($select, $style='class="width50"') {
		$result='
<select '. $style .'>';

		reset($select);
		while(list($k, $v) = each($select))
			$result .= '
<option value="'.$k.'" '.((isset($v['sel']))?'selected':'').'>'.$v['name'].'</option>';
		$result .= '
</select>';
		return $result;
	}


	static function pagination(&$url) {
		$i = 0;
		foreach($url as $key=>$value) {
			if($i++) echo ' ';
			if(isset($value['url'])) echo '<a href="'. $value['url'] .'" class="nav"><img src="'. $key .'" alt="#"></a>';
			else echo ' <span>'. $key .' из '. $value .'</span> ';
		}
	}

	static function paginationAjax($func, &$url, $param) {
		$i = 0;
		foreach($url as $key=>$value) {
			if($i++) echo ' ';
			if(isset($value['sel'])) echo '<span>'. $key .'</span>';
			else echo '<a href="javascript:void(0);" onclick="return '. $func .'('. $param .', '. $key .')">'. $key .'</a>';
		}
	}


    static function optionView($opt) {
        foreach($opt as $k=>$v) {
            ?><option value="<?=$v['url'] ?>" <?= isset($v['sel']) ? 'selected' : '' ?>><?=$v['name'] ?></option><?
        }
    }

}

?>