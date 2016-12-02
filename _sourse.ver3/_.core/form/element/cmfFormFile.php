<?php

class cmfFormFile extends cmfFormElement {

    protected $UPLOAD_FILE = '|7z|aiff|asf|avi|bmp|csv|doc|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xml|zip|';
    protected $size = null;
    protected $upload = true;
    protected $fileSize = null;
    protected $path = null;

    protected function init($o) {
        if (isset($o['size']) and is_array($o['size']) and count($o['size']) == 2)
            $this->setSize($o['size']);
        if (isset($o['path']))
            $this->setPath($o['path']);
        if (isset($o['fileSize']))
            $this->fileSize = $o['fileSize'];
        if (isset($o['noUpload']))
            $this->setUpload(false);

        parent::init($o);
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFolder() {
        return cmfWWW . $this->getPath();
    }

    protected function setUpload($s) {
        $this->upload = $s;
    }

    protected function isUpload() {
        return $this->upload;
    }

    public function html($param, $style = '') {
        return "<input type=\"file\" name=\"" . ($name = $this->getId()) . "\" id=\"{$name}\" type=\"{file}\" {$style} />";
    }

    public function htmlOld() {
        return null;
    }

    public function isFile() {
        return true;
    }

    protected function jsUpdateValue() {
        return '';
    }

    public function jsUpdateOld() {
        return '';
    }

    public function processing($data, $old, $upload) {
        $name = $this->getId();
        $files = $this->form()->getRequest()->getFiles($name);
        $file = $this->uploadFile($name, $files);
        if ($file and $upload) {
            $file = $this->upload($this->getElementId(), $files);
        }
        return $file;
    }

    protected function upload($name, $file) {
        $resize = $this->getSize();
        if (!is_null($resize)) {
            list($width, $height) = $resize;
            cmfImage::resize($file['tmp_name'], $width, $height);
        }

        if ($this->isUpload()) {
            $name = cmfFile::upload($this->getFolder(), $file);
        } else {
            $name = $file['tmp_name'];
        }

        return $name;
    }

    protected function uploadFile($name, $file) {
        if (!isset($file['error']))
            return null;
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $f_error = $file['error'];
            switch ($file['error']) {
                case UPLOAD_ERR_NO_FILE:
                    if ($this->NotEmpty()) {
                        cmfFormError::set(word("Файл не был загружен"));
                    }
                    break;

                case UPLOAD_ERR_PARTIAL:
                    cmfFormError::set(word("Файл был получен только частично"));
                    break;

                case UPLOAD_ERR_FORM_SIZE:
                    cmfFormError::set(word("Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме"));
                    break;

                case UPLOAD_ERR_INI_SIZE:
                    cmfFormError::set(word("Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini"));
                    break;
            }
            return null;
        }

        $sif = preg_replace('`.*\.([^.]*)$`', '$1', $file['name']);
        if (!$sif) {
            cmfFormError::set(word("разрешение не поддерживается"));
            return null;
        }
        if (strpos($this->UPLOAD_FILE, '|' . $sif . '|') === false) {
            cmfFormError::set(word("разрешение не поддерживается"));
            return null;
        }

        if ($this->fileSize) {
            $size = $this->fileSize;
            if ($file['size'] > $size * 1024 * 1024) {
                cmfFormError::set(
                        str_replace('%m%', $size, word("Размер загружаемого файла превысил %m% Mb"))
                );
                return null;
            }
        }

        if ($this->isUpload()) {
            return $file['tmp_name'];
        } else {
            return serialize($file);
        }
    }

    public function deleteFile(&$row, $key=-1) {
        if($key==-1) {
            cmfFile::unlink($this->getFolder() . $this->getValue());
        } else {
            $image = cmfString::unserialize($this->getValue());
            if(isset($image[$key])) {
                cmfFile::unlink($this->getFolder() . $image[$key]);
                unset($image[$key]);
                $row[$this->getElementId()] = cmfString::serialize($image);
                $row['isDelete'] = true;
            }
        }
    }

