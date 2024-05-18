<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="row">
            <!-- First Column: Information and History -->
            <div class="col-md-4">
                <!-- Information Div -->
                <div class="card mb-4 border">
                    <div class="card-header">
                        Information
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Remaining Leave Balance This Year: <strong>{{ $soldeCongeRestant }}</strong></li>
                            <li class="list-group-item">Total Remaining Leave Balance: <strong>{{ $totalSoldeCongeRestant }}</strong></li>
                            <li class="list-group-item">Leave Days Taken This Year: <strong>{{ $joursCongePrisCetteAnnee }}</strong></li>
                            <li class="list-group-item">Total Leave Requests: <strong>{{ $nombreDemandesCongeTotal }}</strong></li>
                            <li class="list-group-item">Approved Leave Requests: <strong>{{ $nombreDemandesCongeAccepte }}</strong></li>
                            <li class="list-group-item">Denied Leave Requests: <strong>{{ $nombreDemandesCongeRefuse }}</strong></li>
                            <li class="list-group-item">Pending Leave Requests: <strong>{{ $nombreDemandesCongeEnAttente }}</strong></li>
                        </ul>
                    </div>
                </div>
                <!-- History Div -->
                <div class="card border">
                    <div class="card-header">
                        History
                    </div>
                    <div class="card-body">
                        @if($userDemandesConges->isEmpty())
                            <p class="text-muted">No history available.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Numero demande:</th>
                                            <th>Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Nombre jours</th>
                                            <th>Status</th>
                                            <th>Etape</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userDemandesConges as $demande)
                                            <tr>
                                                <td>{{ $demande->ID ?? 'N/A' }}</td>
                                                <td>{{ $demande->type->NOM ?? 'N/A' }}</td>
                                                <td>{{ $demande->DATE_DEBUT->format('Y-m-d')}}</td>
                                                <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                                <td>{{ $demande->DATE_DEBUT->diffInDays($demande->DATE_FIN) + 1 }} days</td>
                                                <td>{{ $demande->statuts->last()->STATUT ?? 'Terminé' }}</td>
                                                <td>
                                                    @if($demande->latestEtape)
                                                        {{ $demande->latestEtape->NOM }}
                                                    @else
                                                        ----
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Second Column: Team Info and Calendar -->
            <div class="col-md-4">
                <!-- Team Info Div -->
                <div class="card mb-4 border">
                    <div class="card-header">
                        Team Leave Info
                    </div>
                    <div class="card-body">
                        @if($teamMembersOnLeave->isEmpty())
                            <p class="text-muted">No team members are currently on leave or have upcoming leave planned.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Type of Leave</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Jours restants</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teamMembersOnLeave as $member)
                                            @foreach($member->demandes as $demande)
                                                <tr>
                                                    <td>{{ $member->NOM }} {{ $member->PRENOM }}</td>
                                                    <td>{{ $demande->type->NOM ?? 'N/A' }}</td>
                                                    <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                                    <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if (now()->lte($demande->DATE_FIN))
                                                            {{ now()->diffInDays($demande->DATE_FIN) }} days
                                                        @else
                                                            0 days
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (now()->between($demande->DATE_DEBUT, $demande->DATE_FIN))
                                                            En cours
                                                        @else
                                                            Prévue
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Calendar Div -->
                <div class="card border">
                    <div class="card-header">
                        Calendar
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Third Column: Announcements -->
            <div class="col-md-4">
                <!-- Announcement Div -->
                <div class="card border">
                    <div class="card-header">
                        Announcements
                    </div>
                    <div class="card-body">
                        <!-- Dynamic content for Announcement Div -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .card-header {
        background-color: #f8f9fa;
        color: #333;
    }
    .card-body {
        padding: 20px; /* Adds padding inside each card body for content spacing */
        background-color: #ffffff;
    }
    #calendar {
        height:350px;
        width:390px;
        margin: 0 auto;
    }
    .fc-holiday {
        background-color: #9195F6 !important;
        color: white !important;
    }
    .fc-weekend {
        background-color: #B7C9F2 !important;
        color: white !important;
        position: relative; /* Ensure the custom title is positioned correctly */
    }
    .fc-weekend .weekend-title {
        position: absolute;
        bottom: 0;
        width: 100%;
        text-align: center;
        background-color: #B7C9F2;
        color: black;
        font-weight: bold;
        padding: 2px 0;
    }
    .fc-event {
        border: none !important; /* Remove default border */
        border-radius: 0 !important; /* Remove rounded corners */
        height: 20px !important; /* Adjust height as needed */
    }
</style>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
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
