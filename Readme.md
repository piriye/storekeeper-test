
Coding requirements
==

* you need [docker-compose](https://docs.docker.com/compose/install/) and [docker](https://docs.docker.com/engine/install/) to run this project
* you need [gnu make](https://www.gnu.org/software/make/) as well to execute common commands 
* must use version PHP 7.3 or higher
* `symfony/http-foundation` and `symfony/routing` component must be used in solution 
* no full framework can be used - the symfony components should be used directly (you can use any symfony components thought)
* the service must start in full after running `docker-compose up -d` 
* the gui must be available on http://localhost:8000/
* the api must be available on http://localhost:8000/api/
* all unit tests should execute after running `make test`
* you can use any additional libraries (but they must install after `make install`)
* you can use any docker images (as long and other requirements are still true), 
you can also change the current docker image to different one
* commit as often as it makes sense
* you can use typescript instead of javascript
* use `make start-js-dev` to run webpack dev server with hot reload