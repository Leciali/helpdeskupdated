<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan daftar tiket
     */
    public function dashboardView()
    {
        $sort = request('sort', 'created_at');
        $order = request('order', 'desc');
        $allowedSorts = ['ticket_number', 'company_name', 'description', 'status', 'priority', 'due_date', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }
        $tickets = Ticket::orderBy($sort, $order)->paginate(10);
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
        
        // Log untuk debugging
        Log::info('Open Tickets View - Tickets Count: ' . $tickets->count());
        
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
        
        // Log untuk debugging
        Log::info('Pending Tickets View - Tickets Count: ' . $tickets->count());
        
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
        
        // Log untuk debugging
        Log::info('Solved Tickets View - Tickets Count: ' . $tickets->count());
        
        return view('indexSolvedTicket', compact('tickets'));
    }

    /**
     * Menampilkan halaman laporan
     */
    public function reportView(Request $request)
    {
        $range = $request->input('range', 7); // default 7 hari
        $statistics = Ticket::getTicketStatistics($range);
        $recentTickets = Ticket::orderBy('created_at', 'desc')->take(10)->get();
        return view('indexReport', compact('statistics', 'recentTickets', 'range'));
    }

    /**
     * Menampilkan form pembuatan tiket baru
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Menyimpan tiket baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_email' => 'required|email',
            'company_name' => 'required|string|max:255',
            'asset_name' => 'required|string|max:255',
            'asset_series' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'ticket_duration' => 'required|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Konversi ticket_duration ke integer untuk memastikan
            $ticketDuration = (int)$request->input('ticket_duration');

            $ticket = new Ticket();
            $ticket->ticket_number = Ticket::generateTicketNumber();
            $ticket->company_email = $request->input('company_email');
            $ticket->company_name = $request->input('company_name');
            $ticket->description = $request->input('description');
            $ticket->asset_name = $request->input('asset_name');
            $ticket->asset_series = $request->input('asset_series');
            $ticket->priority = $request->input('priority');
            $ticket->ticket_duration = $ticketDuration;
            $ticket->status = 'open';
            $ticket->start_date = Carbon::now();
            $ticket->due_date = Carbon::now()->addDays($ticketDuration);
            $ticket->save();

            // Log untuk debugging
            Log::info('Ticket Created: ' . $ticket->ticket_number . ' with status: ' . $ticket->status);

            return redirect()->route('user.dashboard')
                ->with('success', 'Tiket berhasil dibuat');
        } catch (\Exception $e) {
            // Log error jika terjadi
            Log::error('Error creating ticket: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
 * Menampilkan detail tiket
 */
public function show(Ticket $ticket)
{
    // Logging untuk debugging
    Log::info('Showing ticket details for: ' . $ticket->ticket_number);
    
    // Return ticket data sebagai JSON
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
        
        // Log untuk debugging
        Log::info('Ticket Status Updated: ' . $ticket->ticket_number . ' to status: ' . $ticket->status);

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

    public function ExportPdf()
    {
        $tickets = Ticket::all();
        // return view('export.DataTicket', compact('tickets'));
        $timestamp = Carbon::now()->format('Ymd_His');
        $pdf = Pdf::loadView('export.DataTicket',  compact('tickets'));
        return $pdf->download('laporan-pengguna-' . $timestamp . '.pdf');
    }
    
    public function ExportExcel()
    {
        $tickets = Ticket::all();
        $timestamp = Carbon::now()->format('Ymd_His');
        return Excel::download(new TicketExport, 'laporan-pengguna-' . $timestamp . '.xlsx');

    }

    /**
     * Menghapus tiket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil dihapus.');
    }
}