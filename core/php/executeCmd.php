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
require_once __DIR__ . '/../class/defauts.class.php';

log::add("defauts","debug", "Lancement de " . __FILE__ );

$options = getopt ("c:t:");

if ( ! $options ) {
	log::add("defauts","error", __FILE__ . " : option erronée");
	exit (1);
}

if (! array_key_exists("c", $options)) {
	log::add("defauts","error", __FILE__ . " : option -c manquante");
	exit (1);
}

if (! array_key_exists("t", $options)) {
	log::add("defauts","error", __FILE__ . " : option -t manquante");
	exit (1);
}

$dateEtat = (int)$options["t"];
$cmd = cmd::byId($options["c"]);

if (! is_object($cmd) ) {
	log::add("defauts","error","Il n'existe pas de commande avec l'id " . $options['c'] );
	exit (1);
}

if ($cmd->getEqType() != "defauts") {
	log::add("defauts","error","La commande " . $options['c'] . " n'est pas de type \"defauts\"");
	exit (1);
}

if (($cmd->getLogicalId() != "surveillance") && ($cmd->getLogicalId() != "survConsigne")) {
	log::add("defauts","error","Le logicalId de la commande " . $options['c'] . " n'est pas celui d'une surveillance");
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

$eqLogic = $cmd->getEqLogic();
$actuValue = $cmd->execCmd(); 
$newValue = $cmd->calculSurveillance();
$eqName = $cmd->getEqLogic()->getName();
$cmdName = $cmd->getName();
$level = $newValue == $actuValue ? "debug" : "info";
log::add("defauts",$level,"$eqName: $cmdName: $actuValue => $newValue (temporisé)");
$eqLogic->checkAndUpdateCmd($cmd,$newValue);

exit (0);
