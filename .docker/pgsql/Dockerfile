FROM kartoza/postgis:9.6-2.4

# Set the locale
RUN sed -i -e 's/# fr_FR.UTF-8 UTF-8/fr_FR.UTF-8 UTF-8/' /etc/locale.gen && \
    locale-gen
ENV LANG fr_FR.UTF-8
ENV LANGUAGE fr_FR:fr
ENV LC_ALL fr_FR.UTF-8

# Add UNIX user so that we can connect with peer authentication
# RUN useradd -ms /bin/bash postgres
# RUN useradd -ms /bin/bash polytheisms

# Push the database init script to container
COPY init.sh /docker-entrypoint-initdb.d/
RUN chmod 0755 /docker-entrypoint-initdb.d/init.sh