var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Routeurs', 'Serveurs', 'Commutateurs', 'Parefeux'],
        datasets: [{
            label: 'Number of elements',
            data: [
                {x: 'Routeurs', y: nbrouteurjs},
                {x: 'Serveurs', y: nbserveurjs},
                {x: 'Commutateurs', y: nbcommutateurjs},
                {x: 'Parefeux', y: nbparefeujs}
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        animation: {
            duration: 2000 // Durée de l'animation (en millisecondes)     
        },
        plugins: {
            title: {
                display: true,
                text: 'Diagramme',
            },
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true
            }
        }
    }
});