FROM nginx:alpine

COPY /docker-compose/nginx/payroll.conf /etc/nginx/conf.d/default.conf