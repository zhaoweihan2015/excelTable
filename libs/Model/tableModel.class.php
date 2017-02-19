<?php
/**
 * tableModel
 */
class TableModel {
	//数据查询
	function CheckSQL() {
		//数据库引入
		require_once ("../config/connect.php");
		//post传值第一组
		$checkName1 = $_POST['checkName1'];
		$textName1 = $_POST['textName1'];
		//变量声明并初始化
		$data = array();
		//从主表查询课程
		$sql1 = "SELECT * FROM `schedule` WHERE `" . $checkName1 . "` = '" . $textName1 . "'";
		//从修改表查询课程
		$sql2 = "SELECT * FROM `changeform` WHERE `" . $checkName1 . "` = '" . $textName1 . "' AND `Delet` = '1'";
		//双数据（eg.classroomcheck）补全sql语句
		if (isset($_POST['checkName2']) && !empty($_POST['checkName2'])) {
			//post传值第二组
			$checkName2 = $_POST['checkName2'];
			$textName2 = $_POST['textName2'];
			$sql1 .= " AND `" . $checkName2 . "` = '" . $textName2 . "'";
			$sql2 .= " AND `" . $checkName2 . "` = '" . $textName2 . "'";
		}
		//进行sql操作
		$Model = new TableModel();
		$data = $Model -> PDO_SQL($pdo, $sql1, $data);
		$data = $Model -> PDO_SQL($pdo, $sql2, $data);
		return $data;
	}
    //sql语句执行和查询结果拼接
	function PDO_SQL($pdo, $query, $data) {
		$stms = $pdo -> prepare($query);
		$res = $stms -> execute();
		while ($row = $stms -> fetch(PDO::FETCH_ASSOC)) {
			array_push($data, $row);
		}
		return $data;
	}

	function ChangeSQL() {
		//数据库引入
		require_once ("../config/connect.php");
		//post传值和变量声明
		$newWeek = $_POST['newWeek'];
		$newDate = $_POST['newDate'];
		$newLine = $_POST['newLine'];
		$mid = $_POST['mid'];
		$oldWeek = $_POST['oldWeek'];
		//sql语句拼接
		$sql = "UPDATE `schedule` SET `week" . $oldWeek . "`='2' WHERE `mid` = '" . $mid . "';";
		if ($pdo -> exec($sql)) {
			$sql = "INSERT INTO `changeform`(`mid`,`cid`, `did`, `head`, `ClassName`, `ClassFloat`, `Classroom`, `ClassDate`, `ClassLine`, `TeacherName`, `Class`)
( SELECT `mid`, `cid`, `did`, `head`, `ClassName`, `ClassFloat`, `Classroom`, `ClassDate`, `ClassLine`, `TeacherName`, `Class` FROM `schedule` WHERE `mid` ='" . $mid . "');";
			if ($pdo -> exec($sql)) {
				$sql = "UPDATE `changeform` SET `week" . $newWeek . "`='1',`ClassDate` = '" . $newDate . "', `ClassLine` = '" . $newLine . "', `oldWeek` = '" . $oldWeek . "'  WHERE `mid` = '" . $mid . "' And `oldWeek` = 0;";
				if ($pdo -> exec($sql)) {
					return TRUE;
				} else {
					return FALSE;
					//error返回
				};
			} else {
				return FALSE;
				//error返回
			};
		} else {
			return FALSE;
			//error返回
		}
	}

	function DeletSQL() {
		//数据库引入
		require_once ("../config/connect.php");
		$mid = $_POST['mid'];
		$oldWeek = $_POST['oldWeek'];
			//修改主表格week值
			$sql = "UPDATE `schedule` SET `week" . $oldWeek . "`='1' WHERE `mid` = '" . $mid . "';";
			if ($pdo -> exec($sql)) {
				//修改修改表格delet值
				$sql = "UPDATE `changeform` SET `Delet`='0'WHERE `mid` = '" . $mid . "' AND `oldWeek` =".$oldWeek.";";
				if ($pdo -> exec($sql)) {
					return TRUE;
				} else {
					return FALSE;
					//error back
				}
			} else {
				return $data;
				//error back
			}
	}

}
?>