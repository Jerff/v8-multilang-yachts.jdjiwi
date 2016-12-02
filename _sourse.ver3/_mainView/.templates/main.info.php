<?php

list($header, $header2, $footer2, $footer) = $this->runAll('/header/', '/info/header/', '/info/footer/', '/footer/');
//global $number_kiev;
//global $number_odessa;
//global $number_crimea;
//if ($number_odessa!='')
//{
//	$find_str = '+38 (094) 928-34-46';
//	$strpos = strpos($content, $find_str);
//	if ($strpos!==FALSE)
//	{
//		$content = substr_replace($content, $number_odessa, $strpos, strlen($find_str));
//	}
//}
//if ($number_kiev!='')
//{
//	$content = str_replace('+38 (044) 383-64-46', $number_kiev, $content);
//	$find_str = '<p><strong>Телефон:</strong> <span itemprop="telephone">+38 (094) 928-34-46</span> <span>(круглосуточно)</span></p>';
//	$strpos = strpos($content, $find_str);
//	if ($strpos!==FALSE)
//	{
//		$content = substr_replace($content, '', $strpos, strlen($find_str));
//	}
//}
//if ($number_crimea!='')
//{
//	$content = str_replace('+38 (094) 928-34-46', $number_crimea, $content);
//}
echo    $header .
        $header2 .
        $content .
        $footer2 .
        $footer;

?>