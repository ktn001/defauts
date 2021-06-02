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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class defauts extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*
	 * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
	 * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
	 */

	/*     * ***********************Methode static*************************** */

	/*
	 * Fonction exécutée automatiquement toutes les minutes par Jeedom
	 */
	public static function cron() {
		$eqLogics = eqLogic::byType("defauts", "true");
		foreach ($eqLogics as $eqLogic) {
			$cmds = cmd::byEqLogicId($eqLogic->getId());
			foreach ($cmds as $cmd) {
				if ($cmd->getLogicalId() == "defaut") {
					if ($eqLogic->getConfiguration("autoAcquittement",0) == 0) {
						continue;
					}
					$delais = $eqLogic->getConfiguration("delaisAcquittement",0);
					if ($delais == 0) {
						continue;
					}
					if ($cmd->execCmd() != 2) {
						continue;
					}
					if (($cmd->getCache("timeLevel2",0) + $delais*60) <= time()) {
						log::add("defauts","info",$cmd->getEqLogic()->getName() . ": Autoacquittement");
						$cmd->acquittement();
					}
				}
				if ($cmd->getLogicalId() == "historique") {
					$cmd->purgeHisto();
				}
			}
		}
	}

	/*
	 * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
	  public static function cron5() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
	  public static function cron10() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
	  public static function cron15() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
	  public static function cron30() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement toutes les heures par Jeedom
	  public static function cronHourly() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement tous les jours par Jeedom
	  public static function cronDaily() {
	  }
	 */



	/*     * *********************Méthodes d'instance************************* */

	// Fonction exécutée automatiquement avant la création de l'équipement
	public function preInsert() {
	}

	// Fonction exécutée automatiquement après la création de l'équipement
	public function postInsert() {
		// Création de la cmd info "defauts"
		$cmd = new cmd();
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId("defaut");
		$cmd->setName("defaut");
		$cmd->setType("info");
		$cmd->setSubType("numeric");
		$cmd->setOrder(0);
		$cmd->setConfiguration("minValue",0);
		$cmd->setConfiguration("maxValue",2);
		$cmd->setIsVisible(0);
		$cmd->save();

		// Création de la commande action "Acquitter"
		$cmd = new cmd();
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId("acquitter");
		$cmd->setName("acquitter");
		$cmd->setType("action");
		$cmd->setSubType("other");
		$cmd->setOrder(1);
		$cmd->setTemplate("dashboard","defauts::acquittement");
		$cmd->setTemplate("mobile","defauts::acquittement");
		$cmd->setDisplay("forceReturnLineAfter",1);
		$cmd->save();

		// Création de la commande info "historique"
		$cmd = new cmd();
		$cmd->setEqLogic_id($this->getId());
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

	// Fonction exécutée automatiquement avant la mise à jour de l'équipement
	public function preUpdate() {
	}

	// Fonction exécutée automatiquement après la mise à jour de l'équipement
	public function postUpdate() {
	}

	// Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
	public function preSave() {
		if ( $this->getConfiguration("autoAcquittement") == 1) {
			if ( !ctype_digit(trim($this->getConfiguration("delaisAcquittement")))) {
				throw new Exception (__("Le délais d'acquittement doit être un entier positif ou nul!",__FILE__));
			}
		}
	}

	// Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
	public function postSave() {
		$surveillances = cmd::byEqLogicIdAndLogicalId($this->getId(),"surveillance",true);
		$values = "";
		foreach ($surveillances as $surveillance) {
			$values .= "#" . $surveillance->getId() . "#";
		}
		$defauts = cmd::byEqLogicIdAndLogicalId($this->getId(),"defaut",true);
		$aquitterValues = "";
		foreach ($defauts as $defaut) {
			$defaut->setValue($values);
			$defaut->save();
			$acquitterValues .= "#" . $defaut->getId() . "#";
		}
		$historiques = cmd::byEqLogicIdAndLogicalId($this->getId(),"historique",true);
		foreach ($historiques as $historique) {
			$historique->setValue($values);
			$historique->save();
		}

		$acquitters = cmd::byEqLogicIdAndLogicalId($this->getId(),"acquitter",true);
		foreach ($acquitters as $acquitter) {
			$acquitter->setValue($acquitterValues);
			$acquitter->save();
		}

	}

	// Fonction exécutée automatiquement avant la suppression de l'équipement
	public function preRemove() {
	}

	// Fonction exécutée automatiquement après la suppression de l'équipement
	public function postRemove() {
	}

	/*
	 * Non obligatoire : permet de modifier l'affichage du widget (également utilisable par les commandes)
	  public function toHtml($_version = 'dashboard') {
	  }
	 */

	/*
	 * Non obligatoire : permet de déclencher une action après modification de variable de configuration
	public static function postConfig_<Variable>() {
	}
	 */

	/*
	 * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
	public static function preConfig_<Variable>() {
	}
	 */

	/*     * **********************Getteur Setteur*************************** */
}

class defautsCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*
	  public static $_widgetPossibility = array();
	*/

	/*     * ***********************Methode static*************************** */


	/*     * *********************Methode d'instance************************* */

	/*
	 * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	  public function dontRemoveCmd() {
	  return true;
	  }
	 */

	public function widgetPossibility($_key = '', $_default = true){
		if ($this->getLogicalId() == "defaut") {
			return false;
		}
		if ($this->getLogicalId() == "acquitter") {
			if ($_key == "custom::widget") {
				return false;
			}
			return true;
		}

		return true;
	}
	
	public function preSave () {

		if ($this->getLogicalId() == 'defaut') {
			$this->setTemplate('dashboard','defauts::defaut');
			$this->setTemplate('mobile','defauts::defaut');
		}

		if ($this->getLogicalId() == 'historique') {
			// Vérification de la taille
			if ( !ctype_digit(trim($this->getConfiguration("histosize")))) {
				throw new Exception (__("La taille de l'historique doit être un nombre entier positif!",__FILE__));
			}
			if ($this->getConfiguration("histosize") > 5) {
				throw new Exception (__("La taille de l'historique ne peut pas être supérieure à 5",__FILE__));
			}
		}

		if ($this->getLogicalId() == 'surveillance') {
			// Vérification de la limite
			if ( !ctype_digit(trim($this->getConfiguration("limite")))) {
				throw new Exception (__("La limite doit être un nombre entier!",__FILE__));
			}
			// Vérification du délais
			if ( !ctype_digit(trim($this->getConfiguration("delais")))) {
				throw new Exception (__("Le délais doit être un nombre entier!",__FILE__));
			}
			// Vérification de l'état
			$etat = trim ($this->getConfiguration('etat'));
			if ( $etat == '' ) {
				throw new Exception (__("L'état doit être défini!",__FILE__));
			}
			if ( is_numeric (stripos ($etat,"#" . $this->getId() . "#"))) {
				throw new Exception (__("Vous ne pouvez utiliser l'info elle même dans l'Etat",__FILE__));
			}
			if (! preg_match ("/^#[^#]+#$/", $etat)) {
				throw new Exception (__("L'etat doit être une information simple",__FILE__));
			}
			// Vérification de la mesure
			$mesure = $this->getConfiguration('mesure');
			if ( $mesure == '' ) {
				throw new Exception (__("La mesure doit être définie!",__FILE__));
			}
			// Renseignement du paramètre "value" qui contient la liste des
			// commandes et variables qui influancent la valeur de $this
			$value = '';
			// recherche de commandes dans "etat"
			$etat = $this->getConfiguration('etat');
			preg_match_all("/#([0-9]+)#/", $etat, $matches);
			foreach ($matches[1] as $cmd_id) {
				$cmd = self::byId($cmd_id);
				if (is_object($cmd) && $cmd->getType() == 'info') {
					$value .= '#' . $cmd_id . '#';
				}
			}
			// recherche de variables dans etat"
			preg_match_all("/variable\((.*?)\)/", $etat, $matches);
			foreach ($matches[1] as $variable) {
				$value .= '#variable(' . $variable . ')#';
			}
			// recherche de commandes dans "mesure"
			$mesure = $this->getConfiguration('mesure');
			preg_match_all("/#([0-9]+)#/", $mesure, $matches);
			foreach ($matches[1] as $cmd_id) {
				$cmd = self::byId($cmd_id);
				if (is_object($cmd) && $cmd->getType() == 'info') {
					$value .= '#' . $cmd_id . '#';
				}
			}
			// recherche de variables dans etat"
			preg_match_all("/variable\((.*?)\)/", $mesure, $matches);
			foreach ($matches[1] as $variable) {
				$value .= '#variable(' . $variable . ')#';
			}
			$this->setValue($value);
		}
	}

	public function toHtml ($_version = 'dashboard', $_options = "") {
		if ($this->getLogicalId() == "acquitter" || $this->getLogicalId() == "defaut") {
			if ($_options == "") {
				$_options = array();
			}
			if (config::byKey('interface::advance::coloredIcons') == 1) {
				$_options["icon_defauts_level_0"] = '<i class="icon icon_green jeedom-alerte2"/>';
				$_options["icon_defauts_level_1"] = '<i class="icon icon_orange jeedom-alerte2"/>';
				$_options["icon_defauts_level_2"] = '<i class="icon icon_red jeedom-alerte2"/>';
			} else {
				$_options["icon_defauts_level_0"] = '<i class="icon jeedom-alerte2" style="opacity:0.2"/>';
				$_options["icon_defauts_level_1"] = '<i class="icon jeedom-alerte2" style="opacity:0.6"/>';
				$_options["icon_defauts_level_2"] = '<i class="icon jeedom-alerte2" style="opacity:1i"/>';
			}
		}
		return parent::toHtml($_version, $_options);
	}

	public function dateEtat () {
		$etat = $this->getConfiguration('etat');
		preg_match_all('/#(\d+)#/',$this->getConfiguration('etat'),$matches);
		$return = 0;
		foreach ($matches[1] as $cmd_id) {
			$cmd = cmd::byId($cmd_id);
			$date = DateTime::createFromFormat("Y-m-d H:i:s",$cmd->getValueDate())->format("U");
			$return = $date > $return ? $date : $return;
		}
		return ($return);
	}

	public function calculSurveillance () {
		$etat =jeedom::evaluateExpression($this->getConfiguration('etat'));

		if ( $etat == 1 && $this->getConfiguration('en',1) == 0 )  {
			return 0;
		}
		if ( $etat == 0 && $this->getConfiguration('hors',1) == 0 )  {
			return 0;
		}

		$mesure =jeedom::evaluateExpression($this->getConfiguration('mesure'));
		$limite =jeedom::evaluateExpression($this->getConfiguration('limite'));
		$invert = jeedom::evaluateExpression($this->getConfiguration('invert'));

		if ($invert == 1) {
			$etat = $etat==1 ? 0 : 1;
		}

		if ($etat == 1) {
			$return = $mesure > $limite ? 0 : 1;
		} else {
			$return = $mesure < $limite ? 0 : 1;
		}
		$this->setCache("alertTime",time());
		return $return;
	}

	// Acquittement du défaut
	public function acquittement () {
		if ($this->getLogicalId() == 'acquitter' || $this->getLogicalId() == 'defaut') {
		$value = 0;
		$surveillances = cmd::byEqLogicIdAndLogicalId($this->getEqLogic_id(), "surveillance", true);
		foreach ($surveillances as $surveillance) {
			if ($surveillance->execCmd() == 1) {
				$value = 1;
			}
		}
		$defauts = cmd::byEqLogicIdAndLogicalId($this->getEqLogic_id(), "defaut", true);
		foreach ($defauts as $defaut) {
			$defaut->event($value);
		}
		} else {
			log::add("defauts","error","Function acquittement appelée pour une commande qui n'a pas le logicalId 'acquitter' ou 'defaut'");
		}
	}

	// Date de retention de l'historique
	public function dateHistoRetention () {
		if ($this->getLogicalId() == 'historique') {
			$retention = $this->getConfiguration("historetention");
			if ($retention == 0) {
				log::add("defauts","debug","Pas de retention");
				return 0;
			}
			switch ( $this->getConfiguration("histounite")){
			case "m":
				$retention *= 60;
				break;
			case "h":
				$retention *= 60*60;
				break;
			case "j":
				$retention *= 60*60*24;
			}
			return date("U") - $retention;

		} else {
			log::add("defauts","erreur","dateHistoRetention  appelé pour une commande erronée");
			return NULL;
		}
	}

	// Mise à jour de l'historique sur la base d'une nouvelle liste
	public function actualiseHistorique($liste) {
		if ($this->getLogicalId() == 'historique') {
			$this->setCache("liste", json_encode($liste));
			$value .= "<div style='text-align:left'>";
			$nbLignes = $this->getConfiguration("histosize");
			for ($i = 0; $i < $nbLignes; $i++) {
				$value .= "<br/>";
				if (array_key_exists($i,$liste)) {
					$value .= date("d-m H:i:s",$liste[$i]["time"]) . " : " . $liste[$i]["nom"];
				}
			}
			$value .= "<br/><br/></div>";
			return $value;
		} else {
			log::add("defauts","erreur","actualiseHistorique  appelé pour une commande erronée");
			return NULL;
		}
	}

	// Purge des éléments expirés de l'historique
	public function purgeHisto () {
		if ($this->getLogicalId() == 'historique') {
			log::add("defauts","debug","Lancement de purgeHisto");
			$retention = $this->dateHistoRetention();
			$liste = json_decode($this->getCache("liste"), true);
			for ($i = count($liste); $i > 0; $i--) {
				if ($liste[$i-1]['time'] < $retention) {
					array_splice($liste,$i-1,1);
				}
			}
			$this->event($this->actualiseHistorique($liste));
		} else {
			log::add("defauts","erreur","purgeHisto appelé pour une commande erronée");
		}
	}

	// Exécution d'une commande
	public function execute ($_options = array()) {
		if ($this->getLogicalId() == 'acquitter') {
			log::add("defauts","debug","execute acquitter" );
			$this->acquittement();
		}

		if ($this->getLogicalId() == 'defaut') {
			log::add("defauts","debug","execute defaut" );

			// L'ancienne valeur de la commande
			$oldValue=$this->execCmd();
			if (! is_numeric($oldValue)) {
				$oldValue = 0;
			}

			// L'ancienne liste des commandes en défaut
			$oldCmdsEnDefaut = $this->getCache("cmdsEnDefaut");

			// La liste des commandes actuellement en défaut
			$cmds = cmd::byEqLogicId($this->getEqLogic_id(),"info");
			$cmdsEnDefaut = [];
			foreach ($cmds as $cmd) {
				if ($cmd->getLogicalId() != "surveillance") {
					continue;
				}
				if ($cmd->execCmd() == 1) {
					$cmdsEnDefaut["cmd_" . $cmd->getId()] = 1;
				}
			}

			// Enrgistremet de la nouvelle liste des commandes en défaut
			$this->setCache("cmdsEnDefaut", $cmdsEnDefaut);

			// Calcul de la nouvelle valeur
			switch ($oldValue){
			case 0:
				if (empty($cmdsEnDefaut)) {
					return 0;
				}
				$eqConfig=$this->getEqLogic()->getConfiguration();
				if ($eqConfig['autoAcquittement'] == 1 && $eqConfig['delaisAcquittement'] == 0) {
					return 1;
				}
				$this->setCache("timeLevel2", time());
				return 2;
			case 1:
				if (empty($cmdsEnDefaut)) {
					return 0;
				}
				$nouveauDefaut = false;
				foreach ($cmdsEnDefaut as $key => $value) {
					if (! array_key_exists($key, $oldCmdsEnDefaut)) {
						$nouveauDefaut = true;
						break;
					}
				}
				if ($nouveauDefaut) {
					$eqConfig=$this->getEqLogic()->getConfiguration();
					if ($eqConfig['autoAcquittement'] == 1 && $eqConfig['delaisAcquittement'] == 0) {
						return 1;
					}
					$this->setCache("timeLevel2", time());
					return 2;
				}
				return 1;
			case 2:
				$newCmdsEnDefaut = array_merge($oldCmdsEnDefaut, $cmdsEnDefaut);
				$this->setCache("timeLevel2", time());
				return 2;
			}
		}

		if ($this->getLogicalId() == "historique") {
			log::add("defauts","debug","execute historique" );
			$liste = json_decode($this->getCache("liste"), true);
			$dernierDate = $this->dateHistoRetention();
			if ( ! is_array($liste)) {
				$liste = array();
			}
			if (count($liste) > 0) {
				$entry = $liste[0];
				$dernierDate = $liste[0]["time"];
			}
			$surveillances = cmd::byEqLogicIdAndLogicalId($this->getEqLogic_id(),"surveillance",true);
			foreach ($surveillances as $surveillance) {
				if ($surveillance->execCmd() == 1) {
					$date = strtotime($surveillance->getValueDate());
					if ($date > $dernierDate) {
						$entry=array("time"=>$date,"nom"=>$surveillance->getName());
						$liste[]=$entry;
					}
				}
			}
			usort ($liste, function($a,$b) {
				return $b["time"]<=>$a["time"];
			});
			while (count($liste) > 5) {
				array_pop($liste);
			}
			return $this->actualiseHistorique($liste);
		}

		if ($this->getLogicalId() == 'surveillance') {
			log::add("defauts","debug","execute surveillance" );
			$etat =jeedom::evaluateExpression($this->getConfiguration('etat'));

			if ( $etat == 1 && $this->getConfiguration('en',1) == 0 )  {
				return 0;
			}
			if ( $etat == 0 && $this->getConfiguration('hors',1) == 0 )  {
				return 0;
			}

			$delais = jeedom::evaluateExpression($this->getConfiguration('delais'));
			$dateEtat = $this->dateEtat();
			if (($delais > 0 ) && ($dateEtat + $delais) > time()) {
				$cmd = __DIR__ . "/../php/executeCmd.php";
				$cmd .= ' -c ' . $this->getId();
				$cmd .= ' -t ' . $dateEtat;
				log::add("defauts","info",$cmd);
				system::php($cmd . ' >> ' . log::getPathToLog('executeCmd.log') . ' 2>&1 &' );
				return $this->execCmd();
			}
			$newValue = $this->calculSurveillance();
			$actuValue = $this->execCmd();
			$eqName = $this->getEqLogic()->getName();
			$cmdName = $this->getName();
			$level = $newValue == $actuValue ? "debug" : "info";
			log::add("defauts",$level,"$eqName: $cmdName: $actuValue => $newValue");
			return $newValue;
		}
	}

	/*     * **********************Getteur Setteur*************************** */

}
