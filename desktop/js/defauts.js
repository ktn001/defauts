
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
  addCmdToTable({type: 'info', logicalId: 'surveillance'});
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

    if (_cmd.logicalId == "defaut") {
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	/* id  et type */
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="id"></span>';
	tr += '<span class="cmdAttr" data-l1key="type" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="subType" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="logicalId" style="display : none"></span>';
	tr += '</td>';
	/* NOM */
	tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>';
	/* FONCTION */
	tr += '<td>Défaut</td>';
	/* ETAT */
	tr += '<td/>';
	/* LIMITE ET TEMPORISATION */
	tr += '<td/>';
	/* PARAMETRES */
	tr += '<td/>';
	/* OPTIONS */
	tr += '<td>';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label>';
	tr += '</td>';
	/* GESTION */
	tr += '<td>';
	tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
	tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
	tr += '</td>';
	tr += '</tr>';
    }

    if (_cmd.logicalId == "acquitter") {
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	/* id  et type */
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="id"></span>';
	tr += '<span class="cmdAttr" data-l1key="type" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="subType" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="logicalId" style="display : none"></span>';
	tr += '</td>';
	/* NOM */
	tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>';
	/* FONCTION */
	tr += '<td>{{Acquittement}}</td>';
	/* ETAT */
	tr += '<td/>';
	/* LIMITE ET TEMPORISATION */
	tr += '<td/>';
	/* PARAMETRES */
	tr += '<td/>';
	/* OPTIONS */
	tr += '<td>';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
	tr += '</td>';
	/* GESTION */
	tr += '<td>';
	tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
	tr += '</td>';
	tr += '</tr>';
    }

    if (_cmd.logicalId == 'historique'){
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	/* id  et type */
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="id"></span>';
	tr += '<span class="cmdAttr" data-l1key="type" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="subType" style="display : none"></span>';
	tr += '<span class="cmdAttr" data-l1key="logicalId" style="display : none"></span>';
	tr += '</td>';
	/* NOM */
	tr += '<td>';
	tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>';
	/* FONCTION */
	tr += '<td>{{Historique}}</td>';
	/* ETAT */
	tr += '<td/>';
	/* LIMITE ET TEMPORISATION */
	tr += '<td/>';
	/* PARAMETRES */
	tr += '<td/>';
	tr +=   '<div class="input-group" style="margin-bottom:5px">';
	tr +=     '<label txpe="text">';
	tr +=       '{{Taille}}: ';
	tr +=       '<input class="cmdAttr form-control input-sm" style="width:60px; float:unset" data-l1key="configuration" data-l2key="histosize"/>';
	tr +=     '</label>';
	tr +=     '<label style="margin-left:5px">';
	tr +=       '{{Rétention}}: ';
	tr +=       '<input class="cmdAttr form-control input-sm" style="float:unset; width:40px; padding-right:0; text-align:right" data-l1key="configuration" data-l2key="historetention"/>';
	tr +=       '<select class="cmdAttr form-select input-sm" style="float:unset; width:100px" data-l1key="configuration" data-l2key="histounite">';
	tr +=         '<option value="m">{{minutes}}</options>';
	tr +=         '<option value="h">{{heures}}</options>';
	tr +=         '<option value="j">{{jours}}</options>';
	tr +=       '</select>';
	tr +=     '</label>';
	tr +=   '</div>';
	tr +=   '<div class="input-group" >';
	tr +=     '<label>';
	tr +=       '{{Format date}}: ';
	tr +=       '<select class="cmdAttr form-select input-sm" style="display:inline-block; width:180px" data-l1key="configuration" data-l2key="formatdate">';
	tr +=         '<option class="input-sm" value="d-m H:i:s">jj-mm HH:MM:SS</options>';
	tr +=         '<option class="input-sm" value="d/m H:i:s">jj/mm HH:MM:SS</options>';
	tr +=         '<option class="input-sm" value="d/m/y H:i:s">jj/mm/aa HH:MM:SS</options>';
	tr +=         '<option class="input-sm" value="d M Y H:i:s">jj mmm aaaa HH:MM:SS</options>';
	tr +=       '</select>';
	tr +=     '</label>';
	tr +=   '</div>';
	tr += '</td>';
	/* OPTIONS */
	tr += '<td>';
	tr +=   '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
	tr += '</td>';
	/* GESTION */
	tr += '<td>';
	tr +=   '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
	tr +=   '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
	tr += '</td>';
	tr += '</tr>';
    }

    if (_cmd.logicalId == 'surveillance') {
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	/* id  et type */
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="id"></span>';
	tr += '<span class="cmdAttr" data-l1key="type" style="display : none">info</span>';
	tr += '<span class="cmdAttr" data-l1key="subType" style="display : none">binary</span>';
	tr += '<span class="cmdAttr" data-l1key="logicalId" style="display : none">surveillance</span>';
	tr += '</td>';
	/* NOM */
	tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom surveillance}}"></td>';
	/* FONCTION */
	tr += '<td>{{Surveillance}}</td>';
	/* ETAT */
	tr += '<td>';
	tr += '<div class="input-group" style="margin-bottom:5px">';
	tr += '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="etat" title="{{Etat}}" placeholder="{{Nom l\'etat}}"/>';
	tr += '<span class="input-group-btn">';
	tr += '<a class="btn btn-default btn-sm cursor listEquipementInfoBinary roundedRight" data-input="etat"><i class="fas fa-list-alt "></i></a>';
	tr += '</span>';
	tr += '</div>';
	/* MESURE */
	tr += '<div class="input-group">';
	tr += '<input class="cmdAttr form-control input-sm roundedLeft tooltips" data-l1key="configuration" data-l2key="mesure" title="{{Mesure}}" placeholder="{{Nom la mesure}}"/>';
	tr += '<span class="input-group-btn">';
	tr += '<a class="btn btn-default btn-sm cursor listEquipementInfoNumeric roundedRight" data-input="mesure"><i class="fas fa-list-alt "></i></a>';
	tr += '</span>';
	tr += '</div>';
	tr += '</td>';
	/* CONSIGNE */
	tr += '<td>';
	tr +=   '<div class="input-sm" style="margin-bottom:5px"></div>';
	/* LIMITE */
	tr +=   '<div class="input-group">';
	tr +=     '<span style="width:80px; display:inline-block">';
	tr +=       '<input class="cmdAttr form-control input-sm tooltips" data-l1key="configuration" data-l2key="limite" title="{{Valeur devant être atteinte après enclechement}}" placeholder="{{Limite}}">';
	tr +=     '</span>';
	/* LIMITE ET TEMPORISATION */
	tr +=     '<span style="width:60px; display:inline-block">';
	tr +=       '<input class="cmdAttr form-control input-sm tooltips" style="margin-left:2px" data-l1key="configuration" data-l2key="delais" title="{{Attente après changement d\'état}}" placeholder="{{secondes}}">';
	tr +=     '</span>';
	tr +=   '</div>';
	tr += '</td>';
	/* PARAMETRES */
	tr += '<td>';
	tr += '<div style="margin-bottom:10px">';
	tr += '<label class="checkbox-inline tooltips" title="{{Inversion du test de la limite}}"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="invert"/>{{Inverser}}</label>';
	tr += '</div>';
	tr += '<div style="margin-bottom:3px">';
	tr += '<label class="checkbox-inline tooltips" title="{{Surveillance pour etat = 1}}"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="en" checked/>{{En}}</label>';
	tr += '<label class="checkbox-inline tooltips" title="{{Surveillance pour etat = 0}}"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="hors" checked/>{{Hors}}</label>';
	tr += '</div>';
	tr += '</td>';
	/* OPTIONS */
	tr += '<td>';
	tr += '<div style="margin-bottom:10px">';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label>';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label>';
	tr += '</div>';
	tr += '<div style="margin-bottom:3px">';
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="display" data-l2key="invertBinary" checked/>{{Affichage inversé}}</label>';
	tr += '</div>';
	tr += '<td>';
	if (is_numeric(_cmd.id)) {
	    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
	    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
	}
	tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
	tr += '</tr>';
    }


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
	    /*jeedom.cmd.changeType(tr, init(_cmd.subType));*/
	}
    });
}
