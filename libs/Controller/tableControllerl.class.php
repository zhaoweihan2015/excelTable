<?php
//connect文件引入
require_once ("../Model/tableModel.class.php");
require_once ("../View/tableView.class.php");
$model = new TableModel();
$view = new tableView(); 
switch ($_POST["mode"]) {
	case 'Check' :
		$data = $model -> CheckSQL();
		$view->showJson($data);
		break;
	case 'Change' :
		$data = $model -> ChangeSQL();
		$view->printJson($data);
		break;
	case 'Delet' :
		$data = $model -> DeletSQL();
		$view->printJson($data);
		break;
	default :
		$view->printJson("error");
		break;
}
?>