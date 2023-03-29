<?php

namespace ShoppyGo\MarketplaceBundle\Engine;

use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;

interface CommissionCalculatorInterface
{
    public function calculateCommission(\Order $order, MarketplaceCommission $commission): float;

}
