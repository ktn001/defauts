
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
* Fonction por l'affichage des commandes
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

    // ID
    tr += '<td>'
    tr += '  <span class="cmdAttr" data-l1key="id"></span>'
    tr += '  <span class="cmdAttr" data-l1key="type" style="display : none"></span>'
    tr += '  <span class="cmdAttr" data-l1key="subType" style="display : none"></span>'
    tr += '  <span class="cmdAttr" data-l1key="logicalId" style="display : none"></span>'
    tr += '</td>';

    // NOM
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>';

    // FONCTION
    tr += '<td>';
    switch (_cmd.logicalId) {
	case 'defaut':
	    tr += 'Défaut';
	    break;
	case 'acquitter':
	    tr += 'Acquittement';
	    break;
	case 'historique':
	    tr += 'Historique';
	    break;
	case 'surveillance':
	    tr += 'Surveillance';
	    break;
	case 'survConsigne':
	    tr += 'Consigne';
	    break;
    }
    tr += '</td>';

    // ETAT / MESURE
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
    tr += '<td>';
    switch (_cmd.logicalId) {
	case 'surveillance':
	case 'survConsigne':
	    tr += '<div class="input-group" style="margin-bottom:5px">'
		+  etat
		+  '</div>'
		+  '<div class="input-group">'
		+  mesure
		+  '</div>';
    }
    tr += '</td>';

    // CONSIGNE / LIMITE / TEMPORISATION
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
    tr += '<td>';
    switch (_cmd.logicalId) {
	case 'surveillance':
	    tr += '<div class="input-sm" style="margin-bottom:5px"></div>'
		+  limite
		+  delais
		+  '</div>';
	    break;
	case 'survConsigne':
	    tr += '<div class="input-group" style="margin-bottom:5px">'
		+  consigne
		+  '</div>'
		+  '<div class="input-group">'
		+  limite
		+  delais
		+  '</div>';
	    break;
    }
    tr += '</td>';

    // PARAMETRES
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
    tr += '<td>';
    switch (_cmd.logicalId) {
	case 'historique':
	    tr += '<div class="input-group" style="margin-bottom:5px">'
	    tr +=  taille
	    tr +=  retention
	    tr +=  '</div>'
	    tr +=  '<div class="input-group" >'
	    tr +=  formatDate
	    tr +=  '</div>';
	    break;
	case 'survConsigne':
	    inverser = "";
	case 'surveillance':
	    tr += '<div calss="input-group" style="margin-bottom:10px">'
	    tr +=  inverser
	    tr +=  '</div>'
	    tr +=  '<div style="margin-bottom:3px">'
	    tr +=  en
	    tr +=  hors
	    tr +=  '</div>';
	    break;
    }
    tr += '</td>';

    // OPTIONS
    afficher = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
    historiser = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label>';
    inverser = '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="display" data-l2key="invertBinary" checked/>{{Affichage inversé}}</label>';
    tr += '<td>';
    switch (_cmd.logicalId) {
	case 'defaut':
	    tr += afficher
	    tr += historiser;
	    break;
	case 'acquitter':
	case 'historique':
	    tr += afficher;
	    break;
	case 'surveillance':
	case 'survConsigne':
	    tr += '<div style="margin-bottom:10px">'
	    tr +=  afficher
	    tr +=  historiser
	    tr +=  '</div>'
	    tr +=  '<div style="margin-bottom:3px">'
	    tr +=  inverser
	    tr +=  '</div>';
	    break;
    }
    tr += '</td>';

    // ETAT
    if (typeof jeeFrontEnd !== 'undefined' && jeeFrontEnd.jeedomVersion !== 'undefined') {
	tr += '<td><span class="cmdAttr" data-l1key="htmlstate"></span></td>';
    }
    tr += '<td>';

    // ACTIONS
    if (is_numeric(_cmd.id)) {
	tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
	if (_cmd.type == 'action' || typeof jeeFrontEnd === 'undefined') {
	    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
	}
    }
    if (_cmd.logicalId != 'defaut' && _cmd.logicalId != 'acquitter' && _cmd.logicalId != 'historique') {
	tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    }
    tr += '</td>';

    tr += '</tr>';

    $('#table_cmd tbody').append(tr);

    var tr = $('#table_cmd tbody tr').last();
    tr.setValues(_cmd, '.cmdAttr');
}
