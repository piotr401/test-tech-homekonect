# test-tech-homekonect

**Installation**

1- git clone https://github.com/piotr401/test-tech-homekonect.git

2- nano /etc/hosts

3- ajouter: 127.0.0.1    test.local www.test.local

4- cd test-tech-homekonect

5- docker-compose build

6- docker-compose up -d

7- composer install

8- docker exec -ti test-tech-homekonect_php_1 bash

9- php bin/console doctrine:schema:update --dump-sql

10- php bin/console doctrine:schema:update --force

11- php bin/console doctrine:fixture:load