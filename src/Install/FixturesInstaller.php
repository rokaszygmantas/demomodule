<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoViewOrderHooks\Install;

use Doctrine\ORM\EntityManagerInterface;
use joshtronic\LoremIpsum;
use Order;
use PrestaShop\Module\DemoViewOrderHooks\Entity\OrderReview;
use PrestaShop\Module\DemoViewOrderHooks\Entity\Signature;

/**
 * Installs data fixtures for the module.
 */
class FixturesInstaller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function install(): void
    {
        $orderIds = Order::getOrdersIdByDate('2000-01-01', '2100-01-01');
        $loremIpsum = new LoremIpsum();

        foreach ($orderIds as $orderId) {
            $signature = new Signature();
            $signature->setFilename('john_doe.png')
                ->setOrderId($orderId);

            $this->entityManager->persist($signature);

            $orderReview = new OrderReview();
            $orderReview->setOrderId($orderId)
                ->setScore(rand(0, 3))
                ->setComment($loremIpsum->sentence());

            $this->entityManager->persist($orderReview);
        }

        $this->entityManager->flush();
    }
}
