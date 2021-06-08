<?php
if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}

function mkpath($path)
{
    $dirs = array();
    $path = preg_replace('/(\/){2,}|(\\\){1,}/', '/', $path); //only forward-slash
    $dirs = explode("/", $path);
    $path = "";
    foreach ($dirs as $element) {
        $path .= $element . "/";
        if (!is_dir($path)) {
            if (!mkdir($path)) {echo "something was wrong at : " . $path;return 0;}
        }
    }
    //echo("<B>".$path."</B> successfully created");
}

function loadfile($file, $method = 'rb')
{
    $fp = fopen($file, $method);
    $data = fread($fp, filesize($file));
    fclose($fp);
    return $data;
}

function writefile($file, $content, $method = 'wb')
{
    $path = explode('/', $file);
    array_pop($path);
    $path = implode('/', $path);
    if (!file_exists($file)) {
        mkpath($path);
    }

    $fp = fopen($file, $method);
    fwrite($fp, $content);
    fclose($fp);
}

function get_url_content($url, $charset)
{
    if (function_exists('file_get_contents')) {
        $data = file_get_contents($url);
    } else {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
    }
    $encode_arr = array('UTF-8', 'ASCII', 'BIG5', 'JIS', 'eucjp-win', 'sjis-win', 'EUC-JP', 'GBK', 'GB2312');
    $encoded = mb_detect_encoding($data, $encode_arr);
    $data = mb_convert_encoding($data, $charset, $encoded);
    return $data;
}

function compress_html($string)
{
    $string = str_replace("\r\n", '', $string);
    $string = str_replace("\n", '', $string);
    $string = str_replace("\t", '', $string);
    $pattern = array(
        "'<!--[/!]*?[^<>]*?>'si",
        "'  '",
    );
    $replace = array(
        "",
        "",
    );
    return preg_replace($pattern, $replace, $string);
}

function clear_html($string)
{
    $pattern = array(
        "'<!--[/!]*?[^<>]*?>'si",
        "'  '",
    );
    $replace = array(
        "",
        "",
    );
    return preg_replace($pattern, $replace, $string);
}

//new best
function clean_html($string)
{
    $pattern = array(
        "#<!--[^\!\[]*?(?<!\/\/)-->#",
        "'  '",
    );
    $replace = array(
        "",
        "",
    );
    return str_ireplace(array("\r\n", "\r", "\n", "\t"), "", preg_replace($pattern, $replace, $string));
}

function cut($string, $start, $end)
{
    $string = explode($start, $string);
    $string = explode($end, $string[1]);
    return $string[0];
}

function str_cut($string, $start, $end)
{
    $string = strstr($string, $start);
    $string = substr($string, strlen($start), strpos($string, $end) - strlen($start));
    return $string;
}

function str_cutall($string, $start, $end, $retain = 0)
{
    $m = explode($start, $string);
    $a = array();
    $sum = count($m);
    if ($retain) {
        for ($i = 1; $i < $sum; $i++) {
            $my = explode($end, $m[$i]);
            $a[] = $start . $my[0] . $end;
            unset($my);
        }
    } else {
        for ($i = 1; $i < $sum; $i++) {
            $my = explode($end, $m[$i]);
            $a[] = $my[0];
            unset($my);
        }
    }
    return $a;
}

function utf8strcut($string, $length, $endfix, $charset)
{
    if (strlen($string) <= $length) {
        return $string;
    }

    return $string = mb_strcut($string, 0, $length - strlen($endfix), $charset) . $endfix;
}

/**
 * Fetch the contents of a remote fle.
 *
 * @param string The URL of the remote file
 * @return string The remote file contents.
 */
function fetch_remote_file($url, $post_data = array())
{
    $post_body = '';
    if (!empty($post_data)) {
        foreach ($post_data as $key => $val) {
            $post_body .= '&' . urlencode($key) . '=' . urlencode($val);
        }
        $post_body = ltrim($post_body, '&');
    }

    if (function_exists("curl_init")) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($post_body)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } else if (function_exists("fsockopen")) {
        $url = @parse_url($url);
        if (!$url['host']) {
            return false;
        }
        if (!$url['port']) {
            $url['port'] = 80;
        }
        if (!$url['path']) {
            $url['path'] = "/";
        }
        if ($url['query']) {
            $url['path'] .= "?{$url['query']}";
        }
        $fp = @fsockopen($url['host'], $url['port'], $error_no, $error, 10);
        @stream_set_timeout($fp, 10);
        if (!$fp) {
            return false;
        }
        $headers = array();
        if (!empty($post_body)) {
            $headers[] = "POST {$url['path']} HTTP/1.0";
            $headers[] = "Content-Length: " . strlen($post_body);
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
        } else {
            $headers[] = "GET {$url['path']} HTTP/1.0";
        }

        $headers[] = "Host: {$url['host']}";
        $headers[] = "Connection: Close";
        $headers[] = "\r\n";

        if (!empty($post_body)) {
            $headers[] = $post_body;
        }

        $headers = implode("\r\n", $headers);
        if (!@fwrite($fp, $headers)) {
            return false;
        }
        while (!feof($fp)) {
            $data .= fgets($fp, 12800);
        }
        fclose($fp);
        $data = explode("\r\n\r\n", $data, 2);
        return $data[1];
    } else if (empty($post_data)) {
        return @implode("", @file($url));
    } else {
        return false;
    }
}

