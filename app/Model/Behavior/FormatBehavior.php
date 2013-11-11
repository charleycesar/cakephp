<?php
/**
 *
 * Format behavior
 * @Charley Oliveira 29/10/2013 
 * Formatação de campos datas e valores com a propria validade de cada Model
 *
 */

class FormatBehavior extends ModelBehavior {

	
	/**
	* Callback
	*
	*
    * beforeFind
    * afterFind
    * beforeValidate
    * afterValidate
    * beforeSave
    * afterSave
    * beforeDelete
    * afterDelete
	*
	*/

	//Formato Brasileiro
    var $dateFormat = 'dd/mm/yyyy';
    //Formato Banco de dados
    var $databaseFormat = 'Y-m-d';
    //delimitador brasileiro
    var $delimiterDateFormat = '/';
    //delimitador banco de dados
    var $delimiterDatabaseFormat = '-';
    
    //se for empty o behavior
	function setup(&$model) {
		$this->model = $model;
	}

	
	/**
	*@author Charley Oliveira
	*@param Model
	*@return Faz a formatação dos campos dinamicamente
	*/
	function beforeValidate($Model){
	/**
	*Com a propria validate eu formato todos os campos de acordo com o banco de dados
	*
	*
	*/	
		$campos = $Model->validate;
		foreach ($campos as $campo => $value) {
			$nomeInput = $campo;
			if(isset($Model->data[$Model->alias][$nomeInput])){
				foreach($value as $regra){
					//Se a regra for array
					if(is_array($regra['rule'])){
						if(!isset($i)){$i = 0;}
						//return tipo de validação
						switch ($regra['rule'][0]) {
							case 'date':
								$Model->data[$Model->alias][$nomeInput] = date($this->databaseFormat, strtotime(str_replace($this->delimiterDateFormat,$this->delimiterDatabaseFormat,$Model->data[$Model->alias][$nomeInput])));
							break;
							case 'decimal':
								$Model->data[$Model->alias][$nomeInput] = str_replace('R$ ', '', str_replace(',','.',str_replace('.','',$Model->data[$Model->alias][$nomeInput])));
							break;
						}
						$i++; 
					}
					//Se a regra nao for array
					else{
						switch($regra['rule']){
							case 'notEmpty':
								$Model->data[$Model->alias][$nomeInput] = trim($Model->data[$Model->alias][$nomeInput]);
							break;
							case 'numeric':                    
								$Model->data[$Model->alias][$nomeInput] = str_replace('/','',str_replace('.','',str_replace('-','',$Model->data[$Model->alias][$nomeInput])));

							break;
						}
					}
				}
			}
		}
		 debug($Model->data);
		return true;
   	}

}
