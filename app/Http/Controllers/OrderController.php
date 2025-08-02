<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request, Product $product)
    {

        $user = Auth::user();
        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_price' => 0]
        );


        $existingItem = OrderItem::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += 1;
            $existingItem->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        $order->total_price += $product->price;
        $order->save();

        return response()->json(['message' => 'Product added to order.', 'order' => $order->load('orderItems')], 200);
    }

    public function showOrder()
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with(['orderItems.product', 'table'])
            ->first();

        if (!$order) {
            return response()->json(['message' => 'No pending order found.'], 404);
        }

        return response()->json(['order' => $order], 200);
    }

    public function showTableSelection()
    {
        $tables = Table::where('is_available', true)->get();
        return response()->json(['tables' => $tables]);
    }

    public function chooseTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required|exists:tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'pending'],
            ['total_price' => 0]
        );

        $order->table_id = $request->table_id;
        $order->save();

        return response()->json(['message' => 'Table selected.', 'order' => $order], 200);
    }

    public function removeItem($itemId)
    {

        try {
            $orderItem = OrderItem::findOrFail($itemId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Item not found.'], 404);
        }

        if ($orderItem->order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }
        elseif($orderItem -> quantity > 1){
          $orderItem -> quantity -=1;
            $orderItem->save();
            return response()->json(['message' => 'Item -1'], 200);
        }
        else{
            $orderItem->delete();
        }
        return response()->json(['message' => 'Item removed from order.'], 200);
    }

    public function submitOrder()
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return response()->json(['error' => 'No pending order found.'], 404);
        }

        if (!$order->table_id) {
            return response()->json(['error' => 'Table not selected.'], 400);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Order placed successfully.', 'order' => $order], 200);
    }

    public function clearOrder()
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $order->orderItems()->delete();
            $order->delete();
        }

        return response()->json(['message' => 'Order cleared.'], 200);
    }
    public function adminIndex()
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders], 200);
    }

    public function index()
    {
        $orders = Order::with(['user', 'table', 'orderItems.product'])->get();
        return response()->json(['orders' => $orders], 200);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'table', 'orderItems.product'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order], 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully.'], 200);
    }
}
