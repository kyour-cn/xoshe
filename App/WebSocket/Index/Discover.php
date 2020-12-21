<?php declare(strict_types=1);
namespace App\WebSocket\Index;

use App\Model\Association as AssocModel;
use App\Model\AssocMember;
use App\Model\Topic as TopicModel;
use App\Model\TopicFollow;
use App\WebSocket\Base;
use EasySwoole\Socket\AbstractInterface\Controller;


/**
 * 前端发现页相关
 */
class Discover extends Controller
{

    //话题分类列表获取
    public function classList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;
        $page = $param['page']??1;

        $class_model = TopicModel::create()
            ->field('id,name,status,icon');

        $class_model->where('status', 1);
        //搜索优先级高于选择
        if(!empty($param['search'])){
            $class_model->where('name', '%'.$param['search'].'%', 'like')
                ->where('pid', 0, '>');
        }else if(isset($param['pid'])){
            //子类
            $class_model->where('pid', $param['pid']);
        }else{
            //主分类
            $limit = 100;
            $class_model->where('pid', 0);
        }

        //分页
        $class_model->limit($limit * ($page - 1), $limit);

        $list = $class_model->all();

//        $lastQuery = \EasySwoole\ORM\DbManager::getInstance()->getLastQuery()->getLastQuery();
//        var_dump($lastQuery);

        $s_user = $base->session('user');

        $inArr = [];
        //遍历是否关注
        if($s_user and isset($param['pid'])){
            if($list){
                $ids = [];
                foreach($list as $v) $ids[] = $v['id'];

                $inArr = TopicFollow::create()
                    ->where('tid', $ids, 'in')
                    ->where('uid', $s_user['id'])
                    ->column('tid')?:[];
            }
        }

        foreach($list as $k => $v){
            $v['follow'] = in_array($v['id'], $inArr);
        }

        $base->withData(0, 'success', [
            'list' => $list
        ]);
    }

    //获取社团列表
    public function assocList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $list = AssocModel::create()
            ->all();

        $base->withData(0, 'success', [
            'list' => $list
        ]);
    }

    //获取我的信息
    public function myList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $s_user = $base->session('user');
        if(!$s_user){
            return $base->withData(101, '未登录');
        }

        //我的话题
        $topic = TopicFollow::create()
            ->alias('tf')
            ->join('topic as t', 't.id = tf.tid')
            ->where('uid', $s_user['id'])
            ->field('t.*')
            ->limit(10)
            ->all();

        //我的社团
        $assoc = AssocMember::create()
            ->alias('am')
            ->join('association as a', 'a.id = am.aid')
            ->where('uid', $s_user['id'])
            ->field('a.*')
            ->limit(10)
            ->all();

        $base->withData(0, 'success', [
            'topic' => $topic,
            'assoc' => $assoc
        ]);
    }
}
