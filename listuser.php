<!DOCTYPE html>
<html lang="en">

<head>
	<title>me</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=KoHo|Sarabun" rel="stylesheet">
	<link rel="stylesheet" href="dist/css/bootstrap-select.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<style>
		.dropbtn {
			background-color: #4CAF50;
			color: white;
			padding: 16px;
			font-size: 16px;
			border: none;
			cursor: pointer;
		}

		.dropbtn:hover,
		.dropbtn:focus {
			background-color: #3e8e41;
		}

		#myInput {
			border-box: box-sizing;
			background-image: url('searchicon.png');
			background-position: 14px 12px;
			background-repeat: no-repeat;
			font-size: 16px;
			padding: 14px 20px 12px 45px;
			border: none;
			border-bottom: 1px solid #ddd;
		}

		#myInput:focus {
			outline: 3px solid #ddd;
		}

		.dropdown {
			position: relative;
			display: inline-block;
		}

		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f6f6f6;
			min-width: 230px;
			overflow: auto;
			border: 1px solid #ddd;
			z-index: 1;
		}

		.dropdown-content a {
			color: black;
			padding: 12px 16px;
			text-decoration: none;
			display: block;
		}

		.dropdown a:hover {
			background-color: #ddd;
		}

		.show {
			display: block;
		}
	</style>
	<style>
		body {
			font-family: 'Sarabun', sans-serif;
			font-family: 'KoHo', sans-serif;
		}
	</style>
	<script type="text/javascript">
		$( document ).ready( function () {

			$( '.js-example-basic-single' ).select2();
			var today = moment().format( 'mm-dd-YYYY' );




		} );
	</script>
</head>
<body>

	<div class="container-fluid">
		<?php
				require("db.php");


		?>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>ลำดับ</th>
					<th>ชื่อ</th>
					<th>รูปโปรไฟล์</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
$sql = "SELECT * FROM contact";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "<p>" . $row["exam_name"] ."</p>";
?>
				<tr>
					<td>
						<?php echo $i ; ?>
					</td>
					<td>
						<?php echo $row["name"] ; ?>
					</td>
					<td>
						<a href="<?php echo $row["profilepic"] ; ?>" target="_blank"><img src="<?php echo $row["profilepic"] ; ?>" class= "img-fluid rounded" alt= "Responsive image" style="width:200px;height:200px;"></a>
					</td>
					<td>
						<a href="listuser.php?menu=del&u=<?php echo $row["userid"]; ?>">ลบ</a>
					</td>
				</tr>
				<?php
				$i++;
				}

				} else {
					echo "0 results";
				}

				?>
			</tbody>
		</table>






	</div>

</body>

</html>
