<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_methods = PaymentMethod::orderBy('name', 'asc')->get();
        return view('backend.payment_method.index', compact('payment_methods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.payment_method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'photo' => 'nullable|string',
                'status' => 'required|in:active,inactive'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Dump error validasi ke log
            \Log::error('Validation Error in PaymentMethodController@store:', $e->errors());
            // Tampilkan error ke browser untuk debugging
            dd([
                'validation_errors' => $e->errors(),
                'input_data' => $request->all(),
                'file_info' => $request->hasFile('photo') ? [
                    'name' => $request->file('photo')->getClientOriginalName(),
                    'mime' => $request->file('photo')->getMimeType(),
                    'size' => $request->file('photo')->getSize(),
                ] : 'No file uploaded'
            ]);
        }

        $data = $request->all();
        $data['code'] = Str::slug($data['name'], '-');

        // Handle image path from file manager
        if ($request->has('photo') && !empty($request->photo)) {
            $data['photo'] = $request->photo;
        } else {
            $data['photo'] = null;
        }

        PaymentMethod::create($data);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('backend.payment_method.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('backend.payment_method.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'photo' => 'nullable|string',
                'status' => 'required|in:active,inactive'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Dump error validasi ke log
            \Log::error('Validation Error in PaymentMethodController@update:', $e->errors());
            // Tampilkan error ke browser untuk debugging
            dd([
                'validation_errors' => $e->errors(),
                'input_data' => $request->all(),
                'file_info' => $request->hasFile('photo') ? [
                    'name' => $request->file('photo')->getClientOriginalName(),
                    'mime' => $request->file('photo')->getMimeType(),
                    'size' => $request->file('photo')->getSize(),
                ] : 'No file uploaded'
            ]);
        }

        $data = $request->all();
        $data['code'] = Str::slug($data['name'], '-');

        // Handle image path from file manager
        if ($request->has('photo') && !empty($request->photo)) {
            // Delete old image if exists and it's different from the new one
            if ($paymentMethod->photo && $paymentMethod->photo !== $request->photo && file_exists(public_path($paymentMethod->photo))) {
                unlink(public_path($paymentMethod->photo));
            }
            $data['photo'] = $request->photo;
        } else {
            // If no new image is provided, keep the old one
            $data['photo'] = $paymentMethod->photo;
        }

        $paymentMethod->update($data);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if the payment method is being used in orders
        if ($paymentMethod->orders()->count() > 0) {
            return redirect()->route('payment-methods.index')
                ->with('error', 'Tidak dapat menghapus metode pembayaran yang sudah digunakan dalam transaksi');
        }

        // Delete image if exists
        if ($paymentMethod->photo && file_exists(public_path($paymentMethod->photo))) {
            unlink(public_path($paymentMethod->photo));
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus');
    }
}
