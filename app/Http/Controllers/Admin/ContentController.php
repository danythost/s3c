<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Announcement;
use App\Models\CommunicationLog;

class ContentController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.content.index', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        Announcement::create($request->all());

        return back()->with('success', 'Announcement created successfully.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $announcement->update($request->all());

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function toggleAnnouncement(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', 'Announcement status updated.');
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    public function logs(Request $request)
    {
        $query = CommunicationLog::with('user')->latest();

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('recipient', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        return view('admin.content.logs', compact('logs'));
    }
}
