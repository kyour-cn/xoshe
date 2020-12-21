<?php
namespace App\WebSocket\Index;

use App\Model\Article;
use App\Model\UserFollow;
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
    //查询我的信息
    public function getInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $sess = $base->session('user');
        if(!$sess){
            return $base->withData(101, '未登录');
        }

        $user = UserModel::create()->get($sess['id'])->toArray();

        $user['born'] = date('Y-m-d', $user['born']);

        $base->withData(0, 'success',['info' => $user]);
    }

    //获取指定用户的主页信息
    public function getUserInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $user = UserModel::create()->default()->get($param['id']);
        if(!$user){
            return $base->withData(404, '找不到数据',['info' => $user]);
        }
        $arr = $user->toArray();

        //统计发帖数
        $countA = Article::create()->where(['uid' => [$user['id']], 'status' => [1]])->count();

        $arr['countA'] = $countA;

        //计算年龄
        $arr['age'] = intval((time() - $arr['born']) / (86400 * 365));

        $base->withData(0, 'success',['info' => $arr]);
    }

    //关注用户
    public function followUser()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user){
            return $base->withData(101, '未登录');
        }
        $type = (bool)$param['type'];

        $check = UserFollow::create()->get([
            'uid' => $s_user['id'],
            'fuid' => $param['fuid']
        ]);

        if($type) {
            //取关
            if($check){
                $check->destroy();
                //更新用户info
                UserModel::create()->get($param['fuid'])->setInfo(['fans' => [1, 'sub']]);
                UserModel::create()->get($s_user['id'])->setInfo(['follow' => [1, 'sub']]);
            }
            return $base->withData(1, '取关成功');
        }else{
            if(!$check) {
                //关注
                UserFollow::create([
                    'uid' => $s_user['id'],
                    'fuid' => $param['fuid']
                ])->save();
                //更新用户info
                UserModel::create()->get($param['fuid'])->setInfo(['fans' => [1, 'add']]);
                UserModel::create()->get($s_user['id'])->setInfo(['follow' => [1, 'add']]);
            }
            return $base->withData(1, '已关注');
        }
    }

    //关注用户列表
    public function getFollowList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;$page = $param['page']??1;

        $s_user = $base->session('user');
        if(!$s_user) $s_user = ['id' => 0];

        $lists = UserFollow::create()
            ->alias('f')
            ->join('user as u', 'f.fuid = u.id')
            ->field('u.id,u.nickname,u.avatar')
            ->limit($limit * ($page - 1), $limit)
            ->order('f.id', 'DESC')
            ->all([
                'f.uid' => $s_user['id']
            ]);

        $base->withData(0, 'success', ['list' => $lists]);
    }

    //粉丝列表
    public function getFansList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;$page = $param['page']??1;

        $s_user = $base->session('user');
        if(!$s_user) $s_user = ['id' => 0];

        $lists = UserFollow::create()
            ->alias('f')
            ->join('user as u', 'f.uid = u.id')
            ->field('u.id,u.nickname,u.avatar')
            ->limit($limit * ($page - 1), $limit)
            ->order('f.id', 'DESC')
            ->all([
                'f.fuid' => $s_user['id']
            ]);

        $base->withData(0, 'success', ['list' => $lists]);
    }

}
