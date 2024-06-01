<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMcO5cG2z6pJ4VTxnp9jZm+lz1prnp4fKn4aFjc" crossorigin="anonymous">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Bienvenue sur le site de congé de l'entreprise E.P.A.L") }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="row">
            <!-- First Column: Information and History -->
            <div class="col-md-6">
                <!-- Information Div -->
                <div class="card mb-4 border">
                    <div class="card-header">
                        Informations:
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Information -->
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Employer:</strong> {{ $employe->NOM }}  {{ $employe->PRENOM }}</li>
                                    <li class="list-group-item"><strong>Matricule:</strong> {{ $employe->MATRICULE }}</li>
                                    <li class="list-group-item"><strong>Poste:</strong> {{ $employe->POSTE }}</li>
                                    <li class="list-group-item"><strong>Date d'embauche:</strong> {{ $employe->DATE_EMBAUCHE }}</li>
                                    <li class="list-group-item"><strong>Structure:</strong> {{ $employe->structure->NOM }}</li>
                                </ul>
                            </div>

                            <!-- Chart Container -->
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <div class="chart-container">
                                    <canvas id="leaveStatusChart"></canvas>
                                    <canvas id="leaveBalanceChart"></canvas>
                                </div>
                            </div>

                            <!-- Total Remaining Leave Balance -->
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush ml-4">
                                    <li class="list-group-item"><strong>Solde de congé total: {{ $totalSoldeCongeRestant }} Jrs</strong> </li>
                                    @foreach($soldeAnnee as $conge)
                                    <li class="list-group-item">Année: <strong>{{ $conge->ANNEE }}</strong>, Solde: <strong>{{ $conge->JOURS_RESTANT }}</strong> Jrs</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <!-- Total Leave Requests -->
                            <div class="col-6 mb-4">
                                <div class="d-flex align-items-center border p-3 rounded">
                                    <img src="{{ asset('images/totale.jpg') }}" alt="Total Requests" class="me-3" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $nombreDemandesCongeTotal }}</strong>
                                        <div>Demandes de congé au totale</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Approved Leave Requests -->
                            <div class="col-6 mb-4">
                                <div class="d-flex align-items-center border p-3 rounded">
                                    <img src="{{ asset('images/approved.jpg') }}" alt="Approved Requests" class="me-3" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $nombreDemandesCongeAccepte }}</strong>
                                        <div>Demandes de congé approuvé</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Denied Leave Requests -->
                            <div class="col-6 mb-4">
                                <div class="d-flex align-items-center border p-3 rounded">
                                    <img src="{{ asset('images/denied.jpg') }}" alt="Denied Requests" class="me-3" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $nombreDemandesCongeRefuse }}</strong>
                                        <div>Demandes de congé refusée</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Leave Requests -->
                            <div class="col-6 mb-4">
                                <div class="d-flex align-items-center border p-3 rounded">
                                    <img src="{{ asset('images/pending.jpg') }}" alt="Pending Requests" class="me-3" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $nombreDemandesCongeEnAttente }}</strong>
                                        <div>Demandes de congé en attente</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- History Div -->

                <div class="card border">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Historique des mes demandes:</span>
                        <a href="{{ route('statutsconges.index') }}" class="btn btn-primary btn-sm">Afficher tout</a>
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
                                            <th>Date debut</th>
                                            <th>Date fin</th>
                                            <th>Nombre jours</th>
                                            <th>Statut</th>
                                            <th>Etape</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userDemandesConges as $demande)
                                            <tr>
                                                <td>{{ $demande->ID ?? 'N/A' }}</td>
                                                <td>{{ $demande->type->NOM ?? 'N/A' }}</td>
                                                <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                                <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                                <td>{{ $demande->DATE_DEBUT->diffInDays($demande->DATE_FIN) + 1 }} days</td>
                                                <td>{{ $demande->latestStatut ?? 'Terminé' }}</td>
                                                <td>
                                                    @if($demande->latestStatut === 'Accepter')
                                                        Terminé
                                                    @else
                                                        {{ $demande->latestEtape ?? '----' }}
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
            <div class="col-md-6">
                <!-- Team Info Div -->
                @if($employe->ROLE_ID != 7)
                <div class="card mb-4 border">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Plan de congé de l'équipe:</span>
                        <a href="{{ route('statutsconges.index') }}" class="btn btn-primary btn-sm">Afficher tout</a>
                    </div>
                    <div class="card-body">
                        @php
                            $hasFutureLeaves = false;
                        @endphp

                        @foreach($teamMembersOnLeave as $member)
                            @foreach($member->demandes as $demande)
                                @if($demande->DATE_FIN->isFuture())
                                    @php
                                        $hasFutureLeaves = true;
                                    @endphp
                                @endif
                            @endforeach
                        @endforeach

                        @if(!$hasFutureLeaves)
                            <p class="text-muted">No team members are currently on leave or have upcoming leave planned.</p>
                        @else
                            @foreach($teamMembersOnLeave as $member)
                                @foreach($member->demandes as $demande)
                                    @if($demande->DATE_FIN->isFuture())
                                        <div class="row mb-3 pb-3 border-bottom">
                                            <div class="col-6">
                                                <div><strong>Employee:</strong> {{ $member->NOM }} {{ $member->PRENOM }}</div>
                                                <div><strong>Date debut:</strong> {{ $demande->DATE_DEBUT->format('Y-m-d') }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div><strong>Statut:</strong>
                                                    @if (now()->between($demande->DATE_DEBUT, $demande->DATE_FIN))
                                                        En cours
                                                        ({{ now()->diffInDays($demande->DATE_FIN) + 1 }} jours restants)
                                                    @else
                                                        Prévue
                                                    @endif

                                                </div>
                                                <div><strong>Date fin:</strong> {{ $demande->DATE_FIN->format('Y-m-d') }}</div>
                                                <div><strong>Congé:</strong> {{ $demande->type->NOM }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- demandes en attente --}}



                        <div class="card mb-4 border">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>demandes en attente:</span>
                                <a href="{{ route('listedemandes.index') }}" class="btn btn-primary btn-sm">Afficher tout</a>
                            </div>
                            <div class="card-body">
                                <p>Nombre de demandes en attente : {{ $count}} </p>
                            </div>
                        </div>

                 @endif

                 <div class="card mb-4 border">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Notifications:</span>
                    </div>
                    <div class="card-body">
                        @if ($employe->notifications)
                            @foreach ($employe->notifications as $notif )
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0">{{$notif->MESSAGE}} le {{$notif->DATE_ENVOIE}}</p>
                                </div>
                                <div>
                                    <form action="{{ route('notifications.destroy', $notif->ID) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 50px;">lu</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        @if ($employe->notifications->isEmpty() )
                            <p>vous n'avez pas de notification</p>
                        @endif


                    </div>
                </div>


                <!-- Calendar Div -->
                <div class="card border">
                    <div class="card-header">
                        Calendrier des vacances:
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
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
        height: 350px;
        width: 100%; /* Make the calendar fit the card width */
        margin: 0 auto;
    }
    .fc-holiday {
        background-color: #435161 !important;
        color: white !important;
    }
    .fc-weekend {
        background-color: #4A90E2 !important;
        color: white !important;
        position: relative; /* Ensure the custom title is positioned correctly */
    }
    .fc-weekend .weekend-title {
        position: absolute;
        bottom: 0;
        width: 100%;
        text-align: center;
        background-color: #4A90E2;
        color: black;
        font-weight: bold;
        padding: 2px 0;
    }
    .fc-event {
        border: none !important; /* Remove default border */
        border-radius: 0 !important; /* Remove rounded corners */
        height: 20px !important; /* Adjust height as needed */
    }
    .chart-container{
        width: 200px; /* Adjust as necessary */
    height: 200px; /
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
            title: "Jour ferié: " + holiday.DESIGNATION,
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

                titleEl.style.color = "white";
                let arrayOfDomNodes = [titleEl];
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

    //     document.addEventListener('DOMContentLoaded', function() {
    //     const ctx = document.getElementById('leaveBalanceChart').getContext('2d');
    //     const leaveBalanceChart = new Chart(ctx, {
    //         type: 'pie',
    //         data: {
    //             labels: ['Solde annuel restant', 'Solde annuel pris      '],
    //             datasets: [{
    //                 data: [{{ $soldeCongeRestant }}, {{ $joursCongePrisCetteAnnee }}],
    //                 backgroundColor: ['#4CAF50', '#FF6384'],
    //                 hoverBackgroundColor: ['#45A049', '#FF4384']
    //             }]
    //         },
    //         options: {
    //             responsive: true,
    //             maintainAspectRatio: false,
    //             plugins: {
    //                 legend: {
    //                     position: 'bottom',
    //                 }
    //             }
    //         }
    //     });
    // });

    document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('leaveStatusChart').getContext('2d');
    const leaveStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Acceptées', 'Refusées'],
            datasets: [{
                data: [{{$nombreDemandesCongeAccepte}}, {{$nombreDemandesCongeRefuse}}],
                backgroundColor: ['#4CAF50', '#FF6384'],
                hoverBackgroundColor: ['#45A049', '#FF4384']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>