    public function copyFile(&$row, $name) {
        $row[$name] = cmfFile::copy($this->getFolder() . $this->getValue(), $this->getFolder() . $this->getValue());
    }

}

class cmfFormFileMulti extends cmfFormFile {

    protected $UPLOAD_FILE = '|gif|jpeg|jpg|png|';

    public function html($param, $style = '') {
        return "<input type=\"file\" name=\"" . ($name = $this->getId()) . "[{key}]\" id=\"{$name}[{key}]\" type=\"{file}\" {$style} class=\"cmfHide\" />";
    }

    protected static function setError($key, $error) {
        cmfFormError::set($error, 'formErrorImage' . $key);
    }

    protected function uploadFile($name, $files) {
        if (empty($files['name']))
            return null;
        foreach ($files['name'] as $key => $value) {
            if (!isset($files['error'][$key])) {
                continue;
            }
            if ($files['error'][$key] !== UPLOAD_ERR_OK) {
                $f_error = $files['error'][$key];
                switch ($files['error'][$key]) {
                    case UPLOAD_ERR_NO_FILE:
                        if ($this->NotEmpty()) {
                            self::setError($key, word("Файл не был загружен"));
                        }
                        break;

                    case UPLOAD_ERR_PARTIAL:
                        self::setError($key, word("Файл был получен только частично"));
                        break;

                    case UPLOAD_ERR_FORM_SIZE:
                        self::setError($key, word("Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме"));
                        break;

                    case UPLOAD_ERR_INI_SIZE:
                        self::setError($key, word("Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini"));
                        break;
                }
                continue;
            }

            $sif = preg_replace('`.*\.([^.]*)$`', '$1', $files['name'][$key]);
            if (!$sif) {
                self::setError($key, word(str_replace('%m%', $sif, "разрешение не поддерживается (%m%)")));
                continue;
            }
            if (strpos($this->UPLOAD_FILE, '|' . $sif . '|') === false) {
                self::setError($key, word(str_replace('%m%', $sif, "разрешение не поддерживается (%m%)")));
                continue;
            }

            if ($this->fileSize) {
                $size = $this->fileSize;
                if ($files['size'][$key] > $size * 1024 * 1024) {
                    self::setError(
                            $key, str_replace('%m%', $size, word("Размер загружаемого файла превысил %m% Mb"))
                    );
                    continue;
                }
            }
        }

        if ($this->isUpload()) {
            unset($files['tmp_name']['{key}']);
            return serialize($files['tmp_name']);
        } else {
            return serialize($file);
        }
    }

    public function jsUpdate() {
        $id = $this->getId();
        $error = $this->form()->getError();
        $js = "\n\$('.formErrorImage').hide();";
        if (is_array($this->form()->getError())) {
            foreach ($this->form()->getError() as $key => $value) {
                if (strpos($key, 'formErrorImage') !== false and !empty($value)) {
                    $js .= "\ncmf.form.error.view('{$id}', '{$key}', '{$value}');";
                }
            }
        }
        return $js;
    }

    protected function upload($name, $files) {
        $resize = $this->getSize();
        $name = array();
        if (empty($files['name']))
            return null;
        foreach ($files['name'] as $key => $value) {
            if (empty($value))
                continue;
            if (!is_null($resize)) {
                list($width, $height) = $resize;
                cmfImage::resize($files['tmp_name'][$key], $width, $height);
            }
            if ($this->isUpload()) {
                $new = cmfFile::upload($this->getFolder(), $files, $key);
            } else {
                $new = $file['tmp_name'];
            }
            if(!empty($new)) {
                $name[] = $new;
            }
        }

        return empty($name) ? null : cmfString::serialize($name);
    }
}

class cmfFormFileMain extends cmfFormFileMulti {

    public function htmlError($s = null, $s2 = null) {
        if ($this->isErrorView()) {
            return '<span class="formwrap">' . $s . $s2 . '</span>';
        } else {
            return $s . $s2;
        }
    }

}

?>