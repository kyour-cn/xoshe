<?php declare(strict_types=1);
namespace App\WebSocket\Index;

use App\Model\TopicFollow;
use App\WebSocket\Base;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\Socket\AbstractInterface\Controller;

use App\Model\Topic as TopicModel;

class Topic extends Controller
{

    //话题关注
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

}
