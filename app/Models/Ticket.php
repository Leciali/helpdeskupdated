<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'company_email',
        'company_name',
        'asset_name',
        'asset_series',
        'description',
        'priority',
        'ticket_duration',
        'status',
        'start_date',
        'end_date',
        'due_date',
        'resolved_date'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'due_date',
        'resolved_date'
    ];

    // Tambahkan cast untuk memastikan ticket_duration selalu integer
    protected $casts = [
        'ticket_duration' => 'integer',
    ];

    /**
 * Generate unique ticket number
 */
    public static function generateTicketNumber()
    {
        $prefix = 'T - ';
    
        $lastTicket = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastTicket ? intval(substr($lastTicket->ticket_number, strlen($prefix))) : 0;
        $newNumber = str_pad($lastNumber + 1, 11, '0', STR_PAD_LEFT);
    
        return $prefix . $newNumber;
    }

    /**
     * Scope queries for different ticket statuses
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSolved($query)
    {
        return $query->where('status', 'solved');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    /**
     * Check and update ticket status
     */
    public function checkAndUpdateStatus()
    {
        $now = Carbon::now();
        $dueDate = Carbon::parse($this->due_date);

        // Jika tiket sudah melebihi batas waktu
        if (($this->status === 'pending' || $this->status === 'open') && $now->greaterThan($dueDate)) {
            $this->status = 'late';
            $this->save();
            Log::info('Ticket status auto-updated to late: ' . $this->ticket_number);
        }

        // Jika tiket diselesaikan
        if ($this->status === 'solved' && empty($this->resolved_date)) {
            $this->resolved_date = $now;
            $this->save();
            Log::info('Ticket resolved_date set for: ' . $this->ticket_number);
        }
    }

    /**
     * Calculate ticket lifecycle statistics
     */
    public static function getTicketStatistics($range = 7)
    {
        return [
            'total_tickets' => self::count(),
            'open_tickets' => self::open()->count(),
            'pending_tickets' => self::pending()->count(),
            'solved_tickets' => self::solved()->count(),
            'late_tickets' => self::late()->count(),
            'tickets_by_priority' => [
                'low' => self::where('priority', 'low')->count(),
                'medium' => self::where('priority', 'medium')->count(),
                'high' => self::where('priority', 'high')->count(),
                'critical' => self::where('priority', 'critical')->count(),
            ],
            'weekly_trend' => self::getWeeklyTicketTrend($range)
        ];
    }

    /**
     * Get weekly ticket trend
     */
    private static function getWeeklyTicketTrend($range = 7)
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays($range - 1);

        $trend = [];
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $trend[$dateString] = [
                'open' => self::whereDate('created_at', $dateString)->open()->count(),
                'pending' => self::where(function($q) use ($dateString) {
                    $q->whereDate('created_at', '<=', $dateString)
                      ->where(function($q2) use ($dateString) {
                          $q2->whereNull('resolved_date')->orWhereDate('resolved_date', '>', $dateString);
                      });
                })->where('status', 'pending')->count(),
                'in_progress' => self::where(function($q) use ($dateString) {
                    $q->whereDate('created_at', '<=', $dateString)
                      ->where(function($q2) use ($dateString) {
                          $q2->whereNull('resolved_date')->orWhereDate('resolved_date', '>', $dateString);
                      });
                })->where('status', 'in_progress')->count(),
                'solved' => self::whereDate('resolved_date', $dateString)->solved()->count()
            ];
        }

        return $trend;
    }
}