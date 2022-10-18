
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

/*
 * Affichage des option d'auto-acquittement
 */
$('[data-l2key="autoAcquittement"]').on('change', function (event) {
    if ($(this).is(':checked')) {
	$(".auto-acquittement-option").show();
    } else {
	$(".auto-acquittement-option").hide();
    }
});
	
/*
 * Bouton pour la création d'une surveillance
 */
$("#bt_addSurveillance").on('click', function (event) {
  addCmdToTable({type: 'info', subType: 'binary', logicalId: 'surveillance'});
  modifyWithoutSave = true;
});

/*
 * Bouton pour le création de suveillance de condigne
 */
$("#bt_addSurvConsigne").on('click', function (event) {
  addCmdToTable({type: 'info', subType: 'binary', logicalId: 'survConsigne'});
  modifyWithoutSave = true;
});

/* 
 * Permet la réorganisation des commandes dans l'équipement
 */
$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

/*
 * Affiche le popup pour la sélection d'une info binaire
 */
$("#table_cmd").delegate(".listEquipementInfoBinary", 'click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info', subType: 'binary'}}, function (result) {
	var calcul = el.closest('tr').find('.cmdAttr[data-l1key=configuration][data-l2key=' + el.data('input') + ']');
	calcul.atCaret('insert', result.human);
    });
});

/*
 * Affiche le popup pour la sélection d'une info numerique
 */
$("#table_cmd").delegate(".listEquipementInfoNumeric", 'click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info', subType: 'numeric'}}, function (result) {
	var calcul = el.closest('tr').find('.cmdAttr[data-l1key=configuration][data-l2key=' + el.data('input') + ']');
	calcul.atCaret('insert', result.human);
    });
});

/*
 * Fonction créant la cellule "ID"
 */
function _celID() {
	return '<td>'
	 + '<span class="cmdAttr" data-l1key="id"></span>'
	 + '<span class="cmdAttr" data-l1key="type" style="display : none"></span>'
	 + '<span class="cmdAttr" data-l1key="subType" style="display : none"></span>'
	 + '<span class="cmdAttr" data-l1key="logicalId" style="display : none"></span>'
	 + '</td>';
}

/*
 * Fonction créant la cellule "Nom"
 */
function _celNom(){
	 return '<td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>';
}

/*
 * Fonction créant la cellule "Fonction"
 */
function _celFonction(_cmd){
	cel = '<td>';
	switch (_cmd.logicalId) {
		case 'defaut':
			cel += 'Défaut';
			break;
		case 'acquitter':
			cel += 'Acquittement';
			break;
		case 'historique':
			cel += 'Historique';
			break;
		case 'surveillance':
			cel += 'Surveillance';
			break;
		case 'survConsigne':
			cel += 'Consigne';
			break;
	}
	cel += '</td>';
	return cel;
}

/*
 * Fonction créant la cellule "Etat/mesure"
 */
function _celEtatMesure(_cmd){
	etat = '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="etat" title="{{Etat}}" placeholder="{{Nom de l\'etat}}"/>'
	     + '<span class="input-group-btn">'
	     +   '<a class="btn btn-default btn-sm cursor listEquipementInfoBinary roundedRight" data-input="etat">'
	     +     '<i class="fas fa-list-alt "></i>'
	     +   '</a>'
	     + '</span>';
	mesure = '<input class="cmdAttr form-control input-sm roundedLeft tooltips" data-l1key="configuration" data-l2key="mesure" title="{{Mesure}}" placeholder="{{Nom de la mesure}}"/>'
	       + '<span class="input-group-btn">'
	       +   '<a class="btn btn-default btn-sm cursor listEquipementInfoNumeric roundedRight" data-input="mesure">'
	       +     '<i class="fas fa-list-alt "></i>'
	       +   '</a>'
	       + '</span>';
	cel = '<td>';
	switch (_cmd.logicalId) {
		case 'surveillance':
		case 'survConsigne':
			cel += '<div class="input-group" style="margin-bottom:5px">'
			    +  etat
			    +  '</div>'
			    +  '<div class="input-group">'
			    +  mesure
			    +  '</div>';
	}
	cel += '</td>';
	return cel;
}

/*
 * Fonction créant la cellule des arguments
 */
function _celArguments(_cmd) {
	consigne =  '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="consigne" title="{{Consigne}}" placeholder="{{Nom la consigne}}"/>'
		 +  '<span class="input-group-btn">'
		 +    '<a class="btn btn-default btn-sm cursor listEquipementInfoNumeric roundedRight" data-input="consigne">'
		 +      '<i class="fas fa-list-alt "></i>'
		 +    '</a>'
		 +  '</span>';
	limite = '<span style="width:80px; display:inline-block">'
	       +   '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="limite" title="{{Valeur devant être atteinte après enclechement}}" placeholder="{{Limite}}">'
	       + '</span>';
	delais = '<span style="width:60px; display:inline-block; margin-left:2px">'
	       +   '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="delais" title="{{Attente après changement d\'état}}" placeholder="{{secondes}}">'
	       +'</span>';
	cel = '<td>';
	switch (_cmd.logicalId) {
		case 'surveillance':
			cel += '<div class="input-sm" style="margin-bottom:5px"></div>'
			    +  limite
			    +  delais
			    +  '</div>';
			break;
		case 'survConsigne':
			cel += '<div class="input-group" style="margin-bottom:5px">'
			    +  consigne
			    +  '</div>'
			    +  '<div class="input-group">'
			    +  limite
			    +  delais
			    +  '</div>';
			break;
	}
	cel += '</td>';
	return cel;
}

