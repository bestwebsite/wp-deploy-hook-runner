# WP Deploy Hook Runner

**Secure webhook + WP‑CLI endpoint to run post‑deploy maintenance tasks from CI/CD.**

## What it Does
- Flush rewrite rules
- Flush object cache (if available)
- Delete expired transients (and allows full transient purge via filter)

## Installation
1. Upload `wp-deploy-hook-runner` to `/wp-content/plugins/`
2. Activate via Plugins screen
3. Go to **Tools → Deploy Hook Runner** to view your endpoint + token

## Webhook Usage
**Endpoint:** `POST /wp-json/deploy/v1/run`

Authenticate with either:
- Header: `X-Deploy-Token: <your-token>`
- Query param: `?token=<your-token>`

### cURL
```bash
curl -X POST -H "X-Deploy-Token: YOUR_TOKEN" https://example.com/wp-json/deploy/v1/run
```

### GitHub Actions Example
```yaml
- name: WordPress post-deploy
  run: |
    curl -X POST -H "X-Deploy-Token: ${{ secrets.DEPLOY_TOKEN }}" https://example.com/wp-json/deploy/v1/run
```

## WP‑CLI
```bash
wp deploy-hook run
wp deploy-hook token
wp deploy-hook token --regenerate
```

## Extensibility
- Filter `wdhr_clear_all_transients` to `true` to purge *all* transients.
- Action `wdhr_after_tasks` receives the `$report` array after execution.

## Security
- Uses a random 32‑character token stored in the database.
- Accepts token via header or query param (for CI flexibility).

## Author
Built and maintained by **Best Website** — https://bestwebsite.com  
Contact: support@bestwebsite.com

## License
GPL‑2.0 or later
