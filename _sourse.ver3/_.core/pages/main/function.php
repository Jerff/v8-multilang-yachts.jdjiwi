<?php

function cmfGetUrl($page, $param=null) {
	$conf = cmfPages::getPage($page);
	if(!isset($conf['url'])) return false;
    $u = cmfPages::getBase($conf['part']) . cmfLang::getUri() . cmfUrl::reform($conf['url'], $param);
	$u = str_replace(
            array(
                '/prodagha_jaxt/stroitelstvo_jaxty/',
                'nsk-yachts.com.ua/blog/',
                'nsk-yachts.com.ua/en/blog/'
                ),
            array(
                '/building/',
                'blog.nsk-yachts.com.ua/',
                'blog.nsk-yachts.com.ua/en/'
                ),
            $u);
    return $u;
	return str_replace(
            array(
                'dev.blog.nsk-yachts.com.ua'
                ),
            array(
                'blog.dev.nsk-yachts.com.ua'
                ),
            $u);
}

function cmfGetLangUrl($lang, $page, $param=null) {
	$conf = cmfPages::getPage($page);
	if(!isset($conf['url'])) return false;
	return cmfPages::getBase($conf['part']) . cmfLang::getUri($lang) . cmfUrl::reform($conf['url'], $param);
}

function cmfChangeLang($lang, $url) {
	return cmfPages::getBase(cmfPart) . cmfLang::getUri($lang) . $url;
}

function cmfGetUri($page, $param=null) {
	$conf = cmfPages::getPage($page);
	if(!isset($conf['url'])) return false;
	return cmfLang::getUri() . cmfUrl::reform($conf['url'], $param);
}

?>