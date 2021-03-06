<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include ("lib/mySQL/MySQL.php");
include ("lib/user/LibUser.php");
include ("lib/javaPlugin/player/Player.php");
$mySQL = new MySQL();
$con = $mySQL -> getConnection();
$PC = new PlayerCache($con);
$player = new Player($con);
$d = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
if ($con == null) {
	die("
	<center>
	<img src='static/image/system/db_error.png'>
	
	<h4>A connection to the database server could not be made. </h4> <br /> <i>Check " . $d . "configuration/config.php for any errors.</i></center>");
}
//die(phpinfo());
include ("lib/configuration/config.php");
include ("lib/user/user.php");

session_start();
if(isset($_GET['UUID']) || isset($_GET['STAFF_UUID']))
{
	$alt = true;
}
$_SESSION['DIR'] = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
$dir = str_replace($_SERVER['PHP_SELF'], "", strtok($_SERVER["REQUEST_URI"], '?'));
$_SESSION['HTTP_DIR'] = $dir;
?>
<!DOCTYPE HTML>
<html>
<head>
	

<!--- JQuery --->

		
        <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
			<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

<!--- Done Loading --->
</head>
<body>
<style>
</style>


 <script>
	$(document).on({
		ajaxStart : function() {
			$("#loader").show();
		},
		ajaxStop : function() {
			$("#loader").hide();
		}
	});

	 </script>
	<center>
		<a href="<?php echo $dir ?>"><img src="lib/static/image/system-default.png"></a>
	</center>
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $dir ?>"><?php echo $config['SERVER-NAME'];
		echo $user['isLoggedIn'];
 ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		  <li class="active"><a href="#"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a></li>
        <li><a href="#"><i class="fa fa-info-circle"></i> About Server..</a></li>
        <?php if($user['isLoggedIn'] == true){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i> Admin <span class="caret"></span></a>
          <ul class="dropdown-menu">
            
          </ul>
        </li>
        <?php } ?>
      </ul>
    
      <ul class="nav navbar-nav navbar-right">
      <?php
	if ($user['isLoggedIn'] == false) {
		echo "<li><a href=\"#\" id=\"loginLink\" name=\"loginLink\"><i class=\"fa fa-key\"></i> Login</a></li>";
	} else {
		echo "<li><a href=\"#\" id=\"logoutLink\" name=\"loginLink\"><i class=\"fa fa-sign-out\"></i> Log out</a></li>";
	}
	  ?>
    
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-info-circle"></i> More<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-gavel"></i> Staff...</a></li>
            <li role="separator" class="divider"></li>
			 <li><a href="#"><i class="fa fa-bug"></i> Report Bug...</a></li>
			 <?php
			 if ($user['isLoggedIn'] == true) {
			 ?>
			 <li><a href="#" id="changePass"><i class="fa fa-pencil"></i> Change password..</a></li>
			 <?php
			 }
			 ?>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<form id="GET">
	<input id="getUserUUID" name="getUserUUID" hidden>
</form>
<center>
	<?php
	if($alt != true){
	?>
	<h3>Live Stats</h3>
	<?php 
	}else if(isset($_GET['UUID'])){
		$username = $PC->getPlayerName($_GET['UUID']);
	?>
	<h3><?php echo $player->getStatusHTML($_GET['UUID'])." ".$username; ?></h3> 
	<?php echo "<img src='lib/javaPlugin/player/full_skin.php?u=$username' />"; ?>
	<center>
		<h4>Total Bans: <?php echo $player->getAmountBanned($_GET['UUID']); ?></h4>
	</center>
	
		<div id="alt_results">
			
		</div>
		
		<script>
		<?php
		$uuid = htmlspecialchars($_GET['UUID']);
		?>
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getUser.php?UUID=<?php echo $uuid ?>",
				data : $("#GET").serialize(),
				success : function(result) {
					
					$("#alt_results").html(result);
				}
			});
		</script>
	<?php
	}
	?>
