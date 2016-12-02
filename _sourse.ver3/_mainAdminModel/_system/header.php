<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");


$this->assing('title', 'Админ панель');

cmfHeader::setJs('/sourseJs/jquery-1.8.2.min.js');
cmfHeader::setJs('/sourseJs/jquery.easing.1.3.min.js');
cmfHeader::setJs('/sourseJs/json.min.js');
cmfHeader::setJs('/sourseJs/include/jquery.ba-hashchange.min.js');
cmfHeader::setJs('/sourseJs/JsHttpRequest.min.js');


cmfHeader::setJs('/sourseJs/cmf-0.1.js');
cmfHeader::setJs('/sourseJs/cmf.ajax.js');
cmfHeader::setJs('/sourseJs/cmf.ajax.driver.js');
cmfHeader::setJs('/sourseJs/cmf.style.js');
cmfHeader::setJs('/sourseJs/cmf.form.js');
cmfHeader::setJs('/sourseJs/function.js');


cmfHeader::setJs('/sourseJs/admin/cmfController.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.menu.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.calendar.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.gallery.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.param.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.ajax.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.ajax.driver.js');

cmfHeader::setJs('/sourseJs/admin/function.js');
cmfHeader::setJs('/sourseJs/admin/functionWysiwing.js');


cmfHeader::setCss('/sourseCss/admin/styles.css');
cmfHeader::setCss('/sourseCss/cmfStyle.css');


cmfHeader::setJs('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.1.pack.js');
cmfHeader::setCss('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.1.css');

cmfHeader::setJs('/sourseJs/resizable/jquery-ui-1.8.9.custom.min.js');
cmfHeader::setCss('/sourseJs/resizable/jquery-ui-1.8.9.custom.css');

cmfHeader::setJs('/sourseJs/colorpicker/js/colorpicker.js');
cmfHeader::setCss('/sourseJs/colorpicker/css/colorpicker.css');


cmfHeader::setJs('/sourseJs/jquery.imgareaselect/scripts/jquery.imgareaselect.pack.js');
cmfHeader::setCss('/sourseJs/jquery.imgareaselect/css/imgareaselect-default.css');


cmfHeader::setJs('/sourseJs/JSCal/js/jscal2.js');
cmfHeader::setJs('/sourseJs/JSCal/js/lang/ru.js');
cmfHeader::setCss('/sourseJs/JSCal/css/jscal2.css');
cmfHeader::setCss('/sourseJs/JSCal/css/border-radius.css');
cmfHeader::setCss('/sourseJs/JSCal/css/gold/gold.css');


cmfHeader::setJs('/sourseJs/admin/loadDocument.js');

?>