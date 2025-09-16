<?php
// public/index.php
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title> Screen Share</title>
<link rel="stylesheet" href="style.css">
</head>

<body>
<a href="/LL2.0/dashboard.php">Go to Dashboard</a>
<div class="container">
<h1>Screen Share                                                                                              </h1>


<label>Room ID: <input id="roomId" value="room1"></label>
<label>Role:
<select id="roleSelect">
<option value="broadcaster">Broadcaster</option>
<option value="viewer">Viewer</option>
</select>
</label>


<div class="controls">
<button id="connectBtn">Connect</button>
<button id="startBtn" disabled>Start Sharing</button>
<button id="stopBtn" disabled>Stop Sharing</button>
</div>


<video id="remoteVideo" autoplay playsinline controls></video>
<p id="status">Status: idle</p>
</div>


<script src="client.js">
 
</script>
</body>
</html>