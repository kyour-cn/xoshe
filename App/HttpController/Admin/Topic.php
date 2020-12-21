<?php declare(strict_types=1);
/**
 * 用户管理
 */
namespace App\HttpController\Admin;

use App\Model\Topic as TopicModel;

class Topic extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['login' => 'admin']
    ];

    //获取列表
//    public function getList()
//    {
//        $param = $this->input('get');
//        $limit = (int)$param['limit']??10;
//        $page = (int)$param['page']??1;
//
//        $model = TopicModel::create()
//            ->limit($limit * ($page - 1), $limit)
//            ->withTotalCount();
//
//        $list = $model->all();
//        $count = $model->lastQueryResult()->getTotalCount();
//
//        $this->withData(0, 'success', [
//            'data' => $list,
//            'count' => $count,
//            'code' => 0
//        ]);
//    }

}
