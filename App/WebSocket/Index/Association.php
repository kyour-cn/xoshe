<?php declare(strict_types=1);
namespace App\WebSocket\Index;

use App\Model\Association as AssocModel;
use App\Model\Topic as TopicModel;
use App\Model\TopicFollow;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;
use App\Common\Utils;
use App\Common\Db;

/**
 * 社团相关控制器
 */
class Association extends Controller
{

    //社团加入
    public function topicFollow()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user){
            return $base->withData(101, '未登录');
        }

        $id = $param['id']??0;
        $follow = TopicModel::create()->get($id);
        $type = (bool)$param['type'];

        $check = TopicFollow::create()->get([
            'uid' => $s_user['id'],
            'tid' => $id
        ]);
        if($type){
            //取关
            if($check){
                $check->destroy();
                $follow->update(['fans' => QueryBuilder::dec(1)]);
            }
            return $base->withData(1, '取关成功');
        }else{
            if(!$check){
                //关注
                TopicFollow::create([
                    'uid' => $s_user['id'],
                    'tid' => $id
                ])->save();
                $follow->update(['fans' => QueryBuilder::inc(1)]);
            }
            return $base->withData(1, '已关注');
        }
    }

    //获取信息
    public function getInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user){
            return $base->withData(101, '未登录');
        }

        $assoc = AssocModel::create()->get($param['id']);

        return $base->withData(0, 'success', $assoc);
    }

}
