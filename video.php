<?php

$data = $_GET;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>video play</title>
    <meta http-equiv="x-ua-compatible" content="IE=edge" >
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <link rel="stylesheet" href="public/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/aliplay/aliplayer-min.css" />
</head>
<body>
    <div class="container">
        <h1 class='page-header'>在线文件管理系统</h1>
        <div id="player-con"></div>
    </div>
</body>
<script src="/public/bs/js/jquery.min.js"></script>
<script src="/public/layer/layer.js"></script>
<script src="/public/bs/js/bootstrap.js"></script>
<script src="/public/js/tools.js"></script>
<!-- <script src="/public/js/common.js"></script> -->
<script type="text/javascript" charset="utf-8" src="public/aliplay/aliplayer-min.js"></script>
<script>
  var player = new Aliplayer({
    id: "player-con",
    source: <?php echo "'http://video.com/" . $data['path'] . "'"; ?>,
    width: "100%",
    height: "500px",
    // cover: 'https://img.alicdn.com/tps/TB1EXIhOFXXXXcIaXXXXXXXXXXX-760-340.jpg',
    /* To set an album art, you must set 'autoplay' and 'preload' to 'false' */
    autoplay: true,
    preload: false,
    isLive: false
  }, function (player) {
    console.log("The player is created");
  });
</script>
</html>
