<?php


//REPLACE `s03_arenda_yachts` SELECT `id`, 0, shipyard, `type`, `data`, 'no', `url`, `title_h1`, `text`, '', `technical`, year, `long`, people,0, 0, 0, '','',`name_foto`, `main_foto`, `main_foto_thumb`, `main_foto_thumb`, `title`, `keywords`, `description` FROM 2011_01_old.arenda
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small, image_best FROM ?t WHERE visible='no' LIMIT 0, 3000", db_arenda_yachts)
            ->fetchAssocAll('id');
$path = cmfWWW . cmfPathArendaYachts;
foreach($res as $k=>$row) {

    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = substr($row['image_main'], 0, 1) .'/'. substr($row['image_main'], 1, 1) .'/'. $row['image_main'];
    cmfDir::mkdir(dirname($path . $file1));
    file_put_contents($path . $file1, file_get_contents($main1));
    cmfImage::resize($path . $file1, yachtsWidth, yachtsHeight);

    $main2 = 'http://nsk-yachts.com.ua/upload/'. $row['image_small'];
    $file2 = 'small/'. substr($row['image_small'], 0, 1) .'/'. substr($row['image_small'], 1, 1) .'/'. $row['image_small'];
    cmfDir::mkdir(dirname($path . $file2));
    file_put_contents($path . $file2, file_get_contents($main2));
    cmfImage::resize($path . $file2, yachtsSmallWidth, yachtsSmallHeight);

    $main3 = 'http://nsk-yachts.com.ua/upload/'. $row['image_best'];
    $file3 = 'best/'. substr($row['image_best'], 0, 1) .'/'. substr($row['image_best'], 1, 1) .'/'. $row['image_best'];
    cmfDir::mkdir(dirname($path . $file3));
    file_put_contents($path . $file3, file_get_contents($main3));
    cmfImage::resize($path . $file3, yachtsBestWidth, yachtsBestHeight);

    $image = array('image'=>array('image_main'=>$file1, 'image_small'=>$file2, 'image_best'=>$file3));
    $send = array();
    $send['visible'] = 'yes';
    $send['image'] = serialize($image);
    $send['image_main'] = $file1;
    $send['image_small'] = $file2;
    $send['image_best'] = $file3;
    $sql->add(db_arenda_yachts, $send, $k);
    //sleep(1);
}*/


/*
UPDATE `s03_param` SET `value`='';
UPDATE `s03_arenda_yachts` SET `param`='', `visible`='no';
UPDATE `s03_arenda_yachts` s SET `param`='', `notice`=(SELECT text FROM 2011_01_old.yacht i WHERE i.id=s.id), `paramText`=(SELECT technical FROM 2011_01_old.yacht i WHERE i.id=s.id), `visible`='no'
*/
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, type, search, name, prefix, prefixParse, parseType, value FROM ?t", db_param)
            ->fetchAssocAll('name');
$_param = $_search = array();
foreach($res as $k=>$v) {
    $v['value'] = cmfString::unserialize($v['value']);
    $_param[$k = mb_strtolower($k, cmfCharset)] = $v;

    $prefix = $v['prefixParse'] ."\n;\n". $v['prefix'];
    $_param[$k]['parseType'] = $v['parseType'];
    $_param[$k]['prefix'] = array();
    foreach(array_map('trim', explode("\n", $prefix)) as $name) if(!empty($name)) {
        $_param[$k]['prefix'][mb_strtolower($name, cmfCharset)] = mb_strtolower($name, cmfCharset);
    }

    foreach(array_map('trim', explode("\n", $v['search'])) as $name) if(!empty($name)) {
        $_search[mb_strtolower($name, cmfCharset)] = $k;
    }
}
cmfModul('param_edit_db');

