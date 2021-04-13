<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @auteur	NkDeuS
 * @version	$Id: index.php 0001 05-04-2010 xpLosIve. $
 *
 * Copyright 2010 NkDeuS.Com
 */

class AdminStyle {
	private $fields;
	
	private $sortBy;
	private $sens;
	
	private $sortByDef;
	private $sensDef;
	
	public function __construct(array $fields, $sortByDef = null, $sens = 'ASC') {
		if (empty($fields)) {
			throw new Exception("AdminStyle::__construct() : aucun champ précisé");
		}
		$this->fields = $fields;
		$this->defineDefault(empty($sortByDef) ? $fields[0] : $sortByDef, $sens);
	}
	
	public function defineDefault($sortBy, $sens) {
		if (in_array($sortBy, $this->fields)) {
			$this->sortByDef = $sortBy;
		}
		$this->sensDef = $this->clearSens($sens);
	}
	
	private function clearSens($sens) {
		return (strtoupper($sens) == 'DESC') ? 'DESC' : 'ASC';
	}
	
	public function getSorting($sortBy, $sens) {
		if (in_array($sortBy, $this->fields)) {
			$this->sortBy = $sortBy;
		} else {
			$this->sortBy = $this->sortByDef;
		}
		if (!empty($sens)) {
			$this->sens = $this->clearSens($sens);
		} else {
			$this->sens = $this->sensDef;
		}
		return array($this->sortBy, $this->sens);
	}
	
	public function getTplVars() {
		$vars = array();
		foreach ($this->fields as $field) {
			if ($field == $this->sortBy) {
				$vars[$field.'_class'] = ($this->sens == 'ASC') ? 'sortAsc' : 'sortDesc';
				$vars[$field.'_sort'] = ($this->sens == 'ASC') ? 'desc' : 'asc';
			} else {
				$vars[$field.'_class'] = '';
				$vars[$field.'_sort'] = 'asc';
			}
		}
		return $vars;
	}
}

?>