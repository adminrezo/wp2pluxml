# wp2pluxml

## Présentation

wp2pluxml vous permet de convertir le contenu de votre blog Wordpress en un blog PluXml.
Attention, ce script est à exécuter en local et non en environnement de production (selon la taille de votre blog Wordpress, la génération peut prendre du temps).

## Pré-requis

Avant toute chose, sachez que ce script a besoin de PHP 5 pour fonctionner (en effet, nous utilisons l'extension [SimpleXML](http://fr.php.net/manual/fr/book.simplexml.php)).

Ce script est compatible à partir de PluXml 5.1.x et WordPress 3.5.x. Nous n'avons pas testé avec des versions antérieures, impossible donc de dire s'il est compatible avec ces versions. Il y a de fortes chances que ça ne fonctionne pas, PluXml ayant modifié le format de génération des fichiers XML.

Avant de faire quoique ce soit, ouvrez le fichier index.php de wp2pluxml avec votre éditeur préféré pour modifier le tableau de configuration, à partir de la ligne 45 (# Configuration de base). Copiez le dossier wp2pluxml dans le répertoire de votre PluXml fraichement installé.

## Export de vos données Wordpress

Pour générer un export XML depuis votre blog Wordpress, il faut installer et activer le plugin [Advanced Export for WP & WPMU](http://wordpress.org/extend/plugins/advanced-export-for-wp-wpmu/) (dernière version testée, la 2.9). L'utilisation de ce plugin n'étant pas très complexe, nous nous passerons d'une documentation détaillée. Nous vous conseillons tout de même de n'exporter que les billets publiés (les brouillons et les pages seront traités par wp2pluxml prochainement).

Copiez l'export XML dans le même répertoire que celui de wp2pluxml. Actualisez la page pour afficher votre export dans la liste déroulante ci-dessous.

Concernant vos médias (images et documents) de Wordpress, copiez-les dans le répertoire data/images de votre blog PluXml. wp2pluxml se charge de modifier les chemins dans vos billets.

## Go !

Sélectionnez votre export XML dans la liste déroulante et validez pour lancer la conversion. Allez boire un café. Revenez. Vous êtes l'heureux possesseur d'un blog PluXml :) ! Enjoy.