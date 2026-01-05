<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Pobieramy zamówienia z relacjami (użytkownik i status), sortujemy od najnowszych
        $orders = Order::with(['user', 'status'])->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Szczegóły zamówienia + produkty + statusy do selecta
        $order->load(['items.product', 'user', 'status']);
        $statuses = OrderStatus::all();

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $order->update(['order_status_id' => $request->order_status_id]);

        return redirect()->back()->with('success', "Status zamówienia #{$order->id} został zmieniony.");
    }
}