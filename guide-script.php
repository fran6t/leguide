<?php
	//////////////////////////////////////////////////////////
	// Exports de catalogue vers Leguide.com				//
	// (c)2009 - www.creation-shop.ch						//
	//////////////////////////////////////////////////////////

//---------------------------
//Module réalisé par "M1bs"
//---------------------------
//Message d'origine : http://www.prestashop.com/forums/viewthread/16269/developpement_et_modules/module_free__module_leguide_extraction_des_articles__declinaison_selon_categorie

/*
V1.58
Fran6t
Quelques corrections pour le rendre compatible avec prestashop 1.5.4.1
Pour cela changement complet du contenu de art_info.php par une version de johnny57 (forum prestashop)
Divers corrections identifiees par marquage fran6t juste avant dans plusieurs fichiers .php


V1.57
KTechnologie.com :
Suppression de l'exclusion du produit N°1
Utilisation des produits de niveau 1 pour la racine de l'arborescence si le niveau 0 n'existe pas
Gestion des réductions de Prestashop 1.4

V1.56
KTechnologie.com :
Correction d'un éventuel recouvrement des fichiers temporaires lors d'une création par tranches
Passage à jquery.treeview v1.4
Arborescence repliée par défaut

V1.55
KTechnologie.com :
Gestion de la copie du catalogue dans un autre répertoire après sa génération
Simplification de l'appel via cron
Alerte si aucune catégorie sélectionnée

V1.54
KTechnologie.com :
Modification CSS
Sauvegarde valeur option onMutuOnceaDay

V1.54
KTechnologie.com :
Correction CSS

V1.53
KTechnologie.com :
Correction CSS

V1.52
KTechnologie.com :
Ajout de la génération par tranches pour outrepasser la limitation du timeout PHP sur un serveur mutualisé
Ajout de la gestion du paramètre 'auto' dans l'URL permettant de lancer automatiquement la génération du catalogue (à utiliser avec une tâche cron)
Ajout de l'affichage de la date du catalogue trouvé sur le disque
Mise en option de l'affichage du catalogue
Séparateur définie automatiquement en fonction du catalogue
Extension du fichier définie automatiquement en fonction du catalogue
BUGFIX: Correction de quelques valeurs non définies
BUGFIX: Affichage de l'arborescence correctement indentée
BUGFIX: Prise en compte des produits de la racine
BUGFIX: Calcul de la TVA compatible avec Prestashop 1.4

V1.51 (pas de .zip pour cette version)
KTechnologie.com :
Intégration des correctifs pppplus - 06 January 2010 06-20 PM

V1.50 (pas de .zip pour cette version)
KTechnologie.com :
Intégration des correctifs dangee - 16 December 2009 04-05 PM - Shopping

V1.49 (pas de .zip pour cette version)
KTechnologie.com :
Intégration des correctifs dangee - 15 December 2009 12-43 PM - Shopzilla bis

V1.48 (pas de .zip pour cette version)
KTechnologie.com :
Intégration des correctifs de dangee - 14 December 2009 12-49 PM - Shopzilla

V1.47 (pas de .zip pour cette version)
Correctifs de pppplus - 14 December 2009 12-49 AM

V1.46
Fabien LAHAULLE - 19/11/2009
*ajout de la gestion des doublons pour kelkoo (soluce 'pppplus')
*fermeture du fichier généré (fclose) qui était inexistante (plus propre!)
*forcage du mode UTF8 des fichiers générés par un fwrite ((soluce 'pppplus')

V1.45
Fabien LAHAULLE - 18/11/2009
Pour kelkoo, remplacement de la fonction substr (tronquage) par mb_substr sinon les caractères accentués ressortent mal.

V1.44
Fabien LAHAULLE - 17/11/2009
Tronquage à 160c (et ajout de "...")  du champ description pour Kelkoo, pour être conforme à leurs exigences

V1.43
Fabien LAHAULLE - 17/11/2009
ajout de l'export vers le comparateur Kelkoo
Fichiers ajoutés : catalogue_header_kelkoo.php, catalogue_kelkoo.php, catalogue_declinaisons_kelkoo.php
Docs ajoutées : doc officielle Kelkoo ExtranetMarchandKelkoo.pdf, et un guide trouvé sur le net GuideKelkoo.pdf
*/

/* 
CHANGELOG correction Moncler (Avi):

V1.42
Correction url compatibitlité prestashop v1.2.x

V1.40
Suppression des retours chariots pour les descriptions
Ajout du choix gratuité des frais de port
Passage du formatage des prix en notation anglaise (number_format)
Suppression du boutton forcer le telechargement du fichier d export
V1.39
Faille de securité corrigé (merci a Inovatio)
BUGFIX: Correction du bug de la gratuité des frais de port lorsqu les prix, seuil de prix, poids sont > 1000 (format incorrect)
BUGFIX: Correction de la fonction Javascript 'Inverser la sélection' qui modifiais les autre parametres
Refonte de la fonction du nettoyage du texte et suppression des precedentes maj

V1.38
12/07/2009
BUGFIX: Correction des frais de port lorsqu il n y a aucune tranche de poids definit (prix seulement)
Prise en compte des frais de port offert par prix/poids (selon les reglages choisit dans le BO)
BUGFIX: probleme d accent corrigé et ajout de qlq caractere non geré (a verifier et a ameliorer encore)

V1.37b
12/07/2009
BUGFIX: Correction de l’affichage et sauvegarde des valeurs (bug decrit plus haut) Disponibilité, Délai de livraison et Garantie
BUGFIX: Correction bug inversion prix ttc-prix barre
Refonte des fonctions de calcul de prix (simplification)
Ajout de l’option du type de description a exporter (courte/longue)
Ajout de l’option export des produit inactif

V1.355
07/07/2009 : Remplacement de la gestion de l'url rewriting par la classe Link de prestashop
07/07/2009 : Rajout du choix de l'exportation produit avec ou sans les declinaisons
*/

