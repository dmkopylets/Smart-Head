#!/bin/bash

 openssl genrsa -out ./ssl/apache.key 2048
 openssl req -new -key ./ssl/apache.key -out ./ssl/apache.csr -subj '/CN=apache' -extensions EXT -config <( \
 printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:default\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")
 openssl x509 -req  -days 365 -in ./ssl/apache.csr -signkey ./ssl/apache.key -out ./ssl/apache.crt
 chmod 644 ./ssl/apache.key
