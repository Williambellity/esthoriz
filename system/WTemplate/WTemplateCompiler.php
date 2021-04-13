<?php defined('IN_WITY') or die('Access denied');
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author    NkDeuS (fofif)
 * @version   $Id: WTemplateCompiler.php 0003 31-07-2011 fofif $
 * @desc      Compilateur du moteur de template
 * @copyright 2010 NkDeuS.Com
 */

class WTemplateCompiler {
	/**
	 * Compilation d'un élément bien précis
	 */
	public function compileTplCode($matches) {
		$tpl_code = $matches[1];
		
		// Variables ignorées (ex: {\\...} = {...})
		if ($tpl_code[0] == '\\' && $tpl_code[1] == '\\') {
			return '{'.substr($tpl_code, 2).'}';
		}
		
		// Affichage d'une variable
		if ($tpl_code[0] == '$') {
			$output = $this->compile_var("name=".$tpl_code);
		}
		// Fermeture d'une balise
		else if ($tpl_code[0] == '/') {
			// Récupération du nom de balise : {/"name" ...}
			preg_match('#^/([a-zA-Z0-9_]+)#', $tpl_code, $matches);
			
			$name = $matches[1];
			$args = substr($tpl_code, strlen($name));
			
			$handler = 'compile_'.$name.'_close';
			if (method_exists('WTemplateCompiler', $handler)) {
				// Appel de la fonction
				$output = $this->$handler($args);
			} else {
				$output = '';
			}
		} else {
			// Récupération du nom de balise : {"name" ...}
			preg_match('#^([a-zA-Z0-9_]+)#', $tpl_code, $matches);
			
			$name = $matches[0];
			$args = substr($tpl_code, strlen($name));
			
			$handler = 'compile_'.$name;
			if (method_exists('WTemplateCompiler', $handler)) {
				// Appel de la fonction
				$output = $this->$handler($args);
			} else {
				$output = '';
			}
		}
		
		return $output;
	}
	
	/**
	 * Fonction permettant de parser une chaîne fournie en argument, dans les accolades
	 */
	public function getAttributes($string) {
		$string_arr = preg_split('#\s+#', trim($string));
		
		$args = array();
		foreach ($string_arr as $str) {
			list($name, $value) = explode('=', $str);
			$args[$name] = $value;
		}
		
		return $args;
	}
	
	public static function getVar($string) {
		// On supprime le '$' du début
		if ($string[0] == '$') {
			$string = substr($string, 1);
		}
		
		$var_arrays = explode('.', $string);
		
		$result = '$this->tpl_vars[\''.array_shift($var_arrays).'\']';
		if (sizeof($var_arrays) > 0) {
			foreach ($var_arrays as $ar) {
				if (strpos($ar, '$') !== false) {
					$result .= '['.self::parseVars($ar).']';
				} else {
					$result .= '[\''.$ar.'\']';
				}
			}
		}
		
		return $result;
	}
	
	public static function parseVars($string) {
		// Traitement des variables de la chaîne
		$string = preg_replace_callback(
			'#\{?(\$[a-zA-Z0-9\.\-_]+)\}?#',
			create_function(
				'$matches',
				'return WTemplateCompiler::getVar($matches[1]);'
			),
			trim($string)
		);
		
		return $string;
	}
	
	public function compile_var($args) {
		$attr = $this->getAttributes($args);
		
		if (isset($attr['name'])) {
			// Formatage $var.sub_array(.sub2...)|functions
			$var_structure = explode('|', $attr['name']);
			
			$root = $this->getVar($var_structure[0]);
			
			// Couche fonctions
			if (isset($var_structure[1])) {
				$functions = preg_split('#,\s*#', $var_structure[1]);
				foreach ($functions as $f) {
					$root = $f.'('.$root.')';
				}
			}
			
			return '<?php echo '.$root.'; ?>';
		} else {
			return '';
		}
	}
	
	/**
	 * Fonction traitant l'inclusion de fichier .tpl via le moteur
	 * 
	 * @param string $file Le fichier à inclure
	 */
	public function compile_include($args) {
		$attr = $this->getAttributes($args);
		
		if (isset($attr['file'])) {
			$file = $this->parseVars(str_replace(array('{', '}'), array('".{', '}."'), $attr['file']));
			
			return '<?php $this->display("'.$file.'"); ?>';
		} else {
			return '';
		}
	}
	
	public function compile_if($args) {
		$cond = trim($args);
		
		// Traitement des variables de la condition
		$cond = $this->parseVars($cond);
		
		return '<?php if ('.$cond.'): ?>';
	}
	
	public function compile_else($args) {
		return '<?php else: ?>';
	}
	
