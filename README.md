# Installation


- Run `scripts/install-local.sh` in the **root directoy of the project** 
- Specify password
- Wait until installation is complete
- Go to the [http://localhost:9480/](https://http://localhost:9480/)
- add this line to the crontab `* * * * * docker exec dota_app php artisan schedule:run`


## Other

If u r not using xdebug you can disable simly commeting out environment section in docker-compose file
