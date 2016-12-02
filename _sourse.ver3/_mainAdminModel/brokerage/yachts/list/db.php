<?php

class brokerage_yachts_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'brokerage_yachts_edit_db';
    }

    protected function getSort() {
        return array('pos');
    }

    protected function getSortLang() {
        return array('name');
    }

    protected function getTable() {
        return db_brokerage_yachts;
    }

    protected function getTableLang() {
        return db_brokerage_yachts_lang;
    }

    protected function getFields() {
        return array('id', 'shipyards', 'type', 'price', 'currency', 'uri', 'menu', 'pos', 'visible');
    }

    protected function getFieldsLang() {
        return array('shipyardsName', 'name');
    }

    protected function getWhereFilter() {
        $filter = array();

        /* 		$section = $this->getFilter('shipyards');
          if($section>0) {
          $filter['shipyards'] = $section;
          } elseif(!$section) {
          $filter['shipyards'] = cmfGlobal::get('$sectionId');
          } else {
          $filter[] = $this->getSql()->getQuery("`shipyards` NOT ?@", cmfGlobal::get('$sectionId'));
          }
          $filter[] = 'AND'; */

        $type = $this->getFilter('type');
        if ($type) {
            $filter['type'] = $type;
            $filter[] = 'AND';
        }

        $filter[] = 1;
        return $filter;
    }

    public function loadData(&$row) {
        $row['price'] = empty($row['price']) ? 'по запросу' : number_format($row['price'], 0, '.', ' ') . ' ' . $row['currency'];
//        $row['shipyardsName'] = empty($row['shipyardsName']) ? 'по запросу' : $row['shipyardsName'];
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