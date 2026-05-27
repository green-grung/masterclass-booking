<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Craft;
use App\Models\MasterClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MasterClassController extends Controller
{
    public function create()
    {
        $crafts = Craft::all();
        $timeSlots = $this->getTimeSlots();
        $occupiedSlots = $this->getOccupiedSlotsForCurrentMaster();

        return view('master-class.form', [
            'masterClass' => null,
            'crafts' => $crafts,
            'timeSlots' => $timeSlots,
            'occupiedSlots' => $occupiedSlots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'craft_id' => 'required|exists:crafts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => ['required', Rule::in(['9-11', '11-13', '13-15', '15-17'])],
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Проверка занятости слота у этого ведущего
        $exists = MasterClass::where('master_id', Auth::id())
            ->whereDate('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['time_slot' => 'На это время у вас уже запланирован мастер-класс.'])->withInput();
        }

        $validated['master_id'] = Auth::id();
        MasterClass::create($validated);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс успешно создан.');
    }

    public function edit(MasterClass $masterClass)
    {
        $this->authorizeOwner($masterClass);

        $crafts = Craft::all();
        $timeSlots = $this->getTimeSlots();
        $occupiedSlots = $this->getOccupiedSlotsForCurrentMaster($masterClass->id);

        return view('master-class.form', [
            'masterClass' => $masterClass,
            'crafts' => $crafts,
            'timeSlots' => $timeSlots,
            'occupiedSlots' => $occupiedSlots,
        ]);
    }

    public function update(Request $request, MasterClass $masterClass)
    {
        $this->authorizeOwner($masterClass);

        $validated = $request->validate([
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $masterClass->update($validated);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс обновлён.');
    }

    public function participants(MasterClass $masterClass)
    {
        $this->authorizeOwner($masterClass);

        $participants = $masterClass->registrations()
            ->with('user')
            ->where('status', 'confirmed')
            ->get();

        return view('master-class.participants', compact('masterClass', 'participants'));
    }

    private function getTimeSlots(): array
    {
        return [
            '9-11' => '9:00 - 11:00',
            '11-13' => '11:00 - 13:00',
            '13-15' => '13:00 - 15:00',
            '15-17' => '15:00 - 17:00',
        ];
    }

    private function getOccupiedSlotsForCurrentMaster(?int $ignoreId = null): array
    {
        $query = MasterClass::where('master_id', Auth::id());
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        $occupied = $query->get(['date', 'time_slot']);
  
        $occupiedSlots = [];
        foreach ($occupied as $oc) {
            $occupiedSlots[$oc->date->format('Y-m-d')][] = $oc->time_slot;
        }
        return $occupiedSlots;
    }

    private function authorizeOwner(MasterClass $masterClass): void
    {
        if ($masterClass->master_id !== Auth::id()) {
            abort(403);
        }
    }
}
