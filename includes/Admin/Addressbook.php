<?php

namespace WpPool\Store\Order\Admin;

use WpPool\Store\Order\Traits\Get_Value;

/**
 * Addressbook Handler class
 */
class Addressbook
{

    use Get_Value;


    public function plugin_page()
    {
        //echo Get_Value::get_domain();
        $installed = get_option('wppool_apikey_saved');
        if (!$installed) {
            echo sprintf('<div class="wrap"><a href="#" class="submitforapi" data-domain="%s">%s</a></div>', self::get_domain(), __('GET API KEY', 'wppool-store-order'));
        } else {
            echo sprintf('<div class="wrap"><a href="#" class="submitforapi" data-domain="%s"data-apiaction="remove">%s</a></div>', self::get_domain(), __('REMOVE API KEY', 'wppool-store-order'));
        }
    }

}
