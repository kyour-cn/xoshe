<?php declare(strict_types=1);
/**
 * 申请管理
 */
namespace App\HttpController\Admin;

use App\Common\Utils;
use App\Model\Apply as ApplyModel;
use App\Model\Topic;
use EasySwoole\ORM\DbManager;

class Apply extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['login' => 'admin']
    ];

    //获取话题申请列表
    public function getClassList()
    {
        $param = $this->input('get');
        $limit = (int)$param['limit']??10;
        $page = (int)$param['page']??1;

        $model = ApplyModel::create()
            ->limit($limit * ($page - 1), $limit)
            ->order('id', 'desc')
            ->withTotalCount();

        $listm = $model->all();
        $count = $model->lastQueryResult()->getTotalCount();

        $list = [];
        foreach($listm as $k => $v){
            $list[$k] = $v->toArray();
            $list[$k]['user'] = $v->user;
        }

        $this->withData(0, 'success', [
            'data' => $list,
            'count' => $count,
            'code' => 0
        ]);
    }

    //话题申请审批
    public function classHandle()
    {
        $post = $this->input('post');
        $apply = ApplyModel::create()->get($post['id']);

        try{
            //开启事务
            DbManager::getInstance()->startTransaction();

            $apply->update([
                'status' => $post['type'],
                'result' => $post['result']
            ]);

            //通过
            if($post['type'] == 1){
                //资源完整路径
                $fPath = 'avatar/c/'.date('Ym').'/'. md5((string) time()). rand(1000, 9999).  '.png';

                //创建话题
                $class = Topic::create([
                    'name' => $apply->data['name'],
                    'icon' => $fPath,
                    'pid' => $apply->data['pid'],
                    'model' => 'simple',
                    'founder' => $apply->uid
                ]);
                $class->save();

                //保存图片
                $fName = EASYSWOOLE_ROOT. '/Temp/avatar_'.md5((string) time());
                $ret = Utils::base64Image($apply->data['avatar'], $fName);
                if($ret) {
                    //开始上传
                    list($err, $ret) = Utils::saveFile($fName, $fPath);
                    if(!$ret){
                        DbManager::getInstance()->rollback();
                        return $this->withData(1, '图片保存失败');
                    }
                }else{
                    //回滚事务
                    DbManager::getInstance()->rollback();
                    $this->withData(1, '发生错误');
                }
                //删除临时文件
                @unlink($fName);
            }

        } catch(\Throwable  $e){
            //回滚事务
            DbManager::getInstance()->rollback();
            $this->withData(1, '发生错误');
        } finally {
            //提交事务
            DbManager::getInstance()->commit();
            $this->withData(0, '处理成功');
        }
    }

}
