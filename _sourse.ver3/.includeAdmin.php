<?php


	require('_.core/load/functionMain.php');
	cmfLoad('load/functionAdmin');
	cmfLoad('system/function');
	cmfLoad('url/function');


	cmfLoad('sqlTableList');
	cmfLoad('configAdmin');
	cmfLoad('configSite');


	cmfLoad('register/cmfRegister');
	cmfLoad('debug/cmfDebug');
	cmfLoad('load/cmfAdminAutoload');

	cmfLoad('pages/admin/cmfAdminPages');
	cmfLoad('pageAdmin');


	//cmfLoadForm();
	cmfLoad('pagination/functionAdmin');


?>