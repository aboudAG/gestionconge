<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .alert {
            border-radius: 0.4rem;
            padding: 10px 20px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .calendar-container {
            width: 100%;
        }

        .day-numbers-header {
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: #f8f9fa;
        }

        .day-number {
            flex: 1;
            text-align: center;
            padding: 5px 0;
            font-size: 18px;
        }

        .calendar-month {
            margin-bottom: 20px;
        }

        .days-container {
            display: flex;
            justify-content: space-between;
        }

        .month-header {
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .day-headers {
            display: flex;
            justify-content: space-between;
        }

        .day {
            flex: 1;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            border: none;
        }

        .weekend {
    background-color: #4A90E2; /* Bright blue for weekends */
    color: white;
}

.selected-range {
    background-color: #435161; /* Soft blue for selected range */
    color: white;
}

        .calendar-navigation {
            text-align: center;
            user-select: none;
            cursor: pointer;
        }

        .calendar-navigation span {
            font-size: 24px;
            margin: 0 15px;
        }

        .disabled {
            background-color: #f8f8f8;
            color: #ccc;
            cursor: not-allowed;
        }

        .btn {
            padding: 5px 10px;
            cursor: pointer;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #435161;
            color: white;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demande de congé') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mt-4">
        <button type="button" class="btn btn-black mb-4" data-bs-toggle="modal" data-bs-target="#modalConges" style="width: 100%; height: 50px;">
            Voir mon crédit congés
        </button>

        <div class="calendar-navigation">
            <button onclick="navigateCalendar(-1)">&#x25B2;</button>
            <button onclick="navigateCalendar(1)">&#x25BC;</button>
        </div>
        <div id="calendar" class="mb-4"></div>

        <form action="{{ route('demandes.store') }}" method="POST" enctype="multipart/form-data" id="leaveRequestForm" class="p-4 bg-white shadow-lg rounded-lg" >
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="startDate" class="form-label font-weight-bold">Date de début :</label>
                    <input type="date" class="form-control shadow-sm" id="startDate" name="DATE_DEBUT" readonly>
                </div>
                <div class="col-md-6">
                    <label for="endDate" class="form-label font-weight-bold">Date de fin :</label>
                    <input type="date" class="form-control shadow-sm" id="endDate" name="DATE_FIN" readonly>
                </div>
            </div>
            <div class="row mb-3" id="yearSelection" style="display:none;">
                <div class="col-md-6">
                    <label for="year1" class="form-label font-weight-bold">Année de congé 1 :</label>
                    <select class="form-control shadow-sm" id="year1" name="year1"></select>
                </div>
                <div class="col-md-6" id="yearSelection2" style="display:none;">
                    <label for="year2" class="form-label font-weight-bold">Année de congé 2 (optionnel) :</label>
                    <select class="form-control shadow-sm" id="year2" name="year2" disabled></select>
                </div>
            </div>
            <div class="mb-3">
                <label for="TITRE" class="form-label font-weight-bold">Titre :</label>
                <input type="text" class="form-control shadow-sm" id="TITRE" name="TITRE" placeholder="Entrez un titre">
            </div>
            <div class="mb-3">
                <label for="TYPE_ID" class="form-label font-weight-bold">Type :</label>
                <select class="form-control shadow-sm" id="TYPE_ID" name="TYPE_ID" onchange="handleTypeChange()">
                    @foreach($types as $type)
                        <option value="{{ $type->ID }}">{{ $type->NOM }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="justificatifContainer" style="display: none;">
                <label for="justificatif" class="form-label font-weight-bold">Justificatif :</label>
                <input type="file" class="form-control shadow-sm" id="justificatif" name="justificatif" accept=".pdf, .png">
            </div>
            <div class="mb-3">
                <label for="EMPLOYE_REMPLACEMENT_ID" class="form-label font-weight-bold">Employe remplaçant :</label>
                <select class="form-control shadow-sm" id="EMPLOYE_REMPLACEMENT_ID" name="EMPLOYE_REMPLACEMENT_ID">
                    @foreach($employes as $employe)
                        <option value="{{ $employe->MATRICULE }}">{{ $employe->NOM }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block shadow-sm">Envoyer la demande</button>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="modalConges" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Votre crédit congés</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($droitConges)
                            <ul>
                                @foreach ($droitConges as $droit)
                                    @if ($droit->JOURS_RESTANT > 0)
                                        <li>{{ $droit->ANNEE }}: {{ $droit->JOURS_RESTANT }} jours restants</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        const calendarEl = document.getElementById('calendar');
        let selectedStartDate = null;
        let selectedEndDate = null;
        let currentMonthOffset = 0;
        const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        const dayNames = ["D", "L", "M", "M", "J", "V", "S"];
        var droitConges = @json($droitConges);

        document.getElementById('year1').addEventListener('change', function() {
            updateYearOptions(this.value, document.getElementById('year2'));
            toggleYear2Availability();
        });

        document.getElementById('endDate').addEventListener('change', toggleYear2Availability);

        function handleTypeChange() {
            var typeSelect = document.getElementById('TYPE_ID');
            var year1 = document.getElementById('yearSelection');
            var year2 = document.getElementById('yearSelection2');
            var justificatifContainer = document.getElementById('justificatifContainer');
            var selectedType = typeSelect.options[typeSelect.selectedIndex].text;

            if (selectedType === 'Maladie' || selectedType === 'Maternelle' || selectedType === 'Sans Solde') {
                justificatifContainer.style.display = 'block';
            } else {
                justificatifContainer.style.display = 'none';
            }

            if (selectedType === 'Sans Solde') {
                year1.style.display = 'none';
                year2.style.display = 'none';
            } else {
                year1.style.display = 'block';
                year2.style.display = 'block';
            }
        }

        function createCalendar() {
            calendarEl.innerHTML = '';

            const dayNumbersHeader = document.createElement('div');
            dayNumbersHeader.className = 'day-numbers-header';
            for (let day = 1; day <= 31; day++) {
                const dayNumberDiv = document.createElement('div');
                dayNumberDiv.className = 'day-number';
                dayNumberDiv.textContent = day;
                dayNumbersHeader.appendChild(dayNumberDiv);
            }
            calendarEl.appendChild(dayNumbersHeader);

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const minDate = new Date(today.getTime());
            minDate.setDate(minDate.getDate() + 10);

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

                for (let day = 1; day <= 31; day++) {
                    let dayEl = document.createElement('div');
                    dayEl.className = 'day';
                    let fullDate = new Date(Date.UTC(currentYear, currentMonth + i, day));

                    let dayNameDiv = document.createElement('div');
                    dayNameDiv.className = 'day-name';
                    dayNameDiv.textContent = dayNames[fullDate.getUTCDay()];
                    dayEl.appendChild(dayNameDiv);

                    dayEl.dataset.date = `${fullDate.getUTCFullYear()}-${('0' + (fullDate.getUTCMonth() + 1)).slice(-2)}-${('0' + fullDate.getUTCDate()).slice(-2)}`;

                    if (fullDate >= minDate && day <= daysInMonth) {
                        dayEl.onclick = () => selectDate(fullDate, dayEl);
                    } else {
                        dayEl.classList.add('disabled');
                    }

                    if (fullDate.getDay() === 5 || fullDate.getDay() === 6) {
                        dayEl.classList.add('weekend');
                    }

                    if (day > daysInMonth) {
                        dayEl.style.visibility = 'hidden';
                    }

                    daysContainer.appendChild(dayEl);
                }

                monthContainer.appendChild(daysContainer);
                calendarEl.appendChild(monthContainer);
            }
        }

        function setupCalendarListeners() {
            calendarEl.addEventListener('click', function(event) {
                if (!event.target.classList.contains('day-name')) {
                    clearSelection();
                    hideYearSelection();
                }
            });
        }

        function selectDate(date, dayEl) {
            const minDate = new Date();
            minDate.setDate(minDate.getDate() + 9);

            if (selectedStartDate && date.getTime() === selectedStartDate.getTime()) {
                clearSelection();
                hideYearSelection();
                return;
            }

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
                updateFormFields();
                highlightRange();
                showYearSelection();
            }
        }

        function showYearSelection() {
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
                option.dataset.joursRestant = droit.JOURS_RESTANT;
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
            const differenceInMilliseconds = endDate - startDate;
            const differenceInDays = differenceInMilliseconds / (1000 * 3600 * 24);
            const inclusiveDifference = Math.ceil(differenceInDays + 1);
            const year2Select = document.getElementById('year2');
            const selectedYear1Option = document.querySelector(`#year1 option[value="${year1}"]`);
            const joursRestantsYear1 = parseInt(selectedYear1Option.dataset.joursRestant, 10);
            if (!isNaN(joursRestantsYear1)) {
                if (joursRestantsYear1 >= inclusiveDifference) {
                    year2Select.disabled = true;
                } else {
                    year2Select.disabled = false;
                }
            } else {
                year2Select.disabled = true;
            }
        }

        function updateFormFields() {
            document.getElementById('startDate').value = selectedStartDate ? selectedStartDate.toISOString().split('T')[0] : '';
            document.getElementById('endDate').value = selectedEndDate ? selectedEndDate.toISOString().split('T')[0] : '';
        }

        function clearSelection() {
            selectedStartDate = null;
            selectedEndDate = null;
            document.querySelectorAll('.day').forEach(dayEl => {
                dayEl.classList.remove('selected-range');
            });
            updateFormFields();
            hideYearSelection();
        }

        function hideYearSelection() {
            document.getElementById('yearSelection').style.display = 'none';
            document.getElementById('yearSelection2').style.display = 'none';
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
                event.preventDefault();
            }
        });

        function validateForm() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const today = new Date();
            today.setDate(today.getDate() + 9);

            if (startDate < today) {
                alert('La date de début doit être au moins 10 jours après aujourd\'hui.');
                return false;
            }
            if (endDate <= startDate) {
                alert('La date de fin doit être postérieure à la date de début.');
                return false;
            }
            return true;
        }

        createCalendar();
        setupCalendarListeners();

        document.addEventListener('DOMContentLoaded', function() {
            var replacementSelect = new Choices('#EMPLOYE_REMPLACEMENT_ID', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false
            });
        });
    </script>
</x-app-layout>
