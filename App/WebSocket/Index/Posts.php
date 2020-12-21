<?php declare(strict_types=1);
namespace App\WebSocket\Index;

use App\Model\ArticleHistory;
use App\Model\ArticleStar;
use App\Model\Comment;
use App\Model\CommentFavor;
use App\Model\UserFollow;
use EasySwoole\ORM\DbManager;
use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;
use EasySwoole\Mysqli\QueryBuilder;

use App\Model\User;
use App\Model\Article;
use App\Model\ArticleFavor;

use App\Common\Utils;

/**
 * 前端帖子相关
 */
class Posts extends Controller
{

    //获取推荐列表
    public function getRecommendList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $s_user = $base->session('user');

        $lists = Article::create()
            ->alias('a')
            ->join('topic as t', 't.id = a.tid')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
            ->limit(0, 5)
            ->order('RAND()')
            ->all([
                'a.status' => 1
            ]);

        //遍历判断当前用户是否关注
        $inArr = [];
        if($s_user){
            if($lists){
                $aids = [];
                foreach($lists as $v) $aids[] = $v['uid'];
                $inArr = UserFollow::create()
                    ->where('fuid', $aids, 'in')
                    ->where('uid', $s_user['id'])
                    ->column('fuid')?:[];
            }
        }
        foreach($lists as $k => $v) $v['follow'] = in_array($v['uid'], $inArr);

