<?php declare(strict_types=1);
namespace App\Common\Extend;

/**
 * 文件FTP储存
 * https://github.com/Nicolab/php-ftp-client
 */

use FtpClient\FtpClient;

class Ftp
{
    //配置信息
    public $config;

    //连接客户端对象
    public $client;

    //初始化
    public function __construct(array $config = [])
    {
        /*
        //app配置中新增 FTP服务器
        'dev_res_ftp' => [
            'host' => 'xx',
            'port'    => 'xx',
            'user'  => 'xx',
            'password'  => 'xx'
        ],
         */
        $this->config = $config ?:config('app.dev_res_ftp');
    }

    //连接客户端
    private function conn() :array
    {
        if($this->client != null){
            return [null, $this->client];
        }
        try{
            $this->client = new FtpClient();
            $this->client->connect($this->config['host'], true, $this->config['port']??21);
            $this->client->login($this->config['user'], $this->config['password']);
            return [null, $this->client];
        } catch(\Throwable $e) {
            return [$e,null];
        }
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
        //连接云端
        list($err, $client) = $this->conn();
        if($err){
            return [$err,null];
        }
        try{
            //开始上传文件
            $client->putAll($file, $path);
            //删除源文件
            $delSource && unlink($file);
            return [null, true];
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
        //连接云端
        list($err, $client) = $this->conn();
        if($err) return [$err,null];
        try{
            $ret = $client->rmdir($path);
            return [null, $ret];
        } catch(\Throwable $e) {
            //返回报错
            return [$e, null];
        }
    }
}
