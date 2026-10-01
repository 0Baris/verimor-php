# Errors

## HTTP failures

Convenience and domain services expose `4xx/5xx` responses as `VerimorApiException`:

```php
try {
    $sms->send($payload);
} catch (VerimorApiException $error) {
    $product = $error->product();
    $operation = $error->operationId();
    $status = $error->statusCode();
    $body = $error->body();
}
```

`body()` can be a decoded value for JSON, a string for text, or `null` for an empty response. Check its type before interpreting it. `400`, `401`, `403`, `404`, `429`, `500`, and `503` follow the same normalized contract.

## Network failures

DNS, connection refusal, TLS, and timeout failures remain native Guzzle exception types. This distinguishes an HTTP error response from a request that received no response.

## Malformed success responses

When a `2xx` response violates its schema, generated operations can throw `VerimorApiException`; validated WhatsApp convenience methods throw `UnexpectedResponseException`. Do not record these as successful business outcomes.

The SDK does not retry automatically. Apply service-appropriate backoff for `429`; after a timeout or `5xx` on side-effecting `send` or `originate` operations, a blind retry can cause duplicate delivery.
