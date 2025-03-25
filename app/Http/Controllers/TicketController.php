<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan daftar tiket
     */
    public function dashboardView()
    {
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->paginate(10);
        
        $statistics = Ticket::getTicketStatistics();
        
        return view('indexDashboard', compact('tickets', 'statistics'));
    }

    /**
     * Menampilkan daftar tiket terbuka
     */
    public function openTicketView()
    {
        $tickets = Ticket::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('indexOpenTicket', compact('tickets'));
    }

    /**
     * Menampilkan daftar tiket pending
     */
    public function pendingTicketView()
    {
        $tickets = Ticket::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('indexPendingTicket', compact('tickets'));
    }

    /**
     * Menampilkan daftar tiket terselesaikan
     */
    public function solvedTicketView()
    {
        $tickets = Ticket::where('status', 'solved')
            ->orderBy('resolved_date', 'desc')
            ->paginate(10);
        
        return view('indexSolvedTicket', compact('tickets'));
    }

    /**
     * Menampilkan halaman laporan
     */
    public function reportView()
    {
        $statistics = Ticket::getTicketStatistics();
        
        return view('indexReport', compact('statistics'));
    }

    /**
     * Menampilkan form pembuatan tiket baru
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Menyimpan tiket baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_email' => 'required|email',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'asset_name' => 'nullable|string|max:255',
            'asset_series' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'ticket_duration' => 'required|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ticket = new Ticket();
        $ticket->ticket_number = Ticket::generateTicketNumber();
        $ticket->company_email = $request->input('company_email');
        $ticket->company_name = $request->input('company_name');
        $ticket->description = $request->input('description');
        $ticket->asset_name = $request->input('asset_name');
        $ticket->asset_series = $request->input('asset_series');
        $ticket->priority = $request->input('priority');
        $ticket->ticket_duration = $request->input('ticket_duration');
        $ticket->status = 'open';
        $ticket->start_date = Carbon::now();
        $ticket->due_date = Carbon::now()->addDays($request->input('ticket_duration'));
        $ticket->save();

        return redirect()->route('user.dashboard')
            ->with('success', 'Tiket berhasil dibuat');
    }

    /**
     * Menampilkan detail tiket
     */
    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }

    /**
     * Memperbarui status tiket
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,pending,in_progress,solved,late,closed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $ticket->status = $request->input('status');

        // Jika status diubah menjadi in_progress
        if ($ticket->status === 'in_progress') {
            $ticket->start_date = Carbon::now();
        }

        // Jika status diubah menjadi solved
        if ($ticket->status === 'solved') {
            $ticket->end_date = Carbon::now();
            $ticket->resolved_date = Carbon::now();
        }

        $ticket->save();

        return redirect()->back()
            ->with('success', 'Status tiket berhasil diperbarui');
    }

    /**
     * Menjalankan pengecekan dan pembaruan status tiket secara otomatis
     */
    public function checkTicketStatuses()
    {
        $tickets = Ticket::whereIn('status', ['open', 'pending', 'in_progress'])->get();
        
        foreach ($tickets as $ticket) {
            $ticket->checkAndUpdateStatus();
        }

        return response()->json([
            'message' => 'Ticket statuses updated',
            'updated' => $tickets->count()
        ]);
    }
}