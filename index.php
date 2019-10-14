<?php

session_start();

$obj = new imgRecog();
$obj->start();

class imgRecog{
	//var $evServer = 'https://aicv.everlive.net/scripts/evml_ic/';
	var $evServer = 'http://fw_main.com/scripts/evml_ic/';
	var $baseUri = ''; 
	var $reqVars=null;
	
	public function __construct(){
		$this->reqVars = $_REQUEST;
		$this->baseUri 		= $this->getBaseUri();
	}

	private function getBaseUri(){
		$u = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
			. "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
		$u = explode('?',$u);
		$u = $u[0];
		$u = str_replace('/index.php','/',$u);
		
		return $u;
	}
	
	public function start(){
		$opt = @$this->reqVars['opt'];
		if ($opt=='show_menu'){
			$this->show_menu();
			
		}elseif ($opt=='inf_form'){
			$this->inf_form();
			
		}else{
			$this->show_menu();
		}
	}
	
	private function inf_form(){
		$res = base64_decode( @$this->reqVars['res'] );
		$h = '<form method="post" enctype="multipart/form-data" id="requestjob" 
					action="'.$this->evServer.'index.php?view=dojob">
				<input type="hidden" name="project_id" id="project_id" value="1">
				<input type="hidden" name="op_code" id="op_code" value="900">
				<input type="hidden" name="cburl" id="cburl" value="">
				
				<style>
					#main-cont button{width:90%}
					#dvres {color:blue}
					#output{max-width: 90%;}
				 </style>
				 
				 <div id="main-cont">
				 <div id="dvres">'.$res.'</div>
				 <h5>Upload an Image to Classify</h5>
				 <p><input type="file" name="data" accept="image/*" onchange="loadFile(event)"></p>
				 
				 <p><img id="output" style="" /></p>
				 
				 <p><button class="btn btn-primary" onclick="submit_form()" 
						title="Upload image to classify by deep learning">Upload & Check</button></p>
				 <br><br><br>
				 </div>
			</form>
			<script>			
			function submit_form(pjid,opcode){
				document.querySelector("#cburl").value = encodeURIComponent(window.location.href.split("?")[0]);
				document.querySelector("#requestjob").submit();
			}
			var loadFile = function(event) {
				var output = document.getElementById("output");
				output.src = URL.createObjectURL(event.target.files[0]);
			};
			</script>
			';
		$this->echoPage($h);		
	}
		
	private function show_menu(){
		$pj=1;
		$ret_op = $this->some_in_proc();
		
		$bt100 = $this->getOpBtn(100,$ret_op,'(Re)Model & Run'); 
		$bt200 = $this->getOpBtn(200,$ret_op,'(Re)Start Server'); 
		$bt300 = $this->getOpBtn(300,$ret_op,'Stop Server'); 
		$bt50  = $this->getOpBtn(50, $ret_op,'Upload, ReModel & Run','Upload zip file which contain your dataset images.');
		
		$h = '<form method="post" enctype="multipart/form-data" id="requestjob" 
					action="'.$this->evServer.'index.php?view=dojob">
				<input type="hidden" name="project_id" id="project_id">
				<input type="hidden" name="op_code" id="op_code">
				<input type="hidden" name="cburl" id="cburl" value="">
				
				<style>
					#main-cont button{width:90%}
				 </style>
				 <div id="main-cont">
				 <h4>Upload Zip archive of Image files</h4>
				 <p><input type="file" name="images_zip"></p>
				 <p>'.$bt50.'</p><br>
				 <p>'.$bt100.'</p>
				 <p>'.$bt200.'</p>
				 <p>'.$bt300.'</p>
				 <br><br><br>
				 </div>
			</form>
			<script>
			function submit_form(pjid,opcode){
				document.querySelector("#project_id").value=pjid;
				document.querySelector("#op_code").value=opcode;
				document.querySelector("#cburl").value=encodeURIComponent(window.location.href.split("?")[0]);
				
				document.querySelector("#requestjob").submit();
			}
			</script>
			';
		$this->echoPage($h);
	}

	private function some_in_proc(){
		$u = $this->evServer.'index.php?view=some_in_proc&cburl='.$this->baseUri.'&_='.microtime(true);
		$res = $this->_getCurl($u);
		return $res;
	}

	private function getOpBtn($op,$ret_op,$cap,$title=""){		
		$gif = 'loading.gif';
		$pj = 1;
		if ($title=='')$title=$cap;
		$h = '<button class="btn btn-primary" onclick="submit_form('.$pj.','.$op.')" 
				title="'.$title.'">'.$cap.'</button>';

		if ($ret_op>0 && $ret_op==$op){
			$h='<div style="widthx:150px;"><img title="Current Operation: '.$cap.'" style="" src="'. $gif  .'" /></div>
				<script>
					setInterval(function(){
						var url="'.$this->evServer.'index.php?view=some_in_proc&op_code='.$op.'"
							+"&cburl="+encodeURIComponent(window.location.href.split("?")[0])
							+"&is_ajax=1&_"+new Date().getTime();
						jQuery.get(url, {
							}).done(function(data) {
								if (data=="0") window.location="index.php";
							});
						},3000);
				</script>
					';
		}
		
		return $h;
	}

	private function echoPage($h){
		$msg = @$this->reqVars['rdrmsg'];
		$msgDiv='';
		if ($msg!='') $msgDiv='<div class="alert alert-secondary"><h4>'.$msg.'</h4></div>';
		
		$h = '<html>
				<head>
					<meta charset="utf-8">
					<base href="'.$this->baseUri.'" />
					
					<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

					<link rel="stylesheet" href="bootstrap.min.css">
					<style>
						body {background-color:lightgoldenrodyellow; text-align:center}
						@media only screen and (min-width: 641px) {
							#page-container{width: 640px;  display: inline-block; background-color: lightyellow;}
						}
						#main-cont{margin-top:30px}
						.mainhead{background: lightblue;padding: 12px;}
						.footer { position: absolute; left: 0; bottomZ: 0; width: 100%; background-color: #E6E6BC;
									color: #B7B4B4;  text-align: center; font-size:60%; padding:3px}
						table {}
						.cmh2 {margin-left: 10px}
					</style>
					<script src="jquery.min.js"></script>
				</head>
				
				<body>
					<div id="page-container">
						<h3 class="mainhead">EV Image Recognition</h3>
						<div id="main-cont" class="">
							'.$msgDiv.'
							'.$h.'
						</div>
					</div>
					<script>
						var e = document.querySelector("#page-container");
						var b = document.querySelector("body");
						if (e.scrollHeight <= b.clientHeight){
							//document.querySelector("body").onload = //function(){document.querySelector(".footer").style.bottom="0px";};
						}
					</script>
				</body>
			  </html>';

		$this->burstCache();
		echo $h;
	}
	
	private function burstCache(){
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	function _getCurl( $url ){
		$ch = curl_init();
	    curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		//
		$User_Agent = 'Mozilla/5.0 (Windows; U; Windows NT 10.0; en-US) AppleWebKit/604.1.38 (KHTML, like Gecko) Chrome/68.0.3325.162';
		$request_headers = array();
		$request_headers[] = 'User-Agent: '. $User_Agent;
		$request_headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);		
		
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);

	    $content = curl_exec( $ch );
	    $response = curl_getinfo( $ch );
	    curl_close ( $ch );

		return $content;
	}
	
}

