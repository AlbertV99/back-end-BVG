<!DOCTYPE html>
<html>
<title>B-VG</title>
<head>

    <style>
        table{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-aling: left;
            padding: 8px;
        }
        tr:nth-child(even){
            background-color: #dddddd;
        }
    </style>
    </head>

    <body>
        <h1>Reporte estadistico</h1>
        <!-- <p>{{$datos}}</p> -->

        <div width="1000px" height="1000px" style="display: block; box-sizing: border-box; height: 1000px; width: 1000px;">
            <canvas id="myChart" height="1000px" width="1000px" style="display: block; box-sizing: border-box; height: 1000px; width: 1000px;"></canvas>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">

    const DATA_COUNT = 7;
    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

    const labels = ['enero','febrero','marzo','abril','mayo','junio','julio'];
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Dataset 1',
                data: ['10','11','5','7','16','2','8'],
                borderColor: 'black',
                backgroundColor:'white',
            },
        ]
    };
    const ctx = document.getElementById('myChart');
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Chart.js Line Chart'
                }
            }
        },
    };
    new Chart(ctx,config);

    </script>
    </html>
