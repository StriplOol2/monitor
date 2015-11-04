<?php

namespace VLru\ApiBundle\Configuration\Serialization;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Groups extends \Symfony\Component\Serializer\Annotation\Groups
{
    /** @var string */
    private $mapping;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        if (array_key_exists('mapping', $data)) {
            $this->mapping = $data['mapping'];
        }
    }

    /**
     * @return string
     */
    public function getMapping()
    {
        return $this->mapping;
    }
}
