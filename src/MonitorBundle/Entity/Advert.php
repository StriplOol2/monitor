<?php

namespace MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VLru\ApiBundle\Configuration\Serialization\Groups;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\AdvertRepository")
 * @ORM\Table(name="advert", indexes={
 *     @ORM\Index(columns={"search_id"}),
 *     @ORM\Index(columns={"hash"}),
 *     @ORM\Index(name="IDX_SEARCH_HASH", columns={"search_id", "hash"})
 * }))
 */
class Advert
{
    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Search
     * @ORM\ManyToOne(targetEntity="MonitorBundle\Entity\Search", inversedBy="adverts")
     * @ORM\JoinColumn(name="search_id", referencedColumnName="id", nullable=false)
     */
    protected $search;

    /**
     * @var string
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    protected $url;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $model;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $body;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $color;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $helm;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $city;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $engine;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $power;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $transmission;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $gear;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $mileage;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $additional;

    //@todo contacts

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected $price;

    /**
     * @var string|null
     * @ORM\Column(name="bulletin_id", type="string", length=300, nullable=true)
     */
    protected $bulletinId;

    /**
     * @var \DateTime
     * @ORM\Column(name="bulletin_date", type="datetime", nullable=false)
     */
    protected $bulletinDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     */
    protected $createAt;

    /**
     * @var int|null
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected $year;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $new;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $maker;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $hash;

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = (string) $hash;
        return $this;
    }

    /**
     * Advert constructor.
     */
    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param Search $search
     *
     * @return $this
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = (string) $model;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param null|string $body
     *
     * @return $this
     */
    public function setBody($body = null)
    {
        $this->body = $body;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param null|string $color
     *
     * @return $this
     */
    public function setColor($color = null)
    {
        $this->color = $color;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getHelm()
    {
        return $this->helm;
    }

    /**
     * @param null|string $helm
     *
     * @return $this
     */
    public function setHelm($helm = null)
    {
        $this->helm = $helm;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param null|string $engine
     *
     * @return $this
     */
    public function setEngine($engine = null)
    {
        $this->engine = $engine;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param null|string $power
     *
     * @return $this
     */
    public function setPower($power = null)
    {
        $this->power = $power;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * @param null|string $transmission
     *
     * @return $this
     */
    public function setTransmission($transmission = null)
    {
        $this->transmission = $transmission;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getGear()
    {
        return $this->gear;
    }

    /**
     * @param null|string $gear
     *
     * @return $this
     */
    public function setGear($gear = null)
    {
        $this->gear = $gear;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * @param null|string $mileage
     *
     * @return $this
     */
    public function setMileage($mileage = null)
    {
        $this->mileage = $mileage;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param null|string $additional
     *
     * @return $this
     */
    public function setAdditional($additional = null)
    {
        $this->additional = $additional;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = (int) $price;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getBulletinId()
    {
        return $this->bulletinId;
    }

    /**
     * @param null|string $bulletinId
     *
     * @return $this
     */
    public function setBulletinId($bulletinId = null)
    {
        $this->bulletinId = $bulletinId;
        $this->updateHash();
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBulletinDate()
    {
        return $this->bulletinDate;
    }

    /**
     * @Groups({"default"})
     * @return int
     */
    public function getBulletinDateTimestamp()
    {
        return $this->getBulletinDate()->getTimestamp();
    }

    /**
     * @param \DateTime $bulletinDate
     *
     * @return $this
     */
    public function setBulletinDate(\DateTime $bulletinDate)
    {
        $this->bulletinDate = $bulletinDate;
        $this->updateHash();
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @Groups({"default"})
     * @return int
     */
    public function getCreatedTimestamp()
    {
        return $this->getCreateAt()->getTimestamp();
    }

    /**
     * @param \DateTime $createAt
     *
     * @return $this
     */
    public function setCreateAt(\DateTime $createAt)
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return int|null
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     *
     * @return $this
     */
    public function setYear($year = null)
    {
        $this->year = $year;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return bool|null
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param bool|null $new
     *
     * @return $this
     */
    public function setNew($new = null)
    {
        $this->new = $new;
        $this->updateHash();
        return $this;
    }

    /**
     * @Groups({"default"})
     * @return null|string
     */
    public function getMaker()
    {
        return $this->maker;
    }

    /**
     * @param null|string $maker
     *
     * @return $this
     */
    public function setMaker($maker = null)
    {
        $this->maker = $maker;
        $this->updateHash();
        return $this;
    }


    /**
     * @return string
     */
    protected function updateHash()
    {
        $stringToHash = $this->url .
            $this->model .
            $this->transmission .
            $this->price .
            $this->body .
            $this->city .
            $this->color .
            $this->engine .
            $this->gear .
            $this->helm .
            $this->maker .
            $this->power .
            $this->mileage .
            $this->year;

        if ($this->getBulletinDate() instanceof \DateTime) {
            $stringToHash .= $this->getBulletinDate()->getTimestamp();
        }

        $this->setHash(md5($stringToHash));
    }
}
