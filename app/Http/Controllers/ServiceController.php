<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('owner.services.index', [
            'services' => Service::orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('owner.services.form', ['service' => new Service]);
    }

    public function store(Request $request)
    {
        Service::create($this->validateData($request));

        return redirect()->route('owner.services.index')->with('status', 'Paket berhasil dibuat.');
    }

    public function edit(Service $service)
    {
        return view('owner.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validateData($request));

        return redirect()->route('owner.services.index')->with('status', 'Paket berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('status', 'Paket berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:600'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