//Archive basée sur le zip de "jolvil" contenant diverses corrections
//http://www.prestashop.com/forums/viewreply/87757/

// CHANGELOG correction Jolvil:
//V1.36
//Jolvil - 08/07/2009 - Utilisation de name pour le nom de produit au lieu de short description
// ajout option O pour etat disponible pour leguide

// CHANGELOG (correction Neodreamer) :
//V1.35
//	20/06/2009 : BUGFIX - correction du problème des prix barrés qui été érronés.
//
// CHANGELOG (évolutions de Fabien LAHAULLE "fabienl" www.mariage-tranquille.fr) :
//V1.34
//      02/06/2009 - BUGFIX - fonctions.php - spécification pour les montants du séparateur décimal et pas de caractère pour les milliers
//V1.33
//	02/06/2009 : BUGFIX - les catégories de type "1.XXXXX", ""2.YYYYY" n'étaient pas récupérées.
//	30/05/2009 : ADD - ajout des docs pour shopmania, leguide et tigoon
//	30/05/2009 : BUGFIX - correction d'un probleme de script javascript dont le chemin était incorrect, donc introuvable.
//V1.32
//	28/05/2009 : BUGFIX - la fonction de nettoyage de caractere separateur dans les chaines faisait planter.
//	28/05/2009 : ADD - suppression du fichier csv avant de l'ouvrir.
//V1.31
//	27/05/2009 : nettoyage des balises <br/> et remplacement par un blanc.
//V1.3
//	26/05/2009 : ajout d'une liste déroulante afin de choisir un site de comparateur de prix
//		-->permet de définir le nom du fichier généré
//	BUGFIX : on ne ramène maintenant que les zones de transport actives, et pas toutes.

//	V1.2
//	26/05/2009 : ajout de la section "Caractéristiques du fichier"
//		-->permet de donner un nom spécifique au fichier pour générer des catalogues pour différents sites
//		-->permet de choisir l'extension du fichier (certains sites imposent un fichier .csv)
//		-->permet de choisir le séparateur de champs utilisé dans le fichier généré
//			(certains sites imposent un fichier avec séparateur point-virgule par exemple)

// CHANGELOG (évolutions de Fabien LAHAULLE "fabienl" www.mariage-tranquille.fr) :
//	V1.0
//	25/05/2009 : modification de la fonction qui récupère l'image du produit --> ajout du critère cover=1 afin de sélectionner l'image par défaut du produit
//			fichiers modifies : art_info.php
//	25/05/2009 : correction du problème de gestion des retours chariots dans les descriptions (libellés collés)
//			fichiers modifies : guide_script.php
//	22/05/2009 : Mise en place de la fonction d'unicité (uniqid) pour les déclinaisons de produits
//			fichiers modifies : guide_script.php


	$startTime = mktime();

	// Classes //
 	require_once("class/mysql.php");
 	require_once("class/tools_guide.php");
 	require_once("class/form.php");
 	require_once("class/art_info.php");
 	require_once('class/html2text.inc.php');
	$ps_ =	_DB_PREFIX_; 					// préfix des table spéficique à chaque shop

	$knownCatalogues = array('leguide','shopmania','tigoon','kelkoo','shoppydoo','shopzilla','shopping');
	
	$module	 = new leguide();		// Nom du module
	$module_name 	= $module->getName();	// nom du module = répertoire
	include("fonctions.php");		// fonctions
	
 	
	echo "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"../modules/".$module_name."/css/styles.css\">";

	$site_base = __PS_BASE_URI__;		// préfix du site
	$url_site = $_SERVER['HTTP_HOST'];	// url du site base Serveur
	$url_site_base_prestashop = $url_site.$site_base;	// url du site prestashop Complet pour la génération du fichier

	// Connexion à la base de donnée
	try
	{
		$Mysql = new my_sql($Serveur = _DB_SERVER_ , $Bdd = _DB_NAME_, $Identifiant = _DB_USER_, $Mdp = _DB_PASSWD_);
		$form = new form($ps_,$Mysql);
	}
	catch (Erreur $e){echo $e -> RetourneErreur();}
	include("import_parameter.php");	// importes les catégories pour les guides

	if (!isset($_GET['auto'])) {
?>

		<!-- JQUERY TREEVIEW --><head>
		<script src="../modules/<?php echo $module_name ;?>/jquery/jquery-latest.js"></script>
		<link rel="stylesheet" href="../modules/<?php echo $module_name ;?>/jquery/jquery.treeview.css" type="text/css" />
		<script type="text/javascript" src="../modules/<?php echo $module_name ;?>/jquery/jquery.treeview.js"></script>

		<script type="text/javascript">
		function chkall()
		{
			var taille = document.forms['form_extract'].elements.length;
			var element = null;
			for(i=0; i < taille; i++)
			{
				element = document.forms['form_extract'].elements[i];
				//Avi - fix check seulement le treeview
				if(element.type == "checkbox" && element.name == "id_cat[]")
				{
					if(!element.checked)
					{
						element.checked = true;
					}else{
						element.checked = false;
					}
				}
			}
		}

		$(document).ready(function(){
			$("#treeview_categories").treeview({
			animated:"normal",
			collapsed: true,
			control: "#treecontrol",
			});
		});


		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
		<!-- JQUERY TREEVIEW -->

		
		<div id="treecontrol">
			<a title="Collapse the entire tree below" href="#" style="color:#268CCD; display:none;"> Tout replier</a>
			<a title="Expand the entire tree below" href="#" style="color:#268CCD; display:none;"> Tout déplier</a>
			<a title="Toggle the tree below, opening closed branches, closing open branches" href="#" style="color:#268CCD;">Tout Déplier/Replier</a> |
			<a title="Toggle selection" href="javascript: chkall();" style="color:#268CCD;">Inverser la sélection</a>
		</div>
<?php
	}
	echo "<span class='leguide'>";
	$form->f_form_header("POST","form_extract");

	$tool_guides = new tool_guides($ps_,$Mysql);
	$cronKey	= $tool_guides->f_get_value('cronKey','leguide');

	// Création du treeview
	if (!isset($_GET['auto'])) {
		// Modif fran6t le 6/5/2013 pour ajout de la langue
		base_arbre($ps_, $module_name, $tool_guides->f_get_value('lang_export','leguide'));
	}
	// Création du formulaire
	$form->f_header_guide_logo("http://marchand.leguide.com","../modules/".$module_name."/catalogue/leguide.com.gif");
	$form->f_header_credits_doc($url_site_base_prestashop, $cronKey, $knownCatalogues);
	$id_currency = $form->f_form_devise_shop();
  	$form->f_list_langue();
	if (version_compare(_PS_VERSION_,'1.4.0.0','>='))
		$form->f_displayGroups();
	//Avi - suppression de la gestion de l url rewriting par le module
  	//$form->f_url_rewriting();
 	$form->f_disponibilite_leguide();
	$form->f_display_carrier();
  	$form->f_zone();
   	$form->f_delai_livraison_leguide();
   	$form->f_garantie_leguide();
   	$form->f_etat_leguide();
	//Avi - Ajout du choix de l'exportation des declinaison et du choix du type de description
	$form->f_makedeclinaison();
	$form->f_description_courte();
	$form->f_usefreeshipping();
	//$form->f_exportallproduct();
	$form->f_actif_only();
	//Fabien LAHAULLE - 26/05/2009 - Ajout d'une section pour les caractéristiques du fichier généré
	$form->f_Informations($module_name,$url_site_base_prestashop);
	$form->f_CaracteristiquesFichier();
	$comparateur = $form->f_DisplayComparateurs();
   	$prefixe_nom_fichier = $form->f_nom_fichier();
   	$destDir = $form->f_destDir();
	
	$sIdxFullPath = '';
	$fullDestDir = '';
	if ($destDir!='') {
		$fullDestDir = str_replace('\\', '/', realpath(dirname(__FILE__).'/../../')."/$destDir");
		$form->f_fullDestDir($fullDestDir);
	}

	if ($fullDestDir!='' && !is_dir($fullDestDir)) {
		if (!mkdir($fullDestDir)) {
			echo "<br>La création du répertoire : $fullDestDir a échoué. Créez le à la main.<br>";
		}
	}

   	//$extension_fichier = $form->f_extension_fichier();		//géré automatiquement à partir de v1.52
   	//$form->f_separateur();	// géré automatiquement

	$form->f_displayCatalog();
	$form->f_onMutu();

   	$form->f_form_submit("Sauvegarder mes pr&eacute;f&eacute;rences et cat&eacute;gories","valid_form_maj");
   	$form->f_form_submit("G&eacute;n&eacute;rer","valid_form");
   	$form->f_form_end();

