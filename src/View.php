<?php namespace Shorty;

class View {

	public function render($__template, $__data)
	{
		extract($__data);

		include TEMPLATE_ROOT.'/'.$__template.'.tmpl.php';
	}

} 