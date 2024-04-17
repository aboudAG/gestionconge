<!
DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier de Demande de Congé</title>
    <style>
        .calendar-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .month {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .days-container {
            display: flex;
            background: #f0f0f0;
            padding: 5px;
            flex-wrap: wrap;
        }
        .day {
            width: 2.5em;
            margin: 2px;
            text-align: center;
            cursor: pointer;
        }
        .weekend {
            background-color: #ADD8E6;
        }
        .selected {
            background-color: #FFD700;
        }
        .month-name {
            font-size: 1em;
            font-weight: bold;
        }
        .form-group label {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Faire une demande</h2>
        <div id="calendar"></div>
        <form id="demandeCongeForm" style="margin-top: 20px;">
            <div class="form-group">
                <label for="date_debut">Date de début:</label>
                <input type="text" id="date_debut" name="DATE_DEBUT" readonly>
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin:</label>
                <input type="text" id="date_fin" name="DATE_FIN" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
        </form>
    </div>
    <script>
        const months = ["Septembre", "Octobre", "Novembre", "Décembre"]; // Example months
        const calendarContainer = document.querySelector('#calendar');
        let selectedStartDate = null;
        let selectedEndDate = null;

        function updateFormWithSelectedDates() {
            const dateDebutInput = document.getElementById('date_debut');
            const dateFinInput = document.getElementById('date_fin');
            dateDebutInput.value = selectedStartDate ? selectedStartDate.toISOString().split('T')[0] : '';
            dateFinInput.value = selectedEndDate ? selectedEndDate.toISOString().split('T')[0] : '';
        }

        function clearSelection() {
            selectedStartDate = null;
            selectedEndDate = null;
            document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected'));
            updateFormWithSelectedDates();
        }

        function selectDate(dayElement, year, month, day) {
            const date = new Date(year, month, day);
            if (!selectedStartDate || (date < selectedStartDate || selectedEndDate)) {
                clearSelection();
                selectedStartDate = date;
                dayElement.classList.add('selected');
            } else if (selectedStartDate && !selectedEndDate && date >= selectedStartDate) {
                selectedEndDate = date;
                dayElement.classList.add('selected');
                updateFormWithSelectedDates();
            }
        }

        function createDay(year, month, day, isWeekend) {
            const dayElement = document.createElement('div');
            dayElement.textContent = day;
            dayElement.className = `day ${isWeekend ? 'weekend' : ''}`;
            dayElement.onclick = () => selectDate(dayElement, year, month, day);
            return dayElement;
        }

        function createMonth(year, monthIndex) {
            const month = document.createElement('div');
            month.className = 'month';

            const monthName = document.createElement('div');
            monthName.textContent = months[monthIndex];
            monthName.className = 'month-name';
            month.appendChild(monthName);

            const daysContainer = document.createElement('div');
            daysContainer.className = 'days-container';
            month.appendChild(daysContainer);

            for (let day = 1; day <= 31; day++) {
                // For simplicity, assuming all months have 31 days. Adjust logic for correct days per month.
                const dayOfWeek = new Date(year, monthIndex, day).getDay();
                const isWeekend = dayOfWeek === 5 || dayOfWeek === 6; // Mark Friday and Saturday as weekend
                const dayElement = createDay(year, monthIndex, day, isWeekend);
                daysContainer.appendChild(dayElement);
            }

            return month;
        }

        // Initialize the months
        const currentYear = new Date().getFullYear();
        for (let i = 0; i < months.length; i++) {
            const monthBlock = createMonth(currentYear, i + 8); // Starting from September
            calendarContainer.appendChild(monthBlock);
        }
    </script>
    </body>
    </html>
