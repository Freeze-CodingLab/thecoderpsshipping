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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ThecoderpsshippingCityShippingRepository
 * @package Thecoderpsshipping\Repository
 */
class ThecoderpsshippingCityShippingRepository extends EntityRepository
{
    public function getShippingPrice()
    {
        $tcs = $this->createQueryBuilder('tcs')
            ->leftJoin('tcs.thecoderpsshipping', 't')
            ->addSelect('t.cityName')
            ->andWhere('t.active = 1');

        $shippingPrice = $tcs->getQuery()->getResult();
        return $shippingPrice;
    }
}