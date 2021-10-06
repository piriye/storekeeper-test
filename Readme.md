
Coding requirements
==

* must use version PHP 7.3 or higher
* `symfony/http-foundation` and `symfony/routing` component must be used in solution 
* no full framework can be used - the symfony components should be used directly (you can use any symfony components thought)
* the service must start in full after running `docker-compose up -d` 
* the gui must be available on http://localhost:8000/
* the api must be available on http://localhost:8000/api/
* all unit tests should execute after running `make test`
* you can use any additional libraries (but they must install after `composer install`)
* you can use any docker images (as long and other requirements are still true), 
you can also change the current docker image to different one
* commit as often as it makes sense