#!/bin/bash
docker exec -t -u 1000:1000 dota_app php artisan $@
