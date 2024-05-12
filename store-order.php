<?php

/**
 * Plugin Name: Wppool Store order
 * Description: Store order plugin
 * Plugin URI: https://tanvirhasan.co
 * Author: Tanvir hasan
 * Author URI: https://tanvirhasan.co
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * text-domain: wppool-store-order
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class WpPool_Store_Order
{

    /**
     * Plugin version
     *
     * @var string
     */

    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct()
    {

        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);

        add_action('admin_init', [$this, 'check_woocommerce_dependency']);
    }

    /**
     * Initializes a singleton instance
     *
     * @return \WpPool_Store_Order
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('WD_WPPOOL_VERSION', self::version);
        define('WD_WPPOOL_FILE', __FILE__);
        define('WD_WPPOOL_PATH', __DIR__);
        define('WD_WPPOOL_URL', plugins_url('', WD_WPPOOL_FILE));
        define('WD_WPPOOL_ASSETS', WD_WPPOOL_URL . '/assets');
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin()
    {

        new WpPool\Store\Order\Assets();

        if (defined('DOING_AJAX') && DOING_AJAX) {
            new WpPool\Store\Order\Ajax();
        }

        if (is_admin()) {
            new WpPool\Store\Order\Admin();
        }
        new WpPool\Store\Order\API();
        new WpPool\Store\Order\Woo();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate()
    {

        $installer = new WpPool\Store\Order\Installer();
        $installer->run();
    }

    function check_woocommerce_dependency()
    {
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            return;
        }
        add_action('admin_notices', [$this, 'woocommerce_dependency_notice']);
    }

    function woocommerce_dependency_notice()
    {
?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Please activate WooCommerce to use "Wppool Store order".', 'wppool-store-order'); ?></p>
        </div>
<?php
    }
}

/**
 * Initializes the main plugin
 *
 * @return \WpPool_Store_Order
 */
function wppool_order()
{
    return WpPool_Store_Order::init();
}

// kick-off the plugin
wppool_order();
