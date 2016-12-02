<?php

class cmfImage {

    const logo = 'logo.png';

    static public function isImageMagic() {
        return ImageMagickIs;
    }

    static public function command($command) {
        if (ImageMagickPath) {
            return ImageMagickProg . ImageMagickPath . $command . ImageMagickProg;
        } else {
            return $command;
        }
    }

    static public function createLogo($size, $notice) {
        if (!self::isImageMagic())
            return;
        $notice = addslashes($notice);
        $path = cmfWWW . cmfPathWatermark;
        $logo = $path . cmfImage::logo;
        exec($com = self::command('convert') . " -size 400x100 xc:grey30 -font Arial -pointsize $size -gravity center  -draw \"fill grey70  text 0,0  '{$notice}'\" {$path}stamp_fgnd.png");
        exec($com = self::command('convert') . " -size 400x100 xc:black -font Arial -pointsize $size -gravity center  -draw \"fill white  text  1,1  '{$notice}' text  0,0  '{$notice}' fill black  text -1,-1 '{$notice}'\" +matte {$path}stamp_mask.png");
        exec($com = self::command('composite') . " -compose CopyOpacity {$path}stamp_mask.png {$path}stamp_fgnd.png {$logo}");
        exec($com = self::command('mogrify') . " -trim +repage {$logo}");
    }

    static public function watermark($image) {
        if (!self::isImageMagic())
            return;
        $place = cmfConfig::read('watermark', 'place');
        $type = cmfConfig::read('watermark', 'type');
        if ($type === 'text') {
            $logo = cmfWWW . cmfPathWatermark . cmfImage::logo;
        } else {
            $logo = cmfWWW . cmfPathWatermark . cmfConfig::read('watermark', 'image');
        }
        exec(self::command('composite') . " -gravity {$place} -geometry +10+10 {$logo} {$image} {$image}");
    }

