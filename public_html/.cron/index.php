<?php

chdir(dirname(__FILE__));
$file = basename($_SERVER['REQUEST_URI'], '.php');
$file= "../../_sourse.ver3/.cron/{$file}.php";


if(is_file($file)) {
	include($file);
}

?>