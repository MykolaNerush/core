# Authentication System

## Table of Contents

1. [API Endpoints](#api-endpoints)
2. [Console Commands](#console-commands)
3. [Usage Examples](#usage-examples)
4. [Security](#security)
5. [Architecture](#architecture)

## Overview

The authentication system is built using JWT tokens and includes the following features:

- Refresh tokens for access renewal
- Blacklist for revoked tokens
- Authentication attempt logging
- IP-based rate limiting with whitelist support
- Automatic expired token cleanup

### 3. Cron Job

Add the following task to your crontab for expired token cleanup:

```cron
0 * * * * php /path/to/your/project/bin/console auth:tokens:cleanup
```

## API Endpoints

### Get Token

```http
curl --location 'http://core.lc/api/v1/internal/signin' \
--header 'Cookie: api_deauth_profile_token=129b6e' \
--form 'email="farrell.joy@metz.biz"' \
--form 'password="test"'
```

Response:

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "expires_in": 3600
}
```

### Refresh Token

```http
curl --location 'http://core.lc/api/v1/internal/refresh-token' \
--header 'Cookie: api_deauth_profile_token=129b6e' \
--form 'refreshToken="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...."'
```

Response is the same as for token generation.

### Revoke Token

```http
POST http://core.lc/api/v1/internal/revoke-token
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

Response: HTTP 204 No Content

## Console Commands

### IP Whitelist Management

Add IP to whitelist:

```bash
php bin/console auth:ip:whitelist <ip>
```

Remove IP from whitelist:

```bash
php bin/console auth:ip:whitelist <ip> --remove
```

### Token Cleanup

```bash
php bin/console auth:tokens:cleanup
```

## Security

### Rate Limiting

By default, the system limits failed authentication attempts to 5 per 5 minutes from a single IP address.

### IP Whitelist

For critical operations, it's recommended to use IP whitelisting:

```bash
# Add administrator IP
php bin/console auth:ip:whitelist 192.168.1.100
```

### Monitoring

All authentication attempts are logged. You can monitor:

- Failed login attempts
- Token refresh attempts
- Token revocations
- IP-based access patterns

### Security

- Redis-based token blacklist
- IP whitelist for critical operations
- Rate limiting for failed attempts
- Automatic token cleanup
