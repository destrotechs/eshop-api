<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderShipped;
use App\Models\OrderItem;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Services\Cart;
use App\Http\Resources\OrderResource;

class ordersController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = $request->user();

    // Check if the user is an admin
    if ($user->hasRole('admin')) {
        // Fetch all orders for admins, ordered by latest first (descending order)
        $orders = OrderResource::collection(Order::orderBy('created_at', 'desc')->get());
        return $this->success($orders, 'Orders fetched successfully',null);
    } else {
        // Fetch orders for the current user (non-admins), ordered by latest first (descending order)
        $orders = OrderResource::collection(Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get());
        return $this->success($orders, 'Orders fetched successfully',null);
    }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'payment_mode'=>'required',
            'address'=>'required',
            'cart'=>'required',
        ]);
        $cart = new Cart('shopping_cart',null,$user);
        $items = $cart->fetchCart();
        if (count($items)>0){
            $order_number =  date('Ymd_His') . '_' . uniqid();
            $user_id = $user->id;
            $address_id = $request->address;

            $order = new Order();
            $order->order_number = $order_number;
            $order->address_id = $address_id;
            $order->user_id = $user_id;
            $order->status = 'Created';
            $order->payment_mode_id = $request->payment_mode;
            $order->total_cost= $request->cart['total'];
            $order->vat= $request->cart['tax'];
            $order->discount= $request->cart['discount'];
            $order->save();
            $order_items = new OrderItem();
            $order_items->items = json_encode($request->cart['items']);

            if($order->items()->save($order_items)){
                $cart->clearCart();
                return $this->success(new OrderResource($order),"Order added successfully","Order added successfully");
            }
        }
             
        return $this->error(null,"Order could not be created, try again");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return $this->success(new OrderResource($order),"Order fetched successfuly");
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $orderId)
    {
        // Find the order
        $order = Order::findOrFail($orderId);

        // Check if the current status is not already 'Confirmed'
        
            // Update the order status
            $order->status = $request->status;
            $order->save();
        if ($order->status=='Confirmed') {
            // Notify the user (who placed the order) about the status update
            $user = $order->user;  // Assuming you have a 'user' relationship on the Order model
            $user->notify(new OrderConfirmed($order)); // Send the notification

            return $this->success(null, 'Order status updated to confirmed, and user notified.','Order status updated to confirmed, and user notified.');
        }
        if ($order->status=='Shipped') {
            // Notify the user (who placed the order) about the status update
            $user = $order->user;  // Assuming you have a 'user' relationship on the Order model
            $user->notify(new OrderShipped($order)); // Send the notification

            return $this->success(null, 'Order status updated to Shipped, and user notified.','Order status updated to Shipped, and user notified.');
        }

        return $this->success($order, 'Order status has been changed successfully.','Order status has been changed successfully.', 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
