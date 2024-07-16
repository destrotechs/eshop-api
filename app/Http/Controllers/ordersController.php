<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
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
        $request->validate([
            'user_id'=>'required',
            'address_id'=>'required',
        ]);
        $cart = \Cart::session($request->user_id)->getContent();
        $order_number =  date('Ymd_His') . '_' . uniqid();
        $user_id = $request->user_id;
        $address_id = $request->address_id;

        $order = new Order();
        $order->order_number = $order_number;
        $order->address_id = $address_id;
        $order->user_id = $user_id;
        $order->save();
        $order_items = new OrderItem();
        $order_items->items = json_encode($cart);

        if($order->items()->save($order_items)){

            return $this->success(new OrderResource($order),"Order added successfully");
        }
        return $this->error(null,"Order could not be created, try again");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
