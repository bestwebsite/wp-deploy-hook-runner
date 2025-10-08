<?php
class WDHR_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu() {
        add_management_page(
            __('Deploy Hook Runner', 'wp-deploy-hook-runner'),
            __('Deploy Hook Runner', 'wp-deploy-hook-runner'),
            'manage_options',
            'wp-deploy-hook-runner',
            [$this, 'render']
        );
    }

    public function render() {
        if (!current_user_can('manage_options')) return;
        $notice = '';

        if (isset($_POST['wdhr_regen']) && check_admin_referer('wdhr_regen_token')) {
            $new = WDHR_Core::regenerate_token();
            $notice = '<div class="updated"><p>Token regenerated.</p></div>';
        }

        if (isset($_POST['wdhr_run']) && check_admin_referer('wdhr_run_tasks')) {
            $report = WDHR_Core::run_tasks();
            $notice = '<div class="updated"><p>Tasks executed.</p><pre>' . esc_html(print_r($report, true)) . '</pre></div>';
        }

        $token = WDHR_Core::ensure_token();
        $endpoint = esc_url_raw( rest_url('deploy/v1/run') );

        echo '<div class="wrap"><h1>WP Deploy Hook Runner</h1>';
        echo '<p>Secure webhook endpoint and tools to run post‑deploy maintenance tasks from your CI/CD pipeline.</p>';
        if ($notice) echo $notice;

        echo '<h2>Webhook</h2>';
        echo '<p><strong>Endpoint:</strong> <code>' . esc_html($endpoint) . '</code></p>';
        echo '<p><strong>Header:</strong> <code>X-Deploy-Token: ' . esc_html($token) . '</code></p>';
        echo '<p>Or use query param: <code>?token=' . esc_html($token) . '</code></p>';

        echo '<form method="post" style="margin-top:1em;">';
        wp_nonce_field('wdhr_regen_token');
        echo '<button name="wdhr_regen" class="button button-secondary">Regenerate Token</button>';
        echo '</form>';

        echo '<h2 style="margin-top:2em;">Manual Run</h2>';
        echo '<form method="post">';
        wp_nonce_field('wdhr_run_tasks');
        echo '<button name="wdhr_run" class="button button-primary">Run Tasks Now</button>';
        echo '</form>';

        echo '<h2 style="margin-top:2em;">What it does</h2>';
        echo '<ul><li>Flush rewrite rules</li><li>Flush object cache (if supported)</li><li>Delete expired transients</li></ul>';
        echo '</div>';
    }
}
new WDHR_Admin();
