<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getAllEvents()
    {
        $events = Event::with(['vendor', 'category', 'ticket.sku'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $events
        ], 200);
    }

    public function show(Request $request)
    {
        $event = Event::find($request->event_id);

        $event->load(['vendor', 'category']);
        $skus = $event->skus;
        $event['skus'] = $skus;

        return response()->json([
            'status' => 'success',
            'message' => 'Event details fetched Successfully',
            'data' => $event
        ], 200);
    }

    public function categories()
    {
        $categories = EventCategory::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Event Categories fetched Successfully',
            'data' => $categories
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'event_category_id' => 'required|exists:event_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $event = Event::create($request->all());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'event_category_id' => 'required|exists:event_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $event = Event::findOrFail($id);

        $event->update($request->all());

        if ($request->hasFile('image')) {
            if ($event->image) {
                $oldImagePath = public_path('images/events/' . $event->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event
        ], 200);

    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Event deleted successfully'
        ], 200);
    }
}
