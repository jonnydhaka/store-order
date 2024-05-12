<?php

/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wd_ac_insert_address($args = [])
{
    global $wpdb;

    if (empty($args['name'])) {
        return new \WP_Error('no-name', __('You must provide a name.', 'wppool-store-order'));
    }

    $defaults = [
        'name'       => '',
        'address'    => '',
        'phone'      => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time('mysql'),
    ];

    $data = wp_parse_args($args, $defaults);

    if (isset($data['id'])) {

        $id = $data['id'];
        unset($data['id']);

        $updated = $wpdb->update(
            $wpdb->prefix . 'ac_addresses',
            $data,
            ['id' => $id],
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            ],
            ['%d']
        );

        wd_ac_address_purge_cache($id);

        return $updated;
    } else {

        $inserted = $wpdb->insert(
            $wpdb->prefix . 'ac_addresses',
            $data,
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            ]
        );

        if (!$inserted) {
            return new \WP_Error('failed-to-insert', __('Failed to insert data', 'wppool-store-order'));
        }

        wd_ac_address_purge_cache();

        return $wpdb->insert_id;
    }
}



/**
 * Purge the cache for books
 *
 * @param  int $book_id
 *
 * @return void
 */
function wd_ac_address_purge_cache($book_id = null)
{
    $group = 'address';

    if ($book_id) {
        wp_cache_delete('book-' . $book_id, $group);
    }

    wp_cache_delete('count', $group);
    wp_cache_set('last_changed', microtime(), $group);
}
