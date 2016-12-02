<?php


abstract class driver_modul_gallery_list_edit extends driver_modul_gallery_edit {


    protected function updateIsErrorData($data, &$isError) {
        if(!$this->getId() and !isset($data['image'])) {
            $isError = true;
            $this->getForm()->setError('image', 'Выберите файл');
        }
        return $isError;
    }

}

?>
