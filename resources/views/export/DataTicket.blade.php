<!DOCTYPE html>
<html>
<head>
    <title> Data Ticket </title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2> Data Ticket </h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Tiket</th>
                <th>Perusahaan</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Durasi (hari)</th>
                <th>Start</th>
                <th>Due</th>
                <th>Resolve Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $i => $ticket)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->company_name }}</td>
                <td>{{ ucfirst($ticket->priority) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                <td>{{ $ticket->ticket_duration }}</td>
                <td>{{ $ticket->start_date ? \Carbon\Carbon::parse($ticket->start_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $ticket->due_date ? \Carbon\Carbon::parse($ticket->due_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $ticket->resolved_date ? \Carbon\Carbon::parse($ticket->resolve_date)->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