if(isset($_POST['valid_form']) || isset($_GET['auto'])){

	// Paramètres généraux appliqués à tous les articles
	if (isset($_GET['auto'])) {

		$categories = array();
		getSavedCategories($ps_, $module_name, $categories);
		
		$id_lang				= $tool_guides->f_get_value('lang_export','leguide');
		$delai_livraison		= $tool_guides->f_get_value('delai_livraison','leguide');
		$garantie				= $tool_guides->f_get_value('garantie','leguide');
		$id_carrier				= $tool_guides->f_get_value('livreur','leguide');
		$id_zone				= $tool_guides->f_get_value('frais','leguide');			                      
		$etat					= $tool_guides->f_get_value('etat','leguide');
		//$separateur				= $tool_guides->f_get_value('separateur','leguide');
		//$extension_fichier		= $tool_guides->f_get_value('extension_fichier','leguide');
		$prefixe_nom_fichier	= $tool_guides->f_get_value('nom_fichier','leguide');
		$comparateur			= $tool_guides->f_get_value('nom_comparateur','leguide');
		$disponibilite			= $tool_guides->f_get_value('disponibilite','leguide');		
		$makedeclinaison		= $tool_guides->f_get_value('makedeclinaison','leguide');
		$description_courte		= $tool_guides->f_get_value('description_courte','leguide');
		$usefreeshipping		= $tool_guides->f_get_value('usefreeshipping','leguide');
		$exportallproduct		= $tool_guides->f_get_value('exportallproduct','leguide');
		$actif_only				= $tool_guides->f_get_value('actif_only','leguide');
		$displayCatalog			= $tool_guides->f_get_value('displayCatalog','leguide');
		$onMutu					= $tool_guides->f_get_value('onMutu','leguide');
		$onMutuStep				= $tool_guides->f_get_value('onMutuStep','leguide');
		$onMutuOnceaDay			= $tool_guides->f_get_value('onMutuOnceaDay','leguide');
		$id_group			= $tool_guides->f_get_value('id_group','leguide');
		
		if ($_GET['auto'] != '')
			$comparateur = strtolower(strip_tags($_GET['auto']));
		
	} else {
		$categories				= isset($_POST['id_cat'])?$_POST['id_cat']:NULL;
		$id_lang				= $_POST['lang'];
		$delai_livraison		= $_POST['delai-livraison'];
		$garantie				= $_POST['garantie'];
		$id_carrier				= $_POST['carrier'];
		$id_zone				= $_POST['zone'];
		$etat					= $_POST['etat'];
		//$separateur				= $_POST['separateur'];
		//$extension_fichier		= $_POST['extension_fichier'];
		$prefixe_nom_fichier	= $_POST['nom_fichier'];
		$comparateur 			= $_POST['comparateur'];
		$disponibilite			= $_POST['disponibilite'];
		$makedeclinaison		= isset($_POST['makedeclinaison'])?1:0;
		$description_courte		= isset($_POST['description_courte'])?1:0;
		$usefreeshipping		= isset($_POST['usefreeshipping'])?1:0;
		$exportallproduct		= isset($_POST['exportallproduct'])?1:0;
		$actif_only				= isset($_POST['actif_only'])?1:0;
		$displayCatalog			= isset($_POST['displayCatalog'])?1:0;
		$onMutu					= isset($_POST['onMutu'])?1:0;
		$onMutuStep				= isset($_POST['onMutuStep'])?$_POST['onMutuStep']:2;
		$onMutuOnceaDay			= isset($_POST['onMutuOnceaDay'])?1:0;
		$id_group			= isset($_POST['id_group'])?$_POST['id_group']:0;
	}

	$devise		= isset($_POST['devise'])?$_POST['devise']:'EUR';

	if (!$id_carrier) {
		echo "<span class='alert'>Transporteur/Livraison non définie. Impossible de continuer.</span>";
		return;
	}
	
	switch($comparateur) {
		case 'leguide':
			$extension_fichier = "txt";
			$separateur = "|" ;
			break;
		case 'shopmania' : 
			$extension_fichier = "csv";
			$separateur = "|" ;
			break;
		case 'tigoon' : 
			$extension_fichier = "csv";
			$separateur = ";" ;
			break;
		case 'kelkoo' : 
			$extension_fichier = "txt";
			$separateur = "\t" ;
			break;
		case 'shoppydoo' : 
			$extension_fichier = "txt";
			$separateur = "|" ;
			break;
		case 'shopzilla' : 
			$extension_fichier = "txt";
			$separateur = "|" ;
			break;
		case 'shopping' : 
			$extension_fichier = "txt";
			$separateur = "," ;
			break;
		default :
			echo "<span class='alert'>Comparateur non reconnu !</span>";
			return;
	}
			
	$path_parts = pathinfo(__FILE__);
	$fullfilename = $path_parts['dirname']."/exports/".$prefixe_nom_fichier.$comparateur.".".$extension_fichier;

	// Fichier pour sauvegarder les articles déjà traités
	$sExportedArticles = $path_parts['dirname']."/exports/$comparateur.art";

	if ($displayCatalog) {
		echo "<table class=\"stats\" border=1>
		<tr><td class=\"hed\" colspan=\"8\">Liste des produits</td></td>";
	}

	//Fabien LAHAULLE - 27/05/2009
	$rand=new UniqueRand();

	//Fabien LAHAULLE - 26/05/2009
	$strSeparateur = getSeparateurFromCode($separateur);

	//Avi - 07/07/09 -
	global $link;

	// Nombre de catégories à extraire
	$total_catego	= count($categories);
	if (!$total_catego) {
		echo "<br><span class='alert'>Aucune catégorie ne semble avoir été sélectionné</span><br>";
		return;
	}

	$lastIndex = 0;
	$startedIndex = 0;
	$nbLinesCatalog = 0;
	
	if(!isset($productsExported))
		$productsExported=array();	//Fabien LAHAULLE - 19/11/2009 - solution de 'pppplus'

	if ($onMutu) {
		$idx_filename = "$comparateur.idx";
		$path_parts = pathinfo(__FILE__);
		$sIdxFullPath = $path_parts['dirname']."/exports/".$idx_filename;

		// Si une seule génération par jour
		clearstatcache();
		if ($onMutuOnceaDay && file_exists($fullfilename) && !file_exists($sIdxFullPath) && ( time() <= strtotime('tomorrow', filemtime($fullfilename)))) {
			echo '<br><b>Le catalogue existe déjà : </b> '.basename($fullfilename).' - '.date('j/n/Y - H:i:s', filemtime($fullfilename))."</b><br><br>";
			url_file_download($module_name, $url_site_base_prestashop, $comparateur, $prefixe_nom_fichier.$comparateur, $extension_fichier, $destDir);
			return;
		}

		// Récupération du dernier index traité
		if (file_exists($sIdxFullPath)) {
			if ($hIdxFullPath = fopen($sIdxFullPath, 'r')) {
				$lastIndex = fread($hIdxFullPath, filesize($sIdxFullPath));
				// Stockage de l'index de reprise
				if (!$startedIndex)
					$startedIndex = $lastIndex;
				fclose($hIdxFullPath);

				if ($hIdxFullPath = fopen($sExportedArticles, 'r')) {
					while (!feof($hIdxFullPath)) {
						$productsExported[] = fgets($hIdxFullPath, 1024);
					}
					fclose($hIdxFullPath);
				}
			}
		} else {
			@unlink($sIdxFullPath);
		}

		/*if ($lastIndex == $total_catego) {
			unlink($sIdxFullPath);
			echo '<b>Traitement terminé</b><br><br>';
			exit;
		}*/

		$hExportedArticles = fopen($sExportedArticles, 'a');
		if (!$hExportedArticles) {
			echo "<span class='alert'>Impossible de créer le fichier $sExportedArticles</span><br>";
			return;
		}
		
		echo "<br><b>Tranche</b> ".(floor($startedIndex/$onMutuStep)+1)."/".ceil($total_catego/$onMutuStep)." <b>Catégorie(s) :</b>";
	}

	if (!file_exists($sIdxFullPath)) {
		//Fabien LAHAULLE - 28/05/2009 - Suppression du fichier avant traitement. + KTech
		@unlink($fullfilename);
	}

	// Création du fichier
	$fichier = fopen($fullfilename,"a");
	if (!$fichier) {
		echo "<span class='alert'>Impossible d'ouvrir le fichier $fullfilename en écriture</span><br>";
		return;
	}
	fwrite($fichier, "\xEF\xBB\xBF");	//Fabien LAHAULLE - 19/11/2009 - force UTF8 - solution de 'pppplus'

	// Création header du fichier
	if(filesize ($fullfilename) < 4){
		//Fabien LAHAULLE - 17/11/2009 - ajout moteur kelkoo
		if ($comparateur == 'kelkoo')
			include('catalogue/catalogue_header_kelkoo.php');
		elseif ($comparateur == 'shoppydoo')
			include('catalogue/catalogue_header_shoppydoo.php');
		elseif ($comparateur == 'shopzilla')
			include('catalogue/catalogue_header_shopzilla.php');
		elseif ($comparateur == 'shopping')
			include('catalogue/catalogue_header_shopping.php');
		else
			include('catalogue/catalogue_header.php');	//Leguide, Shopmania, tigoon
	}

	if (!defined('_PS_VERSION_')) {
		echo "<span class='alert'>Version de Prestashop indéfinie ! Vérifiez le fichier config.inc</span>";
		return;
	}
	
	if (version_compare(_PS_VERSION_,'1.4.0.0','>=')){
		$req = "SELECT value FROM ".$ps_."configuration WHERE name='PS_COUNTRY_DEFAULT'";
		$Resulats = $Mysql->TabResSQL($req);
		$ps_country_default = $Resulats[0]['value'];
	}

	for($i=$lastIndex; $i<$total_catego; $i++) {
		// Traitement sur la catégorie en cours
		$id_catego 	=  $categories[$i];

		// IMPORTANT :: Sélection des articles à extraire (basé sur id_product)
		// Catégorie Home exclus
		try {
			$req = "SELECT id_product FROM ".$ps_."category_product where id_category = $id_catego ORDER BY id_product";
			$Resulats = $Mysql->TabResSQL($req);
			//$nbResult = $Mysql->RetourneNbRequetes();

			if ($onMutu) {
				if ($lastIndex != $i)
					echo ',';
				echo " $id_catego";
			}

			foreach ($Resulats as $Valeur) {
				$id_product_r	= $Valeur['id_product'];

				// IMPORTANT :: Sélection des informations liées aux articles
				try {
					//echo "Categorie(id):".$id_catego."<br>";
					//V1.4 retourne id_tax_rules_group 
					if (version_compare(_PS_VERSION_,'1.4.0.0','>=')) {
						// Fran6t 07/05/2013 ajout du critere produit a la vente available_for_order = 1
						$req = "SELECT * FROM ".$ps_."product p
						LEFT JOIN ".$ps_."product_lang pl ON p.id_product = pl.id_product 
						LEFT JOIN `".$ps_."tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group` AND tr.`id_country` = ".$ps_country_default." AND tr.`id_state` = 0) LEFT JOIN `".$ps_."tax` t ON (t.`id_tax` = tr.`id_tax`)
						WHERE p.id_product = $id_product_r".($actif_only?" AND p.available_for_order = 1 AND p.active = 1":"")." AND pl.id_lang=$id_lang";
					} else {
						$req = "SELECT * FROM ".$ps_."product 
						LEFT JOIN ".$ps_."product_lang ON ".$ps_."product.id_product = ".$ps_."product_lang.id_product 
						WHERE ".$ps_."product.id_product = $id_product_r".($actif_only?" and ".$ps_."product.active = 1":"")." and ".$ps_."product_lang.id_lang=$id_lang";
					}
					
					$Resulats = $Mysql->TabResSQL($req);

					foreach ($Resulats as $Valeur) {
						// Informations générales selon article de base
						$id_product			= $Valeur['id_product'];

						/*
						// Descriptions avec nettoyage du texte
						//Jolvil - 08/07/2009 - Utilisation de name pour le nom de produit au lieu de short description
						$nom_produit        =& new html2text(f_convert_text($Valeur['name']));
						//Fabien LAHAULLE - 25/05/2009 - remplacement par un espace au lieu de la suppression simple du retour chariot
						$nom_produit 		= trim(str_replace(CHR(10)," ",$nom_produit->get_text()));  //Fabien LAHAULLE - 25/05/2009
						//Fabien LAHAULLE - 25/05/2009 - remplacement par un espace au lieu de la suppression simple du retour chariot
						$nom_produit 		= str_replace(CHR(9)," ",$nom_produit); //Fabien LAHAULLE - 25/05/2009
						$nom_produit 		= str_replace($strSeparateur," ",$nom_produit); //Fabien LAHAULLE - 27/05/2009

						*/
						/*$desc_produit       =& new html2text(f_convert_text($description_courte?$Valeur['description_short']:$Valeur['description']));

						//Fabien LAHAULLE - 25/05/2009 - remplacement par un espace au lieu de la suppression simple du retour chariot
						$desc_produit 		= trim(str_replace(CHR(10)," ",$desc_produit->get_text())); //Fabien LAHAULLE - 25/05/2009
						//Fabien LAHAULLE - 25/05/2009 - remplacement par un espace au lieu de la suppression simple du retour chariot
						$desc_produit 		= str_replace(CHR(9)," ",$desc_produit); //Fabien LAHAULLE - 25/05/2009
						//Fabien LAHAULLE - 26/05/2009 - remplacement par un espace des caractères identiques au séparateur de champs utilisé
						$desc_produit 		= str_replace($strSeparateur," ",$desc_produit); //Fabien LAHAULLE - 26/05/2009
						*/
						//Avi - simplication et modif dans la fonction f_convert_text2;
						$nom_produit	= f_convert_text2("",$Valeur['name'],false);
						$desc_produit	= f_convert_text2($strSeparateur,$description_courte ? $Valeur['description_short'] : $Valeur['description'], false);

						$weight_base		= $Valeur['weight'];
						$price				= $Valeur['price'];
						$price_s_tva		= $Valeur['price'];
						$price_s_red		= $Valeur['price'];
						$ean13				= $Valeur['ean13'];
						$reference			= $Valeur['reference'];
						$link_rewrite		= $Valeur['link_rewrite'];
						$id_manufacturer	= $Valeur['id_manufacturer'];
						$id_category_default= $Valeur['id_category_default'];
						$ecotax				= $Valeur['ecotax'];;
						$id_tax				= $Valeur['id_tax'];
						if (version_compare(_PS_VERSION_,'1.4.0.0','>=')) {
							$price_with_reduc = SpecificPrice::getSpecificPrice($id_product, (int)(Shop::getCurrentShop()), $id_currency, $ps_country_default, $id_group, 1);
							if ($price_with_reduc['reduction_type'] == 'percentage') {
								$reduction_price	= 0;
								$reduction_percent	= $price_with_reduc['reduction']*100;
							} else {
								$reduction_price	= $price_with_reduc['reduction'];
								$reduction_percent	= 0;
							}
							$date_reduction_s	= $price_with_reduc['from'];
							$date_reduction_e	= $price_with_reduc['to'];;
						} else {
							$reduction_price	= $Valeur['reduction_price'];
							$reduction_percent	= $Valeur['reduction_percent'];
							$date_reduction_s	= $Valeur['reduction_from'];
							$date_reduction_e	= $Valeur['reduction_to'];
						}
						$supplier_reference = $Valeur['supplier_reference'];	// champs "Référence collection" de Prestashop
						//$shopping_cat		= $Valeur['shopping_cat'];
						$id_supplier		= $Valeur['id_supplier'];
						$quantity_stock		= $Valeur['quantity'];
						$type_promotion		= $Valeur['on_sale']; 			//damocles - http://www.prestashop.com/forums/viewthread/22501/P240/#365410

						// Nouvelle instance
						$article = new art_info($ps_,$Mysql);
						// Nom du shop
						$nom_shop = $article->f_shop_name();
						// Taux de TVA
						if ($id_tax)
							$taux_tva		= $article->f_tva_taux($id_tax);
						else
							$taux_tva		= 0;
						// Nom de la catégorie
						$category_name = $article->f_category_name($id_catego,$id_lang);
						// Url de l'article
						//$url_article = $article->f_url_article($url_site_base_prestashop,$link_rewrite,$id_product,$id_lang,$id_category_default);
						//Avi - 07/07/09 - recuperation de l url mise en place sur le site (classe Link)
						$catrewrite=Category::getLinkRewrite($id_category_default, intval($id_lang));
						//$url_article = $link->getProductLink($id_product,$link_rewrite,$catrewrite);
						//Fran6t - 06/05/2013 recup de l'url dans $this->context
						$url_article = $this->context->link->getProductLink($id_product,$link_rewrite,$catrewrite);						

						// Url de l'image
						//Fran6t - 06/05/2013 modif de la fonction f_url_image dans art_info.php
						// et application de $this->context pour support nouveau rangement des images 
						// depuis version presta 1.5 
						$url_image_b = $article->f_url_image($url_site_base_prestashop,$id_product);

						// Sélection du prix pour la livraison
						$delivery_price= $article->f_delivery_price($weight_base,$id_carrier,$id_zone);

						//Avi - 14/07/09 - recuperation des fdp offert par seuil de prix et poids
						$seuil_prix_fdp_offert=preg_replace("#(,| )#","",(Configuration::get('PS_SHIPPING_FREE_PRICE')));
						$seuil_poids_fdp_offert=preg_replace("#(,| )#","",(Configuration::get('PS_SHIPPING_FREE_WEIGHT')));
						$shipping_method= intval(Configuration::get('PS_SHIPPING_METHOD'));

						// Nom fournisseur
						$supplier_name = $article->f_fournisseur_name($id_supplier);
						// Nom fabricant
						$manufacturer_name = $article->f_fabricant($id_manufacturer);

						// Sélection des déclinaisons
						try {
							if($makedeclinaison) {
								$Resulats = $Mysql->TabResSQL("SELECT * FROM ".$ps_."product_attribute LEFT JOIN ".$ps_."product_lang ON ".$ps_."product_attribute.id_product = ".$ps_."product_lang.id_product WHERE ".$ps_."product_attribute.id_product= $id_product AND id_lang = $id_lang");

								foreach ($Resulats as $Valeur) {
									$attributes_detail		= $article->f_attribute_name($Valeur['id_product_attribute'],$id_lang);
									//Jolvil - 08/07/2009 - remplacement ['description_short'] par ['name']
									$description_short		= f_convert_text($Valeur['name'])." ".$attributes_detail;
									//Avi - rajout du type de desciption a exporter
									$description			= f_convert_text($description_courte?$Valeur['description_short']:$Valeur['description']);

									//Fabien LAHAULLE - 26/05/2009 - remplacement par un espace des caractères identiques au séparateur de champs utilisé
									$description_short 		= str_replace($strSeparateur," ",$description_short); //Fabien LAHAULLE - 26/05/2009
									$description 			= str_replace($strSeparateur," ",$description); //Fabien LAHAULLE - 26/05/2009

									//Fabien LAHAULLE - 25/05/2009 - ajout d'une fonction d'unicité pour l'id du produit en déclinaison
									//$id_product_attribute 	= uniqid(); //$id_product-$Valeur['id_product_attribute'];
									//Fabien LAHAULLE - 27/05/2009 - changement de la fonction de génération d'identifiant unique pour avoir un ID numérique.
									$id_product_attribute = $rand->uRand(100000000,999999999);

									$id_image			 	= $Valeur['id_image'];
									if($id_image<>0) {
										$url_image_d		= $article->f_url_image($url_site_base_prestashop,$id_image);
									} else {
										$url_image_d		= $url_image_b;
									}

									$id_product			 	= $Valeur['id_product'];
									$reference_d			= $Valeur['reference'];
									$supplier_reference		= $Valeur['supplier_reference'];
									$location				= $Valeur['location'];
									$ean13_d				= $Valeur['ean13'];
									$wholesale_price		= $Valeur['wholesale_price'];
									$price_supp_decl		= $Valeur['price'];
									$price_barred_d			= f_prix_barre($reduction_percent,$reduction_price,$price,$taux_tva,$price_supp_decl);
									$price_ttc_d			= f_prix_ttc($price,$taux_tva,$price_supp_decl,$reduction_price,$reduction_percent);
									$ecotax					= $Valeur['ecotax'];
									$quantity				= $Valeur['quantity'];
									$weight_attribute		= $weight_base+$Valeur['weight'];

									$delivery_price=f_calc_fdp($usefreeshipping,$shipping_method,$delivery_price,$seuil_prix_fdp_offert,$seuil_poids_fdp_offert,$price_ttc_d,$weight_attribute);
									//Fabien LAHAULLE - 17/11/2009 - ajout moteur kelkoo
									if(!in_array($id_product,$productsExported))
									{
										$productsExported[]=$id_product;
										if ($onMutu)
											fwrite($hExportedArticles, "$id_product\n");
										
										if ($comparateur == 'kelkoo')
											include("catalogue/catalogue_declinaisons_kelkoo.php");
										else 
											include("catalogue/catalogue_declinaisons.php");
										
										$nbLinesCatalog++;
									}
									else continue;
								}
							}
							//include("catalogue/catalogue.php");
							// Si il n'y a pas de déclinaison on prend les valeurs par défaut
							if(empty($Resulats) || !$makedeclinaison) {
								$price_ttc_b = f_prix_ttc($price,$taux_tva,"",$reduction_price,$reduction_percent);
								$price_barred_b	= f_prix_barre($reduction_percent,$reduction_price,$price,$taux_tva,0);
								$delivery_price=f_calc_fdp($usefreeshipping,$shipping_method,$delivery_price,$seuil_prix_fdp_offert,$seuil_poids_fdp_offert,$price_ttc_b,$weight_base);
								//Fabien LAHAULLE - 17/11/2009 - ajout moteur kelkoo
								if(!in_array($id_product,$productsExported))	//Fabien LAHAULLE - 19/11/2009 - gestion doublons kelkoo - soluce 'ppplus'
								{
									$productsExported[]=$id_product;
									if ($onMutu)
										fwrite($hExportedArticles, "$id_product\n");
									
									if ($comparateur == 'kelkoo')
										include("catalogue/catalogue_kelkoo.php");
									elseif($comparateur == 'shoppydoo')
										include('catalogue/catalogue_shoppydoo.php');
									elseif($comparateur == 'shopzilla')
										include('catalogue/catalogue_shopzilla.php');
									elseif($comparateur == 'shopping')
										include('catalogue/catalogue_shopping.php');
									else
										include('catalogue/catalogue.php');

									$nbLinesCatalog++;
								}
								else continue;								
							}
						}
						catch (Erreur $e) {
							echo $e -> RetourneErreur('Impossible de sélectionner les déclinaisons');
						}
					}
				}
				catch (Erreur $e) {
					echo $e -> RetourneErreur('Impossible de sélectionner les informations produits');
				}
			}

			if ($onMutu) {
				// Sauvegarde de la catégorie traitée
				if (!$hIdxFullPath = fopen($sIdxFullPath, 'w')) {
					echo "<span class='alert'>Impossible d'ouvrir le fichier '$sIdxFullPath'</span><br>";
					return;
				}
				if (fwrite($hIdxFullPath, $i+1) === FALSE) {
				   echo "<span class='alert'>Impossible d'écrire dans le fichier '$sIdxFullPath'</span><br>";
				   return;
				}
				fclose($hIdxFullPath);

				// arrêt si nombre de catégories à traiter atteint
				if (($i+1) >= ($startedIndex+$onMutuStep)) {
					echo " traitée(s)<br><br>$nbLinesCatalog produits insérés dans le catalogue en ".(mktime()-$startTime)."s (limite du serveur : ".ini_get('max_execution_time')."s)";
					echo "<br><br><span class='alert'>Relancez le script pour continuer à créer le catalogue</span><br><br>";
					fclose($hExportedArticles);
					return;
				}
			}
		}
		catch (Erreur $e) {
			echo $e -> RetourneErreur('Impossible de sélectionner les id_product');
		}

	} // END FOR

	fclose($fichier);	//Fabien LAHAULLE - 19/11/2009 - fermeture du fichier d'export.

	if ($onMutu) {
		unlink($sIdxFullPath);
		fclose($hExportedArticles);
		unlink($sExportedArticles);
		echo '<br><br><b>Traitement terminé</b><br><br>';
	}
	
	url_file_download($module_name, $url_site_base_prestashop, $comparateur, $prefixe_nom_fichier.$comparateur, $extension_fichier, $destDir);

}



