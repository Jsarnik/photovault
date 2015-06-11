<?php

$url = basename($_SERVER['SCRIPT_FILENAME']);
//Get file upload progress information.

    session_start();
    $progress_key = strtolower(ini_get("session.upload_progress.prefix").'demo');
    $progress = 100;
  
	if(isset($_GET['progress_key'])) {
	    if (!isset($_SESSION[$progress_key])){
	    	$progress = 100;
	    }
	    else{
	    	$upload_progress = $_SESSION[$progress_key];
	    	/* get percentage */
	    	$progress = round( ($upload_progress['bytes_processed'] / $upload_progress['content_length']) * 100, 2 );
	    }

	    echo $progress;
	    session_destroy();
	    die;
	}

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>
<link href="css/style_progress.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function() { 
//
	var attempts = 0;
	parent.IsSuccess(false);

	var progress = setInterval(function() 
		{
	$.get("<?php echo $url; ?>?progress_key=<?php echo strtolower(ini_get('session.upload_progress.prefix').'demo'); ?>&randval="+ Math.random(), { 
		//get request to the current URL (progress_frame.php) which calls the code at the top of the page.  It checks the file's progress based on the file id "progress_key=" and returns the value with the function below:
	},
		function(data)	//return information back from jQuery's get request
			{
				$('#progress_container').fadeIn(100);	//fade in progress bar	
				$('#progress_bar').width(data +"%");	//set width of progress bar based on the $status value (set at the top of this page)
				$('#progress_completed').html(parseInt(data) +"%");	//display the % completed within the progress bar

				if(parseInt(data) === 100){
					$('#status').html('Upload Ready');
					parent.IsSuccess(true);
					clearInterval(progress);
				}
				else if(attempts > 1200){ // cancel if upload hasn't finished after 10 minutes
					$('#status').html('Upload Failed');
					clearInterval(progress);
				}
				attempts+=1;
			}

		)},500);	//Interval is set at 500 milliseconds (the progress bar will refresh every .5 seconds)

});


</script>

<body style="margin:0px">
<!--Progress bar divs-->
<div id="progress_container">
	<div id="progress_bar">
  		 <div id="progress_completed"></div><br/>
  		 <div id="status">
  		 	<img id="preview"/>
	</div>
</div>
<!---->
</body>