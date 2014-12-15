# wp2pluxml

WP2Pluxml est un script de conversion de Wordpress vers Pluxml (parce que c'est mieux ;-) ).
Ce script ne fonctionne plus avec les versions récentes de Apache, Php, Mysql.
J'ai fait une image Docker pour avoir un wp2pluxml qui marche, basé sur Debian Squeeze.

## Usage

 - Installer Docker
 - Lancer mon container :

```
docker run -it adminrezo/wp2pluxml /start.sh
```
 - Aller sur http://172.17.0.X/pluxml/
 - Installer pluxml
 - Dans le container :

```
xxxxx# cd /var/www/pluxml/wp2pluxml
xxxxx# wget http://monserveur/monexport-wp.xml
```

 - Aller sur http://172.17.0.X/pluxml/wp2pluxml
 - It works
