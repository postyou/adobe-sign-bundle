# Adobe Sign Bundle
Simple Adobe Sign Api Client

## Default Configuration

You probably have to override the provider_options with the options provided in your account.

```yaml
# config/config.yaml
knpu_oauth2_client:
    clients:
        adobe_sign:
            provider_options:
                web_access_point: 'https://secure.eu2.adobesign.com/'
                api_access_point: 'https://api.eu2.adobesign.com'
                path_oauth: 'public/oauth'
                path_refresh: 'oauth/refresh'
                path_token: 'oauth/token'
                path_revoke: 'oauth/revoke'
```