</center>
<div id="example" name="example">
 <table class="table table-striped">
 	<?php
 	if($alt != true)
	{
		?>
 <div id="filterForm">
 	<form id="filters" name="filters" action="#">
 		<input type="text" id="start" name="start" hidden>
 		<input type="text" id="end" name="end" hidden>
 		<div class="form-group">
      <label for="filterName">Name:</label>
      <input type="text" class="form-control" id="filterName" name="filterName" value="<?php echo $_GET['name']; ?>">
    </div>
    <div class="form-group">
      <label for="filterBy">Banned By:</label>
      <input type="text" class="form-control" id="filterBy" name="filterBy" value="<?php echo $_GET['byUUID']; ?>">
    </div>
  <div class="form-group">
  <label for="actionType">Status:</label>
  <select class="form-control" id="actionType" name="actionType">
   	<option>All</option>
    <option>Ban</option>
    <option>TempBan</option>
    <option>Mute</option>
    <option>Probation</option>
    <option>OK</option>
  </select>
</div>
 	</form>
 	<?php
 	}
	if ($_SESSION['show_logout'] == true) {
		echo("
 
 
 			$('#alerts').html('<div class=\'alert alert-success fade in\'> <strong>&#x2714;</strong> Operation Completed.');
			window.setTimeout(function() {
				$('.alert').fadeTo(500, 0).slideUp(500, function() {
					$(this).remove();
				});
			}, 4000);
		});
 
 
 
 ");

	}
	?>
 	<script>
		$(document).ready(function() {
			$("#loader").hide();
			$('#loginModal').modal('hide');
			$("#loginLink").click(function() {
				$('#loginModal').modal('show');
			});
			$("#logoutLink").click(function() {
				$.ajax({
					type : "POST",
					url : "lib/AJAX/logOut.php",
					success : function(result) {

						location.reload();
					}
				});
			});
		});
	 </script>
 </div>
 
 
 
 
 
 
 
 
 
 
 <!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="loginModalLabel">System Login</h4>
      </div>
      <div class="modal-body">
         <form class="form-horizontal" name="login" id="login" action="#">
<fieldset>

<!-- Form Name -->


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="username">Username</label>  
  <div class="col-md-4">
  <input id="username" name="username" type="text" placeholder="username" class="form-control input-md" required="">
    
  </div>
</div>
<br />
<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password">Password</label>
  <div class="col-md-4">
    <input id="password" name="password" type="password" placeholder="password" class="form-control input-md" required="">
    
  </div>
</div>

</fieldset>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick='' data-toggle="modal" data-target="#loginModal">Close</button>
        <button type="button" class="btn btn-success" onclick='' id="subLogin">Submit</button>
      </div>
    </div>
  </div>
</div>
	
	
	
	
	
	
	
	<!-- Edit Modal -->
<div class="modal fade" id="setStatus" tabindex="-1" role="dialog" aria-labelledby="setStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="setStatusLabel">Set Player Status</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="editForm" name="editForm">
        	<input id="editUUID" name="editUUID" type="text" value="foo" hidden>
        	
<fieldset>
 
<!-- Form Name -->
<legend></legend>
 
<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Current Status</label>
  <div class="col-md-4">
    <select id="selectbasic" name="selectbasic" class="form-control">
      <option value="Ban">Banned</option>
      <option value="TempBan">TempBanned</option>
      <option value="Probate">Probation</option>
      <option value="Mute">Mute</option>
      <option value="OK">Active</option>
    </select>
  </div>
</div>
<br />
 <div class="form-group" id="reason" name="reason">
  <label class="col-md-4 control-label" for="setReason">Reason</label>
  <div class="col-md-4">

    <input type='text' class="form-control" id="setReason" name="setReason">
	
  </
  </div>
</div>
<br />
<br />
<div class="form-group" id="expireDate" name="expireDate">
  <label class="col-md-4 control-label" for="setDate">Expire Date</label>
  <div class="col-md-4">
  	<div class='input-group date' id='datetimepicker1'>
    <input type='text' class="form-control" / readonly="" id="setDate" name="setDate">
	<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
  </div>
  </div>
</div>


<script type="text/javascript">
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format : "YYYY-MM-DD H:mm:ss",
			ignoreReadonly : true
		});
	});
			</script>



