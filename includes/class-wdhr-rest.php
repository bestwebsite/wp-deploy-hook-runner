<?php
class WDHR_REST {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('deploy/v1', '/run', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_run'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_run($request) {
        $token = $request->get_header('X-Deploy-Token');
        if (!$token) {
            $token = $request->get_param('token');
        }
        if (!WDHR_Core::is_valid_token($token)) {
            return new WP_REST_Response(['error' => 'Invalid token'], 401);
        }
        $report = WDHR_Core::run_tasks();
        return new WP_REST_Response(['status' => 'ok', 'report' => $report], 200);
    }
}
new WDHR_REST();
