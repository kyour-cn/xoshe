<?php


namespace EasySwoole\HttpAnnotation\Annotation;


use EasySwoole\Annotation\Annotation;

interface ParserInterface
{
    function __construct(?Annotation $annotation = null);

    function parseObject(\ReflectionClass  $reflectionClass):ObjectAnnotation;
}