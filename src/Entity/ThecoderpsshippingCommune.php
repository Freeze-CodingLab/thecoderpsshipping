<?php
// modules/yourmodule/src/Entity/ProductComment.php
namespace Thecoderpsshipping\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ThecoderpsshippingCommune
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_thecoderpsshipping_commune", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="commune
     * _name", type="string", length=64)
     */
    private $communeName;

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
     * @return string
     */
    public function getCommuneName()
    {
        return $this->communeName;
    }

    /**
     * @param string $communeName
     *
     * @return Thecoderpsshipping
     */
    public function setCommuneName($communeName)
    {
        $this->communeName = $communeName;

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
            'id_thecoderpsshipping_commune' => $this->getId(),
            'commune_name' => $this->getCityName(),
            'active' => $this->getActive(),
        ];
    }
}