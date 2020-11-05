<?php
namespace App\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\Mysqli\QueryBuilder;
/**
 * Class Article
 */
class Article extends AbstractModel
{

    /**
      * @var string 
      */
    protected $tableName = 'article';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function setTitleAttr($value, $data)
    {
//        $value = Utils::emojiTextEncode($value);
        //最长200
        if(strlen($value) > 200){
            return mb_substr($value, 0 ,200) .'...';
        }
        return $value;
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
//    protected function getTitleAttr($value, $data)
//    {
//        return Utils::emojiTextDecode($value);
//    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
//    protected function getContentAttr($value, $data)
//    {
//        return Utils::emojiTextDecode($value);
//    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
//    protected function setContentAttr($value, $data)
//    {
//        $value = Utils::emojiTextEncode($value);
//        return $value;
//    }

    //模型关联
    public function user(string $fields = 'id,nickname,avatar')
    {
        return $this->hasOne(User::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'uid', 'id');
    }

    public function articleClass(string $fields = 'id,name,icon,model')
    {
        return $this->hasOne(ArticleClass::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'class', 'id');
    }
}
