<?php

	include_once('TalkPHP_Gravatar.php');
	
	$pAvatar = new TalkPHP_Gravatar();
	$pAvatar->setEmail('charly.poly@live.fr')->setSize(180)->setRatingAsPG()->setDefaultImageAsIdentIcon();

?>
<img src="<?php echo $pAvatar->getAvatar(); ?>" />
<?php
// l'avatar li� � l'email est affich� gr�ce au service gravatar =D
?>