<?php
/**
* Gestion des erreurs avec les exceptions
*/ 
class Erreur  extends Exception {
    
    public function __construct($Msg) {
        parent :: __construct($Msg);
    }
    
    public function RetourneErreur($req) {
        $msg  = '<div><strong>' . $this->getMessage() . '</strong>';
        $msg .= ' Ligne : ' . $this->getLine() . '<br>'.
        $msg .= ' <br>Fichier : ' . $this->getFile() . '<br>'.
		$req.'</div>';
        return $msg;
    }
}

class My_sql
{
	
  	private
      $Serveur     = '',
      $Bdd         = '',
      $Identifiant = '',
      $Mdp         = '',
      $Lien        = 'monlien',   
      $Debogue     = true,   
      $NbRequetes  = 0; 

 
  	public
      function __construct($Serveur = 'localhost', $Bdd = 'base', $Identifiant = 'root', $Mdp = '') 
      {
         $this->Serveur     = $Serveur;
         $this->Bdd         = $Bdd;
         $this->Identifiant = $Identifiant;
         $this->Mdp         = $Mdp;

         $this->Lien=mysql_connect($this->Serveur, $this->Identifiant, $this->Mdp);
         if (!$this->Lien && $this->Debogue) throw new Erreur ('Erreur de connexion au serveur MySql');            
         $Base = mysql_select_db($this->Bdd,$this->Lien);
         if (!$Base && $this->Debogue) throw new Erreur ('Erreur de connexion à la base de donnees');
      }

	
	//Retourne le nombre de requêtes SQL effectué par l'objet
   	public
      function RetourneNbRequetes() 
      {
         return $this->NbRequetes;
      }


/**
* Envoie une requête SQL et récupère le résultât dans un tableau pré formaté
*
* $Requete = Requête SQL
*/ 
   public
      function TabResSQL($Requete)
      {
         $i = 0;
   
         $Ressource = mysql_query($Requete,$this->Lien);
         
           $TabResultat=array();

           if (!$Ressource and $this->Debogue) throw new Erreur ('Erreur de requete SQL: <br>'.$Requete);
         while ($Ligne = mysql_fetch_assoc($Ressource))
         {
            foreach ($Ligne as $clef => $valeur) $TabResultat[$i][$clef] = $valeur;
            $i++;
         }

         mysql_free_result($Ressource);
         
         $this->NbRequetes++;
         
         return $TabResultat;
      }
      
/**
* Retourne le dernier identifiant généré par un champ de type AUTO_INCREMENT
*
*/ 
   public
      function DernierId()
      {   
         return mysql_insert_id($this->Lien);
      }
      
      
/**
* Envoie une requête SQL et retourne le nombre de table affecté
*
* $Requete = Requête SQL
*/ 
   public
      function ExecuteSQL($Requete)
      {
		$Ressource = mysql_query($Requete,$this->Lien);

		if (!$Ressource and $this->Debogue) throw new Erreur ('Erreur de requete SQL ! <br>'.$Requete);

		$this->NbRequetes++;
		$NbAffectee = mysql_affected_rows();

		return $NbAffectee;         
      }

}
?>
