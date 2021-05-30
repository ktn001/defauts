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
	$eqLogics = eqLogic::byType("defauts");
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
					log::add("defauts","info","  Mise à jour de la commande " . $cmd->getId() . " - " . $cmd->getName());
					$cmd->setIsVisible(0);
					$cmd->save();
				}
				if ($cmd->getLogicalId() == "acquitter") {
					log::add("defauts","info","  Mise à jour de la commande " . $cmd->getId() . " - " . $cmd->getName());
					$cmd->setTemplate("dashboard","defauts::acquittement");
					$cmd->setTemplate("mobile","defauts::acquittement");
					$cmd->setDisplay("forceReturnLineAfter",1);
					$cmd->save();
				}
				if ($cmd->getLogicalId() == "surveillance") {
					log::add("defauts","info","  Mise à jour de la commande " . $cmd->getId() . " - " . $cmd->getName());
					$cmd->setDisplay("invertBinary",1);
					$cmd->save();
				}
			}
			$cmds = cmd::byEqLogicIdAndLogicalId($eqLogic_id,"historique",true);
			if (count($cmds) == 0) {
				log::add("defauts","info","Création de la commande pour l'historique");
				$cmds = cmd::byEqLogicId($eqLogic_id);
				foreach ($cmds as $cmd) {
					$order = $cmd->getOrder();
					if ($order > 1) {
						$cmd->setOrder($order+1);
						$cmd->save();
					}
				}
				// Création de la commande info "historique"
				$cmd = new cmd();
				$cmd->setEqLogic_id($eqLogic_id);
				$cmd->setLogicalId("historique");
				$cmd->setName("historique");
				$cmd->setType("info");
				$cmd->setSubType("string");
				$cmd->setOrder(2);
				$cmd->setConfiguration("histosize",3);
				$cmd->setConfiguration("historetention",7);
				$cmd->setConfiguration("histounite","j");
				$cmd->save();
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
