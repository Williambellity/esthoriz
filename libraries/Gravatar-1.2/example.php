<?php

	include_once('TalkPHP_Gravatar.php');
	
	$pAvatar = new TalkPHP_Gravatar();
	$pAvatar->setEmail('charly.poly@live.fr')->setSize(180)->setRatingAsPG()->setDefaultImageAsIdentIcon();

?>
<img src="<?php echo $pAvatar->getAvatar(); ?>" />
<?php
// l'avatar lié à l'email est affiché grâce au service gravatar =D
?>