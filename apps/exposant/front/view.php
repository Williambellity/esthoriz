<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class evenementView extends WView {
	private $model;
	
	public function __construct(evenementModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function evenement() {
 		$this->assign('pageTitle', 'Forum Est-Horizon | Exposants');
		$this->assign('css', '/apps/evenement/front/css/css.css');		

        $expos = $this->model->getExposants();
        foreach($expos as $expo) {
			$logo_href = 'upload/firms_logo/new/2019/thumb_'.$expo['id'].'.png';
			if (file_exists($logo_href)) {
				$expo['logo'] = '/'.$logo_href;
			
                $this->tpl->assignBlockVars('expo', $expo);
            }
        }
	}
}
?>