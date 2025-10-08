openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:4096 -out private.pem

openssl pkey -in private.pem -pubout -out public.pem

in env put these :
RSA_PRIVATE_KEY_PATH=storage/app/keys/private.pem
RSA_PUBLIC_KEY_PATH=storage/app/keys/public.pem
