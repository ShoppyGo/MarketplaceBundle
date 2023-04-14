<?php

namespace ShoppyGo\MarketplaceBundle\Engine;

use ShoppyGo\MarketplaceBundle\DTO\MarketplaceOrderCommissionDTO;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;

interface CommissionCalculatorInterface
{
    public function calculateCommission(
        MarketplaceOrderCommissionDTO $order_amounts,
        MarketplaceCommission $commission
    ): float;
}
