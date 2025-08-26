FROM mariadb:11.5

ENV MARIADB_ROOT_PASSWORD=my-root-pw \
    MARIADB_DATABASE=mydb \
    MARIADB_USER=myuser \
    MARIADB_PASSWORD=mypassword

COPY ./my.cnf /etc/mysql/conf.d/custom.cnf

EXPOSE 3306

HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
  CMD ["healthcheck.sh", "--connect", "--innodb_initialized"]
