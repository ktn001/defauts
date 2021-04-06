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

class panne extends eqLogic {
    /*     * *************************Attributs****************************** */

  /*
   * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
   * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
   */

    /*     * ***********************Methode static*************************** */

    public static function event () {
	log::add('panne','debug',"Début d event()");
    }
    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {
      }
     */

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

    }

 // Fonction exécutée automatiquement avant la mise à jour de l'équipement
    public function preUpdate() {

    }

 // Fonction exécutée automatiquement après la mise à jour de l'équipement
    public function postUpdate() {

    }

 // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
    public function preSave() {
    }

 // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
    public function postSave() {

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

class panneCmd extends cmd {
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

    public function preSave () {

	if ($this->getLogicalId() == 'surveillance') {

	    // Vérification de la limite
	    if ( !is_numeric($this->getConfiguration('limite'))) {
		throw new Exception (__('La limite doit être un nombre entier!',__FILE__));
	    }

	    // Vérification du délais
	    if ( !is_numeric($this->getConfiguration('delais'))) {
		throw new Exception (__('Le délais doit être un nombre entier!',__FILE__));
	    }

	    // Vérification de la répétition
	    if ( !is_numeric($this->getConfiguration('repetition'))) {
		throw new Exception (__('Le temps d\'attente avant répétition doit être un nombre entier!',__FILE__));
	    }

	    // Vérification de l'état
	    $etat = trim ($this->getConfiguration('etat'));
	    if ( $etat == '' ) {
		throw new Exception (__('L\'état doit être défini!',__FILE__));
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
		throw new Exception (__('La mesure doit être définie!',__FILE__));
	    }

	    // Renseignement du paramête "value" qui contient la liste des
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

  // Exécution d'une commande
    public function execute($_options = array()) {
	if ($this->getLogicalId() == 'surveillance') {
	    
#	    $etat = $this->getConfiguration('etat');
#
#	    preg_match_all('/#(\d+)#/',$this->getConfiguration('etat'),$matches);
#
#	    $age = time(); // Age de la plus récente de info;
#	    foreach ($matches[1] as $cmd_id) {
#		$cmd_e = cmd::byId($cmd_id);
#		$a = time() - DateTime::createFromFormat("Y-m-d H:i:s",$cmd_e->getValueDate())->format("U");
#		$age = $a < $age ? $a : $age;
#	    }
#	    log::add("panne", "debug", "AGE : ". $age . " secondes");
#
#	    $delais = jeedom::evaluateExpression($this->getConfiguration('delais'));
#	    if ($age < $delais) {
#		$pid = pcntl_fork();
#		log::add("panne", "debug","pid: " . $pid . "\n");
#		if ( $pid == 0) {
#			log::add ("panne", "error", "Erreur lors du fork");
#		}
#		$wait = $delais - $age;
#	    }
	    

	    $etat =jeedom::evaluateExpression($this->getConfiguration('etat'));
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
	    return $return;
	}
    }

    /*     * **********************Getteur Setteur*************************** */

}