// Mise à jour, sauvegarde des paramètres
if(isset($_POST['valid_form_maj'])){

	$p_maj_lang 												= $_POST['lang'];
	isset($_POST['url-rewriting'])		? $p_maj_url_rewriting 	= $_POST['url-rewriting'] :	$p_maj_url_rewriting = '';
	$p_maj_disponibilite 										= $_POST['disponibilite'];
	$p_maj_carrier									 			= $_POST['carrier'];
	$p_maj_zone 												= $_POST['zone'];
	$p_maj_delai_livraison									 	= $_POST['delai-livraison'];
	$p_maj_garantie 											= $_POST['garantie'];
	$p_maj_etat 												= $_POST['etat'];
	//$p_maj_separateur		= $_POST['separateur'];
	//$p_maj_extension_fichier= $_POST['extension_fichier'];
	$p_maj_comparateur											= $_POST['comparateur'];
	$p_maj_nom_fichier											= $_POST['nom_fichier'];
	$makedeclinaison											= $_POST['makedeclinaison'];
	$description_courte											= $_POST['description_courte'];
	isset($_POST['usefreeshipping'])	?	$usefreeshipping 	= $_POST['usefreeshipping']		:	$usefreeshipping = '';
	isset($_POST['actif_only'])			?	$actif_only			= $_POST['actif_only']			:	$actif_only = '';
	isset($_POST['exportallproduct'])	?	$exportallproduct 	= $_POST['exportallproduct']	:	$exportallproduct = '';
	isset($_POST['displayCatalog'])		?	$displayCatalog 	= $_POST['displayCatalog']		:	$displayCatalog = '';
	isset($_POST['onMutu'])				?	$onMutu 			= $_POST['onMutu']				:	$onMutu = '';
	$onMutuStep													= $_POST['onMutuStep'];
	isset($_POST['onMutuOnceaDay'])		?	$onMutuOnceaDay 	= $_POST['onMutuOnceaDay']		:	$onMutuOnceaDay = '';
	isset($_POST['destDir'])			?	$destDir		 	= $_POST['destDir']				:	$destDir = '';
	isset($_POST['id_group'])		?	$id_group		= $_POST['id_group']		:	$id_group = 1;

	$tool_guides->f_update_value("lang_export",$p_maj_lang,$module_name);
	$tool_guides->f_update_value("url_rewriting",$p_maj_url_rewriting,$module_name);
	$tool_guides->f_update_value("disponibilite",$p_maj_disponibilite,$module_name);
	$tool_guides->f_update_value("livreur",$p_maj_carrier,$module_name);
	$tool_guides->f_update_value("frais",$p_maj_zone,$module_name);
	$tool_guides->f_update_value("delai_livraison",$p_maj_delai_livraison,$module_name);
	$tool_guides->f_update_value("garantie",$p_maj_garantie,$module_name);
	$tool_guides->f_update_value("etat",$p_maj_etat,$module_name);
	//$tool_guides->f_update_value("separateur",$p_maj_separateur,$module_name);
	//$tool_guides->f_update_value("extension_fichier",$p_maj_extension_fichier,$module_name);
	$tool_guides->f_update_value("nom_fichier",$p_maj_nom_fichier,$module_name);
	$tool_guides->f_update_value("nom_comparateur",$p_maj_comparateur,$module_name);
	$tool_guides->f_update_value("parameter_save","1",$module_name);
	$tool_guides->f_update_value("makedeclinaison",$makedeclinaison,$module_name);
	$tool_guides->f_update_value("description_courte",$description_courte,$module_name);
	$tool_guides->f_update_value("actif_only",$actif_only,$module_name);
	$tool_guides->f_update_value("usefreeshipping",$usefreeshipping,$module_name);
	$tool_guides->f_update_value("exportallproduct",$exportallproduct,$module_name);
	$tool_guides->f_update_value("displayCatalog",$displayCatalog,$module_name);
	$tool_guides->f_update_value("onMutu",$onMutu,$module_name);
	$tool_guides->f_update_value("onMutuStep",$onMutuStep,$module_name);
	$tool_guides->f_update_value("onMutuOnceaDay",$onMutuOnceaDay,$module_name);
	$tool_guides->f_update_value("destDir",$destDir,$module_name);
	$tool_guides->f_update_value("id_group",$id_group,$module_name);
	
	// On ne peut pas sauvegarder les catégories si elles n'ont pas été affichées
	if (!isset($_GET['auto'])) {
		// On vide les préférences des catégories
		$tool_guides->f_get_delete_category($module_name);

		// On insert les id des catégories a mémoriser
		if (isset($_POST['id_cat'])) {
			$categories			= $_POST['id_cat'];
			$total_catego		= count($categories);
			echo $total_catego;
			for($i=0;$i<$total_catego;$i++)
			{
				$id_catego 	=  $categories[$i];
				$tool_guides->f_get_insert_category($id_catego,$module_name);
			}
		}
	}

	header ("location: ".f_url_actuelle());
	exit;

}

