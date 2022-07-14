FROM nginx:1.19

ADD vhost.conf /etc/nginx/conf.d/default.conf

