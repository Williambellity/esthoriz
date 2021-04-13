<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/news/front/model.php 0002 31-07-2011 Fofif $
 */

class NewsModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * R�cup�re la liste compl�te des pages
	 */
	public function getNewsList($from, $number, $order = 'date', $asc = false) {
		$prep = $this->db->prepare('
			SELECT id, url, title, author, content, DATE_FORMAT(date, "%d/%m/%Y %H:%i") AS date, views, image
			FROM news
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :3, :number
		');
		$prep->bindParam(':start', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll();
	}
	
	/**
	 * Filtre les news selon une cat�gorie
	 */
	public function getNewsByCat(array $cats, $from, $number, $order = 'date', $asc = false) {
		if (empty($cats)) {
			return;
		}
		
		if ($order == 'date') {
			$order = 'news.date';
		}
		
		// Elaboration de la cha�ne conditionnelle
		$s = '';
		foreach ($cats as $c) {
			$s .= 'shortname = "'.$c.'" OR ';
		}
		$s = substr($s, 0, -4);
		
		$prep = $this->db->prepare('
			SELECT DISTINCT(id), url, title, author, content, DATE_FORMAT(date, "%d/%m/%Y %H:%i") AS date, DATE_FORMAT(modified, "%d/%m/%Y %H:%i") AS modified, views, image
			FROM news
			LEFT JOIN news_cats_relations
			ON id = news_id
			LEFT JOIN news_cats
			ON cat_id = cid
			WHERE '.$s.'
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :start, :number
		');
		$prep->bindParam(':start', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll();
		return array();
	}
	
	/**
	 * R�cup�ration des donn�es d'une page
	 */
	public function loadNews($id) {
		$prep = $this->db->prepare('
			SELECT url, title, author, content, keywords, creation_time, edit_time
			FROM news
			WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch();
	}
	
	public function validId($id) {
		$prep = $this->db->prepare('
			SELECT * FROM news WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() == 1;
	}
	
	public function getCid($cname) {
		$prep = $this->db->prepare('
			SELECT cid FROM news_cats WHERE name = :name
		');
		$prep->bindParam(':name', $cname);
		$prep->execute();
		return $prep->fetchColumn();
	}
}

?>