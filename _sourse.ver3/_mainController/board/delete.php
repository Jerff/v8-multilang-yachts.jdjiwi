<?php

cmfAjax::start();
$r = cmfRegister::getRequest();


$boardId = (int) get($_POST, 'board');
$key = (int) get($_POST, 'key');

$board = cmfRegister::getSql()->placeholder("SELECT * FROM ?t WHERE id=? AND user=?", db_board, $boardId, cmfRegister::getUser()->getId())
        ->fetchAssoc();
if (!$board)
    exit;

if (!empty($board['image'])) {
    $images = cmfString::unserialize($board['image']);
    if (!isset($images[$key]))
        exit;
    unset($images[$key]);
    $data = array(
        'image' => $images = cmfString::serialize($images)
    );
    cmfRegister::getSql()->add(db_board, $data, $boardId);

    cmfLoad('board/cmfBoardEdit');
    $boardEdit = new cmfBoardEdit();
    $boardEdit->jsHtmlImage($boardId, $images);
    cmfAjax::get()->script('cmf.form.images.command.init();');
}
?>