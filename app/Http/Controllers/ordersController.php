<?php

namespace App\Http\Controllers;
use App\Models\Order;
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
    public function index()
    {
        $orders = OrderResource::collection(Order::all());
        return $this->success($orders,'Orders fetched successfully');
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
            $order->total_cost= $request->cart['total'];
            $order->vat= $request->cart['tax'];
            $order->discount= $request->cart['discount'];
            $order->save();
            $order_items = new OrderItem();
            $order_items->items = json_encode($request->cart['items']);

            if($order->items()->save($order_items)){
                $cart->clearCart();
                return $this->success(new OrderResource($order),"Order added successfully");
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
