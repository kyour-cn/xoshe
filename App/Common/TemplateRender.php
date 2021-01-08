<?php
namespace App\Common;

use EasySwoole\Template\RenderInterface;
use think\Template;

class TemplateRender implements RenderInterface
{
    protected $template;
    function __construct()
    {
        $config = config('view');
        $this->template = new Template($config);
    }

    public function render(string $template, ?array $data = null, ?array $options = null): ?string
    {
        // TODO: Implement render() method.
        ob_start();
        $this->template->assign($data);
        $this->template->fetch($template);
        return ob_get_contents();
    }

    public function onException(\Throwable $throwable ,$arg): string
    {
        // TODO: Implement onException() method.
        $msg = "{$throwable->getMessage()} at file:{$throwable->getFile()} line:{$throwable->getLine()}";
        trigger_error($msg);
        return $msg;
    }
}
