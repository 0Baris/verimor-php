# Testing and safety

This package is community-maintained and unofficial. This release is verified with offline contract and localhost tests; it has not yet been validated against the live Verimor service.

## Credential safety

- Keep credentials on servers only; never place them in a browser bundle, mobile app, or public repository.
- Use environment variables or a secret manager. Redact SMS body/query values, the Switch `key` query, and the WhatsApp `X-API-Key` header from logs.
- Never use real credentials in tests.

## Safe testing

The repository suite uses a localhost HTTP recorder and never calls live endpoints. Applications can provide a custom `baseUrl` for their own mock server. Cover success, `4xx`, `429`, `5xx`, timeout, connection failure, and malformed-response scenarios.

## Retry and idempotency

The SDK does not retry automatically and contains no rate limiter. A timeout, `429`, or `5xx` does not prove the operation was rejected. For SMS/WhatsApp sends and Switch calls, use business keys, persistent status, and reconciliation; uncontrolled retries can cause duplicate delivery.

The default timeout is 30 seconds. Queue visibility and outer HTTP timeouts should not be shorter than this value. Retry decisions belong to the application.