	public function compile_elseif($args) {
		return str_replace('<?php if', '<?php elseif', $this->compile_if($args));
	}
	
	public function compile_if_close($args) {
		return '<?php endif; ?>';
	}
	
	public function compile_block($args) {
		$attr = $this->getAttributes($args);
		
		if (isset($attr['name'])) {
			$name = trim($attr['name'], '"');
			return "<?php \$this->tpl_vars['count'] = 0;"
				."if (isset(\$this->tpl_vars['".$name."_block']) && is_array(\$this->tpl_vars['".$name."_block'])):\n"
				. "\tforeach (\$this->tpl_vars['".$name."_block'] as \$this->tpl_vars['".$name."']): ?>";
		} else {
			return '';
		}
	}
	
	public function compile_block_close($args) {
		return '<?php $this->tpl_vars[\'count\']++; endforeach; endif; ?>';
	}
	
	/* Syntaxe de foreach :
	 * {foreach item="{$array}" as="{$var}"}
	 */
	public function compile_foreach($args) {
		$args = $this->parseVars($args);
		$attr = $this->getAttributes($args);
		
		if (isset($attr['item']) && isset($attr['as'])) {
			return '<?php '.$attr['as'].'[\'count\'] = -1;'."\n"
				.'foreach('.$attr['item'].' as '.$attr['as'].'[\'key\'] => '.$attr['as'].'[\'value\']):'."\n"
				.$attr['as'].'[\'count\']++; ?>';
		} else {
			return '';
		}
	}
	
	public function compile_foreach_close($args) {
		return '<?php endforeach; ?>';
	}
	
	public function compile_while($args) {
		
	}
	
	public function compile_for($args) {
		
	}
	
	/* Ancien compileur
	public function compiler() {
		// Les variables
		$code = preg_replace('#\$([a-z0-9_]+)\.([a-z0-9]+)#i', "$$1['$2']", $code);
		$code = preg_replace('#\$([a-z0-9_]+)#i', "\$this->vars['$1']", $code);
		
		// Constantes de langue
		$code = preg_replace('#\{lang:"([a-zA-Z0-9.]+)"}#i', "<?php echo wtLang::_('$1'); ?>", $code);
		
		// Helpers
		$code = preg_replace('#\{helper:"([a-zA-Z0-9.]+)"-"([a-zA-Z0-9.-]+)"}#i', "<?php echo wtHelpers::display('$1', $2); ?>", $code);
		
		// Affichage d'une expression
		$code = preg_replace('#{(.+)}#U', "<?php echo $1; ?>", $code);
		
		// Affichage d'une variable d'environnement
		$code = preg_replace('#<env var="([a-z0-9_]+)" ?/>#', "<?php echo \$this->env('$1'); ?>", $code);
		
		// Les blocks
		$code = preg_replace
		(
			'#<block name="([a-z0-9_]+)">#', 
			"<?php if (isset(\$this->vars['$1_block']) && is_array(\$this->vars['$1_block'])):\n"
			. "\tforeach (\$this->vars['$1_block'] as \$this->vars['$1']): ?>", 
			$code
		);
		
		// Les boucles foreach
		$code = preg_replace('#<foreach item="(.+)" as="(.+)">#', "<?php foreach ($1 as $2): ?>", $code);
		
		// Les boucles while
		$code = preg_replace('#<while cond="(.+)">#', "<?php while ($1): ?>", $code);
		
		// Les boucles for
		$code = preg_replace('#<for init="(.+)" cond="(.+)" do="(.+)">#', "<?php for ($1; $2; $3): ?>", $code);
		
		// Conditions
		$code = preg_replace('#<(else)?if cond="(.+)"\s?/?>#U', "<?php $1if ($2): ?>", $code);
		$code = str_replace(array('<else>', '<else />'), "<?php else: ?>", $code);
		
		// Création de variable
		$code = preg_replace('#<(var|assign) name="([a-zA-Z0-9_]+)" value=(.+) />#sU', "<?php \$this->vars['$2'] = $3; ?>", $code);
		
		// Les inclusions
		$code = preg_replace('#<include file="(.+)" ?/>#', "<?php \$this->include_tpl($1); ?>", $code);
		
		// Remplacement divers
		$code = str_replace
		(
			array(
				'</block>',
				'</foreach>',
				'</while>',
				'</for>',
				'</if>',
				'$\\' // Echapement des variables (ex: {\VAR} s'affichera {VAR})
			), 
			array(
				"<?php endforeach; endif; ?>",
				"<?php endforeach; ?>",
				"<?php endwhile; ?>",
				"<?php endfor; ?>",
				"<?php endif; ?>",
				'$'
			), 
			$code
		);
	}
	*/
}

?>