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
<?php 				require("db.php"); ?>
<?php
if (isset($_GET["menu"]) && $_GET["menu"] == "del" && isset($_GET["u"])) {
    $userid = $_GET["u"];
    $sql = "DELETE FROM contact WHERE userid = $userid";

    if (mysqli_query($conn, $sql)) {
        echo "<script>window.location='form.php'</script>";
    //echo "<script>setTimeout(function() {  window.location.href = 'openexam.php';}, 1000);</script>";
    } else {
        echo "<script>window.location='form.php'</script>";
        //echo "<script>setTimeout(function() {  window.location.href = 'home.php?menu=agenda';}, 1000);</script>";
    }
}
?>
	<div class="container">
		<form method="post" action="push.php">

			<div class="input-group mb-3 input-group-sm">

				<select multiple class="form-control" name="userid[]" multiple required>
					<?php
                    $sql = "SELECT * FROM contact ";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
					<option value="<?php echo $row[ "userid" ]; ?>" >
						<?php echo $row[ "name" ]; ?> </option>

					<?php
                        }
                    } else {
                        echo "<option>ไม่พบข้อมูล</option>";
                    }
                    ?>
				</select>

		  </div>

			<div class="form-group">


</div>
		<div class="input-group mt-5 mb-5">

			<input type="text" class="form-control" placeholder="" name="message" required>
			<div class="input-group-append">
				<button class="btn btn-success" type="submit">ส่งข้อความ</button>
			</div>
		</div>
		</form>

<div class="float-left">
	<img src="https://qr-official.line.me/sid/M/526xpyzj.png" class="img-fluid" style="height:200px;width:200px;">

	<a href="http://nav.cx/2zL1zqF"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/th.png" alt="เพิ่มเพื่อน" height="36" border="0">เพิ่มเพื่อนและแชท</a>
	</div>

</body>

</html>
