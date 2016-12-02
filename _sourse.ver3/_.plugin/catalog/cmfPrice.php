<?php

class cmfPrice {

    static public function currencyPrice($p, $i, $n) {
        switch ($i) {
            case 'EURO':
                $p = ($p / cmfConfig::get('site', 'usdVsEuro'));
                break;

            case 'RUR':
                $p = ($p / cmfConfig::get('site', 'usdVsRur'));
                break;

            case 'UAH':
                $p = ($p / cmfConfig::get('site', 'usdVsUah'));
                break;

            default:
                $p = ($p);
                break;
        }
        switch ($n) {
            case 'EURO':
                return round($p * cmfConfig::get('site', 'usdVsEuro'));
                break;

            case 'RUR':
                return round($p * cmfConfig::get('site', 'usdVsRur'));
                break;

            case 'UAH':
                return round($p * cmfConfig::get('site', 'usdVsUah'));
                break;

            default:
                return round($p);
                break;
        }
    }

    static public function currencyView($p, $c, $format = false) {
        static $i = 0;
        $i++;
        $html = '<select id="yachts-currency' . $i . '" class="yachts-currency" onchange="shangeCurrency(this);">';
        foreach (array('EURO', 'USD', 'RUR', 'UAH') as $cur) {
            $is = $cur == $c;
            if ($is) {
                $price = self::formatPrice($p);
            } else {
                if (is_numeric($p)) {
                    $price = self::formatPrice(self::currencyPrice($p, $c, $cur));
                } else {
                    preg_match_all('~([0-9\s]+)~', $p, $tmp);
                    if (empty($tmp[1])) {
                        $price = self::formatPrice(self::currencyPrice($p, $c, $cur));
                    } else {
                        $replace = array();
                        foreach ($tmp[1] as $value) {
                            $new = (int) preg_replace('~[^0-9]+~', '', $value);
                            $new = self::currencyPrice($new, $c, $cur);
                            $replace[(string)$value] = ' ' . self::format($new) . ' ';
                        }
                        uksort($replace, 'cmfPrice::priceSort');
                        $price = str_replace(array_keys($replace), $replace, $p);
                    }
                }
            }
            $html .= '<option value="' . $cur . '" ' . ($is ? 'selected="selected"' : '') . ' data-price="' . $price . '">' . $cur . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static public function formatPrice($p) {
        if (is_numeric($p)) {
            return self::format($p);
        } else {
            preg_match_all('~([0-9\s]+)~', $p, $tmp);
            if (empty($tmp[1])) {
                return self::format($p);
            } else {
                $replace = array();
                foreach ($tmp[1] as $value) {
                    $new = (int) preg_replace('~[^0-9]+~', '', $value);
                    $replace[(string)$value] = ' ' . self::format($new) . ' ';
                }
                uksort($replace, 'cmfPrice::priceSort');
                return str_replace(array_keys($replace), $replace, $p);
            }
        }
    }

    static public function priceSort($a, $b) {
        return strlen($a)<strlen($b);
    }

    static public function format($p) {
        return number_format((float) $p, 0, '.', ' ');
    }

    static public function view($p, $c) {
        if ($p) {
            return '<span class="yachts-price">' . self::formatPrice($p) . '</span>' . self::currencyView($p, $c, true);
        } else {
            return word('по запросу');
        }
    }

    static public function view2($p, $c) {
        if (!empty($p) and $p != 'по запросу') {
            return '<span class="yachts-price">' . self::formatPrice($p) . '</span>' . self::currencyView($p, $c);
        } else {
            return word('по запросу');
        }
    }

    static public function parse($p, $c) {
        $p = (float) preg_replace('~[^0-9\.e\+]+~', '', $p);
        switch ($c) {
            case 'EURO':
                return round($p * cmfConfig::get('site', 'usdVsEuro'));
                break;

            case 'RUR':
                return round($p * cmfConfig::get('site', 'usdVsRur'));
                break;

            case 'UAH':
                return round($p * cmfConfig::get('site', 'usdVsUah'));
                break;

            default:
                return round($p);
                break;
        }
    }

    static public function priceToUsd($p, $c) {
        $p = (float) preg_replace('~[^0-9\.e\+]+~', '', $p);
        switch ($c) {
            case 'EURO':
                return round($p / cmfConfig::get('site', 'usdVsEuro'));
                break;

            case 'RUR':
                return round($p / cmfConfig::get('site', 'usdVsRur'));
                break;

            case 'UAH':
                return round($p / cmfConfig::get('site', 'usdVsUah'));
                break;

            default:
                return round($p);
                break;
        }
    }

}

?>