function page($p, $total, $itemsperpage, $pagelimit, $pagenum, $sitelisturl, $sitelistindexurl = "", $reverse = "")
{
    $listlink = array();
    $i = 0;
    if ($pagelimit == 1) {
        return;
    }

    if ($p > $pagelimit) {
        return;
    }

    if ($pagenum > $total) {
        $pagenum = $total;
    }

    $pn = round(($pagenum - 1) / 2);
    $imin = $p - $pn;
    $imax = $p + $pn;
    if ($imin <= 0) {
        $imin = 1;
        $imax = $imax + 1;
        if ($p - $pn + $imax < $pagenum) {
            $imax = $pagenum;
        }

    }
    if ($imax > $pagelimit) {
        //$imin = $imin - 1;
        $imax = $pagelimit;
        if ($imax - $imin < $pagenum) {
            $imin = $imax - $pagenum + 1;
        }

        if ($imin <= 0) {
            $imin = 1;
        }

    }
    if ($p > ceil($pagenum / 2)) {
        $listlink[] = '<li><a href="' . $sitelistindexurl . '">first</a></li>';
        $listlink[] = '<li><a href="' . str_replace("{page}", ($p - 1), $sitelisturl) . '">prev</a></li>';
    }
    for ($i = $imin; $i <= $imax; $i++) {
        if ($i == $p) {
            $listlink[] = '<li>' . $p . '</li>';
        } else {
            if ($i == 1) {
                $listlink[] = '<li><a href="' . $sitelistindexurl . '">' . $i . '</a></li>';
            } else {
                $listlink[] = '<li><a href="' . str_replace("{page}", $i, $sitelisturl) . '">' . $i . '</a></li>';
            }
        }
        //echo $i;
    }
    if ($p < $pagelimit - floor($pagenum / 2)) {
        $listlink[] = '<li><a href="' . str_replace("{page}", ($p + 1), $sitelisturl) . '">next</a></li>';
        $listlink[] = '<li><a href="' . str_replace("{page}", $pagelimit, $sitelisturl) . '">end</a></li>';
    }
    if ($reverse) {
        return implode("", array_reverse($listlink));
    } else {
        return implode("", $listlink);
    }
} //End page()

function shortenNumber($n, $precision = 1)
{
    if ($n < 1e+3) {
        $out = number_format($n);
    } else if ($n < 1e+6) {
        $out = number_format($n / 1e+3, $precision) . 'K';
    } else if ($n < 1e+9) {
        $out = number_format($n / 1e+6, $precision) . 'M';
    } else if ($n < 1e+12) {
        $out = number_format($n / 1e+9, $precision) . 'B';
    }

    return $out;
}

//Byte KB、MB、GB、TB
function getFilesize($num)
{
    $p = 0;
    $format = 'bytes';
    if ($num > 0 && $num < 1024) {
        $p = 0;
        return number_format($num) . ' ' . $format;
    }
    if ($num >= 1024 && $num < pow(1024, 2)) {
        $p = 1;
        $format = 'KB';
    }
    if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
        $p = 2;
        $format = 'MB';
    }
    if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
        $p = 3;
        $format = 'GB';
    }
    if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
        $p = 3;
        $format = 'TB';
    }
    $num /= pow(1024, $p);
    return number_format($num, 3) . ' ' . $format;
}

function short_number($number, $devider = 1000, $precision = 3, $seprate = "", $type = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'))
{
    $i = 0;
    while ($number >= $devider) {
        $number /= $devider;
        $i++;
    }
    return round($number, $precision) . $seprate . $type[$i];
}

function trim_dotzero($number)
{
    return rtrim(rtrim(sprintf('%.8f', $number), "0"), ".");
}
