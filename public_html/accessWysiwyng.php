<?php

$old = getcwd();

chdir(dirname(__FILE__));
$path = require('../_sourse.ver3/adminAccessWysiwyng.php');

chdir($old);
return $path;

?>
