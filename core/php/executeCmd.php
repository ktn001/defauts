<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once __DIR__ . '/../class/panne.class.php';

log::add("panne","debug", "Lancement de " . __FILE__ );

$options = getopt ("c:t:");

if ( ! $options ) {
	log::add("panne","error", __FILE__ . " : option erronée");
	exit (1);
}

if (! array_key_exists("c", $options)) {
	log::add("panne","error", __FILE__ . " : option -c manquante");
	exit (1);
}

if (! array_key_exists("t", $options)) {
	log::add("panne","error", __FILE__ . " : option -t manquante");
	exit (1);
}

$dateEtat = (int)$options["t"];
$cmd = cmd::byId($options["c"]);

if (! is_object($cmd) ) {
	log::add("panne","error","Il n'existe pas de commande avec l'id " . $options['c'] );
	exit (1);
}

if ($cmd->getEqType() != "panne") {
	log::add("panne","error","La commande " . $options['c'] . " n'est pas de type \"panne\"");
	exit (1);
}

if ($cmd->getLogicalId() != "surveillance") {
	log::add("panne","error","Le logicalId de la commande " . $options['c'] . " n'est pas \"surveillance\"");
	exit (1);
}

$delay = (int)$cmd->getConfiguration("delais");

$attente = $dateEtat + $delay - time();

if ($attente >= 0) {
	sleep ($attente);
}

if ($dateEtat < $cmd->dateEtat()) {
	// L'état a été modifié durant l'attente
	exit (0);
}

$eqLogic=$cmd->getEqLogic();
$eqLogic->checkAndUpdateCmd($cmd,$cmd->calculSurveillance());

exit (0);
