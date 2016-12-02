<?php

	require('_.core/load/functionMain.php');
	cmfLoad('system/function');
	cmfLoad('url/function');


	cmfLoad('configSite');
    cmfLoad('sqlTableList');


	cmfLoad('register/cmfRegister');
	cmfLoad('request/cmfRequest');
	cmfLoad('session/cmfSession');
	cmfLoad('cookie/cmfCookie');
	cmfLoad('global/cmfGlobal');

	cmfLoad('config/cmfConfig');
	cmfLoad('command/cmfCommand');

	cmfLoad('lang/cmfLang');


	cmfLoad('debug/cmfDebug');
	cmfLoad('compile/cmfCompileFile');


	cmfLoad('file/cmfDir');
	cmfLoad('file/cmfFile');

	cmfLoad('pages/main/cmfMainPages');

	cmfLoad('cache/cmfCache');
	cmfLoad('cache/cmfCacheSite');
	cmfLoad('cache/cmfCacheUser');

	cmfLoad('user/cmfAdmin');

?>
