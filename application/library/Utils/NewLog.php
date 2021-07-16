<?php

namespace Library\Utils;

use Exception;
use Illuminate\Support\Facades\Redis;
use Library\Traits\SingletonTrait;
use Monolog\Handler\RedisHandler;
use Monolog\Logger;
use Yaf\Application;

/**
 * Class NewLog
 * @package Base
 * @method static infoLog(array $message)
 * @method static errorLog(string $errorMsg)
 * @method static responseLog(string $data)
 * @method static queueLog(string $msg)
 * @method static jobFailLog(string $getMessage)
 * @method static issueEbsPushLog(string $msg)
 * @method static userInfoLog(false|string $json_encode_c)
 * @method static issueLog(string $string)
 */
class NewLog
{
    use SingletonTrait;

    private static $_gitCache = [];

    /**
     * @param $name
     * @param $arguments
     * @return bool
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if (str_ends_with('Log', $name)) return false;

        $logType = str_replace('Log', '', $name);

        $message = json_encode_c([
            'time' => getLogDateTime(),
            'ip' => gethostbyname(gethostname()),
            'tag' => $_SERVER['CUSTOM_TAG'],
            'logType' => $logType,
            'accessInfo' => self::extraInfo(),
            'messages' => head($arguments)
        ]);

//        $log = new Logger('OSS');
//        $log->pushHandler(new RedisHandler());

        // 本地环境则直接写入文件中
        if (!isDevelop()) {
            // 将日志写入redis

        }
        if (isDevelop()) {
            return self::localWrite($message);
        }

        // todo: 将日志队列写作为一个附加选项
//        return !!Queue::getInstance()->addLog($message);
    }

    /**
     * 日志存储的固定额外信息
     */
    private static function extraInfo()
    {
        return array_merge(self::getAccessInfo(), self::getGitInfo());
    }

    /**
     * @return array
     */
    private static function getAccessInfo(): array
    {
        $request = Application::app()->getDispatcher()->getRequest();

        $record = [
            'Module' => $request->getModuleName(),
            'Controller' => $request->getControllerName(),
            'Action' => $request->getActionName(),
            'Method' => $request->getMethod(),
        ];

        return $record;
    }

    /**
     * 获取git信息
     * @return array
     */
    private static function getGitInfo()
    {
        if (isEmpty(self::$_gitCache)) {
            $branches = `git branch -v --no-abbrev`;
            if (preg_match('{^\* (.+?)\s+([a-f0-9]{40})(?:\s|$)}m', $branches, $matches)) {
                self::$_gitCache = array(
                    'GitBranch' => $matches[1],
                    'GitCommit' => $matches[2],
                );
            }
        }

        return self::$_gitCache;
    }

    /**
     * @param string $message
     * @return bool
     */
    private static function localWrite(string $message)
    {
        $dir = LOG_DIR ?? joinPaths(APPLICATION_PATH, 'logs');
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }

        $filePath = joinPaths($dir, date('Y-m-d') . '.log');
        file_put_contents($filePath, $message . PHP_EOL, FILE_APPEND);

        return true;
    }
}