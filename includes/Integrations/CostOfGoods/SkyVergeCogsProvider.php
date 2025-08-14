<?php

/**
 * Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package FacebookCommerce
 */

namespace WooCommerce\Facebook\Integrations\CostOfGoods;

defined('ABSPATH') || exit;

use Exception;
use WooCommerce\Facebook\Integrations\IntegrationIsNotAvailableException;

/**
 * Integration for the SkyVerge Cost-of-Goods feature on WooCommerce plugin.
 *
 * @since 2.0.0-dev.1
 */

class SkyVergeCogsProvider extends AbstractCogsProvider {

    const INTEGRATION_NAME = 'WooCommerce Cost of Goods by SkyVerge';

    public function __construct()
    {
        if ( ! self::is_available() ) {
            throw new IntegrationIsNotAvailableException( self::INTEGRATION_NAME );
        }
    }

    public function get_cogs_value( $product ) {
        throw new Exception("Not Implemented");
    }

    public static function is_available() {
        return false;
    }
}