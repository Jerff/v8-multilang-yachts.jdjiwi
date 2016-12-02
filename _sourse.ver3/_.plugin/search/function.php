<?php

function cmfSearcView(&$res, $search) {
    $word = array();
    $search = mb_strtolower($search, cmfCharset);
    $search = preg_replace("/(\s{2,})/", ' ', $search);
    $search = preg_replace("/[^а-я|a-z|0-9|\-]+/iu", ' ', $search);
    foreach(explode(' ',$search) as $key=>$value)
        if(!empty($value) and ($len=mb_strlen($value))>3) {
            $word[$value] = $value;
		}
    foreach($res as $k=>$list) {
        list(, $v) = each($list);
        cmfLang::data($v, $list);
        $res[$k] = $v;
        $content = $v['notice'];
        $point1 = false;
        foreach($word as $v) {
            $pos = mb_stripos($content, $v);
            if($point1===false) {
                $point1 = $pos;
            } elseif($pos!==false AND $point1>$pos) {
                $point1 = $pos;
            }
        }

        $content_len=strlen($content);
        $select_len = 200;
        $select_count = 3;
        if($point1>0) {
            if(($point1-$select_len)>0) {
                $content_start=substr($content,$point1-$select_len,$select_len);
                $end_pos=0;
                $i=$select_count;
                while($i-- and $select_len>abs($end_pos)) {
                    $point0=strrpos($content_start,'?',$end_pos);
                    $tmp_pos=strrpos($content_start,'!',$end_pos);
                    if($tmp_pos>$point0) $point0=$tmp_pos;
                    $tmp_pos=strrpos($content_start,'.',$end_pos);
                    if($tmp_pos>$point0) $point0=$tmp_pos;
                    $end_pos=$point0-$select_len-1;
                }
                if($point0===false) {
                    $point0=0;
                    while($point0<$point1 && $content_start[$point0]!=' ') $point0++;
                    $content_start='... '.substr($content_start,$point0+1);
                } else $content_start=substr($content_start,$point0+1);
            } else $content_start=substr($content,0,$point1);
        } else $content_start=substr($content,0,$point1);

        if($point1+$select_len<$content_len) {
            $content_end=substr($content,$point1,$select_len);
            $i=$select_count;
            $end_pos=$select_len/2;
			while($i--) {
				$point0=strpos($content_end,'?',$end_pos);

				$tmp_pos=strpos($content_end,'!',$end_pos);
				if($point0===false or ($tmp_pos<$point0 and $tmp_pos!==false)) $point0=$tmp_pos;
				$tmp_pos=strpos($content_end,'.',$end_pos);
				if($point0===false or ($tmp_pos<$point0 and $tmp_pos!==false)) $point0=$tmp_pos;
				if($point0===false) break;
				$end_pos=$point0+1;
			}
			if($point0===false) {
				$point0=$select_len-1;
				while($point0 && $content_end[$point0]!=' ') $point0--;
				$content_end=substr($content_end,0,$point0).' ...';
			}
			else $content_end=substr($content_end,0,$point0+1);

		} else $content_end=substr($content,$point1);
		$content=$content_start.$content_end;

        foreach($word as $v) {
            $content = preg_replace('~('. preg_quote($v) .')~i', '<span class="ajaxSearch_highlight ajaxSearch_highlight1">$1</span>', $content);
        }
        $res[$k]['notice'] = $content;
    }
}

?>