<?php

declare(strict_types=1);

use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;

if (! function_exists('readFileName')) {
    /**
     * 取出某目录下所有php文件的文件名.
     * @param string $path 文件夹目录
     * @return array 文件名
     */
    function readFileName(string $path): array
    {
        $data = [];
        if (! is_dir($path)) {
            return $data;
        }

        $files = scandir($path);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..', '.DS_Store'])) {
                continue;
            }
            $data[] = preg_replace('/(\w+)\.php/', '$1', $file);
        }
        return $data;
    }
}

if (! function_exists('responseDataFormat')) {
    function responseDataFormat($code, string $message = '', array $data = []): array
    {
        return [
            'code' => $code,
            'msg'  => $message,
            'data' => $data,
        ];
    }
}

if (! function_exists('isDiRequestInit')) {
    function isDiRequestInit(): bool
    {
        try {
            \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\RequestInterface::class)->input('test');
            $res = true;
        } catch (\TypeError $e) {
            $res = false;
        }
        return $res;
    }
}

if (! function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param null|string $id
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }
        return $container;
    }
}

if (! function_exists('format_throwable')) {
    /**
     * Push a job to async queue.
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(Hyperf\ExceptionHandler\Formatter\FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('dd')) {
    /**
     * 调试 打印传参到控制台.
     */
    function dd(...$var)
    {
        var_dump($var);
        exit();
    }
}

if (! function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}
