  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
/*  $(function() {
    $( "#progressbar" ).progressbar({
      value: 37
    });
  });*/
var toRequest="";
$(document).ready(function (e){

$("#uploadForm").on('submit',(function(e){
		e.preventDefault();
				$.ajax({
					url: "http://sun355/intranet/csv_handle.php",
					type: "POST",
					data:  new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
					file_data = $.parseJSON(data);
					process_file(file_data);
					
					
					},
				error: function(){}           
				});
		}));
});
var toRequest;
function process_file(file_data){
var total_records = file_data[1];
for(i=0; i<file_data[0].length; i++){
	var file_name = "http://sun355/intranet/"+file_data[0][i]['file'];
//	console.log(file_name);
	$.getJSON(file_name).done(function(data_reponse){
	toRequest=data_reponse;
	doRequest(0);
	});


}
		//			console.log();

}



function doRequest(index) {


var data = toRequest[index];
	//console.log(data);
/*  $.ajax({
    url:"http://sun355/intranet/csv_add.php",
    type:"POST",
    data:data,
    async:true,
    success: function(data){        
      //do whatever you want do do
		console.log(data);
      if (index+1<toRequest.length) {
        doRequest(index+1);
      }
    }
  }); */


  $.post("http://sun355/intranet/csv_add.php", {data:data}, "json").done(function(data_r){

  		var counter = ((index/toRequest.length)*100);
  		  //console.log(counter);
  		  $( "#progressbar" ).progressbar({value: counter });
		      if (index+1<toRequest.length) {
		        doRequest(index+1);
		      }

  });
}

  </script>
</head>
<body>
 
<div id="progressbar"></div>
 
 <form id="uploadForm" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>


<div id="data_result"></div>


</body>
</html>