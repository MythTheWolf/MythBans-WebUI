<?php
include ("lib/mySQL/MySQL.php");
$mySQL = new MySQL();
$con = $mySQL -> getConnection();
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
$_SESSION['DIR'] = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
$dir = str_replace($_SERVER['PHP_SELF'], "", strtok($_SERVER["REQUEST_URI"], '?'));
$_SESSION['HTTP_DIR'] = $dir;
?>
<!DOCTYPE HTML>
<html>
<head>

<!--- JQuery --->
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>
		
        <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="./css/prettify-1.0.css" rel="stylesheet">
        <link href="./css/base.css" rel="stylesheet">
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
    ajaxStart: function() {  $("#loader").show();},
     ajaxStop: function() { $("#loader").hide(); }    
});

	 </script>
	<center>
		<img src="lib/static/image/system-default.png">
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
      <a class="navbar-brand" href="#"><?php echo $config['SERVER-NAME']; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		  <li class="active"><a href="#"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a></li>
        <li><a href="#"><i class="fa fa-info-circle"></i> About Server..</a></li>
        <?php if($user['isLoggedIn']){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i> Admin <span class="caret"></span></a>
          <ul class="dropdown-menu">
            
          </ul>
        </li>
        <?php } ?>
      </ul>
    
      <ul class="nav navbar-nav navbar-right">
      
        <li><a href="#" id="loginLink" name="loginLink"><i class="fa fa-key"></i> Login</a></li>
    
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-info-circle"></i> More<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-gavel"></i> Staff...</a></li>
            <li role="separator" class="divider"></li>
			 <li><a href="#"><i class="fa fa-bug"></i> Report Bug...</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<center>
	<h3>Live Stats</h3>
</center>
<div id="example" name="example"></div>
 <table class="table table-striped">
 	
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
  <label for="actionType">Action type:</label>
  <select class="form-control" id="actionType" name="actionType">
   	<option>All</option>
    <option>Ban</option>
    <option>TempBan</option>
    <option>Mute</option>
    <option>Probation</option>
  </select>
</div>
 	</form>
 	
 	<script>
		$(document).ready(function() {
			$('#loginModal').modal('hide');
			$("#loginLink").click(function() {
				$('#loginModal').modal('show');
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
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick='' data-toggle="modal" data-target="#loginModal">Close</button>
        <button type="button" class="btn btn-success" onclick=''>Submit</button>
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
 
 
 
 
 <div id="alerts" name="alerts">
 	
 </div>
 
 <div class="myClass" id="resultTable" name="resultTable">
 
 
	</div>	 
	    </table>
	    <div id="loader">
 		<center>
 			<img src="lib/AJAX/CSS/loading.gif">
 		</center>
 	</div>
	    <a href="javascript:void(0);" id="loadMore" name="loadMore">Load more..</a>
	    
	<script>
		$(document).ready(function() {
			$("#loader").hide();
			$("#start").val("0");
			$("#end").val("5");
			$("#loadMore").click(function(){
				var old_start = parseInt($("#start").val(), 10);
				$("#start").val(old_start +5);
				var old_end = parseInt($("#end").val(), 10);
				$("#end").val(old_end + 5);
				$("html, body").animate({ scrollTop: $(document).height() }, 1000);
				$.ajax({
				type : "POST",
				url : "lib/AJAX/getMoreStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					
					$("table tbody").append(result);
					$("html, body").animate({ scrollTop: $(document).height() }, 1000);
				}
			});
				
			});
			$("#expireDate").hide();
			if ($("input[name='optAction']").val() == "kick") {
				$("#OptReasonDiv").show();
			}
			$("#OptReasonDiv").hide();
			bindButtonClick();

			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					
					$("#resultTable").html(result);
					$("html, body").animate({ scrollTop: $(document).height() }, 1000);
					bindButtonClick();

				}
			});

		});
		$("input[name='optAction']").change(function() {

			if (this.value == "kick") {

				$("#OptReasonDiv").show();
			} else {
				$("#OptReasonDiv").hide();
			}
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
			
			
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {
					$("#resultTable").html(result);
					$("html, body").animate({ scrollTop: $(document).height() }, 1000);
					bindButtonClick();
				}
			});

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
			$(".moreOptionsButton").click(function() {
				triggerOptions(this.id);
			});
		}

		function reloadData() {
			
			$.ajax({
				type : "POST",
				url : "lib/AJAX/getStats.php",
				data : $('#filters').serialize(),
				success : function(result) {

					$("#resultTable").html(result);
					$("html, body").animate({ scrollTop: $(document).height() }, 1000);
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

						reloadData();
						$("#editGo").prop('disabled', false);
						$("#setStatus").modal('hide');
						$("#alerts").html("<div class=\"alert alert-success fade in\"> <strong>&#x2714;</strong> Updated user.");
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