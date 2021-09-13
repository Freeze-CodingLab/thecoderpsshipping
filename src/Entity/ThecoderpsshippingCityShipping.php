<?php
// modules/yourmodule/src/Entity/ProductComment.php
namespace Thecoderpsshipping\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Thecoderpsshipping\Repository\ThecoderpsshippingCityShippingRepository")
 */
class ThecoderpsshippingCityShipping
{

    /**
     * @var Thecoderpsshipping
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Thecoderpsshipping\Entity\Thecoderpsshipping")
     * @ORM\JoinColumn(name="id_thecoderpsshipping", referencedColumnName="id_thecoderpsshipping", nullable=false)
     */
    private $thecoderpsshipping;


    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=20, scale=6)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_time", type="string")
     */
    private $deliveryTime;

    /**
     * @var int
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;



    /**
     * @return Thecoderpsshipping
     */
    public function getThecoderpsshipping()
    {
        return $this->thecoderpsshipping;
    }

    /**
     * @param Thecoderpsshipping $thecoderpsshipping
     *
     * @return $this
     */
    public function setThecoderpsshipping(Thecoderpsshipping $thecoderpsshipping)
    {
        $this->thecoderpsshipping = $thecoderpsshipping;

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
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * @param deliveryTime $deliveryTime
     *
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @return active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param active $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }
}