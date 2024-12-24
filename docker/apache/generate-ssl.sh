#!/bin/bash

 openssl genrsa -out ./conf/ssl/apache.key 2048
 openssl req -new -key ./conf/ssl/apache.key -out ./conf/ssl/apache.csr -subj '/CN=apache' -extensions EXT -config <( \
 printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:default\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")
 openssl x509 -req  -days 365 -in ./conf/ssl/apache.csr -signkey ./conf/ssl/apache.key -out ./conf/ssl/apache.crt
 chmod 644 ./conf/ssl/apache.key
