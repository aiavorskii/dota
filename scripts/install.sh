echo Please specify DB password?
read password


cp app/.env.example app/.env && \
sed -i "s/DB_PASSWORD=/DB_PASSWORD=$password/" "app/.env" && \
docker-compose -f docker-compose.yml up -d && \
docker exec -t dota_app composer install && \
docker exec -t dota_app php artisan key:generate && \
docker exec -t dota_app chown 1000:1000 -R /var/www/ && \
docker exec -t dota_app chown 1000:www-data -R /var/www/storage && \
docker exec -t dota_app chmod 775 -R /var/www/storage && \
docker-compose -f docker-compose.yml up -d 
