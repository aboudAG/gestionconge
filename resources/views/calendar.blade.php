<!DOCTYPE html>
<html>
<head>
    <title>Calendar</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <style>
        .fc-holiday {
            background-color: #9195F6 !important;
            color: white !important;
        }
        .fc-weekend {
            background-color: #B7C9F2 !important;
            color: white !important;
        }
        .fc-event {
            border: none !important; /* Remove default border */
            border-radius: 0 !important; /* Remove rounded corners */
            height: 20px !important; /* Adjust height as needed */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            // Fetch holidays from the Blade template
            const holidays = @json($holidays);

            // Map holidays to FullCalendar events
            const events = holidays.map(holiday => ({
                title: "Jour ferié: "+ holiday.DESIGNATION,
                start: holiday.JOUR_DEBUT,
                end: holiday.JOUR_FIN ? new Date(new Date(holiday.JOUR_FIN).getTime() + 24 * 60 * 60 * 1000) : holiday.JOUR_DEBUT, // FullCalendar's end date is exclusive, so add one day
                className: 'fc-holiday'
            }));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                eventContent: function(arg) {
                    // Custom rendering to remove random number and adjust appearance
                    let titleEl = document.createElement('div');
                    titleEl.innerHTML = arg.event.title;
                    titleEl.style.lineHeight = '20px'; // Adjust line height for thicker appearance
                    titleEl.style.fontWeight='bold';
                    titleEl.style.fontSize='20px';
                    titleEl.style.color="white";    
                    let arrayOfDomNodes = [ titleEl ];
                    return { domNodes: arrayOfDomNodes };
                },
                
                dayCellDidMount: function(info) {
                    const date = new Date(info.date);
                    if (date.getDay() === 5 || date.getDay() === 6) { // Friday (5) or Saturday (6)
                        info.el.classList.add('fc-weekend');
                        info.el.setAttribute('title', 'Weekend');
                    }
                }
            });

            calendar.render();
        });
    </script>
</head>
<body>
    <div id='calendar'></div>
</body>
</html>