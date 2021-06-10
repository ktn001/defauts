# ![icon](../images/defauts_icon.png) Documentation du plugin Défauts 

Le plugin **Défauts** permet de détecter des défauts en vérifiant la cohérence entre un état et une mesure.
# Quelques exemples:
- une lampe allumée mais pas de consommation (ampoule défectueuse ou mauvais signal de retour).
- Une lampe éteinte mais consomation de plus de 1 watt (mauvais état de retour).
- Une pompe enclenchée mais pas de débit.

Le lugin peut aussi des deéfaut lorsque la valuer d'une mesure s'écarte trop d'une consigne.

# Configuration du plugin
Le plugin ne nécessite aucune configuration, il faut juste l’activer.

![Page de configuration du plugin](../images/config_plugin.png)

# Les équipements
Un équipement **Défauts** permet de surveiller la cohérence de plusieurs combinaisons d'état et de valeur. Une info
indique pour chacune de ces surveillances si la situation actuelle est cohérente ou non.

De plus, une info **défaut** est activée lorsqu'une surveillance détecte une incohérence. Cette info reste activée
jusqu'à ce qu'elle soit acquittée même si l'incohérence disparaît.

## Création
Les équipements **Défauts** sont créés sur la page du plugin qui est atteignable via le menu `plugins`==> `Monitoring` ==> `Défauts`.
L'équipement est créé avec trois commandes:
+ Une commande **defaut**, de type info, qui indique si une incohérence est ou a été détectée par une des surveillances de l'équipement.
+ Une commande **Acquittement**, de type action, pour acquitter les défauts détectés.
+ Une commande **historique**, de type info, qui permet d'afficher les dernières anomalies détectées.

## Configuration
### De l'équipement
Outre les configurations habituelles, l'équipement a deux paramêtres permettant de définir le fonctionnement de l'**Auto acquittement**:
* **Auto Acquittement**   
   indique si les défauts doivent être acquittés automatiqeuement ou non.
* **Délais** *(visible uniquement si l'auto acquittement est activé)*  
   Le délais d'attente
   
### Des surveillances de cohérence
Le panneau `surveillances` permet de géger les surveillances de l'équipement. Le bouton `Ajouter une surveillance` ajoute une surveillance de cohérence à l'équipement.

#### Les surveillances de cohérence ont plusieurs paramètres:
![configuration d'une surveillance de cohérence](../images/config_surveillance.png)
* ***Nom :*** Nom de la surveillance.
* ***Etat :*** Info binaire à surveiller.
* ***Mesure :*** Info numérique à surveiller.
* ***Limite :*** Valeur dant être atteinte pas le mesure lorsque l'état est à 1 (incohérence si cette valeur n'est pas atteinte. Il y a aussi incohérence si cette valeur est atteinte alors que l'état est à 0.
* ***Temporisation :*** Délais pour atteindre la limite après changement d'état.
* ***Inverser :*** Inversion de la surveillance. Lamesure doit être supérieure à la limite lorsque l'état est à 0.
* ***En :*** Surveillance active lorsque l'état est à 1.
* ***Hors :*** Surveillance active lorsque l'état est à 0.
* ***Afficher :*** Affichage de l'info.
* ***Affichage inversé :*** Inversion de la valeur pour l'affichage (permet d'avoir une icône verte si tout va bien et rouge en cas de défaut).
* ***Historiser :*** Historisation de l'info.

### Des surveillances de consigne
![configuration d'une surveillance de consigne](../images/config_consigne.png)
* ***Nom :*** Nom de la surveillance.
* ***Etat :*** Info binaire utilisée pour controler le fonctonnement de lasurveillance.
* ***Mesure :*** Info numérique, un defaut sera signalé si la valeur de cette mesure est trop distante de la valeur de la consigne.
* ***Consigne :*** Info numérique, valeur que la masure doit atteindre.
* ***Limite :*** Un défaut est remonté si la valeur absolue de la différence entre la cngne et la mesure est supérieur à cette limite.
* ***Temposiration :*** Période, en secondes, durant laquelle la surveillance est désactivée après un changement de l'***état***.
* ***En :*** La surveillance est activée lorsque l'***état*** est à 1 si cette option est activée.
* ***Hors :*** La surveillance est activée lorsque l'***état*** est à 0 si cette option est activée.
* ***Historiser :*** Historisation de l'info.

### De l'historique
![historique](../images/historique.png)

La commande *historique* et son widget permettent de voir les cinq derniers défauts qui se sont produits. Le nombre d'événements listés est configurable.

Une durée rétention de events listé peut-être configurée. Le events qui se sont produit avant la durée de rétention sont retiré de la liste. Ainsi les event qui se sont produit il y a plus de 2 jours sont retirés de la liste si la rétention est de 2 jours.

#### L'historique a plusieurs paramètres:

![configuration de l'historique](../images/config_histo.png)

* ***Nom :*** Nom de lĥistorique.
* ***Taille :***  Nombre d'entrées de l'historique affichées dans le widget (5 au maximum)
* ***Rétention :*** Durée de rétention d'une entrée dans l'historique. Cette durée peut être exprimée en minutes, heures ou jours
* ***Format date :*** Format de la date dans l'historique.    
     les formats suivants sont possibles (Faites une demande via le forum de Jeedom pour l'ajout d'autres formats):

    | format | exemple |
    | ------ | ------- |
    | jj-mm HH:MM:SS | 02-06 17:35:40 |
    | jj/mm HH:MM:SS | 02/06 17:35:40 |
    | jj/mm/aa HH:MM:SS | 02/06/21 17:35:40 |
    | jj mmm aaaa HH:MM:SS | 02 Jun 2021 17:35:40 |

* ***Afficher :*** Indique si le widget doit être affiché ou non.

## Exemple

| Etape | Widget de l'équipement (couleur) | Widget de l'équipement (noir/blanc) | Observations |
| :---- | :----:  | :----: | :---- |
| Situation de départ: | ![](../images/defauts_initial.png "Etat initial") | ![](../images/defauts_initial_bw.png "Etat initial") | Pas de défaut, Les surveillances sont en état normal. |
| 1<sup>ière</sup> surveillance en en état anormal | ![](../images/defauts_premier_defaut.png) | ![](../images/defauts_premier_defaut_bw.png) | L'icône de défaut indique qu'il y a eu une anomalie qui n'a pas été acquittée. |
| Acquittement du défaut | ![](../images/defauts_acquitte.png) | ![](../images/defauts_acquitte_bw.png) | Le défaut a été acquitté pas un clic sur l'icône (ou par l'auto-acquittement). L'Icône de défaut indique que l'anomalie est toujours présente. |
| 2<sup>ième</sup> anomalie |![](../images/defauts_deuxieme_defaut.png) | ![](../images/defauts_deuxieme_defaut_bw.png) | L'icône de défaut indique qu'il y a une nonvelle anomalie. |
| Disparition des anomalies |![](../images/defauts_plus_de_defaut_pas_acquitte.png) | ![](../images/defauts_plus_de_defaut_pas_acquitte_bw.png) | L'icône de défaut indique qu'il y a eu au moins une anomalie qui n'a pas été acquittée. |
| Retour à la normale: | ![](../images/defauts_initial.png "Etat initial") | ![](../images/defauts_initial_bw.png "Etat initial") | Les anomalies ont été aquittées et ont disparues. |
