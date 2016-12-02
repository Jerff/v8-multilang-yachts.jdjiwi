<?php


abstract class _interface_controller_siteUrl extends _interface_controller_ajax {

    private $url = null;
    protected function setSiteUrl($url) {
        $this->url = $url;
    }
    public function getSiteUrl() {
        return $this->url;
    }

    public function viewSiteData($d) {
        $data = $this->getModul()->getData();
        return get($data, $d);
    }

    public function viewSiteUrl() {
        $arg = func_get_args();
        $url = array_shift($arg);
        $url = cmfGetUrl($url, $arg);
        return $this->isCallFunction() ? $url : view_siteUrl::siteUrl($this->getId(), $url);
    }

    public function viewSiteUrl2() {
        $arg = func_get_args();
        $url = array_shift($arg);
        $url = cmfGetUrl($url, $arg);
        return $this->isCallFunction() ? $url : view_siteUrl::siteUrl(true, $url);
    }

    public function viewListSiteUrl() {
        $arg = func_get_args();
        $url = array_shift($arg);
        $url = cmfGetUrl($url, $arg);
        return view_siteUrl::viewListSiteUrl($url);
	}

    protected function updateSiteUrl() {
		if($this->getId()) {
            $url = $this->viewSiteUrl();
		    $this->getResponse()->addScript("
		        $('#viewSiteUrl').html('$url');
		        $('#viewSiteUrl').attr('href', '$url');
		    ");
		}
    }

}

?>
