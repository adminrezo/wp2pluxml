# WP2Pluxml
# Pour Debian Squeeze
#
# VERSION               0.0.1
#


FROM     debian:squeeze
MAINTAINER Nico Dewaele "nico@adminrezo.fr"

ENV DEBIAN_FRONTEND noninteractive

# Depots, mises a jour et installs de Apache/PHP5

RUN (apt-get update && apt-get upgrade -y -q && apt-get dist-upgrade -y -q && apt-get -y -q autoclean && apt-get -y -q autoremove)
RUN apt-get update && apt-get install -y -q apache2 libapache2-mod-php5 php5-cli php5-mysql mysql-server wget nano unzip git

# Installation de pluxml

WORKDIR /var/www
RUN wget http://telechargements.pluxml.org/download.php
RUN unzip *.zip
RUN rm *.zip
RUN wget https://github.com/nicosomb/wp2pluxml/master.zip
RUN unzip *.zip
RUN mv /var/www/wp2* /var/www/pluxml/wp2pluxml
RUN chown www-data.www-data -R /var/www

# Demarrage des services

EXPOSE 443 80
CMD ["/start.sh"]
