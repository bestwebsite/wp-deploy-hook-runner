<?php
class WDHR_Core {

    const OPTION_TOKEN = 'wdhr_token';

    public static function ensure_token() {
        $token = get_option(self::OPTION_TOKEN);
        if (empty($token) || !is_string($token)) {
            $token = wp_generate_password(32, false, false);
            update_option(self::OPTION_TOKEN, $token, false);
        }
        return $token;
    }

    public static function regenerate_token() {
        $token = wp_generate_password(32, false, false);
        update_option(self::OPTION_TOKEN, $token, false);
        return $token;
    }

    public static function is_valid_token($request_token) {
        $token = self::ensure_token();
        return hash_equals($token, (string) $request_token);
    }

    public static function run_tasks() {
        $report = [];

        flush_rewrite_rules(false);
        $report['flush_rewrite_rules'] = 'ok';

        if (function_exists('wp_cache_flush')) {
            $ok = wp_cache_flush();
            $report['wp_cache_flush'] = $ok ? 'ok' : 'not_supported';
        } else {
            $report['wp_cache_flush'] = 'not_available';
        }

        global $wpdb;
        $expired = $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\\_transient\\_timeout\\_%' AND option_value < UNIX_TIMESTAMP()" );
        $report['expired_transients_deleted'] = (int) $expired;

        $clear_all = apply_filters('wdhr_clear_all_transients', false);
        if ($clear_all) {
            $all = $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\\_transient\\_%' OR option_name LIKE '\\_site\\_transient\\_%'" );
            $report['all_transients_deleted'] = (int) $all;
        }

        do_action('wdhr_after_tasks', $report);

        return $report;
    }
}
