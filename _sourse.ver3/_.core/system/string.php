<?php

function cmfStrLen($str) {
	return mb_strlen($str, cmfCharset);
}
function cmfSubStr($str, $start, $len=null) {
	return mb_substr($str, $start, $len, cmfCharset);
}
function cmfConvertEncoding($str) {
	$char = mb_detect_encoding($str);
	if($char!==cmfCharset) {
        $str = mb_convert_encoding($str, cmfCharset);
	}
	return $str;
}



function cmfHtmlEntityDecode($str) {
	return html_entity_decode($str, ENT_QUOTES, cmfCharset);
}

function cmfHtmlSpecialchars($str) {
	return htmlspecialchars($str, ENT_QUOTES, cmfCharset);
}


function cmfStripSlashes(&$d) {
	reset($d);
	$n = array();
	while(list($k, $v) = each($d)) {
		if(is_array($v)) {
			cmfStripSlashes($v);
		} else {
			$v = stripslashes($v);
		}
		$n[stripslashes($k)] = $v;
	}
	$d = $n;
}

function cmfStripSlashesPost() {
	if(get_magic_quotes_gpc()) {
		cmfStripSlashes($_POST);
		cmfStripSlashes($_GET);
		cmfStripSlashes($_COOKIE);
		//cmfStripSlashes($_FILES);
	}
}


function cmfTrim(array &$d) {
	reset($d);
	while(list($k, $v) = each($d))
		$d[$k] = trim($v);
	reset($d);
}

function cmfTrim2($d) {
    if(is_string($d)) return trim($d);
	reset($d);
	while(list($k, $v) = each($d))
		if(is_array($v)) cmfTrim2($v);
		else $d[$k] = trim($v);
	reset($d);
	return $d;
}


function cmfFormtaArray($d) {
	$str = '';
	$max = 0;
	foreach($d as $k=>$v) {
		$len = cmfStrLen($k);
		if($len>$max) $max = $len;
	}
	$max += 5;

	foreach($d as $k=>$v) {
		$len = $max - cmfStrLen($k);
		$str .= "\n". $k .' ';
		for($i = 0; $i<$len; $i++) $str .=' ';
		$str .= $v;
	}
	return $str;
}

function cmfSubContent($content, $pos0=0, $select_len=300) {
	$select_len -= 4;

	$content = strip_tags($content);
	$point1 = $pos0;
	$content_len = cmfStrLen($content);
	if($content_len<=$select_len) return $content;
	$select_count = 2;
	if($point1>0) {
		if(($point1-$select_len)>0) {
			$content_start = cmfSubStr($content, $point1-$select_len, $select_len);
			$end_pos = 0;
			$i = $select_count;
			while($i--) {
				$point0 = cmfSubStr($content_start, '?', $end_pos);
				$tmp_pos=cmfSubStr($content_start, '!', $end_pos);
				if($tmp_pos>$point0) $point0 = $tmp_pos;
				$tmp_pos = cmfSubStr($content_start, '.', $end_pos);
				if($tmp_pos>$point0) $point0 = $tmp_pos;
				$end_pos = $point0-$select_len-1;
			}
			if($point0===false) {
				$point0=0;
				while($point0<$point1 && $content_start[$point0]!=' ') $point0++;
				$content_start = '... '. cmfSubStr($content_start, $point0+1);
			} else $content_start = cmfSubStr($content_start, $point0+1);
		} else $content_start = cmfSubStr($content, 0, $point1);
	} else $content_start = cmfSubStr($content, 0, $point1);

	if($point1+$select_len<$content_len) {
		$content_end = cmfSubStr($content, $point1, $select_len);
		$i = $select_count;
		$end_pos = $select_len/2;
		while($i--) {
			$point0=mb_strrpos($content_end, '?', $end_pos, cmfCharset);

			$tmp_pos=mb_strrpos($content_end, '!', $end_pos, cmfCharset);
			if($point0===false or ($tmp_pos<$point0 and $tmp_pos!==false)) $point0 = $tmp_pos;
			$tmp_pos=mb_strrpos($content_end, '.', $end_pos, cmfCharset);
			if($point0===false or ($tmp_pos<$point0 and $tmp_pos!==false)) $point0 = $tmp_pos;
			if($point0===false) break;
			$end_pos = $point0+1;
		}
		if($point0===false) {
			$point0 = $select_len-1;
			while($point0 && $content_end[$point0]!=' ') $point0--;
			$content_end = cmfSubStr($content_end, 0, $point0).' ...';
		}
		else $content_end = cmfSubStr($content_end, 0, $point0+1);

	} else $content_end = cmfSubStr($content, $point1);
	return $content_start . $content_end;
}


?>