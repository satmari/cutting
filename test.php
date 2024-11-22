<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Progress Bar</title>
<style>
  .progress-container {
    width: 100%;
    height: 30px;
    background-color: #ddd;
    position: relative;
    display: flex;
  }
 
  .progress-bar {
    height: 100%;
    background-color: #b56953;
    text-align: center;
    line-height: 30px;
    color: white;
  }
 
  .checkpoint {
    position: absolute;
    height: 100%;
    width: 2px;
    background-color: black;
  }
 
  .checkpoint-label {
    position: absolute;
    top: 35px;
    transform: translateX(-50%);
    font-size: 12px;
  }
</style>
</head>
<body>
 
<div class="progress-container">
<div id="progress-bar" class="progress-bar">0m</div>
<div class="checkpoint" style="left: 60%;"></div>
<div class="checkpoint-label" style="left: 60%;">3000m/200din</div>
<div class="checkpoint" style="left: 70%;"></div>
<div class="checkpoint-label" style="left: 70%;">3500m</div>
<div class="checkpoint" style="left: 80%;"></div>
<div class="checkpoint-label" style="left: 80%;">4000m</div>
<div class="checkpoint" style="left: 90%;"></div>
<div class="checkpoint-label" style="left: 90%;">4500m</div>
<!-- <div class="checkpoint" style="left: 100%;"></div> -->
<!-- <div class="checkpoint-label" style="left: 100%;">5000m</div> -->
</div>
 
<script>
  function updateProgressBar(meters) {
    const progressBar = document.getElementById('progress-bar');
    let percentage = (meters / 5000) * 100;
    if (percentage > 100) {
      percentage = 100;
    }
    progressBar.style.width = percentage + '%';
    progressBar.innerText = meters + 'm';
  }
 

</script>
 
</body>
</html>
has context menu