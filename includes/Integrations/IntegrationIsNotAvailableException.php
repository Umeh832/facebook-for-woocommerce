<?php
/**
 * Facebook for WooCommerce.
 */

namespace WooCommerce\Facebook\Integrations;

use Throwable;

defined( 'ABSPATH' ) || exit;

/**
 * An Exception which indicates that a 3P integration is not available and accessible
 */
class IntegrationIsNotAvailableException extends \Exception {
    public function __construct( $integration_name, $code = 0, ?Throwable $previous = null )
    {
        parent::__construct( "Integration \'{$integration_name}\' is not available. Make sure the 3P plugin is installed and active", $code, $previous );
    }
}
