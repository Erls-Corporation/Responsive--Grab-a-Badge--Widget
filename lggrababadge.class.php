<?php

class widget_lggrababadge extends tfs_widget_base_class
{

	function output($params=array()) {
		include(dirname(__FILE__).'/db.php');
		include(dirname(__FILE__).'/view.php');
	}
	
}