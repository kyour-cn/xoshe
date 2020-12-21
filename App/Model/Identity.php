<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class Identity
 */
class Identity extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'identity';

    /**
     * 权限检测
     * @param $uri string|int uri字符串或者UserRule的id
     * @param $uid int 用户Id
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function check($uri, int $uid): bool
    {
        $id = User::create()->where('id', $uid)->val('identity');
        if(empty($id)) return false;

        if(gettype($uri) == 'string'){
            $k = 'user_rule_'.$uri;
            $c = container($k);
            if($c){
                $rid = $c;
            }else{
                $rid = UserRule::create()->where('uri', $uri)->val('id');
                container($k, $rid);
            }
            if(empty($rid)) return false;
        }else{
            $rid = $uri;
        }

        $identity = self::create()
            ->where("find_in_set('{$rid}',rules)")
            ->where('id', $id)
            ->val('id');

        return $identity ? true : false;
    }
}
