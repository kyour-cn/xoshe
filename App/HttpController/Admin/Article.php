<?php declare(strict_types=1);
/**
 * 帖子管理
 */
namespace App\HttpController\Admin;

use App\Model\Article as ArticleModel;

class Article extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['login' => 'admin']
    ];

    //默认首页
    public function getList()
    {
        $param = $this->input('get');
        $limit = (int)$param['limit']??10;
        $page = (int)$param['page']??1;

        $model = ArticleModel::create()
            ->limit($limit * ($page - 1), $limit)
            ->withTotalCount();

        $list = $model->all();
        $count = $model->lastQueryResult()->getTotalCount();

        $this->withData(0, 'success', [
            'data' => $list,
            'count' => $count,
            'code' => 0
        ]);
    }

}
