<?php

function row_select($date = "") {
	$mysqli = new mysqli("localhost", "root", "", "views");
	$mysqli->set_charset("utf8");

	$query = "SELECT COUNT(*) as views FROM `view_list` ";

	if($date == "online"){
		$now = date("Y-m-d H:i:s");
		$before = date("Y-m-d H:i:s" , time() - 180);
		$query .= "WHERE date BETWEEN '$before' AND '$now'";

	}else if ($date) {
		$query .= "WHERE date LIKE '%$date%'";
	}

	$data = false;
	if ($res = $mysqli->query($query)) {
		if ($res->num_rows) {
			$row = $res->fetch_assoc();
		}

		$res->close();
	}
	
	return $row;
}

function row_insert() {

	$date = date("Y-m-d");

	$mysqli = new mysqli("localhost", "root", "", "views");
	$mysqli->set_charset("utf8");

	$query = "INSERT INTO `view_list` (date) VALUES (CURRENT_TIMESTAMP)";

	$inserted_id = 0;

	if ($mysqli->query($query)) {
		$inserted_id = $mysqli->insert_id ? $mysqli->insert_id : 0;
	}

	return $inserted_id;

}
if(empty($_COOKIE['user_hint_view'])){
	setcookie("user_hint_view" ,"check", time() + 86400);
	row_insert();
}

date_default_timezone_set("Asia/Tehran");
$view_statistics_online = row_select("online"); // افراد آنلاین ( از زمان حال تا 3 دقیقه پیش را شامل می شود (
$view_statistics_today = row_select(date("Y-m-d")); // بازدید امروز
$view_statistics_yesterday = row_select(date("Y-m-d" , time() - 86400)); // بازدید دیروز
$view_statistics_all = row_select(); // بازدید کل

?>

<table>
	<tbody class="h1">
		
		<tr>
			<td>افراد آنلاین :</td>
			<td><u><?php echo $view_statistics_online['views'] ?></u></td>
		</tr>

		<tr>
			<td>بازدید امروز :</td>
			<td><u><?php echo $view_statistics_today['views'] ?></u></td>
		</tr>

		<tr>
			<td>بازدید دیروز :</td>
			<td><u><?php echo $view_statistics_yesterday['views'] ?></u></td>
		</tr>

		<tr>
			<td>بازدید کل :</td>
			<td><u><?php echo $view_statistics_all['views'] ?></u></td>
		</tr>
	</tbody>
</table>

</div>