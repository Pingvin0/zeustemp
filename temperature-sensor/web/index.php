<?php
require_once 'core.php';

$mysql = new Database();

$data = $mysql->simpleStmt("SELECT * FROM sensor ORDER BY timestamp ASC");

$temperature_data = "";
$pressure_data = "";
$humidity_data = "";
$labels = "";

while($row  = $data->fetch_assoc()) {
    $temperature_data .= "'".$row["temperature"]."'".", ";
    $pressure_data .= "'".$row["pressure"]."'".", ";
    $humidity_data .= "'".$row["humidity"]."'".", ";
    $labels .= "'".$row["timestamp"]."'".", ";
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("head.html"); ?>
</head>
<body>
<div style="width:70%"><canvas id="myChart" width="1920" height="1080"></canvas></div>
    <?php
    
    ?>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo $labels; ?>],
        datasets: [{
            label: 'Temperature',
            data: [<?php echo $temperature_data; ?>],
            backgroundColor: 'rgba(0,0,0,0)',
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1
        },
        {
            label: 'Pressure',
            hidden: true,
            data: [<?php echo $pressure_data; ?>],
            backgroundColor: 'rgba(0,0,0,0)',
            borderColor: 'rgba(0, 255, 0, 1)',
            borderWidth: 1
        },
        {
            label: 'Humidity',
            hidden: true,
            data: [<?php echo $humidity_data; ?>],
            backgroundColor: 'rgba(0,0,0,0)',
            borderColor: 'rgba(0, 0, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {

    }
});
</script>
</body>
</html>