# Simple helpers for local development

CERT_DIR := config/ssl
CERT     := $(CERT_DIR)/cert.pem
KEY      := $(CERT_DIR)/cert-key.pem
CNF      := $(CERT_DIR)/openssl-localhost.cnf
DAYS     := 825

.PHONY: ssl ssl-clean up down build logs ps app-install shell

ssl:
	@mkdir -p $(CERT_DIR)
	@if [ -f "$(CERT)" ] && [ -f "$(KEY)" ]; then \
		echo "SSL certificate already exists:"; \
		echo "  $(CERT)"; \
		echo "  $(KEY)"; \
	else \
		echo "Generating self-signed certificate for localhost..."; \
		openssl req -x509 -nodes -newkey rsa:2048 -days $(DAYS) \
			-keyout "$(KEY)" \
			-out "$(CERT)" \
			-config "$(CNF)"; \
		echo "Done."; \
	fi

ssl-clean:
	rm -f "$(CERT)" "$(KEY)"

build:
	docker compose build --no-cache

up:
	docker compose up -d --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=200

ps:
	docker compose ps

app-install:
	docker compose exec -T webserver composer install --no-interaction

shell:
	docker compose exec webserver bash