/*
 * Fonction pour le paramètres
 */
function _celParametre(_cmd) {
	taille = '<label type="text">'
	       +   '{{Taille}}: '
	       +   '<input class="cmdAttr form-control input-sm" style="width:60px; float:unset" data-l1key="configuration" data-l2key="histosize"/>'
	       + '</label>';
	retention = '<label style="margin-left:5px">'
		  +   '{{Rétention}}: '
		  +   '<input class="cmdAttr form-control input-sm" style="float:unset; width:40px; padding-right:0; text-align:right" data-l1key="configuration" data-l2key="historetention"/>'
		  +   '<select class="cmdAttr form-select input-sm" style="float:unset; width:100px" data-l1key="configuration" data-l2key="histounite">'
		  +     '<option value="m">{{minutes}}</options>'
		  +     '<option value="h">{{heures}}</options>'
		  +     '<option value="j">{{jours}}</options>'
		  +   '</select>'
		  + '</label>';
	formatDate = '<label>'
		   +   '{{Format date}}: '
		   +   '<select class="cmdAttr form-select input-sm" style="display:inline-block; width:180px" data-l1key="configuration" data-l2key="formatdate">'
		   +     '<option class="input-sm" value="d-m H:i:s">jj-mm HH:MM:SS</options>'
		   +     '<option class="input-sm" value="d/m H:i:s">jj/mm HH:MM:SS</options>'
		   +     '<option class="input-sm" value="d/m/y H:i:s">jj/mm/aa HH:MM:SS</options>'
		   +     '<option class="input-sm" value="d M Y H:i:s">jj mmm aaaa HH:MM:SS</options>'
		   +   '</select>'
		   + '</label>';
	inverser = '<label class="checkbox-inline tooltips" title="{{Inversion du test de la limite}}">'
		 +   '<input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="invert"/>'
		 +   '{{Inverser}}'
		 + '</label>';
	en = '<label class="checkbox-inline tooltips" title="{{Surveillance pour etat = 1}}">'
	   +   '<input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="en" checked/>'
	   +   '{{En}}'
	   + '</label>';
	hors = '<label class="checkbox-inline tooltips" title="{{Surveillance pour etat = 0}}">'
	     +   '<input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="hors" checked/>'
     	     +   '{{Hors}}'
	     + '</label>';
	cel = '<td>';
	switch (_cmd.logicalId) {
		case 'historique':
			cel += '<div class="input-group" style="margin-bottom:5px">'
			    +  taille
			    +  retention
			    +  '</div>'
			    +  '<div class="input-group" >'
			    +  formatDate
			    +  '</div>';
			break;
		case 'survConsigne':
			inverser = "";
		case 'surveillance':
			cel += '<div calss="input-group" style="margin-bottom:10px">'
			    +  inverser
			    +  '</div>'
			    +  '<div style="margin-bottom:3px">'
			    +  en
			    +  hors
			    +  '</div>';
			break;
	}
	cel += '</td>';
	return cel;
}

/*
 * Fonction créant la cellule "Options"
 */
function _celOptions(_cmd) {
	afficher = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
	historiser = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label>';
	inverser = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="display" data-l2key="invertBinary" checked/>{{Affichage inversé}}</label>';
	cel = '<td>';
	switch (_cmd.logicalId) {
		case 'defaut':
			cel += afficher
			    +  historiser;
			break;
		case 'acquitter':
		case 'historique':
			cel += afficher;
			break;
		case 'surveillance':
		case 'survConsigne':
			cel += '<div style="margin-bottom:10px">'
			    +  afficher
			    +  historiser
			    +  '</div>'
			    +  '<div style="margin-bottom:3px">'
			    +  inverser
			    +  '</div>';
			break;
	}
	cel += '</td>';
	return cel;
}

/*
 * Fonction créant la cellule "Action"
 */
function _celAction(_cmd) {
	cel = '<td>';
	if (is_numeric(_cmd.id)) {
		cel += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
		if (_cmd.type == "info") {
			cel += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
		}
	}
	if (_cmd.logicalId != 'defaut' && _cmd.logicalId != 'acquitter' && _cmd.logicalId != 'historique') {
		cel += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
	}
	cel += '</td>';
	return cel;
}

/*
* Fonction permettant l'affichage des commandes dans l'équipement
*/
function addCmdToTable(_cmd) {

    // Initialisation d'une nouvelle commande 
    if (!isset(_cmd)) {
	 var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
	_cmd.configuration = {};
    }

    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += _celID();
    tr += _celNom();
    tr += _celFonction(_cmd);
    tr += _celEtatMesure(_cmd);
    tr += _celArguments(_cmd);
    tr += _celParametre(_cmd);
    tr += _celOptions(_cmd);
    if (typeof jeeFrontEnd !== 'undefined' && jeeFrontEnd.jeedomVersion !== 'undefined') {
	tr += '<td><span class="cmdAttr" data-l1key="htmlstate"></span></td>';
    }
    tr += _celAction(_cmd);
    tr += '</tr>';

    $('#table_cmd tbody').append(tr);

    var tr = $('#table_cmd tbody tr').last();
    jeedom.eqLogic.builSelectCmd({
	id:  $('.eqLogicAttr[data-l1key=id]').value(),
	filter: {type: 'info'},
	error: function (error) {
	    $('#div_alert').showAlert({message: error.message, level: 'danger'});
	},
	success: function (result) {
	    tr.find('.cmdAttr[data-l1key=value]').append(result);
	    tr.setValues(_cmd, '.cmdAttr');
	}
    });
}
