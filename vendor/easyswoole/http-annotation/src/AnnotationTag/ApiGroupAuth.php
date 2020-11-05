<?php

namespace EasySwoole\HttpAnnotation\AnnotationTag;

/**
 * Class ApiGroupAuth
 * @package EasySwoole\HttpAnnotation\AnnotationTag
 * @Annotation
 */
class ApiGroupAuth extends Param
{
    public function tagName(): string
    {
        return 'ApiGroupAuth';
    }
}