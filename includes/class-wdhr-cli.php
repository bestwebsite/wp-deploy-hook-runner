<?php
if (defined('WP_CLI') && WP_CLI) {
    class WDHR_CLI extends WP_CLI_Command {

        public function run($args, $assoc_args) {
            $report = WDHR_Core::run_tasks();
            WP_CLI::success('Tasks executed.');
            WP_CLI\Utils\format_items('table', [ $report ], array_keys($report));
        }

        public function token($args, $assoc_args) {
            if (isset($assoc_args['regenerate'])) {
                $token = WDHR_Core::regenerate_token();
                WP_CLI::success('Token regenerated: ' . $token);
            } else {
                $token = WDHR_Core::ensure_token();
                WP_CLI::log('Current token: ' . $token);
            }
        }
    }
    WP_CLI::add_command('deploy-hook', 'WDHR_CLI');
}
