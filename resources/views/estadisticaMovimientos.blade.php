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

        <div width="500px" height="500px" style="display: block; box-sizing: border-box; height: 500px; width: 500px;">
            <canvas id="myChart" height="500px" width="500px" style="display: block; box-sizing: border-box; height: 500px; width: 500px;">
            </canvas>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        // const Datos = JSON.parse("{{json_encode($datos)}}");
        const Datos = JSON.parse({!!json_encode($datos)!!});
        console.log(Datos)

        const DATA_COUNT = 7;
        const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

        const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        const labels = Array.from(new Set(Datos.map((elemento)=>meses[elemento.mes-1])))
        console.log(labels)
        const entradas = Datos.map((elemento)=>{if(elemento.tipo=="ENTRADA"){return elemento.total}else{return;} }).filter((elemento)=> typeof elemento != 'undefined')
        const salidas = Datos.map((elemento)=>{if(elemento.tipo=="SALIDA"){return elemento.total}else{return;} }).filter((elemento)=> typeof elemento != 'undefined')

        console.log({entrada:entradas , salida:salidas})

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'ENTRADAS',
                    data: entradas,
                    borderColor: 'green',
                    backgroundColor:'green',
                },
                {
                    label: 'SALIDAS',
                    data: salidas,
                    borderColor: 'red',
                    backgroundColor:'red',
                },
            ]
        };
        const ctx = document.getElementById('myChart');
        const config = {
            type: 'bar',
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
