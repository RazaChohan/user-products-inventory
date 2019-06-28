# user-products-inventory

# Installation

####  1. First, clone this repository:

```bash
$ git clone https://github.com/RazaChohan/user-products-inventory.git
```

####  2. Next, kindly add following entry in your `/etc/hosts` file:

```bash
127.0.0.1 user_products.localhost
```

- Create docker containers:

```bash
$ docker-compose up -d
```

#### 3. Confirm three running containers for php, nginx, & mysql:

```bash
$ docker-compose ps 
```

#### 4. Install composer packages:

```bash
$ docker-compose run php composer install 
```
#### 5. Create Database schema:

```bash
$ docker-compose run php php artisan migrate 
```

#### 6. Load data is Database:

```bash
$ docker-compose run php php artisan load:data
```

#### 7. Run test cases:

```bash
$ docker-compose run php vendor/bin/phpunit
```

#### 8. Generte token:
```bash
 $ curl -X POST -H "Content-Type: application/json" http://user_products.localhost/auth -d '{"email":"roselyn62@gmail.com","password":"secret"}'
```

#### 9. Get all products call:
```bash
 $ curl -X GET "http://user_products.localhost/product?token={token}"
```

#### 10. Get user call:
```bash
 $ curl -X GET "http://user_products.localhost/user?token={token}"
```
#### 11. Get user products call:
```bash
 $ curl -X GET "http://user_products.localhost/user/products?token={token}"
```
#### 12. Sync user purchased products call:
```bash
 $ curl -X POST -H "Content-Type: application/json" http://user_products.localhost/user/products -d '{"token":"{token}", "product_ids":[comma separated ids]}' 
```

#### 13. Remove user purchased products call:
```bash
 $ curl -X DELETE "http://user_products.localhost/user/products/{sku}?token={token}" 
```

#### 14. Get recommended product for user:
```bash
 $ curl -X GET "http://user_products.localhost/user/recommendation?token={token}"
```

#### 15. Possible Optimisations:
- Some queries can be optimized if query builder or raw queries can be used instead of ORM
- The recommend product logic is currently just pulling the first product that is not purchased by user yet. 
  This logic can be improved by using the initial part of product sku and considering it as product category 
  and if user has purchased one product of a category we can recommend the user products of same category. 

Application logs can be found on following locations:
```bash
  logs/nginx
  application/storage/logs
```
For docker image I have used https://github.com/eko/docker-symfony repo and tweaked it a bit as per my requirements.

