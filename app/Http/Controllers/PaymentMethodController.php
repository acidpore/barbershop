<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:payment_methods,name'],
        ]);

        PaymentMethod::create($data);

        return back()->with('status', 'Metode pembayaran ditambahkan.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('payment_methods', 'name')->ignore($paymentMethod)],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $paymentMethod->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Metode pembayaran diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return back()->with('status', 'Metode pembayaran dihapus.');
    }
}
