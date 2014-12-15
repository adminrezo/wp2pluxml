# wp2pluxml

[WP2Pluxml](https://github.com/nicosomb/wp2pluxml "WP2Pluxml") est un script de conversion de Wordpress vers pluxml (merci @nicosomb).
Ce script ne fonctionne plus avec les versions récentes de Apache, Php, Mysql.
J'ai fait une image Docker pour avoir un wp2pluxml qui marche, basé sur Debian Squeeze.

## Usage

 - Exportez votre Wordpress avec la fonction export incluse dans WP.
 - Créez un répertoire /tmp/wp2pluxml
 - Placez votre fichier XML d'export dans /tmp/wp2pluxml
 - Installer Docker
 - Lancer mon container :

```
docker run -v /tmp/wp2pluxml:/tmp -it adminrezo/wp2pluxml /start.sh
```

 - Au lancement, vous voyez l'adresse IP du container (quelque chose comme 172.17.0.X)
 - Aller sur http://172.17.0.X/pluxml/ pour installer pluxml
 - Dans le container :

```
xxxxx# cd /var/www/pluxml/wp2pluxml
xxxxx# cp /tmp/wp2pluxml/monexport-wp.xml .
```

 - Aller sur http://172.17.0.X/pluxml/wp2pluxml
 - It works!
