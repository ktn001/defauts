<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('defauts');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <!-- Page d'accueil du plugin -->
    <div class="col-xs-12 eqLogicThumbnailDisplay">
	<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
	<!-- Boutons de gestion du plugin -->
	<div class="eqLogicThumbnailContainer">
	    <div class="cursor eqLogicAction logoPrimary" data-action="add">
		<i class="fas fa-plus-circle"></i>
		<br>
		<span>{{Ajouter}}</span>
	    </div>
	    <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
		<i class="fas fa-wrench"></i>
		<br>
		<span>{{Configuration}}</span>
	    </div>
	</div>
	<legend><i class="fas fa-table"></i> {{Mes défauts}}</legend>
	<!-- Champ de recherche -->
	<div class="input-group" style="margin:5px;">
	    <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
	    <div class="input-group-btn">
		<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
	    </div>
	</div>
	<!-- Liste des équipements du plugin -->
	<div class="eqLogicThumbnailContainer">
	    <?php
	    foreach ($eqLogics as $eqLogic) {
		$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
		echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
		echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
		echo '<br>';
		echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
		echo '</div>';
	    }
	    ?>
	</div>
    </div> <!-- /.eqLogicThumbnailDisplay -->

    <!-- Page de présentation de l'équipement -->
    <div class="col-xs-12 eqLogic" style="display: none;">
	<!-- barre de gestion de l'équipement -->
	<div class="input-group pull-right" style="display:inline-flex;">
	    <span class="input-group-btn">
		<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
		<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
		</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
		</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
		</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
		</a>
	    </span>
	</div>
	<!-- Onglets -->
	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
	    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i><span class="hidden-xs"> {{Équipement}}</span></a></li>
	    <li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i><span class="hidden-xs"> {{Surveillances}}</span></a></li>
	</ul>
	<div class="tab-content">
	    <!-- Onglet de configuration de l'équipement -->
	    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
		<!-- Partie gauche de l'onglet "Equipements" -->
		<!-- Paramètres généraux de l'équipement -->
		<form class="form-horizontal">
		    <fieldset>
			<div class="col-lg-6">
			    <legend><i class="fas fa-wrench"></i> {{Général}}</legend>
			    <div class="form-group">
				<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
				<div class="col-sm-7">
				    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
				    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
				</div>
			    </div>
			    <div class="form-group">
				<label class="col-sm-3 control-label" >{{Objet parent}}</label>
				<div class="col-sm-7">
				    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
					<option value="">{{Aucun}}</option>
					<?php
					$options = '';
					foreach ((jeeObject::buildTree(null, false)) as $object) {
					    $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
					}
					echo $options;
					?>
				    </select>
				</div>
			    </div>
			    <div class="form-group">
				<label class="col-sm-3 control-label">{{Catégorie}}</label>
				<div class="col-sm-7">
				    <?php
				    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
					echo '<label class="checkbox-inline">';
					echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
					echo '</label>';
				    }
				    ?>
				</div>
			    </div>
			    <div class="form-group">
				<label class="col-sm-3 control-label">{{Options}}</label>
				<div class="col-sm-7">
				    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
				    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
				</div>
			    </div>
			    <br>

			    <legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
			    <div class="form-group">
				<label class="col-sm-3 control-label">{{Auto acquittement}}</label>
				<div class="col-sm-7">
				    <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="autoAcquittement" />
				</div>
			    </div>
			    <div class="form-group auto-acquittement-option">
				<label class="col-sm-3 control-label">{{Délais}}</label>
				<input type="text" class="eqLogicAttr" data-l1key="configuration" data-l2key="delaisAcquittement" />
			    </div>
			</div>

			<!-- Partie droite de l'onglet "Équipement" -->
			<!-- Affiche l'icône du plugin par défaut mais vous pouvez y afficher les informations de votre choix -->
			<div class="col-lg-6">
			    <legend><i class="fas fa-info"></i> {{Informations}}</legend>
			    <div class="form-group">
				<div class="text-center">
				    <img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;"/>
				</div>
			    </div>
			</div>
		    </fieldset>
		</form>
		<hr>
	    </div><!-- /.tabpanel #eqlogictab-->

	    <!-- Onglet des commandes de l'équipement -->
	    <div role="tabpanel" class="tab-pane" id="commandtab">
		<a class="btn btn-default btn-sm pull-right " id="bt_addSurveillance" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une surveillance}}</a>
		<br/><br/>
		<div id="container_defaut">
		</div>
		<div class="table-responsive">
		    <table id="table_cmd" class="table table-bordered table-condensed">
			<thead>
			    <tr>
				<th style="width:50px">ID</th>
				<th style="width:230px">{{Nom}}</th>
				<th style="width:130px">{{Fonction}}</th>
				<th>{{Etat}}</th>
				<th>{{Mesure}}</th>
				<th style="width:120px">{{Limite}}</th>
				<th style="width:120px">{{Temporisation}}</th>
				<th width="200px">{{paramètres}}</th>
				<th width="200px">{{Options}}</th>
				<th width="150px"></th>
			    </tr>
			</thead>
			<tbody>
			</tbody>
		    </table>
		</div>
	    </div><!-- /.tabpanel #commandtab-->

	</div><!-- /.tab-content -->
    </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'defauts', 'js', 'defauts');?>
<?php include_file('desktop', 'defauts', 'css', 'defauts');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>