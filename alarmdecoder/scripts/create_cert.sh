openssl req \
    -new \
    -newkey rsa:4096 \
    -days 3650 \
    -nodes \
    -x509 \
    -subj "/C=US/ST=VA/L=Vienta/O=CTW/CN=localhost" \
    -keyout server.key \
    -out server.crt
