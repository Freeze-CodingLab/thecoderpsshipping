<?php

/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace Thecoderpsshipping\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ThecoderpsshippingRepository
 * @package Thecoderpsshipping\Repository
 */
class ThecoderpsshippingRepository extends EntityManager
{
    public function getCityInformation()
    {

        $tc = $this->createQueryBuilder('t')
            ->addSelect('t')
            ->andWhere('t.active = 1');

        $citys = $tc->getQuery()->getResult();

        return $citys;
    }
}