<?php

class cmfWysiwyngConfig {

    static public function &getMap() {
        $_map = array(
            'shipyards' => array(cmfPathShipyards, 'shipyards_edit_controller'),
            'shipyards_yachts' => array(cmfPathShipyardsYachts, 'shipyards_yachts_edit_controller'),
            'shipyards_type' => array(cmfPathShipyardsType, 'shipyards_type_edit_controller'),
            'brokerage/yachts' => array(cmfPathBrokerageYachts, 'brokerage_yachts_edit_controller'),
            'brokerage/type' => array(cmfPathBrokerageType, 'brokerage_type_edit_controller'),
            'arenda' => array(cmfPathArenda, 'arenda_edit_controller'),
            'param' => 'param_edit_controller',
            'main' => 'main_info_controller',
            'info' => array(cmfPathInfo, 'menu_info_edit_controller'),
            'faq' => 'menu_faq_edit_controller',
            'content' => array(cmfPathContent, 'content_edit_controller'),
            'static' => 'content_static_edit_controller',
            'news' => array(cmfPathNews, 'news_edit_controller'),
            'article' => array(cmfPathArticle, 'article_edit_controller'),
            'reviews' => array(cmfPathArticle, 'reviews_edit_controller'),
            'blog' => array(cmfPathBlog, 'blog_edit_controller'),
        );
        return $_map;
    }

}

?>