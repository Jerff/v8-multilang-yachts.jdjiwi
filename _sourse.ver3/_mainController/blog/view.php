<?php

header( 'application/javascript' );
cmfDebug::destroy();
cmfAjax::start();
$r = cmfRegister::getRequest();
//cmfDebug::setError();
switch (get($_GET, 'action')) {
    case 'view':
        $blogId = (int)get($_GET, 'id');
        $sql = cmfRegister::getSql();
        $sql->placeholder("UPDATE ?t SET `view`=`view`+1 WHERE id=? AND visible='yes'", db_blog, $blogId);
        $view = $sql->placeholder("SELECT `view` FROM ?t WHERE id=? AND visible='yes'", db_blog, $blogId)
                ->fetchRow(0);
        echo("\n$('#blogView{$blogId}').html('{$view}');");
        break;

    case 'list':
        $blogList = array_map('trim', explode(',', get($_GET, 'list')));
        $sql = cmfRegister::getSql();
//        $sql->placeholder("UPDATE ?t SET `view`=`view`+1 WHERE id ?@ AND visible='yes'", db_blog, $blogList);
        $view = $sql->placeholder("SELECT id, `view` FROM ?t WHERE id ?@ AND visible='yes'", db_blog, $blogList)
                ->fetchRowAll(0, 1);
        foreach ($view as $blogId => $view) {
            echo ("\n$('#blogView{$blogId}').html('{$view}');");
        }
        break;

    default:
        break;
}
?>