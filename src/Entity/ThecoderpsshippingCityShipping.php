<?php
// modules/yourmodule/src/Entity/ProductComment.php
namespace Thecoderpsshipping\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ThecoderpsshippingCityShipping
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_thecoderpsshipping_city_shipping", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_thecoderpsshipping", type="integer")
     */
    private $thecoderpsshippingId;


    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=20, scale=6)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getthecoderpsshippingId()
    {
        return $this->thecoderpsshippingId;
    }

    /**
     * @param int $thecoderpsshippingId
     *
     * @return CityShipping
     */
    public function setThecoderpsshippingId($thecoderpsshippingId)
    {
        $this->thecoderpsshippingId = $thecoderpsshippingId;

        return $this;
    }


    /**
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param price $price
     *
     * @return CityShipping
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $active
     *
     * @return Thecoderpsshipping
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id_thecoderpsshipping_city_shipping' => $this->getId(),
            'id_thecoderpsshipping' => $this->getThecoderpsshippingId(),
            'price' => $this->getPrice(),
            'active' => $this->getActive(),
        ];
    }
}