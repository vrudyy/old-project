<!DOCTYPE html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
 
  <script src="./gantt/codebase/dhtmlxgantt.js"></script>
  <script src="./gantt/codebase/ext/dhtmlxgantt_marker.js"></script>  
  <script src="./gantt/codebase/ext/dhtmlxgantt_tooltip.js"></script>

 <link href="./gantt/codebase/dhtmlxgantt.css" rel="stylesheet">
 <link rel="stylesheet" href="./gantt/codebase/skins/dhtmlxgantt_broadway.css?v=20180227">


  <style type="text/css">
    html, body{
      height:100%;
      padding:0px;
      margin:0px;
      overflow: hidden;
    }

  </style>
</head>
<body>
<div style="text-align: center;height: 40px;line-height: 40px;">
<button style="height: 34px;line-height: 30px;margin:3px auto" onclick="toggleMode(this)">Zoom to Fit</button>
</div>

<input type="radio" id="scale1" name="scale" value="1" checked/><label for="scale1">Day scale</label><br>
<input type="radio" id="scale2" name="scale" value="2"/><label for="scale2">Week scale</label><br>
<input type="radio" id="scale3" name="scale" value="3"/><label for="scale3">Month scale</label><br>
<input type="radio" id="scale4" name="scale" value="4"/><label for="scale4">Year scale</label><br>


<div id="gantt_here" style='width:100%; height:300px; position: relative;'></div>
    <script src="javascript/periodGantt.js">
    </script>
</body>