    static public function resize($image, $width, $height) {
        if (!file_exists($image))
            return false;
        $size = getimagesize($image);
        if ($size === false)
            return false;

        // ���������� �������� ������ �� MIME-����������, ���������������
        // �������� getimagesize, � �������� ��������������� �������
        // imagecreatefrom-�������.
        $img_x = $size[0];
        $img_y = $size[1];
        if (is_array($width) or is_array($height)) {
            $width = get($width, 'max');
            $height = get($height, 'max');

            if ($img_x < $width and $img_y < $height)
                return;
        }

        if ($width === null or $width > $img_x)
            $width = $img_x;
        if ($height === null) {
            $height = $img_y / ($img_x / $width);
        }
        if ($height === null or $height > $img_y)
            $height = $img_y;
        if ($width === $img_x and $height === $img_y)
            return true;

        if (self::isImageMagic()) {
            exec($com = self::command('mogrify') . " -resize {$width}x{$height} $image");
            //pre($com);
            return;
        } else {

            $quality = 100;   // �������� ������
            $rgb = 0xFFFFFF; // ���� ����

            $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
            $icfunc = "imagecreatefrom" . $format;
            if (!function_exists($icfunc))
                return false;

            $x_ratio = $width / $img_x;
            $y_ratio = $height / $img_y;

            $ratio = min($x_ratio, $y_ratio);
            // ���������� ������
            $use_x_ratio = ($x_ratio == $ratio);

            $new_width = $use_x_ratio ? $width : floor($img_x * $ratio);
            $new_height = !$use_x_ratio ? $height : floor($img_y * $ratio);
            $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
            $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);


            $image_in = $icfunc($image);
            $image_out = imagecreatetruecolor($new_width, $new_height);

            imagefill($image_out, 0, 0, $rgb);
            imagecopyresampled($image_out, $image_in, 0, 0, 0, 0, $new_width, $new_height, $img_x, $img_y);
            imagedestroy($image_in);

            $icfunc = "image" . $format;
            $icfunc($image_out, $image, $quality);
            imagedestroy($image_out);
            return true;
        }
    }

    static public function thumbnail($small, $oWidth, $oHeight, $x1, $y1, $w, $h, $width, $height) {
        exec($com = self::command('convert') . " -size {$oWidth}x{$oHeight} {$small} -crop {$w}x{$h}+{$x1}+{$y1} -auto-orient +repage {$small}");
        pre($com);
        //echo '<br />'. $com;
        //exec($com = self::command('convert') ." -size {$oWidth}x{$oHeight} {$small} -thumbnail {$oWidth}x{$oHeight} -gravity center -crop {$x1}x{$y1}+{$w}+{$h} -auto-orient +repage {$small}");
        //exec($com = self::command('convert') ." -size {$oWidth}x{$oHeight} {$small} -thumbnail {$oWidth}x{$oHeight} -gravity center -crop {$x1}x{$y1}+{$w}+{$h} -auto-orient +repage {$small}");
        exec($com = self::command('mogrify') . " -resize {$width}x{$height} $small");
        pre($com);
        //echo '<br />'. $com;
    }

    /**
     * Масштабирование изображения из существующего файла
     *
     * @param string $srcfile - исходный файл
     * @param string $targetfile - конечный файл
     * @param integer $newW - новая ширина
     * @param integer $newH - новая высота
     * @param integer $Quality 1..100 - качество (jpeg)
     * @param string $mode - 'less', 'more', 'both'
     * @param boolean $RF - поворачивать ли размеры $newW и $newH, если ориентация изображения отличается от предполагаемой
     * @param boolean $crop - подрезать ли изображение
     * @return integer/boolean
     */
    static public function resizeImage2File($srcfile, $targetfile, $newW, $newH, $Quality = 100, $mode = 'less', $RF = true, $crop = true) {
        if(!is_file($srcfile)) {
            return false;
        }
//        pre(func_get_args());
        $imginfo = getimagesize($srcfile);
        $width = &$imginfo[0];
        $height = &$imginfo[1];

        $newsize = self::_processSizes($width, $height, $newW, $newH, $mode, $RF, $crop);
//        if(cmfDebug::isError()) {
            $imagick = new \Imagick($srcfile);
            $imagick->thumbnailImage($newsize['width'], $newsize['height']);
            $imagick->writeImage ($targetfile);
//            if(1) {
//                header("Content-Type: image/jpg");
//                echo $imagick->getImageBlob();
//                cmfDebug::destroy();
//            } else {
//                pre($newsize);
//
//            }
//            exit;
//        }
        $command = self::command('convert') . " {$srcfile} -resize";
        $rgeometry = $newsize['width'] . 'x' . $newsize['height'];
        if ($crop) {
            $rgeometry .= '^';
        }

        $command .= ' "' . $rgeometry . '"';
        if ($crop) {
            $command .= " -gravity center -crop {$newW}x{$newH}+0+0 +repage";
        }
        $command .= " -quality $Quality";
        $command .= " {$targetfile}";
        exec($command, $r, $v);

        if (is_file($targetfile)) {
            return filesize($targetfile);
        }
        return false;
    }

    /**
     * функция для вычисления новых размеров изображения
     *
     * @param integer $w - исходная ширина
     * @param integer $h - исходная высота
     * @param integer $nw - новая ширина
     * @param integer $nh - новая высота
     * @param string $mode - 'less', 'more', 'both'
     * @param boolean $RF - поворачивать ли размеры $newW и $newH, если ориентация изображения отличается от предполагаемой
     * @param boolean $crop - подрезать ли изображение
     * @return array('width'=>..., 'height'=>..., ...); 'width','height' - конечные размеры изображения, wshift, hshift, wcut, hcut - начальные позиции и продолжительность "окна", из которого из исходного изображения вырезается кусок
     */
    static private function _processSizes($w, $h, $nw, $nh, $mode = 'less', $RF = true, $crop = false) {

        $mode = strtolower($mode);
        switch ($mode) {
            case 'more' :
                $less = false;
                $more = true;
                break;

            case 'both' :
                $less = true;
                $more = true;
                break;

            default :
                $less = true;
                $more = false;
                break;
        }

        if ($RF && ($w != $h) && (($w < $h) xor ($nw < $nh))) {
            $t = $nw;
            $nw = $nh;
            $nh = $t;
        }

        $rw = $nw / $w;
        $rh = $nh / $h;
        if ($crop) {
            if (($w > $nw && $h > $nh && $less) or ($w < $nw || $h < $nh) && $more or ($w == $nw || $h == $nh)) {
                if ($rw > $rh) {
                    $result['width'] = $nw;
                    $result['height'] = $nh;
                    $result['wcut'] = $w;
                    $result['wshift'] = 0;
                    $result['hcut'] = (int) round($w * $nh / $nw);
                    $result['hshift'] = (int) round(($h - $result['hcut']) / 2);
                } else {

                    $result['width'] = $nw;
                    $result['height'] = $nh;
                    $result['wcut'] = (int) round($h * $nw / $nh);
                    $result['wshift'] = (int) round(($w - $result['wcut']) / 2);
                    $result['hcut'] = $h;
                    $result['hshift'] = 0;
                }
            } elseif ($w > $nw && $h > $nh) {

                $result['width'] = $nw;
                $result['height'] = $nh;
                $result['wcut'] = $nw;
                $result['wshift'] = (int) round(($w - $nw) / 2);
                $result['hcut'] = $nh;
                $result['hshift'] = (int) round(($h - $nh) / 2);
            } elseif ($w < $nw || $h < $nh) {

                if ($w < $nw && $h < $nh) {
                    $result['width'] = $w;
                    $result['height'] = $h;
                    $result['wcut'] = $w;
                    $result['wshift'] = 0;
                    $result['hcut'] = $h;
                    $result['hshift'] = 0;
                } elseif ($w < $nw) {

                    $result['width'] = $w;
                    $result['height'] = $nh;
                    $result['wcut'] = $w;
                    $result['wshift'] = 0;
                    $result['hcut'] = $nh;
                    $result['hshift'] = (int) round(($h - $nh) / 2);
                } else {

                    $result['width'] = $nw;
                    $result['height'] = $h;
                    $result['wcut'] = $nw;
                    $result['wshift'] = (int) round(($w - $nw) / 2);
                    $result['hcut'] = $h;
                    $result['hshift'] = 0;
                }
            }
        } else {

            if (($w < $nw && $h < $nh && $more) or (($w > $nw || $h > $nh) && $less) or ($w == $nw || $h == $nh)) {
                if ($rw > $rh) {
                    $result['width'] = (int) round($w * $rh);
                    $result['height'] = $nh;
                } else {
                    $result['width'] = $nw;
                    $result['height'] = (int) round($h * $rw);
                }
            } else {
                $result['width'] = $w;
                $result['height'] = $h;
            }

            $result['wshift'] = $result['hshift'] = 0;
            $result['wcut'] = $w;
            $result['hcut'] = $h;
        }

        $result['string'] = 'width=' . $result['width'] . ' height=' . $result['height'];
        return $result;
    }

    static public function preview($img, $newW, $newH, $alias='',$i = 0,$i2 = 0,$i3 = 0) {
        static $isRun = false;
        if(!$isRun) {
            set_time_limit(0);
            ignore_user_abort(true);
            $isRun= true;
        }

		$prefix = '';
        if(empty($alias)){
			$name = sha1($img . $newW . $newH) . preg_replace('`(.*)\.([^.]*)$`', '.$2', $img);
		}else{
			$alias = _tr($alias);
            if(empty($i)) $prefix = substr($alias, 0, 2);
			else $prefix = substr($alias, 0, 2) .'/'. $i .($i2 ? '-'. $i2 : '') .($i3 ? '-'. $i3 : '');
			$name = $alias . preg_replace('`(.*)\.([^.]*)$`', '.$2', $img);
		}

        //$path = cmfPathPreview . substr($name, 0, 1) . '/' . substr($name, 1, 1) . '/';
		$path = cmfPathPreview_new .$prefix .'/' .$newW .'x'. $newH .'/';
        if (!is_file(cmfWWW . $path . $name) or cmfDebug::isError()) {
            if (!is_dir(cmfWWW . $path)) {
                if (!cmfDir::mkdir(cmfWWW . $path)) {
                    new cmfException('file_upload.not create folder', $path);
                }
            }
            if(strpos($img, 'http://')!==0) {
                $img = cmfWWW . $img;
            } elseif(strpos($img, cmfBaseImg)===0) {
                $img = str_replace(cmfBaseImg, cmfWWW, $img);
            }
            self::resizeImage2File($img, cmfWWW . $path . $name, $newW, $newH);
        }
        return cmfBaseImg . $path . $name;
    }

}

?>
