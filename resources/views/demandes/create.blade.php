<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier de Demande de Congé</title>
    <style>
        .calendar-navigation {
            text-align: center;
            margin: 10px 0;
        }
        .calendar-month {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-family: Arial, sans-serif;
            margin: 5px;
        }
        .month-label {
            padding: 5px;
            font-weight: bold;
        }
        .days-container {
            display: flex;
            margin-bottom: 10px;
        }
        .day {
            width: 2em;
            text-align: center;
            cursor: pointer;
        }
        .weekend {
            background-color: #ADD8E6;
        }
        .selected-range {
            background-color: #FFD700;
        }
        .btn {
            padding: 5px 10px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="calendar-navigation">
        <button onclick="navigateCalendar(-1)">&#x25B2;</button>
        <button onclick="navigateCalendar(1)">&#x25BC;</button>
    </div>
    <div id="calendar"></div>
    <form action="{{ route('demandes.store') }}" method="POST" id="leaveRequestForm">
        @csrf <!-- Ajout du token CSRF -->
    <div class="form-group">
        <label for="startDate">Date de début :</label>
        <input type="date" id="startDate" name="DATE_DEBUT" readonly> <!-- Changement du type en 'date' -->
    </div>
    <div class="form-group">
        <label for="endDate">Date de fin :</label>
        <input type="date" id="endDate" name="DATE_FIN" readonly> <!-- Changement du type en 'date' -->
    </div>
        <div class="form-group">
            <label for="TITRE">Titre</label>
            <input type="text" class="form-control" id="TITRE" name="TITRE">
        </div>
        <div class="form-group">
            <label for="TYPE_ID">Type</label>
            <select class="form-control" id="TYPE_ID" name="TYPE_ID">
                @foreach($types as $type)
                    <option value="{{ $type->ID }}">{{ $type->NOM }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="EMPLOYE_REMPLACEMENT_ID">Employe remplacant</label>
            <select class="form-control" id="EMPLOYE_REMPLACEMENT_ID" name="EMPLOYE_REMPLACEMENT_ID">
                @foreach($employes as $employe)
                    <option value="{{ $employe->ID }}">{{ $employe->NOM }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn" >Envoyer la demande</button>
    </form>
    <script>
        const calendarEl = document.getElementById('calendar');
        let selectedStartDate = null;
        let selectedEndDate = null;
        let currentMonthOffset = 0;
        const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

        function createCalendar() {
            calendarEl.innerHTML = ''; // Clear the calendar
            const today = new Date();
            const currentMonth = today.getMonth() + currentMonthOffset;
            const currentYear = today.getFullYear();
            for (let i = 0; i < 4; i++) { // Four months at a time
                let monthDate = new Date(currentYear, currentMonth + i, 1);
                let daysInMonth = new Date(currentYear, currentMonth + i + 1, 0).getDate();
                let monthContainer = document.createElement('div');
                monthContainer.className = 'calendar-month';
                let monthLabel = document.createElement('div');
                monthLabel.className = 'month-label';
                monthLabel.textContent = monthNames[monthDate.getMonth()] + ' ' + monthDate.getFullYear();
                monthContainer.appendChild(monthLabel);
                let daysContainer = document.createElement('div');
                daysContainer.className = 'days-container';

                for (let day = 1; day <= daysInMonth; day++) {
                    let dayEl = document.createElement('div');
                    dayEl.className = 'day';
                    if ((new Date(currentYear, currentMonth + i, day).getDay() + 1) % 7 === 1 || (new Date(currentYear, currentMonth + i, day).getDay() + 1) % 7 === 0) { // Weekend
                        dayEl.classList.add('weekend');
                    }
                    dayEl.textContent = day;
                    dayEl.dataset.date = `${monthDate.getFullYear()}-${('0' + (monthDate.getMonth() + 1)).slice(-2)}-${('0' + day).slice(-2)}`;
                    dayEl.onclick = () => selectDate(new Date(currentYear, currentMonth + i, day), dayEl);
                    daysContainer.appendChild(dayEl);
                }
                monthContainer.appendChild(daysContainer);
                calendarEl.appendChild(monthContainer);
            }
        }

        function selectDate(date, dayEl) {
            if (!selectedStartDate || date < selectedStartDate || (selectedStartDate && selectedEndDate)) {
                clearSelection();
                selectedStartDate = date;
                selectedEndDate = null;
                dayEl.classList.add('selected-range');
                updateFormFields();
            } else if (selectedStartDate && !selectedEndDate && date >= selectedStartDate) {
                selectedEndDate = date;
                updateFormFields();
                highlightRange();
            }
        }

        function updateFormFields() {
            document.getElementById('startDate').value = selectedStartDate ? selectedStartDate.toISOString().split('T')[0] : '';
            document.getElementById('endDate').value = selectedEndDate ? selectedEndDate.toISOString().split('T')[0] : '';
        }

        function clearSelection() {
            document.querySelectorAll('.day').forEach(dayEl => dayEl.classList.remove('selected-range'));
        }

        function highlightRange() {
            document.querySelectorAll('.day').forEach(dayEl => {
                let dayDate = new Date(dayEl.dataset.date);
                if (dayDate >= selectedStartDate && dayDate <= selectedEndDate) {
                    dayEl.classList.add('selected-range');
                }
            });
        }

        function navigateCalendar(offset) {
            currentMonthOffset += offset;
            createCalendar();
            if (selectedStartDate && selectedEndDate) {
                highlightRange();
            }
        }

        function submitForm() {
        document.getElementById('leaveRequestForm').submit(); // Soumission du formulaire
    }

        // Initialize the calendar
        createCalendar();
    </script>
</body>
</html>
