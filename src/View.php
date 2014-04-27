<?php namespace Shorty;

class View {

	public function render($__template, $__data)
	{
		ob_start();

		extract($__data);

		include TEMPLATE_ROOT.'/'.$__template.'.tmpl.php';

		return ob_get_clean();
	}

} 