      $base->withData(0, 'success', ['list' => $lists]);
    }

    //获取关注列表
    public function getFollowList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 5;
        $page = $param['page']??1;

        $s_user = $base->session('user');
        if(!$s_user) $s_user = ['id' => 0];

        $lists = Article::create()
            ->alias('a')
            ->join('user_follow as f', 'f.fuid = a.uid')
            ->join('topic as t', 't.id = a.tid')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
            ->limit($limit * ($page - 1), $limit)
            ->order('a.id', 'DESC')
            ->all([
                'f.uid' => $s_user['id'],
                'a.status' => 1
            ]);

        //遍历判断当前用户是否关注
        if($s_user){
            foreach($lists as $k => $v){
                $v['follow'] = true; //本来就是关注的列表
            }
        }

        $base->withData(0, 'success', ['list' => $lists]);
    }

    //获取帖子列表 -个人中心
    public function getPostsList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 5;
        $page = $param['page']??1;

        $s_user = $base->session('user');
        if(!$s_user) $s_user = ['id' => 0];

        switch($param['type']){
            case 'release':
                //我的发布
                $lists = Article::create()->alias('a')
                    ->join('topic as t', 't.id = a.tid')
                    ->join('user as u', 'u.id = a.uid')
                    ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
                    ->limit($limit * ($page - 1), $limit)
                    ->order('a.id', 'DESC')
                    ->all([
                        'a.uid' => $s_user['id'],
                        'a.status' => 1
                    ]);
                break;
            case 'star':
                //我的收藏
                $lists = ArticleStar::create()->alias('s')
                    ->join('article as a', 'a.id = s.aid')
                    ->join('user as u', 'u.id = a.uid')
                    ->join('topic as t', 't.id = a.tid')
                    ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
                    ->limit($limit * ($page - 1), $limit)
                    ->order('s.id', 'DESC')
                    ->all([
                        's.uid' => $s_user['id'],
                        'a.status' => 1
                    ]);
                break;
            case 'good':
                //我的点赞
                $lists = ArticleFavor::create()->alias('af')
                    ->join('article as a', 'a.id = af.aid')
                    ->join('user as u', 'u.id = a.uid')
                    ->join('topic as t', 't.id = a.tid')
                    ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
                    ->limit($limit * ($page - 1), $limit)
                    ->order('af.id', 'DESC')
                    ->all([
                        'af.uid' => $s_user['id'],
                        'af.num' => 1,
                        'a.status' => 1
                    ]);
                break;
            case 'history':
                //浏览记录
                $lists = ArticleHistory::create()->alias('ah')
                    ->join('article as a', 'a.id = ah.aid')
                    ->join('user as u', 'u.id = a.uid')
                    ->join('topic as t', 't.id = a.tid')
                    ->field('a.*,u.id as uid,u.nickname,u.avatar,c.name as cname,t.id as tid')
                    ->limit($limit * ($page - 1), $limit)
                    ->order('ah.id', 'DESC')
                    ->all([
                        'ah.uid' => $s_user['id'],
                        'a.status' => 1
                    ]);
                break;
            default:
                return $base->withData(1, '参数错误');
                break;
        }

        //遍历判断当前用户是否关注
        $inArr = [];
        if($s_user){
            if($lists){
                $aids = [];
                foreach($lists as $v) $aids[] = $v['uid'];
                $inArr = UserFollow::create()
                    ->where('fuid', $aids, 'in')
                    ->where('uid', $s_user['id'])
                    ->column('fuid')?:[];
            }
        }
        foreach($lists as $k => $v) $v['follow'] = in_array($v['uid'], $inArr);

        $base->withData(0, 'success', ['list' => $lists]);
    }

    //获取指定用户列表
    public function getUserPostsList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;
        $page = $param['page']??1;

        $lists = Article::create()->alias('a')
            ->join('topic as t', 't.id = a.tid')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid')
            ->limit($limit * ($page - 1), $limit)
            ->order('a.id', 'DESC')
            ->all([
                'a.uid' => $param['uid']
            ]);

        $s_user = $base->session('user');

        //遍历判断当前用户是否关注
        if($s_user){

            $follow = UserFollow::create()
                ->where('uid', $s_user['id'])
                ->where('fuid', $param['uid'])
                ->count() ? true : false;
            foreach($lists as $k => $v){
                $v['follow'] = $follow;
            }
        }

        $base->withData(0, 'success', ['list' => $lists]);
    }

    //获取帖子内容
    public function getArticle()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $article = Article::create()
            ->alias('a')
            ->join('topic as t', 't.id = a.tid')
            ->join('user as u', 'u.id = a.uid')
            ->field('a.*,u.id as uid,u.nickname,u.avatar,t.name as cname,t.id as tid,t.icon')
            ->get(['a.id'=>$param['id']??0]);

        $isStar = false;
        $isFavor = false;

        if($article){
            $s_user = $base->session('user');
            if($s_user){
                $a_uid = $article->uid;
                //添加浏览记录
                ArticleHistory::add($article->id, $s_user['id']);

                //判断是否收藏
                if(ArticleStar::create()
                    ->where('uid', $s_user['id'])
                    ->where('aid', $article->id)
                    ->val('id'))
                    $isStar = true;

                //判断是否点赞
                if(ArticleFavor::create()
                    ->where('uid', $s_user['id'])
                    ->where('aid', $article->id)
                    ->val('num') == 1)
                    $isFavor = true;
            }else{
                $a_uid = 0;
            }

            //增加浏览量
            $article->update(['views' => QueryBuilder::inc()]);

            $base->withData(0, 'success', [
                'info' => $article,
                'isMy' => $a_uid == $article['uid'],
                'isStar' => $isStar,
                'isFavor' => $isFavor
            ]);
        }else{
            $base->withData(1, '未找到数据');
        }
    }

    //获取帖子评论列表
    public function getCommentList()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $limit = 10;
        $page = $param['page']??1;

        //取出10条审评或高赞评论 -> 按照赞数排序
        //后面的按时间排序

        $lists = Comment::create()
            ->alias('c')
            ->join('user as u', 'u.id = c.uid')
            ->field('c.*,u.avatar,u.nickname,u.avatar')
            ->limit($limit * ($page - 1), $limit)
            ->order('c.favor', 'DESC')
            ->order('c.id', 'DESC')
            ->all([
                'c.aid' => $param['id']
            ]);

        foreach($lists as $k => $v){
            $lists[$k]['time'] = Utils::formatExperience($v['create_time'], time());
        }

        $base->withData(0, 'success', ['list' => $lists]);
    }

    //发布评论
    public function sendComment()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user) return $base->withData(101, '您还未登录');

        $article = Article::create()->get($param['id']??0);
        if($article){
            //保存评论
            Comment::create([
                'aid' => $article['id'],
                'uid' => $s_user['id'],
                'content' => $param['content']
            ])->save();

            $base->withData(0, '发送成功');
        }else{
            $base->withData(404, '该帖子已不存在');
        }

    }

    //评论赞踩
    public function commentFavor()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user) return $base->withData(101, '您还未登录');

        $type = ((int)$param['type'])?1:-1;

        $comm = Comment::create()->get($param['id']??0);
        if($comm){
            try{
                //开启事务
                DbManager::getInstance()->startTransaction();

                if($cf = CommentFavor::create()->get(['tid' => $comm['id'], 'uid' => $s_user['id']])){
                    if($cf['type'] == $type){
                        DbManager::getInstance()->rollback();
                        return $base->withData(1, '请勿重复操作');
                    }
                    //操作数据库
                    $comm->update([
                        'favor' => ($cf['type'] == 1 ? QueryBuilder::dec(2) : QueryBuilder::inc(2))
                    ]);
                    $cf->update(['type'=>$type]);
                }else{
                    //操作数据库
                    $comm->update([
                        'favor' => ($type == 1 ? QueryBuilder::inc() : QueryBuilder::dec())
                    ]);
                    CommentFavor::create([
                        'tid' => $comm['id'],
                        'uid' => $s_user['id'],
                        'type' => $type
                    ])->save();
                }

                //提交事务
                DbManager::getInstance()->commit();
                $base->withData(0, '操作成功', $comm);
            } catch(\Throwable  $e){
                //回滚事务
                DbManager::getInstance()->rollback();
                $base->withData(1, '发生错误');
            }
        }else{
            $base->withData(404, '该评论已不存在');
        }
    }

    //帖子赞踩
    public function articleFavor()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if(!$s_user) return $base->withData(101, '您还未登录');

        $type = $param['type'];

        $article = Article::create()->get($param['id']??0);
        if($article){
            $num = $type == 1 ? 1 : -1;
            $uddata = [];

            //验证是否已操作
            $check = ArticleFavor::create()
                ->get(['aid' => $article->id, 'uid' => $s_user['id']]);
            if($check){
                if($check->num == $num){
                    return $base->withData(1, ($type == 1 ?'已赞过': '已踩过').'不能重复操作');
                }else{
                    //更改操作
                    $uddata[$type == 1?'up':'low'] = QueryBuilder::inc();
                    $uddata[$type == 1?'low':'up'] = QueryBuilder::dec();
                    //设置获赞
                    $article->user->setInfo(['appreciate' => [2, $type == 1?'add':'sub']]);
                }
            }else{
                $uddata[$type == 1?'up':'low'] = QueryBuilder::inc();
                //设置获赞
                $article->user->setInfo(['appreciate' => [1, $type == 1?'add':'sub']]);
            }

            $article->update($uddata);
            if($check){
                $check->update(['num' => $num]);
            }else{
                ArticleFavor::create([
                    'aid' => $article->id,
                    'uid' => $s_user['id'],
                    'num' => $num
                ])->save();
            }

            $base->withData(0, '操作成功', [
                'up' => $article['up'],
                'low'=> $article['low'],
                'isFavor' => $type == 1
            ]);
        }else{
            $base->withData(404, '该帖子已不存在');
        }
    }

    //帖子收藏
    public function articleStar()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());
        $param = $args['data'];

        $s_user = $base->session('user');
        if (!$s_user) return $base->withData(101, '您还未登录');
        $user = User::create()->get($s_user['id']);

        $type = (int)$param['type'];

        $article = Article::create()->get($param['id']??0);
        if($article){
            if($type){
                $article->update(['star' => QueryBuilder::inc()]);
                ArticleStar::create([
                    'aid' => $article['id'],
                    'uid' => $s_user['id']
                ])->save();
                $user->setInfo(['star'=> [1, 'add']]);
            }else{
                ArticleStar::create()->destroy([
                    'aid' => $article['id'],
                    'uid' => $s_user['id']
                ]);
                $article->update(['star' => QueryBuilder::dec()]);
                $user->setInfo(['star'=> [1, 'sub']]);
            }

            $base->withData(0, '操作成功', ['isStar' => (bool)$type, 'star' => $article['star']]);
        }else{
            $base->withData(404, '该帖子已不存在');
        }

    }

}
