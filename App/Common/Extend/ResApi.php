<?php declare(strict_types=1);
namespace App\Common\Extend;

/**
 * 文件对象储存OSS
 */


class ResApi
{
    //配置信息
    public $config;

    //初始化
    public function __construct(array $config = [])
    {
        $this->config = $config ?:config('app.dev_res_api');
    }

    /**
     * 上传文件
     * @param $file string 本地文件地址
     * @param $path string 保存路径(包含文件名)
     * @param $delSource bool 是否删除源文件
     * @return array
     */
    public function upload(string $file, string $path, bool $delSource = false) :array
    {
        try{
            //开始上传文件
            $cli = new \Swoole\Coroutine\Http\Client($this->config['Host'], 80);
            $cli->setHeaders([
                'Host' => $this->config['Host']
            ]);
            $cli->set(['timeout' => -1]);
            $cli->addFile($file, 'file');
            $cli->post('/api.php', [
                'Path' => $path,
                'User' => $this->config['User'],
                'Secret' => $this->config['Secret'],
                'Action' => 'upload'
            ]);
            echo $cli->body;
            $cli->close();

            //删除源文件
            $delSource && unlink($file);
            return [null, $cli->body];
        } catch(\Throwable $e) {
            //返回报错
            return [$e, null];
        }
    }

    /**
     * 删除文件
     * @param $path string 保存路径(包含文件名)
     * @return array
     */
    public function delete(string $path) :array
    {
        try{
            $cli = new \Swoole\Coroutine\Http\Client($this->config['Host'], 80);
            $cli->setHeaders([
                'Host' => $this->config['Host']
            ]);
            $cli->set(['timeout' => -1]);
            $cli->post('/api.php', [
                'Path' => $path,
                'User' => $this->config['User'],
                'Secret' => $this->config['Secret'],
                'Action' => 'delete'
            ]);
            echo $cli->body;
            $cli->close();

            return [null, $cli->body];
        } catch(\Throwable $e) {
            //返回报错
            return [$e, null];
        }

    }
}
