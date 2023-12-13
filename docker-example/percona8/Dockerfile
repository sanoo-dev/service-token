FROM percona:8.0

MAINTAINER NguyenQuocDai <quocdaijr@gmail.com>

USER 0
RUN usermod -u 1000 mysql
RUN chown -R mysql:mysql /var/lib/mysql
RUN chown -R mysql:mysql /var/run/mysqld

COPY ./conf.d/my.cnf /etc/my.cnf.d/my.cnf
RUN chmod a-w /etc/my.cnf.d/my.cnf