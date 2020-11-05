<?php
namespace App\WebSocket\Index;

use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;
use EasySwoole\Mysqli\QueryBuilder;

use App\Model\User as UserModel;

use App\Common\Utils;
use App\Common\Db;

/**
 * 前端帖子相关
 */
class User extends Controller
{
    //分类列表获取
    public function getInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
//        $param = $args['data'];

        $sess = $base->session('user');
        if(!$sess){
            $base->withData(101, '未登录');
            return;
        }
        $base->withData(0, 'success',['info' => $sess]);
    }

}
