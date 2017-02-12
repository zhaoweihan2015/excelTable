<?php
  /**
   * tableView
   */
  class tableView{
  	function showJson($data) {
  		echo json_encode($data);
  	}
	function printJson($data) {
  		print_r($data);
  	}
  }
  
?>