# PAuth
An authentication library with OAuth, CSRF, Dynamic form generation and much more!

## Authentication Methods:
- Credential based login:
  - Username + Password
  - Email + Password
- OAuth
  - Google
  - Github
- Magic Links :: One time links sent to the users email

## Automated Emails for:
- Verification
- Password changes / resets.
- Account deletion.
- Magic Links

## Installation

Install PAuth with composer

```bash
  composer require patryknamyslak/pauth
```
    
## Authors
- [@patryknamyslak](https://www.github.com/patryknamyslak)


## License

[MIT](https://mit-license.org/)

## Dependencies
- [PatForm](https://patl.ink/form-builder/) :: Dynamic form Generator
- [PatBase](https://patl.ink/patbase/) :: Database Communication Interface (DCI)
- [Resend](https://resend.com/) :: Email Sending Client
- [GuzzleHTTP](https://github.com/guzzle/guzzle) :: HTTP Client
- [Vlucas PHPDotEnv](https://github.com/vlucas/phpdotenv) :: Environment variable loader