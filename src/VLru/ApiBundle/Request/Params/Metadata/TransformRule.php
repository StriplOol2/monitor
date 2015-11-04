<?php

namespace VLru\ApiBundle\Request\Params\Metadata;

class TransformRule
{
    /** @var string */
    public $transformBy;

    /** @var string[] */
    public $mapping;

    /** @var array */
    public $options;
}
