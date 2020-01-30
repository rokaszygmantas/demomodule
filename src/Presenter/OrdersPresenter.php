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

namespace PrestaShop\Module\DemoViewOrderHooks\Presenter;

use OrderState;
use PrestaShop\Module\DemoViewOrderHooks\Collection\Orders;
use PrestaShop\Module\DemoViewOrderHooks\DTO\Order;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrdersPresenter
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Present a collection of orders for usage in rendering.
     *
     * @return array presented array of orders
     */
    public function present(Orders $orders, int $languageId): array
    {
        $presented = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $orderState = new OrderState($order->getOrderStateId(), $languageId);
            $presented[] = [
                'id' => $order->getOrderId(),
                'reference' => $order->getReference(),
                'link' => $this->urlGenerator->generate('admin_orders_view', [
                    'orderId' => $order->getOrderId(),
                ]),
                'status' => [
                    'name' => $orderState->name,
                    'color' => $orderState->color
                ],
                'placedAt' => $order->getOrderDate(),
            ];
        }

        return $presented;
    }
}
