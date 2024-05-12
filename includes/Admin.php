<?php

namespace WpPool\Store\Order;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        $addressbook = new Admin\Addressbook();
        new Admin\Menu( $addressbook );
    }

}
