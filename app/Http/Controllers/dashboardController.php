<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Traits\HttpResponses;
use App\Models\subcategory;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;

class dashboardController extends Controller
{
    use HttpResponses;

    public function getDashboardData()
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();
        $startOfMonth = $today->copy()->startOfMonth(); // 2024-11-01 00:00:00
        $endOfMonth = $today->copy()->endOfMonth();

        // Number of confirmed orders
        $confirmedOrders = Order::where('status', 'confirmed')->count();

        // Total weekly sales
        $weeklySales = Payment::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        // Inventory or stock status
        $stockStatus = Stock::select('product_id', 'quantity_added')
            ->with('product:id,common_name')
            ->get();

        // New customers (weekly registration)
        $newCustomers = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $orders  = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $products = [];
        $soldProducts = [];
        foreach($orders as $order){
            $orderItems = OrderItem::where('order_id', $order->id)->first();
            // dd($orderItems);
            foreach($orderItems as $orderItem){
                $ordered_items = json_decode($orderItems['items'],true);
                foreach($ordered_items as $item){
                    if(!in_array($item['product']['id'],$products) ){
                        array_push($products,$item['product']['id']);
                        array_push($soldProducts,array("product_id"=>$item['product']['id'],"quantity"=>$item['quantity'],'product'=>$item['product']));
                    }else{
                        $soldProducts[array_search($item['product']['id'],$products)]['quantity'] += $item['quantity'];
                    }
                }

            }
        }

        $quantities = array_column($soldProducts,'quantity');
         array_multisort($quantities,SORT_DESC,$soldProducts);
        // Transaction (Total sales by month)
        $monthlySales = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_sales')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Return all data as a single response
        return $this->success([
            'confirmedOrders' => $confirmedOrders,
            'weeklySales' => $weeklySales,
            'stockStatus' => $stockStatus,
            'newCustomers' => $newCustomers,
            'topSellingProducts' => $soldProducts,
            'monthlySales' => $monthlySales,
        ]);
    }
    public function getTopSellingProducts(Request $request){
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();
        $startOfMonth = $today->copy()->startOfMonth(); // 2024-11-01 00:00:00
        $endOfMonth = $today->copy()->endOfMonth();

        $orders  = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $products = [];
        $soldProducts = [];
        foreach($orders as $order){
            $orderItems = OrderItem::where('order_id', $order->id)->first();
            // dd($orderItems);
            foreach($orderItems as $orderItem){
                $ordered_items = json_decode($orderItems['items'],true);
                foreach($ordered_items as $item){
                    if(!in_array($item['product']['id'],$products) ){
                        array_push($products,$item['product']['id']);
                        array_push($soldProducts,array("product_id"=>$item['product']['id'],"quantity"=>$item['quantity'],'product'=>new ProductsResource(Product::find($item['product']['id']))));
                    }else{
                        $soldProducts[array_search($item['product']['id'],$products)]['quantity'] += $item['quantity'];
                    }
                }

            }
        }

        $quantities = array_column($soldProducts,'quantity');
         array_multisort($quantities,SORT_DESC,$soldProducts);
         return $this->success($soldProducts,'Top Selling Products');
    }

}
