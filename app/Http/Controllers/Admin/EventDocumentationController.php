<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventDocumentationRequest;
use App\Models\Event;
use App\Models\EventDocumentation;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;

class EventDocumentationController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function store(EventDocumentationRequest $request, Event $event): RedirectResponse
    {
        foreach ($request->file('photos') as $photo) {
            $path = $this->imageUploadService->upload(
                $photo,
                folder: 'events/documentations',
                maxWidth: 1200
            );

            $event->documentations()->create([
                'photo' => $path,
                'caption' => $request->validated('caption'),
            ]);
        }

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('success', 'Foto dokumentasi berhasil ditambahkan.');
    }

    public function destroy(Event $event, EventDocumentation $documentation): RedirectResponse
    {
        $this->imageUploadService->delete($documentation->photo);
        $documentation->delete();

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('success', 'Foto dokumentasi berhasil dihapus.');
    }
}
