<?php

$page = new cmfAdminController();

$main_edit = $page->load('arenda_yachts_edit_controller');
if (!$main_edit->getId() and cmfAdminMenu::getSubMenuId()) {
    $main_edit->setId(cmfAdminMenu::getSubMenuId());
}
$page->run();
if ($main_edit->notId())
    return cmfAdminNotRecord;

if (0 and $_GET['tewuith'] === '23253') {
    if (0) {
        $res = cmfRegister::getSql()->placeholder("SELECT image_main, image_small FROM ?t", db_arenda_yachts)
                ->fetchAssocAll();
        foreach ($res as $row) {
            if (empty($row['image_main']) or empty($row['image_small'])) {
                pre('not_found image', $row);
                exit;
            } else {
                $main = cmfWWW . cmfPathArendaYachts . $row['image_main'];
                $small = cmfWWW . cmfPathArendaYachts . $row['image_small'];
                if (is_file($main) and is_file($small)) {
                    copy($main, $small);
//                pre('copy image', $row, $main, $small);
                } else {
                    pre('not_found image', $row, $main, $small);
                }
            }
        }
    }

    if (0) {
        $res = cmfRegister::getSql()->placeholder("SELECT image_main, image_small FROM ?t", db_shipyards_yachts)
                ->fetchAssocAll();
        foreach ($res as $row) {
            if (empty($row['image_main']) or empty($row['image_small'])) {
                pre('not_found image', $row);
                exit;
            } else {
                $main = cmfWWW . cmfPathShipyardsYachts . $row['image_main'];
                $small = cmfWWW . cmfPathShipyardsYachts . $row['image_small'];
                if (is_file($main) and is_file($small)) {
                    copy($main, $small);
//                pre('copy image', $row, $main, $small);
                } else {
                    pre('not_found image', $row, $main, $small);
                }
            }
        }
    }

    if (0) {
        $res = cmfRegister::getSql()->placeholder("SELECT image_main, image_small FROM ?t", db_brokerage_yachts)
                ->fetchAssocAll();
        pre($res);
        foreach ($res as $row) {
            if (empty($row['image_main']) or empty($row['image_small'])) {
                pre('not_found image', $row);
                exit;
            } else {
                $main = cmfWWW . cmfPathBrokerageYachts . $row['image_main'];
                $small = cmfWWW . cmfPathBrokerageYachts . $row['image_small'];
                if (is_file($main) and is_file($small)) {
                    copy($main, $small);
                pre('copy image', $row, $main, $small);
                } else {
                    pre('not_found image', $row, $main, $small);
                }
            }
        }
    }
}

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);


list($formArenda, $dataArenda) = $main_edit->current()->arenda;
$this->assing('formArenda', $formArenda);
$this->assing('dataArenda', $dataArenda);

if ($main_edit->getId()) {
    cmfAdminMenu::setSubMenuId($main_edit->getId());
    cmfAdminMenu::setUserMenu('yachts');
}

list($langForm, $langData) = $main_edit->current()->lang;
$this->assing('langForm', $langForm);
$this->assing('langData', $langData);
?>