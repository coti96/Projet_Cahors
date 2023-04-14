window.onload = () => {
    // On va chercher la div dans le HTML
    let calendarEl = document.getElementById('calendrier');

    // On instancie le calendrier
    let calendar = new FullCalendar.Calendar(calendarEl, {
        // On charge le composant "dayGrid"
        plugins: [ 'dayGrid' ],
    });

    // On affiche le calendrier
    calendar.render();
}