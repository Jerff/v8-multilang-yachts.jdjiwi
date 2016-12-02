<?php

class arenda_yachts_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'arenda_yachts_edit_db';
    }

    protected function getTable() {
        return db_arenda_yachts;
    }

    protected function getTableLang() {
        return db_arenda_yachts_lang;
    }

    protected function getFields() {
        return array('id', 'shipyards', 'type', 'priceHour', 'priceLightDay', 'priceDay', 'priceWeek', 'currency', 'uri', 'pos', 'visible');
    }

    protected function getFieldsLang() {
        return array('shipyardsName', 'name');
    }

    protected function startSaveWhere() {
        return array('type');
    }

    protected function getSort() {
        return array('type', 'pos');
    }

    protected function getSortLang() {
        return array('name');
    }

    protected function getWhereFilter() {
        $filter = array();

        $section = $this->getFilter('shipyards');
        if ($section > 0) {
            $filter['shipyards'] = $section;
        } elseif (!$section) {
            $res = cmfGlobal::get('$sectionId');
            array_unshift($res, 0);
            $filter['shipyards'] = $res;
        } else {
            $filter[] = $this->getSql()->getQuery("a.`shipyards` NOT ?@", cmfGlobal::get('$sectionId'));
        }
        $filter[] = 'AND';

        $arenda = $this->getFilter('arenda');
        if ($arenda) {
            if ($arenda > 0) {
                $filter[] = $this->getSql()->getQuery("a.id IN (SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE id=? or path LIKE '%[?i]%'))", db_arenda_list, db_arenda, $arenda);
            } else {
                $filter[] = $this->getSql()->getQuery("a.id IN (SELECT yachts FROM ?t WHERE arenda ?@)", db_arenda_list, cmfGlobal::get('$arendaId'));
            }
        } else {
            $filter[] = $this->getSql()->getQuery("a.id NOT IN (SELECT yachts FROM ?t WHERE arenda ?@)", db_arenda_list, cmfGlobal::get('$arendaId'));
        }
        $filter[] = 'AND';

        $type = $this->getFilter('type');
        if ($type) {
            $filter['type'] = $type;
            $filter[] = 'AND';
        }

        $filter[] = 1;
        return $filter;
    }

    public function loadData(&$row) {
        $row['priceHour'] = empty($row['priceHour']) ? 'по запросу' : ($row['priceHour']) . ' ' . $row['currency'];
        $row['priceLightDay'] = empty($row['priceLightDay']) ? 'по запросу' : ($row['priceLightDay']) . ' ' . $row['currency'];
        $row['priceDay'] = empty($row['priceDay']) ? 'по запросу' : ($row['priceDay']) . ' ' . $row['currency'];
        $row['priceWeek'] = empty($row['priceWeek']) ? 'по запросу' : ($row['priceWeek']) . ' ' . $row['currency'];
        $row['shipyardsName'] = empty($row['shipyardsName']) ? 'по запросу' : $row['shipyardsName'];
        parent::loadData($row);
    }

    public function getYachtsList($section, $filter = null, $fileds = null) {
        if (is_null($filter))
            $filter = $this->getWhereFilter();
        if (is_array($fileds)) {
            array_push($fileds, 'id', 'name');
        } else {
            $fileds = array('id', 'name');
        }

        $res = $this->getSql()->placeholder("SELECT ?fields:p, special_image AS main, special_small AS small FROM ?t p WHERE p.section ?@ AND ?w:p AND special='yes' ORDER BY ?o:p", $fileds, $this->getTable(), $section, $filter, $this->getSort())
                ->fetchAssocAll('id');
        foreach ($res as $k => $v) {
            if ($v['main']) {
                $res[$k]['main'] = cmfBaseImg . cmfPathSpecial . $v['main'];
            }
            if ($v['small']) {
                $res[$k]['small'] = cmfBaseImg . cmfPathSpecial . $v['small'];
            }
            $res[$k]['image'] = $v['main'] && $v['small'];
        }
        return $res;
    }

}

?>