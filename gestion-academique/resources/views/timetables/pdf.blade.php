<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #fff;
            padding: 0;
            margin: 0;
        }
        
        .page {
            width: 291mm;
            height: 204mm;
            padding: 3mm;
            margin: 0;
            background: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /* En-tête */
        .header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 6px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            padding-top: 0px;
            min-height: 85px;
            gap: 40px;
            position: relative;
            background: white;
            flex-wrap: nowrap;
        }
        
        .header-left, .header-right {
            font-size: 27px;
            flex: 1;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        .header-left {
            text-align: left;
        }
        
        .header-right {
            text-align: right;
            margin-top: -160px;
            margin-left: -800px;
        }
        
        .logo-img {
            position: absolute;
            height: 100px;
            width: auto;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
        }
        
        .header-text {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.0;
            position: relative;
            z-index: 1;
        }
        
        .university {
            font-size: 20px;
            margin: 1px 0;
            position: relative;
            z-index: 1;
        }
        
        .department {
            font-size: 18px;
            color: #666;
            position: relative;
            z-index: 1;
        }
        
        /* Titre principal */
        .title-section {
            text-align: center;
            margin-bottom: 6px;
        }
        
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #1a3a52;
            margin-bottom: 1px;
        }
        
        .subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #1a3a52;
            margin-bottom: 2px;
        }
        
        .info-line {
            font-size: 9px;
            color: #666;
            margin: 0px 0;
        }
        
        /* Infos principales */
        .info-block {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 5px;
            margin-bottom: 6px;
            margin-left: 20px;
            margin-right: 20px;
            font-size: 11px;
            padding: 4px 0px;
            background-color: transparent;
            border-radius: 2px;
        }
        
        .info-left {
            text-align: left;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-right {
            text-align: right;
            flex: 1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #1a3a52;
            display: inline;
            font-size: 11px;
        }
        
        /* Salles */
        .rooms-section {
            margin-bottom: 0px;
            text-align: right;
        }
        
        .rooms-title {
            font-size: 9px;
            font-weight: bold;
            color: #1a3a52;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .rooms-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            font-size: 8px;
            justify-content: center;
        }
        
        .room-badge {
            background-color: #2c5aa0;
            color: white;
            padding: 3px 8px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 9px;
        }
        
        /* Tableau */
        .timetable {
            width: 95%;
            border-collapse: collapse;
            margin: 0 auto;
            font-size: 15px;
            table-layout: fixed;
            flex-grow: 1;
        }
        
        .timetable th, .timetable td {
            border: 1px solid #000;
            padding: 3px 1px;
            text-align: center;
            vertical-align: middle;
            height: 100px;
        }
        
        .timetable th {
            background-color: #1a3a52;
            color: white;
            font-weight: bold;
            padding: 6px 3px;
            height: auto;
            font-size: 15px;
        }
        
        .time-col {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 60px;
            height: auto;
            font-size: 15px;
        }
        
        .day-header {
            background-color: #2c5aa0;
            color: white;
            font-weight: bold;
        }
        
        .day-cell {
            background-color: #fff;
            padding: 2px;
            position: relative;
        }
        
        .session-item {
            background-color: transparent;
            border: none;
            padding: 2px;
            margin: 1px 0;
            border-radius: 1px;
            font-size: 15px;
            line-height: 1.1;
        }
        
        .session-code {
            font-weight: bold;
            color: #1a3a52;
            display: block;
            font-size: 15px;
        }
        
        .session-group {
            color: #1a3a52;
            font-size: 15px;
            display: block;
            font-weight: bold;
        }
        
        .session-teacher {
            color: #d32f2f;
            font-size: 15px;
            margin-top: 1px;
            display: block;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin-top: 6px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 4px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                margin: 0;
                padding: 5mm;
                page-break-after: avoid;
                page-break-inside: avoid;
                box-shadow: none;
            }
            .timetable {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- En-tête -->
        <div class="header">
            <img src="{{ public_path('images/Blason_uy1.png') }}" alt="Logo UY1" class="logo-img">
            
            <div class="header-left">
                <div class="header-text">REPUBLIQUE DU CAMEROUN</div>
                <div class="university">Paix - Travail - Patrie</div>
                <div style="margin-top: 4px;"></div>
                <div class="header-text">UNIVERSITÉ DE YAOUNDÉ I</div>
                <div class="university">Faculté des Sciences</div>
                <div class="department">Département d'Informatique</div>
            </div>
            
            <div class="header-right">
                <div class="header-text">REPUBLIC OF CAMEROON</div>
                <div class="university">Peace - Work - Fatherland</div>
                <div style="margin-top: 4px;"></div>
                <div class="header-text">UNIVERSITY OF YAOUNDÉ I</div>
                <div class="university">Faculty of Science</div>
                <div class="department">Department of Computer Science</div>
            </div>
        </div>
        
        <!-- Titre principal -->
        <div class="title-section">
            <div class="title">EMPLOI DU TEMPS</div>
            <div class="subtitle">TIME TABLE</div>
            <div class="info-line">SEMESTRE 1 - ANNÉE ACADÉMIQUE {{ $year }}-{{ $year + 1 }}</div>
            <div class="info-line">SEMESTER 1 - {{ $year }}-{{ $year + 1 }} ACADEMIC YEAR</div>
        </div>
        
        <!-- Infos filière/niveau et salles sur une seule ligne -->
        <div class="info-block">
            <div class="info-left">
                @if($filiere || $groupe)
                    <span class="info-label">FILIÈRE :</span>
                    @if($filiere)
                        <span style="font-size: 11px;">{{ $filiere->nom }}</span>
                    @endif
                    @if($groupe)
                        <span style="font-size: 11px;"> / NIVEAU : {{ $groupe->nom }}</span>
                    @endif
                @endif
            </div>
            
            <div class="info-right">
                @if($usedRooms->count() > 0)
                    <span class="info-label">SALLE :</span>
                    @foreach($usedRooms as $room)
                        <span class="room-badge">{{ $room->numero }}</span>
                    @endforeach
                @endif
            </div>
        </div>
        
        <!-- Tableau horaire -->
        <table class="timetable">
            <thead>
                <tr>
                    <th class="time-col">Horaire</th>
                    @foreach($days as $dayName)
                        <th class="day-header">{{ $dayName }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($timeSlots as $slotKey => $slot)
                    <tr>
                        <td class="time-col">{{ $slotKey }}</td>
                        @foreach($days as $dayName)
                            <td class="day-cell">
                                @if(isset($timetableGrid[$dayName][$slotKey]) && $timetableGrid[$dayName][$slotKey]->count() > 0)
                                    @foreach($timetableGrid[$dayName][$slotKey] as $session)
                                        <div class="session-item">
                                            <span class="session-code">{{ $session->ue->code }}</span>
                                            @if($session->group_divisions)
                                                <span class="session-group">{{ $session->group_divisions }}</span>
                                            @endif
                                            <span class="session-teacher">
                                                {{ $session->enseignant ? substr($session->enseignant->first_name, 0, 1) . '. ' . $session->enseignant->last_name : 'N/A' }}
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="color: #eee; font-size: 7px;">-</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Footer -->
        <div class="footer">
            <strong>LE DOYEN / THE DEAN</strong>
        </div>
    </div>
</body>
</html>