$arenda = array(
'year'=>'год постройки',
'long'=>'длина',
'people'=>'пассажировместимость');
$res = $sql->placeholder("SELECT id, paramText, year, `long`, people FROM ?t WHERE visible='no' LIMIT 0, 5000", db_arenda_yachts)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    if(strip_tags($row['paramText'])==$row['paramText']) {
        continue;
    }
    $param = array();
    foreach($arenda as $r=>$n) {
        $paramId = $_param[$n]['id'];
        $res = $_param[$n]['value'];
        $type = $_param[$n]['type'];
        $v = trim($row[$r]);
        if(empty($v)) continue;
        if(in_array($v, $res)) {
            $param[$paramId] = array_search($v, $res);
        } else {
            if($type==='checkbox' or $type==='select' or $type==='radio') {
                $res[] = $v;
                $vId = array_search($v, $res);
                if($type==='checkbox') {
                    $param[$paramId][$vId] = $vId;
                } else {
                    $param[$paramId] = $vId;
                }
                uasort($res, array('param_edit_db', 'paramSort'));
                $_param[$n]['value'] = $res;
                $sql->add(db_param, array('value'=>serialize($res)), $paramId);
            } else {
                $param[$paramId] = $v;
            }
        }
    }

    $paramText = explode('<tr>', $row['paramText']);
    $paramText = array_map('strip_tags', $paramText);
    $paramText = array_map('trim', $paramText);
    foreach($paramText as $line) if(!empty($line)){
        $line = array_map('trim', explode("\n", $line));
        if(!isset($line[1])) {
            pre('Странные данные', $k, $paramText);
            die();
        }
        list($n, $v) = $line;
        $n = mb_strtolower($n, cmfCharset);
        $v = mb_strtolower($v, cmfCharset);
        if($v=='&nbsp;') {
            continue;
        }

        if(!isset($_param[$n])) {
            if(isset($_search[$n])) {
                $n = $_search[$n];
            } else {
                pre('Нет параметра', $paramText, $k, $row['paramText'], $n, $v, $param);
                die();
            }
        }
        $res = $_param[$n]['value'];
        $paramId = $_param[$n]['id'];
        $type = $_param[$n]['type'];
        $v = trim(str_replace($_param[$n]['prefix'], '', $v));
        $v = str_replace(',', '.', $v);

        $parseType = $_param[$n]['parseType'];
        if($parseType) {
            if(strpos($v, '-')!==false) {
                $v = array_map('trim', explode('-', $v));
                foreach($v as $k2=>$v2) {
                    $v[$k2] = parseType($parseType, $v2);
                }
                $v = implode(' - ', $v);
            }
            if(strpos($v, '/')!==false) {
                $v = array_map('trim', explode('/', $v));
                foreach($v as $k2=>$v2) {
                    $v[$k2] = parseType($parseType, $v2);
                }
                $v = implode('/', $v);
            } else {
                $v = parseType($parseType, $v);
            }
        }

        if(in_array($v, $res)) {
            $vId = array_search($v, $res);
            if($type==='checkbox') {
                $param[$paramId][$vId] = $vId;
            } else {
                $param[$paramId] = $vId;
            }
        } else {
            if($type==='checkbox' or $type==='select' or $type==='radio') {
                $res[] = $v;
                $vId = array_search($v, $res);
                if($type==='checkbox') {
                    $param[$paramId][$vId] = $vId;
                } else {
                    $param[$paramId] = $vId;
                }
                uasort($res, array('param_edit_db', 'paramSort'));
                $_param[$n]['value'] = $res;
                $sql->add(db_param, array('value'=>serialize($res)), $paramId);
            } else {
                $param[$paramId] = $v;
            }
        }
    }

    $send = array();
    $send['visible'] = 'yes';
    $send['param'] = serialize($param);
    $sql->add(db_arenda_yachts, $send, $k);
}
function parseType($parseType, $v) {
    if($parseType=='float.1') {
        return number_format($v, 2, '.', ' ');
    } if($parseType=='float.2') {
        return $v;
        $tmp = number_format($v, 2, '.', ' ');
        if(strlen($tmp)<strlen($tmp)) {
            return number_format($v, 2, '.', ' ');
        } else {
            return $tmp;
        }
    } else {
        $tmp = (int)str_replace(' ', '', $v);;
        return str_replace($tmp, number_format($tmp, 0, '.', ' '), $v);
    }
}
pre($_param);
die();*/



/*
UPDATE  2011_01.s03_arenda_yachts a SET a.`visible`='no',
a.`image_main`=(SELECT main_foto FROM 2011_01_old. arenda o WHERE o.id=a.id),
a.`image_small`=(SELECT main_foto_thumb FROM 2011_01_old. arenda o WHERE o.id=a.id)
*//*
$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='no'", db_arenda_yachts)
            ->fetchAssocAll('id');
$path = cmfWWW . cmfPathArendaYachts;
foreach($res as $k=>$row) {

    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = $path . $row['image_main'];
    $file2 = $path . $row['image_small'];

    file_put_contents($file2, file_get_contents($main1));
    cmfImage::resize($file2, yachtsSmallWidth, yachtsSmallHeight);

    $image = array('image'=>array('image_main'=>$row['image_main'], 'image_small'=>$row['image_small']));
    $send = array();
    $send['visible'] = 'yes';
    $send['image'] = serialize($image);
    $sql->add(db_arenda_yachts, $send, $k);
}
die();*/


$page = new cmfAdminController();
$main_list = $page->load('arenda_yachts_list_controller');
$this->assing('filterArenda', $main_list->filterArenda());
$this->assing('filterShipyards', $main_list->filterShipyards());
$this->assing('filterType', $main_list->filterType());
$page->run();

$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

?>
