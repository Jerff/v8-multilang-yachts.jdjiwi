<?php


cmfDebug::destroy();
$offset = (int)cmfPages::getParam(1);
if(!$offset) exit;if($offset>3000) exit;

$limit = cmfConfig::get('site', 'newsLimit');
$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, uri, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT ?i, ?i", db_news, $offset, $limit)
                                ->fetchAssocAll('id');
if(!$res) exit;
$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_news_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
$_news = array();
foreach($res as $id=>$row) {
	cmfLang::result($row, get($list, $id));
	$_news[$id] = array('date'=>date('d/m/Y', strtotime($row['date'])),
                        'header'=>$row['header'],
                        'notice'=>$row['notice'],
                        'url'=>cmfGetUrl('/news/one/', array($row['uri'])));
}
$this->assing('_news', $_news);

?>
