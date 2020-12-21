<?php declare(strict_types=1);
/**
 * 用户管理
 */
namespace App\HttpController\Admin;

use App\Common\Db;
use App\Model\User as UserModel;
// use App\Common\Utils;
use EasySwoole\Validate\Validate;

class User extends BaseAuth
{

    //验证参数
    public $authRule = [
        ['uri' => 'admin']
    ];

    //默认首页
    public function getList()
    {
        $param = $this->input('get');
        $limit = (int)$param['limit']??10;
        $page = (int)$param['page']??1;

        $model = UserModel::create()
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
