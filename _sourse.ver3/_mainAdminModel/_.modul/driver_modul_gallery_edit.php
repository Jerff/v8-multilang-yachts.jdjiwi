<?php


abstract class driver_modul_gallery_edit extends driver_modul_edit {


    protected function saveStart(&$send) {
        parent::saveStart($send);
        if(isset($send['main'])) {
            $this->setNewView();
        }
		if($this->getId()) {
    		$this->setNewView();
        }
	}


    private $path = null;
    protected function setPath($path) {
        $this->path = $path;
    }
    public function getPath() {
        return $this->path;
    }

    private $size = null;
    protected function setSize($w, $h) {
        $this->size = array($w, $h);
	}
	public function getSize() {
	    return $this->size;
	}


    public function updatePreview() {
        $real = isset($_POST['real']);
        $x1 = (int)get($_POST, 'x1');
        $x2 = (int)get($_POST, 'x2');
        $y1 = (int)get($_POST, 'y1');
        $y2 = (int)get($_POST, 'y2');
        $w = (int)get($_POST, 'w');
        if(!$x2 or !$y2) return;

	    $data = $this->runData();
	    if(empty($data['image_main'])) return;


	    $main = cmfWWW . $this->getPath() . $data['image_main'];
        $size = getimagesize($main);
	    $oWidth = $size[0];
	    $oHeight = $size[1];

        list($width, $height) = $this->getSize();
	    $scaleX = $oWidth/($w ? $w : view_gallery::width);
	    $x1 = ceil($x1*$scaleX);
	    $x2 = ceil($x2*$scaleX);
	    $y1 = ceil($y1*$scaleX);
	    $y2 = ceil($y2*$scaleX);

    	$w = $x2-$x1;
    	$h = $y2-$y1;
	    if(!$w or !$h) return;
	    if(!$real) {
            if($width/$w!=$height/$h) {
                $max = $w/$width;
                if($h/$height>$max) {
                    $max = $h/$height;
                }
                $x2 = $x1 + ceil($width*$max);
                $y2 = $y1 + ceil($height*$max);

                $w = $x2-$x1;
    	        $h = $y2-$y1;
//                pre(ceil($width*$max), ceil($height*$max));
            }
	    }

//	    pre(array($main, $x1, $y1, $w, $h, $oWidth, $oHeight), $data, $width, $height);
//
//        exit;
	    $this->updatePreviewAll(array($main, $x1, $y1, $w, $h, $oWidth, $oHeight), $data, $width, $height);
        $image = cmfBaseImg . $this->getPath() . $data['image_small'] .'?'. time();
        $this->getResponse()->addScript("$('#galleryPreviewId img, #preview". $this->getId() ."').attr('src', '{$image}');");
        $this->save(array());
	}

	protected function updatePreviewAll($res, $data, $width, $height) {
         $this->updateThumbnail($res, $data['image_small'], $width, $height);
	}

	protected function updateThumbnail($res, $small, $width, $height) {
        if(empty($small)) return;
        list($main, $x1, $y1, $w, $h, $oWidth, $oHeight) = $res;
        $small = cmfWWW . $this->getPath() . $small;

        file_put_contents($small, file_get_contents($main));
        cmfFile::chmod($small);
        //cmfFile::unlink($small);
	    //cmfFile::copy($main, $small);
	    cmfImage::thumbnail($small, $oWidth, $oHeight, $x1, $y1, $w, $h, $width, $height);
	}

}

?>
