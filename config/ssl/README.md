# SSL certificates (local development)

This project expects the following files to exist:

- `config/ssl/cert.pem`
- `config/ssl/cert-key.pem`

They are mounted into the Apache container at `/etc/apache2/ssl/` and referenced by `config/vhosts/default.conf`.

> These certificates are for **local development only** (self-signed).

## Generate via command line (recommended)

From the project root:

```bash
mkdir -p config/ssl

# Generates:
# - config/ssl/cert.pem
# - config/ssl/cert-key.pem
openssl req -x509 -nodes -newkey rsa:2048 -days 825 -keyout config/ssl/cert-key.pem -out config/ssl/cert.pem -config config/ssl/openssl-localhost.cnf
```

### Optional: trust the certificate (browser)

- **macOS**: import `cert.pem` into Keychain Access and set it to “Always Trust”.
- **Windows**: import into “Trusted Root Certification Authorities”.
- **Linux**: depends on distro (e.g. update-ca-certificates).

## Generate via Makefile

From the project root:

```bash
make ssl
```

Remove generated files:

```bash
make ssl-clean
```

## Notes

- The generated cert includes `localhost` and `127.0.0.1` as Subject Alternative Names (SAN),
  which modern browsers require.
- The generated files are ignored by git (see `.gitignore`).
