<?php


namespace App\Vendor\Code;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use App\Vendor\Code\Annotation\CodeAnnotation;

class CodeManager
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    protected function getGroup()
    {
        $collector = AnnotationCollector::getMethodsByAnnotation(CodeAnnotation::class);
        $new=array_shift($collector);
        return $new['annotation']->group;
    }

    public function getConfig()
    {
        $group = $this->getGroup();
        return $this->config->get("code.{$group}");
    }
}
