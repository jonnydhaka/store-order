<?php

namespace WpPool\Store\Order\Admin;

/**
 * The Menu handler class
 */
class Menu
{

    public $addressbook;

    /**
     * Initialize the class
     */
    function __construct($addressbook)
    {
        $this->addressbook = $addressbook;

        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu()
    {
        $parent_slug = 'wppool-store-order';
        $capability = 'manage_options';
        $hook = add_menu_page(__('Wppool Store Order', 'wppool-store-order'), 'Wppool Store Order', $capability, $parent_slug, [$this->addressbook, 'plugin_page']);
        add_action('admin_head-' . $hook, [$this, 'enqueue_assets']);
    }

  
    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('wppool-admin-style');
        wp_enqueue_script('wppool-admin-script');
    }
}
