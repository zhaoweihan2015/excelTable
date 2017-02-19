//全局变量声明
var MethodName = null; //使用方法名
//测试用代码
function ceshi(data = "TruE") {
	alert(data);
}
//根据内容进行AJAX异步请求
function checkPost(name) {
	MethodName = name;
	//消失彈出窗口
	showhideDiv('#showBox','hide');
	switch(name) {
		//Teacher Name Check
		case "Teacher":
			$TeacherName = $("#TeacherName").val();
			if($TeacherName == "") {
				alert("Empty!!")
				break;
			}
			PostCheck("TeacherName", $TeacherName);
			break;
			//Class Check
		case "Class":
			$ClassName = $("#ClassName").val();
			if($ClassName == "") {
				alert("Empty!!")
				break;
			}
			PostCheck("Class", $ClassName);
			break;
			// ClassRoom Check
		case "Room":
			$ClassFloat = $("#RoomName1").val();
			$ClassRoom = $("#RoomName2").val();
			if($ClassFloat == "") {
				alert("Empty!!")
				break;
			}
			PostCheck("ClassFloat", $ClassFloat, "Classroom", $ClassRoom);
			break;
			//Error Back
		default:
			alert("U have a wrong way.Please check!!")
			break;
	}
}
//AJAX异步请求 课表查询
function PostCheck(checkName1, textName1, checkName2, textName2) {
	$.ajax({
		type: "post",
		url: "./libs/Controller/tableControllerl.class.php",
		dataType: "json",
		data: {
			'checkName1': checkName1,
			'textName1': textName1,
			'checkName2': checkName2,
			'textName2': textName2,
			'mode': 'Check'
		},
		success: function(data) {
			//查询无结果
			if(data.length <= 0) {
				alert("查无此人");
				return false;
			}
			//变量声明并初始化
			$num = $("#weekselect").val() + '';
			//Function
			AjaxSuccess(data, $num)
				//weekselect事件
			$("#weekselect").on("click", function() {
				$num = $("#weekselect").val() + '';
				AjaxSuccess(data, $num)
			})
		}
	});
}
//Ajax Success 后事件集合
function AjaxSuccess(data, num) {
	//填充table
	PrintTable(data, num);
	//box内显示
	show2Box();
}
//改课事件
function ChangeClass() {
	//声明变量并赋值
	$newWeek = $("#newWeek").val();
	$newDate = $("#ClassDate").val();
	$newLine = $("#ClassLine").val();
	$oldWeek = $("#weekselect").val();
	$mid = $("#changebox").find("a").eq(0).attr("id");
	//AJAX异步请求
	$.ajax({
		type: "post",
		url: "./libs/Controller/tableControllerl.class.php",
		dataType: "json",
		data: {
			'newWeek': $newWeek, //新周
			'newDate': $newDate, //新星期
			'newLine': $newLine, //新节
			'mid': $mid,
			'oldWeek': $oldWeek,
			'mode': 'Change' //操作方式
		},
		success: function(data) {
			if(data) {
				alert("修改成功");
				//刷新表格
				checkPost(MethodName);
			}
		}
	});
}
//表格清空事件
function CleanTable() {
	$("#table").find('tr').nextAll().find("td").nextAll().html("");
}
//表格填充事件
function PrintTable(data, num) {
	CleanTable();
	//变量声明并初始化
	$i = 0;
	$ClassDate = 0;
	$ClassLine = 0;
	$res = "";
	var ClassLineTr = "";
	var ClassDateTd = "";
	$oldMid = "";
	$dataChange = "";
	$week = 'week' + num;
	//表格填充
	var oTable = document.getElementById("table");
	var oBox = $("#hadchangebox");
	while(data[$i]) {
		if($oldMid != data[$i]['mid']) {
			if(data[$i][$week] == '1') {
				ClassLineTr = oTable.getElementsByTagName('tr')[data[$i]['ClassLine']]
				ClassDateTd = ClassLineTr.getElementsByTagName('td')[data[$i]['ClassDate']];
				ClassDateTd.innerHTML += "<a class='scheduleA' id='" + data[$i]['mid'] + "'>" + data[$i]['ClassName'] + "</a>";
				$oldMid = data[$i]['mid'];
				//删除操作表格填充
				if(data[$i]['Delet'] == '1') {
					$dataChange += '<li>' + data[$i]['ClassName'] + '  第' + num + '周 星期' + data[$i]['ClassDate'] + ' 第' + data[$i]['ClassLine'] + '节课  (曾在第'+data[$i]['oldWeek']+'周)<a onclick = "DeletClass(' +data[$i]['mid']+ ','+data[$i]['oldWeek'] +');">删除</a></li>';
				}
			}
		}
		oBox.html($dataChange);
		//数组循环
		$i++;
	};
}

function DeletClass(mid,oldWeek) {
	$.ajax({
		type: "post",
		url: "./libs/Controller/tableControllerl.class.php",
		dataType: "json",
		data: {
			'mid': mid,
			'oldWeek':oldWeek,
			'mode': 'Delet'
		},
		success: function(data) {
			if(data) {
				alert("删除成功");
				//刷新表格
				checkPost(MethodName);
			}
		}
	});
}
//schedule article click to show fuction
function show2Box() {
	$(".scheduleA").on('click', function() {
		$mid = this.id;
		$oldDate = $mid[11];
		$oldLine = $mid[12];
		$cid = $mid.substr(0, 11);
		$classname = this.innerHTML;
		$("#changebox").html('<a id="' + $mid + '" >' + $classname + ' 周' + $oldLine + ' 第' + $oldDate + '节课</a>');
	})
}
//查询页动态
function showhideDiv(obj, e) {
	if(e == "show") {
		$(obj).css("display", 'block');
	} else if(e == "hide") {
		$(obj).css("display", 'none');
	}
}
$divLi = $(".showtableul").find("li");
$divLi.eq(0).css("display", 'block');
function showLi(num) {
	$divLi.css("display", 'none');
	$divLi.eq(num).css("display", 'block');
}