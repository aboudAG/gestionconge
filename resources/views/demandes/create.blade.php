<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demande de Congé') }}
        </h2>
    </x-slot>
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

@if ($droitConges)
<h3>Votre crédit congés</h3>
<ul>
    @foreach ($droitConges as $droit)
        <li>{{ $droit->ANNEE }}: {{ $droit->JOURS_RESTANT }} jours restants</li>
    @endforeach
</ul>
@endif

<div class="calendar-navigation">
    <button onclick="navigateCalendar(-1)">&#x25B2;</button>
    <button onclick="navigateCalendar(1)">&#x25BC;</button>
</div>
<div id="calendar"></div>
<form action="{{ route('demandes.store') }}" method="POST" id="leaveRequestForm">
    @csrf
    <div class="form-group">
        <label for="startDate">Date de début :</label>
        <input type="date" id="startDate" name="DATE_DEBUT" readonly>
    </div>
    <div class="form-group">
        <label for="endDate">Date de fin :</label>
        <input type="date" id="endDate" name="DATE_FIN" readonly>
    </div>
    <div class="form-group" id="yearSelection" style="display:none;">
        <label for="year1">Année de congé 1 :</label>
        <select class="form-control" id="year1" name="year1"></select>
    </div>
    <div class="form-group" id="yearSelection2" style="display:none;">
        <label for="year2">Année de congé 2 (optionnel) :</label>
        <select class="form-control" id="year2" name="year2"></select>
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
                <option value="{{ $employe->MATRICULE }}">{{ $employe->NOM }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn">Envoyer la demande</button>
</form>
</x-app-layout>

<script>
const calendarEl = document.getElementById('calendar');
let selectedStartDate = null;
let selectedEndDate = null;
let currentMonthOffset = 0;
const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
var droitConges = @json($droitConges);
document.getElementById('year1').addEventListener('change', function() {
    updateYearOptions(this.value, document.getElementById('year2'));
    toggleYear2Availability();
});

document.getElementById('endDate').addEventListener('change', toggleYear2Availability);

function createCalendar() {
    calendarEl.innerHTML = '';
    const today = new Date();
    today.setHours(0,0,0,0);
    const currentMonth = today.getMonth() + currentMonthOffset;
    const currentYear = today.getFullYear();
    for (let i = 0; i < 4; i++) {
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
            dayEl.textContent = day;
            dayEl.dataset.date = `${monthDate.getFullYear()}-${('0' + (monthDate.getMonth() + 1)).slice(-2)}-${('0' + day).slice(-2)}`;
            dayEl.onclick = () => selectDate(new Date(currentYear, currentMonth + i, day), dayEl);
            if ((new Date(currentYear, currentMonth + i, day).getDay() % 7 === 0) || (new Date(currentYear, currentMonth + i, day).getDay % 7 === 6)) {
                dayEl.classList.add('weekend');
            }
            daysContainer.appendChild(dayEl);
        }
        monthContainer.appendChild(daysContainer);
        calendarEl.appendChild(monthContainer);
    }
}

function selectDate(date, dayEl) {
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 9);
    if (!selectedStartDate || date < selectedStartDate || (selectedStartDate && selectedEndDate)) {
        if (date < minDate) {
            alert('La date de début doit être au moins 10 jours après aujourd\'hui.');
            return;
        }
        clearSelection();
        selectedStartDate = date;
        selectedEndDate = null;
        dayEl.classList.add('selected-range');
        updateFormFields();
    } else if (selectedStartDate && !selectedEndDate && date >= selectedStartDate) {
        selectedEndDate = date;
        if (!validateVacationLength()) {
            alert('La durée du congé doit être entre 15 et 30 jours.');
            selectedEndDate = null;
            updateFormFields();
            return;
        }
        updateFormFields();
        highlightRange();
        showYearSelection();
    }
}

function showYearSelection() {
    // Afficher les sélections d'année lorsque les deux dates sont sélectionnées
    document.getElementById('yearSelection').style.display = 'block';
    document.getElementById('yearSelection2').style.display = 'block';
    fillYearOptions();
}

function fillYearOptions() {
    const year1Select = document.getElementById('year1');
    const year2Select = document.getElementById('year2');
    year1Select.innerHTML = '<option value="">-- Sélectionner une année --</option>';
    year2Select.innerHTML = '<option value="">-- Sélectionner une année (optionnel) --</option>';

    droitConges.forEach((droit) => {
        let option = new Option(`${droit.ANNEE} - ${droit.JOURS_RESTANT} jours disponibles`, droit.ANNEE);
        year1Select.add(option.cloneNode(true));
        year2Select.add(new Option(option.text, option.value));
    });
}

function updateYearOptions(selectedYear, otherSelect) {
    const options = otherSelect.options;
    for (let i = 0; i < options.length; i++) {
        if (options[i].value === selectedYear) {
            options[i].disabled = true;
        } else {
            options[i].disabled = false;
        }
    }
}

function toggleYear2Availability() {
    const year1 = document.getElementById('year1').value;
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);
    const startYear = startDate.getFullYear();
    const endYear = endDate.getFullYear();
    const year2Select = document.getElementById('year2');

    if (year1) {
        if (parseInt(year1) < startYear || parseInt(year1) > endYear) {
            year2Select.disabled = false;
            year2Select.value = ''; // Reset year2 if it's out of range
        } else {
            year2Select.disabled = true;
        }
    } else {
        year2Select.disabled = true; // Disable year2 if year1 is not selected
    }
}


function validateVacationLength() {
    if (selectedStartDate && selectedEndDate) {
        const diffDays = Math.round((selectedEndDate - selectedStartDate) / (1000 * 60 * 60 * 24)) + 1;
        return diffDays >= 15 && diffDays <= 30;
    }
    return false;
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

document.getElementById('leaveRequestForm').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Prevent form submission
    }
});

function validateForm() {
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);
    const today = new Date();
    today.setDate(today.getDate() + 9); // Ensure 10 days advance notice

    if (startDate < today) {
        alert('La date de début doit être au moins 10 jours après aujourd\'hui.');
        return false;
    }
    if (endDate <= startDate) {
        alert('La date de fin doit être postérieure à la date de début.');
        return false;
    }
    if (!validateVacationLength()) {
        alert('La durée du congé doit être entre 15 et 30 jours.');
        return false;
    }
    return true;
}

// Initialize the calendar
createCalendar();
</script>
