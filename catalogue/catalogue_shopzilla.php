<?php
/*
Fabien LAHAULLE - 17/11/2009
ajout de l'export vers le comparateur Kelkoo
*/

if($manufacturer_name==""){
	$marque = $supplier_name;
}else{
	$marque = $manufacturer_name;
}

/* TODO : transco des disponibilités autres que 0 (en stock) */
/* CODES ATTENDUS PAR KELKOO
001 ("En stock")
002 ("Stock en cours de renouvellement")
003 ("Voir site")
004 ("En pré-commande")
005 ("Disponible sur commande")
*/
/*switch ($disponibilite) {
	case 0 : 
		$disponibilite = '001';
		break;
	case 1 : 
		break;
}
*/

$desc = mb_substr($desc_produit, 0, 1000, 'UTF-8');
$desc = $desc . "...";	
$condition = 'NEUF';
$enchere = '';
$offre_speciale = '';
$categorie = $supplier_reference;
$disponibilité ='En Stock' ;
$poids = '';
$mil = '000';

if ($displayCatalog) {
	echo "
	<tr>
		<td>$categorie</td>
		<td>$marque</td>
		<td>$nom_produit </td>
		<td>$desc</td>
		<td>$url_article</td>
		<td>$url_image_b</td>
		<td>$mil$id_product</td>
		<td>$disponibilite</td>
		<td>$condition</td>
		<td>$poids</td>
		<td>$delivery_price</td>
		<td>$enchere</td>
		<td>$offre_speciale</td>
		<td>$ean13</td>
		<td>$price_ttc_b</td>
	</tr>";
}

$sep = $separateur;

fwrite($fichier,
	$categorie . $sep .
	$marque . $sep . 
	$nom_produit . $sep . 
	$desc . $sep . 
	$url_article . $sep . 
	$url_image_b . $sep . 
	$id_product . $sep . 
	$disponibilite . $sep . 
	$condition . $sep .
	$poids . $sep .
	$delivery_price . $sep .
	$enchere. $sep .
	$offre_speciale . $sep .
	$ean13. $sep .
	$price_ttc_b .
	"\n");
?>