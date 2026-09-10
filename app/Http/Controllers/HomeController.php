<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class HomeController extends Controller
{
    // Halaman Beranda: ringkasan statistik + tiket terbaru sesuai role
    public function index()
    {
        $user = auth()->user();
        $isTech = in_array($user->role, ['admin', 'technician']);

        if ($isTech) {
            $stats = [
                'total' => Ticket::count(),
                'open' => Ticket::where('status', 'open')->count(),
                'in_progress' => Ticket::where('status', 'in_progress')->count(),
                'done' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
                'unassigned' => Ticket::whereNull('technician_id')
                    ->whereIn('status', ['open', 'in_progress'])->count(),
            ];
            $recentTickets = Ticket::with(['category', 'user', 'technician'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $mine = Ticket::where('user_id', $user->id);
            $stats = [
                'total' => (clone $mine)->count(),
                'open' => (clone $mine)->where('status', 'open')->count(),
                'in_progress' => (clone $mine)->where('status', 'in_progress')->count(),
                'done' => (clone $mine)->whereIn('status', ['resolved', 'closed'])->count(),
            ];
            $recentTickets = Ticket::with(['category', 'technician'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('home', compact('stats', 'recentTickets', 'isTech'));
    }
}
