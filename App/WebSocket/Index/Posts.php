<?php
namespace App\WebSocket\Index;

use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;
use EasySwoole\Mysqli\QueryBuilder;

use App\Model\User;
use App\Model\Article;
use App\Model\ArticleClass;
use App\Model\ArticleFavor;

use App\Common\Utils;
use App\Common\Db;

/**
 * 前端帖子相关
 */
class Posts extends Controller
{
    //分类列表获取
    public function classList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;
        $page = $param['page']??1;

        // $s_user = $base->session('user');
        // if(!$s_user){
        //     return;
        // }

        $class_model = ArticleClass::create()
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

        $base->withData(0, 'success', [
            'list' => $list
        ]);
    }

    //获取推荐列表
    public function getRecommendList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $article = Article::create()
            ->alias('a')
            ->join('article_class as c', 'c.id = a.class')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,c.name as cname,c.id as cid')
            ->limit(0, 30)
            ->order('RAND()')
            ->all();

        $base->withData(0, 'success', ['list' => $article]);

    }

    //获取关注列表
    public function getFollowList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;
        $page = $param['page']??1;

        $s_user = $base->session('user');
        if(!$s_user){
            $s_user = ['id' => 0];
        }

        $article = Article::create()
            ->alias('a')
            ->join('user_follow as f', 'f.fuid = a.uid')
            ->join('article_class as c', 'c.id = a.class')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,c.name as cname,c.id as cid')
            ->limit($limit * ($page - 1), $limit)
            ->order('a.id', 'DESC')
            ->all([
                'f.uid' => $s_user['id']
            ]);

        $base->withData(0, 'success', ['list' => $article]);

    }

    //获取帖子内容
    public function getArticle()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $article = Article::create()
            ->alias('a')
            ->join('article_class as c', 'c.id = a.class')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,c.name as cname,c.id as cid')
            ->get(['a.id'=>$param['id']??0]);

        if($article){
            $base->withData(0, 'success', $article);
        }else{
            $base->withData(1, '未找到数据');
        }
    }

    //帖子赞踩
    public function favor()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

         $s_user = $base->session('user');
         if(!$s_user){
             $base->withData(101, '您还未登录');
             return;
         }

         $article = Article::create()->get($param['id']??0);
         if($article){
             $uddata = [];
             if($param['type'] == 1){
                 $uddata['up'] = QueryBuilder::inc(1);
             }else{
                 $uddata['low'] = QueryBuilder::inc(1);
             }
             $article->update($uddata);
             ArticleFavor::create([
                 'aid' => $article->id,
                 'uid' => $s_user['id'],
                 'num' => $param['type'] == 1 ? 1 : '-1'
             ])->save();
             $base->withData(0, '操作成功');
         }else{
             $base->withData(404, '该帖子已不存在');
         }
    }
}
