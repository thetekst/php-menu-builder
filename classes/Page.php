<?php
/**
 * Class for localhost or testing
 *
 * init example:
 *
 * <?php require_once('classes/Page.php'); ?>
 * $excludeArr = array('menu.php', 'header.php', 'footer.php');
 * use thetekst\app\lib\localhost\Menu; // or use alias: use thetekst\app\lib\localhost\Menu as MyMenu;
 * $menu = new Menu('php', $excludeArr);
 */

namespace thetekst\app\lib\localhost;

class Menu {
	
	private $fileExtension = '';
	private $excludeArr = array();
	private $files = array();
	private $currentPage = '';
	private $html = '';

	public function __construct($fileExtension, $excludeArr) {
		$this->ext 			= $fileExtension;
		$this->excludeArr 	= $excludeArr;
		$this->currentPage 	= $this->getCurrentPage();

		$this->getPages();
		$this->rmArrEl();
		
		if($this->files) {
			$this->buildHTML();
		}
	}

	public function getPages() {
		$this->files = array();
		
		foreach (glob("*.".$this->ext) as $filename) {
			$this->files[] = $filename;
		}
		
	}

	public function buildHTML() {
		if (count($this->files) < 1) {
			return false;
		}
		
		$html = '<ul class="nav nav-pills nav-stacked">';

		foreach ($this->files as $file) {
			$html .= '<li class="'.$this->isActivePage($file).'"><a href="'.$file.'">'.$file.'</a></li>';
		}
		$this->html = $html.'</ul>';
	}

	public function getHTML() {
		return $this->html;
	}

	public function rmArrEl() {

		if(count($this->files) < 1) {
			return false;
		}

		if(count($this->excludeArr) < 1) {
			return false;
		}

		foreach ($this->excludeArr as $value) {
			$key = array_search($value, $this->files);

			if (!is_null($key) && !$key === false)	{
				unset($this->files[$key]);
			}
		}
	}

	/**
	 * get current page url type: site/index.php
	 * and return string: index.php
	 * @return string
	 */
	public function getCurrentPage() {
		return array_pop(explode('/', $_SERVER['PHP_SELF']));
	}

	public function isActivePage($el, $className = 'active') {
		if (!strcasecmp($el, $this->currentPage)) {
			return $className;
		}
	}
}