</fieldset>

</form>
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick='' data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick='' id="editGo" name="editGo">Go</button>
      </div>
    </div>
  </div>
</div>
 

 
 
 
 
 <!-- Option Modal -->
<div class="modal fade" id="moreOpt" tabindex="-1" role="dialog" aria-labelledby="moreOptLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="moreOptLabel">Advanced Options</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="optForm" name="optForm">
        	<input id="optUUID" name="optUUID" type="text" hidden>
<fieldset>
 
<!-- Form Name -->

 
<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="optAction">Action</label>
  <div class="col-md-4">
  <div class="radio">
    <label for="optAction-0">
      <input type="radio" name="optAction" id="optAction-0" value="kick" >
      Kick User
    </label>
    </div>
  <div class="radio">
    <label for="optAction-1">
      <input type="radio" name="optAction" id="optAction-1" value="clrlogs">
      Clear Logs for this user
    </label>
    </div>
  <div class="radio">
    <label for="optAction-2">
      <input type="radio" name="optAction" id="optAction-2" value="delete">
      Delete User
    </label>
    </div>
  <div class="radio">
    <label for="optAction-3">
      <input type="radio" name="optAction" id="optAction-3" value="dwnld" checked="checked">
      Download Logs
    </label>
    </div>
  </div>
</div>

<br />
<br />
<br />
<br />
<br />
 <div class="form-group" id="OptReasonDiv" name="OptReasonDiv">
  <label class="col-md-4 control-label" for="optReason">Reason</label>
  <div class="col-md-4">

    <input type='text' class="form-control" id="optReason" name="optReason">
	
  </div>
</div>
</fieldset>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick='' data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick='' id="optGo" name="optGo">Go</button>
      </div>
    </div>
  </div>
</div>
 
 <!-- PermaLink Modal -->
