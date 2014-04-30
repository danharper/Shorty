<?php namespace Yolo;

class View {

	public function __construct($templatePath)
	{
		$this->templatePath = $templatePath.'/';
	}

	public function render($__template, $__data)
	{
		ob_start();

		extract($__data);

		include $this->templatePath.$__template.'.tmpl.php';

		return ob_get_clean();
	}

} 