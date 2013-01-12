<!DOCTYPE html>
<html>
<head>
	<title>Uploader</title>
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style>
		body {
			background-color: #FFF;
		}
		.guide {
			text-align: center;
		}
		.guide img {
			text-align: center;
			height: 400px;
		}
		.span6 {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		.span6 .span12 {
			margin-top: 10px;
			margin-bottom: 10px;
			margin-left: 10px;
			margin-right: 10px;
			background-color: #EEE;
		}
		.queue { counter-reset: c; }
		.entry:before {
			counter-increment: c;
			content: counter(c) ". ";
		}
		.entry {
			padding: 5px 10px 0 10px;
			font-size: 1.2em;
		}
	</style>
</head>
<body>
	<div class="container-fluid queue">
		<div class="row-fluid">
			<div class="span6 guide">
				<img src="../img/wrist_v.png" />
			</div>
			<div class="span6">
				<div class="row-fluid">
					<div class="span12 entry">Kevin & Dan</div>
				</div>
				<div class="row-fluid">
					<div class="span12 entry">Mal & Eric</div>
				</div>
				<div class="row-fluid">
					<div class="span12 entry">Sal & Julaiti</div>
				</div>
				<div class="row-fluid">
					<div class="span12 entry">Matt & David</div>
				</div>
				<div class="row-fluid">
					<div class="span12 entry">Matt & David</div>
				</div>
			</div>
		</div>
    </div>
	<div class="guide">
		
	</div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="/js/bootstrap.min.js"></script>
</body>
</html>