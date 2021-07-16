<?php

use Illuminate\Support\Collection;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\Mime\MimeTypes;
use Yaf\Request_Abstract;

/**
 * 获取用户IP地址
 * @return mixed
 */
function getRemoteAddr()
{
    // 如果存在HTTP_X_FORWARD_FOR 则为透明代理服务器 第一个IP是真实IP
    if (!empty($xForward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '')) {
        return head(array_filter(explode(',', $xForward)));
    }

    return $_SERVER['REMOTE_ADDR'];
}

/**
 * 获取代码运行环境
 * @return string
 */
function getYafEnviron(): string
{
    return ini_get('yaf.environ');
}

/**
 * @param $value
 * @return false|string
 */
function setYafEnviron($value)
{
    return ini_set("yaf.environ", $value);
}

/**
 * 是否本地开发环境
 * @return bool
 */
function isDevelop(): bool
{
    return strtolower(getYafEnviron()) === 'develop';
}

/**
 * 是否测试环境
 * @return bool
 */
function isTest(): bool
{
    return strtolower(getYafEnviron()) === 'test';
}

/**
 * 是否生产环境
 * @return bool
 */
function isProduct(): bool
{
    return strtolower(getYafEnviron()) === 'product';
}

/**
 * json化中文编码
 * @param $var
 * @return false|string
 */
function json_encode_c($var)
{
    return json_encode($var, JSON_UNESCAPED_UNICODE);
}

/**
 * decode json串为数组
 * @param string $jsonStr
 * @return mixed
 */
function json_decode_array(string $jsonStr)
{
    return json_decode($jsonStr, true);
}

/**
 * 生成MD5值
 * @return string
 */
function md5Chars(): string
{
    $param = 22;
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = "";
    for ($i = 0; $i < $param; $i++) {
        $key .= $str{mt_rand(0, 61)};
    }
    $key .= time();
    return md5($key);
}

/**
 * 路径拼接
 * @return string
 */
function joinPaths(): string
{
    $paths = [];

    foreach (func_get_args() as $arg) {
        if ($arg !== '') $paths[] = $arg;
    }

    return preg_replace('#/+#', '/', join('/', $paths));
}

/**
 * 拼接URL地址
 * @param string $host
 * @param string $uri
 * @param array $params
 * @return string
 */
function joinUrl(string $host, string $uri, array $params = []): string
{
    $url = rtrim($host, '/') . '/' . ltrim($uri, '/');

    $schema = parse_url($url, PHP_URL_SCHEME);
    $schema = empty($schema) ? 'http' : $schema;

    $host = parse_url($url, PHP_URL_HOST);
    $port = parse_url($url, PHP_URL_PORT);
    if (!isEmpty($port)) {
        $host .= ':' . $port;
    }

    $uri = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    if (!empty($params)) {
        $query .= '&' . http_build_query($params);
    }

    return $schema . '://' . $host . $uri . (empty($query) ? '' : '?' . $query);
}

/**
 * 字符串以什么开头
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function startWith(string $haystack, string $needle): bool
{
    $length = mb_strlen($haystack);
    return mb_substr($needle, 0, $length) === $haystack;
}

/**
 * 字符串以什么结尾
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function endWith(string $haystack, string $needle): bool
{
    $length = mb_strlen($haystack);
    if (!$length) {
        return true;
    }

    return mb_substr($needle, $length) === $haystack;
}

/**
 * 字符串转小写驼峰
 * @param string $name
 * @return string
 */
function snakeToCamelCase(string $name): string
{
    $name = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

    return lcfirst($name);
}

/**
 * @param mixed $var
 * @return bool
 */
function isEmpty($var): bool
{
    if ($var instanceof Collection) {
        return $var->isEmpty();
    }

    return empty($var);
}

/**
 * @param $var
 * @return bool
 */
function notEmpty($var): bool
{
    return !isEmpty($var);
}

/**
 * @param $instance
 * @return mixed
 */
function objectToArray($instance)
{
    return json_decode_array(json_encode_c($instance));
}

/**
 * 解析从ps系统获取的数据从xml到array
 * @param string $xmlData
 * @return mixed
 */
function decodePsXmlToArray(string $xmlData)
{
    return json_decode_array(
        json_encode(
            simplexml_load_string($xmlData, 'SimpleXMLElement')
        )
    );
}

/**
 * @param string $mimeType
 * @return mixed
 */
function mimeToType(string $mimeType)
{
    return head(((new MimeTypes())->getExtensions($mimeType)));
}

/**
 * 数组扁平化 -取最后的key -相同键值导致覆盖
 * @param array $array
 * @param int $depth 是否递归扁平化
 * @return array
 */
function arrayFlatten(array $array, int $depth = 0)
{
    $res = [];

    foreach ($array as $key => $item) {
        $item = $item instanceof Collection ? $item->all() : $item;

        if (!is_array($item)) {
            $res[$key] = $item;
        } else {
            $values = $depth == 0 ? $item : arrayFlatten($item, $depth);

            foreach ($values as $k => $v) {
                $res[$k] = $v;
            }
        }
    }
    return $res;
}

/**
 * 获取原始HTTP请求体 - 主要用于soap接口通讯时捕获
 * @return false|string
 */
function getRawHttpBody()
{
    return @file_get_contents('php://input');
}

/**
 * 校验是否为有效的时间串
 * @param $timeStr
 * @return bool
 */
function validTime($timeStr)
{
    return strtotime($timeStr) !== false;
}

/**
 * 将数组展示为 key: value 的字符串形式
 * @param array $arr
 * @param string $glue
 * @return string
 */
function arrayDisplay(array $arr, string $glue = "\n")
{
    $temp = [];
    foreach ($arr as $key => $value) {
        $temp[] = "{$key}: {$value}";
    }

    return implode($glue, $temp);
}

/**
 * 获取到毫秒时间戳 精确到6位
 * @return string
 * @throws Exception
 */
function getLogDateTime()
{
    $t = microtime(true);
    $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
    $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));

    return $d->format("Y-m-d H:i:s.u");
}

/**
 * 按照pos插入元素到数组， pos不是索引
 * @param array $array
 * @param array $ext
 * @param int $pos
 * @return array
 */
function insertWithPos(array $array, array $ext, int $pos)
{
    $len = count($array);

    $last = array_splice($array, $pos, $len - $pos, $ext);

    return array_merge($array, $last);
}

/**
 * 字符串是否是由 字母、数字、小数点、中划线、下划线组成
 * @param $string
 * @return bool
 */
function isLetterLine(string $string)
{
    return !!preg_match('/^[a-zA-Z_]+$/', $string);
}

/**
 * @param string $filePath
 * @return string
 */
function imageBase64(string $filePath)
{
    return (string)ImageManagerStatic::make($filePath)->encode('data-url');
}

/**
 * @param Request_Abstract $request
 * @param mixed ...$args
 * @return array|mixed|null
 */
function getRequestData(Request_Abstract $request, ...$args)
{
    if (empty($args)) {
        if ($request->isPost()) return $request->getPost();
        else if ($request->isGet()) return $request->getQuery();
        else if ($request->isCli()) return $request->getParams();
        else return null;
    } else {
        $data = [];
        foreach ($args as $arg) {
            if ($request->isPost()) {
                $data[] = $request->getPost($arg);
            } else if ($request->isGet()) {
                $data[] = $request->getQuery($arg);
            } else if ($request->isCli()) {
                $data[] = $request->getParam($arg);
            }
        }
        if (count($data) == 1) {
            return head($data);
        }

        return $data;
    }
}
