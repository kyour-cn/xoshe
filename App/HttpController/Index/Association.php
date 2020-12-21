<?php declare(strict_types=1);
namespace App\HttpController\Index;

use App\Model\Association as AssocModel;
use EasySwoole\Validate\Validate;

/**
 * 社团相关控制器
 */
class Association extends BaseAuth
{
    public $authRule = [];

    //获取列表
    public function index()
    {
        //读取静态文件返回
        $this->fetch('index', [], 'raw');
    }

}
