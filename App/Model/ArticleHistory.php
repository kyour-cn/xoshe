<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class ArticleHistory
 */
class ArticleHistory extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'article_history';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'time';
    protected $updateTime = false;

    //添加一个记录
    public static function add($aid, $uid)
    {
        //先去除原来的
        self::create()
            ->where('aid', $aid)
            ->where('uid', $uid)
            ->destroy();

        //重新创建并返回
        return self::create([
            'aid' => $aid,
            'uid' => $uid
        ])->save();
    }

}
