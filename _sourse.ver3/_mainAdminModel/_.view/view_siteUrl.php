<?php


class view_siteUrl {

    const len = 50;
	protected static function replaceUrl($url) {
        $url = str_replace(cmfBaseUrl, '', $url);
        $len = cmfStrLen($url);
        if($len<self::len) return $url;
        $new = '';
        for($i=0; $i<=$len; $i+=self::len) {
            if($i) $new .= ' ';
            $new .= mb_substr($url, $i, self::len);
        }
        return $new;
    }

	public static function siteUrl($id, $url) {
        return $id ? 'Постоянная ссылка: <a href="'. $url .'" target="site" class="viewSiteUrl">'. self::replaceUrl($url) .'</a>' : 'Информация';
    }

	public static function viewSiteUrl($url) {
        return '<a href="'. $url .'" target="site" class="viewSiteUrl">'. self::replaceUrl($url) .'</a>';
    }

	public static function viewListSiteUrl($url) {
        return '<span class="button"><a href="'. $url .'" target="site">'. self::replaceUrl($url) .'</a></span>';
    }

}

?>
