<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class cvsearchView extends WView {
	private $model;
	
	public function __construct(cvsearchModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function cvsearch() {
 		$this->assign('pageTitle', 'Forum Est-Horizon | CV');
		/* $this->assign('css', '/apps/forum/front/css/exposants.css'); */
		$this->assign('css', '/apps/test20/front/css/profil.css');		
/*
        $expos = $this->model->getExposants(2012);
        foreach($expos as $expo) {
			$logo_href = 'upload/firms_logo/thumb_'.$expo['id'].'.png';
			if (file_exists($logo_href)) {
				$expo['logo'] = '/'.$logo_href;
			} else {
				$expo['logo'] = '';
			}
            $this->tpl->assignBlockVars('expo', $expo);
        }
    */
        // $expos = $this->model->getExposants();
        // foreach($expos as $expo) {
			// $logo_href = "apps/test20/front/templates/images/'.$expo['id'].'_thumb.jpg'";
			// if (file_exists($logo_href)) {
				// $expo['logo'] = '/'.$logo_href;
			
                // $this->tpl->assignBlockVars('expo', $expo);
            // }
        // }
	}
}
?>