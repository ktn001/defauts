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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
  function defauts_install() {

  }

// Fonction exécutée automatiquement après la mise à jour du plugin
function defauts_update() {
	log::add("defauts","info","Mise à jours des commandes pour le pugin defauts");
	$eqLogics = eqLogiq::byType("defauts");
	foreach ($eqLogics as $eqLogic) {
		$eqLogic_id = $eqLogic->getId();
		$eqLogiq_name = $eqLogic->getName();
		$eqLogic_version = $eqLogic->getConfiguration("version");
		log::add("defauts","info","eqLogic $eqLogic_id - $eqLogic_name (version $eqlogic_version)");
		switch ($eqLogic_version) {
		case 0:
			$cmds = cmd::byEqLogicId($eqLogic_id);
			foreach ($cmds as $cmd) {
				if ($cmd->getLogicalId() == "defaut") {
					log::add("defauts","info","  Mise à jour de la commande " . $cmd->getId());
					$cmd->setIsVisible(0);
					$cmd->save();
				}
				if ($cmd->getLogicalId() == "acquitter") {
					log::add("defauts","info","  Mise à jour de la commande " . $cmd->getId());
					$cmd->setTemplate("dashboard","defauts::acquittement");
					$cmd->setTemplate("mobile","defauts::acquittement");
					$cmd->setDisplay("forceReturnLineAfter",1);
					$cmd->save();
				}
				if ($cmd->getLogicalId() == "surveillance") {
					$cmd->setDisplay("invertBinary",1);
					$cmd->save();
				}
			}
		}
		$eqLogic->setConfiguration("version",1);
	}
	defauts::clearCacheWidget();
}

// Fonction exécutée automatiquement après la suppression du plugin
  function defauts_remove() {

  }

?>
