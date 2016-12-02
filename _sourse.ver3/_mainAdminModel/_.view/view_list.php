<?php


class view_list extends view_edit {

 	static public function posMove($jsModul, $name, $key, $pos) {
        $html = '<td class="posTD">';
 		if($key) {
			$cmfBaseUrl = cmfBaseUrl;
        	$html .=<<<HTML
		<table class="pos_table">
		<tr>
		<td rowspan="3" class="pos">
			<span id="{$name}posView{$key}">{$pos}</span>
		    <span id="{$name}posText{$key}" class="cmfHide">
		    <input id="{$name}pos{$key}" type="text" value="{$pos}" size="3"><input id="{$name}posOld{$key}" type="hidden" value="{$pos}">
		    </span></td>
		    <td><a href="javascript:void(0);" onclick="{$jsModul}.move1Ajax('{$key}');"><img src="{$cmfBaseUrl}/sourseImage/admin/pos_up.gif" class="img"></a></td>
		</tr>
		<tr><td height="11px"><a href="javascript:void(0);" onclick="{$jsModul}.moveMoveAjax(this, '{$name}', '{$key}');"><img src="{$cmfBaseUrl}/sourseImage/admin/pos_center.gif" class="img"></a></td></tr>
		<tr><td height="9px"><a href="javascript:void(0);" onclick="{$jsModul}.move2Ajax('{$key}');"><img src="{$cmfBaseUrl}/sourseImage/admin/pos_down.gif" class="img"></a></td></tr>
		</table>
HTML;
 		}
 		return $html .'</td>';
 	}



	static public function startTR(&$modul) {
		static $name = null;
		static $id = 0;
		if($name!==get_class($modul)) {
            $name = get_class($modul);
            $id = 0;
		}
		?><tr <?=self::styleTR($id++ ? '' : 'cmfHide') ?> id="<?=$modul->getHtmlIdDel() ?>"><?
	}

	static public function startTR2($id) {
		static $name = null;
		static $id = 0;
		if($name!==$id) {
            $name = $id;
            $id = 0;
		}
		?><tr <?=self::styleTR($id++ ? '' : 'cmfHide') ?>><?
	}

	static private function styleTR($class='') {
		static $i = false;
		$class .= ($i=!$i) ? ' columnn_svet' : ' columnn_svet2';
		return 'class="'. $class .'"';
	}



	static public function hideInput($k, $v) {
		return '<input name="'. $k . '" type="hidden" value="'. $v .'">';
	}

}

?>
