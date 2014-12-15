# WP2Pluxml
# Pour Debian Squeeze
#
# VERSION               0.2
#


FROM     debian:squeeze
MAINTAINER Nico Dewaele "nico@adminrezo.fr"

ENV DEBIAN_FRONTEND noninteractive

# Depots, mises a jour et installs de Apache/PHP5

RUN (apt-get update && apt-get upgrade -y -q && apt-get dist-upgrade -y -q && apt-get -y -q autoclean && apt-get -y -q autoremove)
RUN apt-get update && apt-get install -y -q apache2 libapache2-mod-php5 php5-gd wget nano unzip

# Installation de pluxml

RUN wget http://telechargements.pluxml.org/download.php -O pluxml.zip
RUN unzip *.zip
RUN mv pluxml /var/www/
RUN rm *.zip
RUN wget https://github.com/nicosomb/wp2pluxml/archive/master.zip --no-check-certificate
RUN unzip master.zip
RUN mv wp2pluxml-master /var/www/pluxml/wp2pluxml
RUN chown www-data.www-data -R /var/www

# Demarrage des services

WORKDIR /var/www/pluxml/wp2pluxml
RUN a2enmod rewrite
EXPOSE 80
CMD ["/bin/bash"]
