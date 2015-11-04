<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ScalarParam;

/**
 * @Annotation
 * @Target({"METHOD", "ANNOTATION"})
 */
class Boolean extends ScalarParam
{
}
