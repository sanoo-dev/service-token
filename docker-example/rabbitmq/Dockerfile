FROM rabbitmq:3.8.19-management

MAINTAINER NguyenQuocDai <quocdaijr@gmail.com>

COPY ./conf.d/rabbitmq.conf /etc/rabbitmq/
COPY ./conf.d/custom_definitions.json /etc/rabbitmq/

CMD ["rabbitmq-server"]