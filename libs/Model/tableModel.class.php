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
		$sql = "";
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
		$row = $mysql -> query($sql1);
		while ($res = $row -> fetch_assoc()) {
			array_push($data, $res);
		}
		$row = $mysql -> query($sql2);
		while ($res = $row -> fetch_assoc()) {
			array_push($data, $res);
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
		if ($mysql -> query($sql)) {
			$sql = "INSERT INTO `changeform`(`mid`,`cid`, `did`, `head`, `ClassName`, `ClassFloat`, `Classroom`, `ClassDate`, `ClassLine`, `TeacherName`, `Class`)
( SELECT `mid`, `cid`, `did`, `head`, `ClassName`, `ClassFloat`, `Classroom`, `ClassDate`, `ClassLine`, `TeacherName`, `Class` FROM `schedule` WHERE `mid` ='" . $mid . "');";
			if ($mysql -> query($sql)) {
				$sql = "UPDATE `changeform` SET `week" . $newWeek . "`='1',`ClassDate` = '" . $newDate . "', `ClassLine` = '" . $newLine . "', `oldWeek` = '" . $oldWeek . "'  WHERE `mid` = '" . $mid . "';";
				if ($mysql -> query($sql)) {
					return $mid;
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
		//获取oldweek
		$sql = 'SELECT `oldWeek` FROM `changeform` WHERE `mid` = "' . $mid . '"';
		if ($row = $mysql -> query($sql)) {
			$data = $row -> fetch_assoc();
			$oldWeek = $data['oldWeek'];
			//修改主表格week值
			$sql = "UPDATE `schedule` SET `week" . $oldWeek . "`='1' WHERE `mid` = '" . $mid . "';";
			if ($mysql -> query($sql)) {
				//修改修改表格delet值
				$sql = "UPDATE `changeform` SET `Delet`='0'WHERE `mid` = '" . $mid . "';";
				if($mysql -> query($sql)){
					return TRUE; 
				}else{
					return FALSE;//error back
				}
			}else{
				return FALSE;//error back
			}
		}else{
			return FALSE;//error back
		}

	}

}
?>