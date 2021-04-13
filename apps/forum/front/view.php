<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class ForumView extends WView {
	private $model;
	
	public function __construct(ForumModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function exposants() {
		$this->assign('pageTitle', 'Forum Est-Horizon | Liste des exposants');
		$this->assign('css', '/apps/forum/front/css/exposants.css');

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
	}
}

?>