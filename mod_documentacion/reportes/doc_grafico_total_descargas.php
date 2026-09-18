<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

<script>
Highcharts.chart('container', {
    title: {
        text: 'Total documentos por Programa'
    },
    subtitle: {
        text: 'Biblioteca Virtual ASJ'
    },
    xAxis: {
        categories: ['Manuales', 'Políticas', 'Convenios', 'Instructivos', 'Otros']
    },
    labels: {
        items: [{
            html: 'Documentos por Programa',
            style: {
                left: '50px',
                top: '18px',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
            }
        }]
    },
    series: [{
        type: 'column',
        name: 'Rescate',
        data: [3, 2, 1, 3, 4]
    }, {
        type: 'column',
        name: 'Paz y Justicia',
        data: [2, 3, 5, 7, 6]
    }, {
        type: 'column',
        name: 'PME',
        data: [4, 3, 3, 9, 0]
    }, {
        type: 'spline',
        name: 'Convenio',
        data: [3, 2.67, 3, 6.33, 3.33],
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
        }
    }, {
        type: 'pie',
        name: 'Total de documentos por programa',
        data: [{
            name: 'Rescate',
            y: 13,
            color: Highcharts.getOptions().colors[0] // Jane's color
        }, {
            name: 'Paz y Justicia',
            y: 23,
            color: Highcharts.getOptions().colors[1] // John's color
        }, {
            name: 'PME',
            y: 19,
            color: Highcharts.getOptions().colors[2] // Joe's color
        }],
        center: [100, 80],
        size: 100,
        showInLegend: false,
        dataLabels: {
            enabled: false
        }
    }]
});
</script>
