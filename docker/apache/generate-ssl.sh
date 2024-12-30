#!/bin/bash

SSL_DIR="./conf/ssl"

# Перевіряємо чи існує директорія
mkdir -p $SSL_DIR

# Генерація приватного ключа
openssl genrsa -out $SSL_DIR/apache.key 2048

# Створюємо тимчасовий конфігураційний файл
cat > $SSL_DIR/openssl.cnf <<EOL
[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
[req_distinguished_name]
CN = localhost
[v3_req]
subjectAltName = @alt_names
keyUsage = digitalSignature
extendedKeyUsage = serverAuth
[alt_names]
DNS.1 = localhost
EOL

# Генерація CSR
openssl req -new -key $SSL_DIR/apache.key -out $SSL_DIR/apache.csr -subj "/CN=localhost" -config $SSL_DIR/openssl.cnf

# Генерація самопідписаного сертифіката
openssl x509 -req -days 365 -in $SSL_DIR/apache.csr -signkey $SSL_DIR/apache.key -out $SSL_DIR/apache.crt -extensions v3_req -extfile $SSL_DIR/openssl.cnf

# Налаштування дозволів
chmod 644 $SSL_DIR/apache.key

# Видаляємо тимчасовий конфігураційний файл
rm $SSL_DIR/openssl.cnf

echo "SSL сертифікати згенеровані у $SSL_DIR"