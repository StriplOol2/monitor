<?php

namespace VLru\ApiBundle\Request\Params\Metadata;

use Symfony\Component\Validator\Constraint;

class ActionMetadata
{
    /** @var Constraint[][] */
    public $sourceConstraints = [];

    /** @var TransformRule[] */
    public $transformRules = [];

    /** @var Constraint[] */
    public $valueConstraints = [];

    /** @var string[] */
    public $paramsBags = [];

    /** @var string */
    public $arraySources = [];

    /** @var string[] */
    public $valueConverters = [];
}