<div class="modal fade" id="getPermaLink" tabindex="-1" role="dialog" aria-labelledby="getPermaLinkLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="getPermaLinkLabel">Perma-Link</h4>
      </div>
      <div class="modal-body">
        <div id="permaLinkContent">
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick='' data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>
 
 
 <div id="alerts" name="alerts">
 	
 </div>
 
 <div class="myClass" id="resultTable" name="resultTable">
 
 
	</div>	 
	    </table>
	    </div>
	    <div id="loader">
 		<center>
 			<img src="lib/AJAX/CSS/loading.gif" id="target" class="target">
 		</center>
 	</div>
 	<div>
 		<?php
 		if($alt != true){
 			?>
 	<center>
	    <button id="loadMore" name="loadMore" type="button" class="btn btn-primary"><i class="fa fa-refresh fa-spin"></i> Load More Data</button>
	    </center>
	    </div>
	    <?php
		}
		?>
	    
	    
	    
	<script>
		function shakeForm() {
			var l = 20;
			for (var i = 0; i < 10; i++)
				$(".modal-body").animate({
					'margin-left' : "+=" + ( l = -l ) + 'px',
					'margin-right' : "-=" + l + 'px'
				}, 50);

		}


		$(document).ready(function() {
			
			$("#subLogin").click(function() {
				$("#subLogin").prop('disabled', true);
				$.ajax({
					type : "POST",
					url : "lib/AJAX/login.php",
					data : $('#login').serialize(),
					success : function(result) {
						shakeForm();
						if (result === "OK") {
							$("#loginModal").modal('hide');
							location.reload();
						}
						$("#subLogin").prop('disabled', false);
					}
				});
			});
			$("#loader").hide();
			$("#start").val("0");
			$("#end").val("5");
			$("#loadMore").click(function() {
				var old_start = parseInt($("#start").val());
				$("#start").val(old_start+5);
				var old_end = parseInt($("#end").val());
				$("#end").val(old_end);
				$.ajax({
					type : "POST",
					url : "lib/AJAX/getMoreStats.php",
					data : $('#filters').serialize(),
					success : function(result) {
						$("table tbody").append(result);
						$("html, body").animate({
							scrollTop : $(document).height()
						}, 1000);
					}
					
				});

			});
			$("#expireDate").hide();
			if ($("input[name='optAction']").val() == "kick") {
				$("#OptReasonDiv").show();
			}
			$("#OptReasonDiv").hide();
			bindButtonClick();
			<?php
	  	if($alt != true){
	    ?>
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {

					$("#resultTable").html(result);

					bindButtonClick();

				}
			});

		});
		$("#filterName").keyup(function(event) {
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					$("#resultTable").html(result);

					bindButtonClick();
				}
			});
		});
		$("#filterBy").keyup(function(event) {
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					$("#resultTable").html(result);

					bindButtonClick();
				}
			});
		});
		$("#actionType").change(function(event) {
				$("#resultTable").html("<center><img src=\"lib/AJAX/CSS/loading.gif\"></center>");
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					$("#resultTable").html(result);

					bindButtonClick();
				}
			});

		});
		<?php
		}
		?>
			$("input[name='optAction']").change(function() {

			if (this.value == "kick") {

				$("#OptReasonDiv").show();
			} else {
				$("#OptReasonDiv").hide();
			}
		});
		
		$("#selectbasic").change(function(event) {
			switch($("#selectbasic").val()) {
			case "OK":
				$("#expireDate").hide();
				$("#reason").hide();
				break;
			case "Ban":
				$("#expireDate").hide();
				$("#reason").show();
				break;
			case "TempBan":
				$("#expireDate").show();
				$("#reason").show();
				break;
			case "Probate":
				$("#expireDate").hide();
				$("#reason").show();
				break;
			case "Mute":
				$("#expireDate").hide();
				$("#reason").hide();
				break;
			}
		});
		function bindButtonClick() {

			$(".testClass").click(function() {
				triggerModal(this.id);
			});
			$(".permaLink").click(function() {
				
				getPermaLink(this.id);
			});
			$(".moreOptionsButton").click(function() {
				triggerOptions(this.id);
			});
		}

		function getPermaLink(uuid)
		{
			$("#permaLinkContent").html(uuid);
			$("#getPermaLink").modal("show");
		}
		function reloadData() {

			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {

					$("#resultTable").html(result);

					bindButtonClick();

				}
			});
		}

		function triggerOptions(uuid) {

			$("#moreOpt").modal();
			$("#optUUID").val(uuid);

			$("#optGo").click(function() {
				$("#optGo").prop('disabled', true);
				$.ajax({
					type : "POST",
					url : "lib/AJAX/moreOptions.php",
					data : $("#optForm").serialize(),
					success : function(result) {
						if (result == "DWN") {
							window.open("lib/AJAX/log_tmp/" + uuid + ".txt");
						}
						$("#optGo").prop('disabled', false);
						$("#moreOpt").modal('hide');
						reloadData();
						$("#alerts").html("<div class=\"alert alert-success fade in\"> <strong>&#x2714;</strong> Operation Completed.");
						window.setTimeout(function() {
							$(".alert").fadeTo(500, 0).slideUp(500, function() {
								$(this).remove();
							});
						}, 4000);
					}
				});

			});
		}

		function triggerModal(uuid) {

			$("#setStatus").modal()
			$("#editUUID").val(uuid);
			$("#editGo").click(function() {
				$("#editGo").prop('disabled', true);

				$.ajax({
					type : "POST",
					url : "lib/AJAX/updateStatus.php",
					data : $("#editForm").serialize(),
					success : function(result) {
						if (result == "ERR_PERM") {
							$("#alerts").html("<div class=\"alert alert-danger fade in\"> <strong>&#9888;</strong> You don't have permission to do that.");
						} else {

						}
						reloadData();
						$("#editGo").prop('disabled', false);
						$("#setStatus").modal('hide');

						window.setTimeout(function() {
							$(".alert").fadeTo(500, 0).slideUp(500, function() {
								$(this).remove();
							});
						}, 4000);
					}
				});

			});

		}
	</script>
	
	


 
<script type="text/javascript">
	$(".form_datetime").datetimepicker({
		format : "dd MM yyyy - hh:ii",
		defaultDate : date
	}); 
</script> 
	
	</body>
</html>