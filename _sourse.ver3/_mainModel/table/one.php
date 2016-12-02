<?php

$tableId = cmfGlobal::get('tableId');
$sql = cmfRegister::getSql();
$table = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_table, $tableId)
        ->fetchAssoc();
if ($table['colum'] and !empty($table['field'])) {
    $field = cmfString::unserialize($table['field']);

    $res = $sql->placeholder("SELECT value FROM ?t WHERE `table`=? AND visible='yes' ORDER BY pos", db_table_data, $tableId)
            ->fetchAssocAll();
    $mData = array();
    foreach ($res as $row) {
        $mData[] = cmfString::unserialize($row['value']);
    }
    $this->assing2('table', $table);
    $this->assing2('mData', $mData);
}
?>