<?php

class cmfParam {

    static public function getParamId($paramId) {
        $paramValue = cmfRegister::getSql()->placeholder("SELECT `type`, prefix, value FROM ?t WHERE id=?", db_param, $paramId)
                ->fetchAssoc();
        $paramValue['value'] = cmfString::unserialize($paramValue['value']);
        if ($paramValue['prefix']) {
            $paramValue['prefix'] = '' . $paramValue['prefix'];
        }
        return $paramValue;
    }

    static public function selectParam($value, $paramId, $paramValue, $isPrefix = true) {
        $value = cmfString::unserialize($value);
        $view = '';
        if (isset($value[$paramId]) and $value[$paramId]!=-1) {
            if ($paramValue['type'] == 'checkbox') {

            } elseif (isset($paramValue['value'][$value[$paramId]])) {
                $view = $paramValue['value'][$value[$paramId]] . ($isPrefix ? $paramValue['prefix'] : '');
            }
        } elseif (isset($value['value-' . $paramId])) {
            if ($paramValue['type'] == 'checkbox') {

            } else {
                $view = $value['value-' . $paramId] . ($isPrefix ? $paramValue['prefix'] : '');
            }
        }
        return $view;
    }

    static public function getNotice($group) {
        if (!$res = cmfCache::getParam('cmfParam::getNotice', $group)) {
            $res = self::getParamList(db_param_group_notice, $group);
            cmfCache::setParam('cmfParam::getNotice', $group, $res, 'sectionList,paramList');
        }
        return $res;
    }

    static private function getParamList($db, $group) {
        $sql = cmfRegister::getSql();
        $list = $sql->placeholder("SELECT param FROM ?t WHERE `group`=? AND visible='yes' ORDER BY pos", $db, $group, db_param)
                ->fetchRowAll(0, 0);
        if ($list) {
            $res = $sql->placeholder("SELECT id, value FROM ?t WHERE id ?@ AND visible='yes'", db_param, $list)
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name, prefix FROM ?t WHERE id ?@", db_param_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                $list[$id] = array(
                    'name' => $row['name'],
                    'prefix' => $row['prefix'],
                    'value' => $row['value'] ? unserialize($row['value']) : array()
                );
            }
        }
        return $list;
    }

    static public function &generateNotice($productParam, $group) {
        $productParam = cmfString::unserialize($productParam);
        $paramList = cmfParam::getNotice($group);
        $notice = array();
        foreach ($paramList as $k => $v) {
            $prefix = empty($v['prefix']) ? '' : ' ' . $v['prefix'];
            if (isset($productParam[$k]) and is_array($productParam[$k])) {
                foreach ($v['value'] as $k2 => $v2) {
                    if (!isset($productParam[$k][$k2])) {
                        unset($v['value'][$k2]);
                    } elseif ($prefix) {
                        $v['value'][$k2] .= $prefix;
                    }
                }
                if ($v['value']) {
                    $notice[$v['name']] = implode(', ', $v['value']);
                }
            } else {
                $v2 = get($v['value'], get($productParam, $k));
                if ($v2) {
                    $notice[$v['name']] = word($v2) . $prefix;
                }
            }
        }
        return $notice;
    }

}

?>