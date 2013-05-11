<?php
	//////////////////////////////////////////////////////////
	// Guide Shopping Pro pour Prestashop 1.x		//
	// Exports de catalogue vers les guides shopping	//
	// (c)2009 - www.creation-shop.ch			//
	//////////////////////////////////////////////////////////

class leguide extends Module
{
	function __construct()
	{
		$this->name = 'leguide';
		$this->tab = 'Tools';
		$this->version = '1.58';
		
		parent::__construct();
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Leguide.com');
		$this->description = $this->l('Exportez vos produits vers Leguide.com, Shopmania, Tigoon, Kelkoo, Shoppydoo, Shopzilla, Shopping !');
	}

	function install()
	{
		if(parent::install() == false)
			return false;
		//require_once("import_parameter.php");
		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		return $output.$this->displayForm();		
	}

	public function getName()
	{
		$output = $this->name;
		return $output;		
	}

	public function displayForm()
	{
		$output = '';
		ob_start();
		include('guide-script.php');
		$output = ob_get_clean();
		return $output;
		ob_end_clean();
	}

	public function uninstall(){
		/*
		$prefix = _DB_PREFIX_ ;
		$sql = "DROP TABLE `".$prefix."guide_parameter`";
		$result = mysql_query($sql);
		*/
		$res = Db::getInstance()->execute('
			DROP TABLE `'._DB_PREFIX_.'guide_parameter`');
			
		Configuration::deleteByName('guide');
		return parent::uninstall();
	}

	function cronTask($catalogue='')
    {
		$_GET['auto']=$catalogue;
		include('guide-script.php');
	}    
}

?>
