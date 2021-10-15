
Prerequisites
==

* you need [docker-compose](https://docs.docker.com/compose/install/) and [docker](https://docs.docker.com/engine/install/) to run this project
* you need [gnu make](https://www.gnu.org/software/make/) as well to execute `Makefile` commands

Services:
==

* SPA gui: http://localhost:8000/
* validation api: http://localhost:8001/ (from inside docker: http://validate-api/)
* php backend api:  http://localhost:8000/api/

For javascript dev server use the http://172.x.x.x address echoed on the server start.

Make commands
== 

```
help                           Outputs this help screen
start                          start the docker services
build                          build javascript files
start-js-dev                   build javascript node server, webpack dev server with hot reload
clean                          clean build
install                        install all js/php libraries
update                         update all js/php libraries
test                           run tests
check-standards                check if code complies to standards
lint-fix                       lint code (fixes most of check-standards)
```