$prefixe_nom_fichier	= $tool_guides->f_get_value('nom_fichier','leguide');
if (!isset($comparateur))
	$comparateur		= $tool_guides->f_get_value('nom_comparateur','leguide');
switch($comparateur) {
	case 'leguide':
		$extension_fichier = "txt";
		break;
	case 'shopmania' : 
		$extension_fichier = "csv";
		break;
	case 'tigoon' : 
		$extension_fichier = "csv";
		break;
	case 'kelkoo' : 
		$extension_fichier = "txt";
		break;
	case 'shoppydoo' : 
		$extension_fichier = "txt";
		break;
	case 'shopzilla' : 
		$extension_fichier = "txt";
		break;
	case 'shopping' : 
		$extension_fichier = "txt";
		break;
	default :
		echo "<span class='alert'><b>Comparateur non reconnu !</b></span>";
		return;
}

$path_parts				= pathinfo(__FILE__);
$catalogFilename		= $prefixe_nom_fichier.$comparateur.".".$extension_fichier;
$fullfilename			= $path_parts['dirname']."/exports/".$catalogFilename;
$destFilename			= $fullDestDir.$catalogFilename;
clearstatcache();
if (file_exists($fullfilename) && !file_exists($sIdxFullPath)) {
	echo "<br><br>Date du fichier <b>$catalogFilename</b> : ".date('j/n/Y - H:i:s', filemtime($fullfilename)).'<br><br>';
	if ($fullDestDir!='' && $fullDestDir!='') {
		if (!copy($fullfilename, "$fullDestDir/$catalogFilename"))
			echo "<span class='alert'>La copie du catalogue</span> de $fullfilename vers<br>$destFilename a échoué<br>";
		else
			echo "<span class=''>Copie du catalogue $catalogFilename vers <b>$destFilename</b> OK</span><br>";
	}
}

if (!isset($_SERVER['WINDIR'])) {
	$perms = substr(sprintf('%o', fileperms($path_parts['dirname']."/exports/")), -4);
	echo "<br>Permissions du répertoire exports : $perms<br>";

	if ($fullDestDir != '') {
		$perms = substr(sprintf('%o', fileperms($fullDestDir)), -4);
		echo "Permissions du répertoire $fullDestDir : $perms<br>";
	}
}


echo "</span>";

class UniqueRand{
	var $alreadyExists = array();

	function uRand($min = NULL, $max = NULL){
		$break='false';
		while($break=='false'){
			$rand=mt_rand($min,$max);

			if(array_search($rand,$this->alreadyExists)===false){
				$this->alreadyExists[]=$rand;
				$break='stop';
			}else{
				echo " $rand already!  ";
				print_r($this->alreadyExists);
			}
		}
		return $rand;
	}
}

?>
