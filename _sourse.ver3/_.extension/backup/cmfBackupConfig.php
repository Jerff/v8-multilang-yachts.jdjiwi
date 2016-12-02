<?php


class cmfBackupConfig {

    static public function menu() {
        $menu = array();
        $menu['catalog']   = 'Каталог';
        $menu['info']      = 'Информация';
        $menu['seo']       = 'Seo данные';
        $menu['config']    = 'Настройки';
        $menu['mail']      = 'Почта';
		$menu['pages']     = 'Система';
		return $menu;
    }

    static public function block($id) {
        $_noData = array();
        switch($id) {
            case 'catalog':
                $_table = array(db_shipyards, db_shipyards_type, db_shipyards_yachts, db_shipyards_yachts_foto, db_shipyards_yachts_plan,
                                db_brokerage_type, db_brokerage_yachts, db_brokerage_yachts_foto, db_brokerage_yachts_plan,
                                db_arenda, db_arenda_list, db_arenda_type, db_arenda_yachts, db_arenda_yachts_foto, db_arenda_yachts_plan,
                                db_param, db_param_group_search, db_param_group_notice, db_param_select, db_param_checkbox,
                                db_search);
                break;

            case 'info':
                $_table = array(db_main, db_main_slider,
                                db_menu, db_menu_arenda, db_menu_sale, db_menu_useful,
                                db_menu_info, db_content, db_content_url, db_content_static,
                                db_news, db_article, db_foto, db_wallpapers);
                break;

            case 'seo':
                $_table = array(db_seo_title,
                                db_seo_copyright, db_seo_counters, db_seo_reklama,
                                db_seo_sitemap);
                break;

            case 'config':
                $_table = array(db_sys_cron, db_sys_config, db_backup_site);
                break;

            case 'mail':
                $_table = array(db_mail_templates, db_mail_var, db_mail_list, db_mail_config);
                break;

            case 'pages':
                $_table = array(db_pages_admin, db_pages_main,
                                db_access_write, db_access_read, db_access_delete,
                                db_admin_cache);
                $_noData = array(db_admin_cache);
                break;
        }
        return array($_table, $_noData);
	}

}

?>
