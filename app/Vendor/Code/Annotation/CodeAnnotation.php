<?php

namespace App\Vendor\Code\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class CodeAnnotation extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $group = 'default';

    public function __construct($val)
    {
        parent::__construct($val);
    }
}
