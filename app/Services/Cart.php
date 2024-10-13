<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Cart
{
    protected $cartType;
    protected $cart;
    protected $taxRate;
    protected $discount;
    protected $coupon;
    protected $discountRate;
    protected $userId;

    public function __construct($cartType = 'shopping_cart', $taxRate = 0.15, $user)
    {
        // Initialize cart type
        $this->cartType = $cartType;
        $this->userId = $user->id;
        $this->taxRate = 16;
        $this->discount = 0;
        $this->coupon = null;
        $this->discountRate = 0;

        // Fetch the cart
        $this->cart = DB::table($this->getTable())->where('user_id', $this->userId)->first();
    }

    protected function getTable()
    {
        return $this->cartType === 'wishlist' ? 'wishlists' : 'shopping_carts';
    }

    public function addToCart($product, $quantity = 1, $replaceQuantity = false)
{
    $existingCart = DB::table($this->getTable())->where('user_id', $this->userId)->first();

    if ($existingCart) {
        $cartContent = json_decode($existingCart->content, true);

        if (isset($cartContent[$product->id])) {
            if ($replaceQuantity) {
                $cartContent[$product->id]['quantity'] = $quantity;
            } else {
                $cartContent[$product->id]['quantity'] += $quantity;
            }
            $cartContent[$product->id]['total'] = $cartContent[$product->id]['price']* $cartContent[$product->id]['quantity'];
        } else {
            $cartContent[$product->id] = [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price,
                'discount' => $product->discount,  // Add product discount field
                'total' => $product->price * $quantity
            ];
        }

        DB::table($this->getTable())->where('user_id', $this->userId)->update(['content' => json_encode($cartContent)]);
    } else {
        $cartContent = [
            $product->id => [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price,
                'discount' => $product->discount,  // Add product discount field
                'total' => $product->price * $quantity 
            ]
        ];

        DB::table($this->getTable())->insert([
            'user_id' => $this->userId,
            'content' => json_encode($cartContent)
        ]);
    }
}

protected function calculateProductTotal($price, $discount, $quantity)
{
    // Apply the product's own discount (percentage)
    $discountAmount = $price * ($discount / 100);
    $priceAfterDiscount = $price - $discountAmount;

    // Calculate total price for the product based on the discounted price and quantity
    return $priceAfterDiscount * $quantity;
}

    public function removeFromCart($productId)
    {
        $existingCart = DB::table($this->getTable())->where('user_id', $this->userId)->first();

        if ($existingCart) {
            $cartContent = json_decode($existingCart->content, true);

            if (isset($cartContent[$productId])) {
                unset($cartContent[$productId]);
                DB::table($this->getTable())->where('user_id', $this->userId)->update(['content' => json_encode($cartContent)]);
            }
        }
    }

    public function updateCart($productId, $quantity)
    {
        $existingCart = DB::table($this->getTable())->where('user_id', $this->userId)->first();

        if ($existingCart) {
            $cartContent = json_decode($existingCart->content, true);

            if (isset($cartContent[$productId])) {
                $cartContent[$productId]['quantity'] = $quantity;
                $cartContent[$productId]['total'] = $cartContent[$productId]['price'] * $quantity;

                DB::table($this->getTable())->where('user_id', $this->userId)->update(['content' => json_encode($cartContent)]);
            }
        }
    }

    public function clearCart()
    {
        DB::table($this->getTable())->where('user_id', $this->userId)->delete();
    }

    public function fetchCart()
    {
        $existingCart = DB::table($this->getTable())->where('user_id', $this->userId)->first();

        if ($existingCart) {
            return json_decode($existingCart->content, true);
        }

        return [];
    }

    public function applyCoupon($couponCode, $discountRate)
    {
        $this->coupon = $couponCode;
        $this->discountRate = $discountRate;
    }

    public function calculateDiscount()
{
    $cartContent = $this->fetchCart();
    $productDiscountTotal = 0;

    // Calculate the total discount from per-item discounts
    foreach ($cartContent as $item) {
        $originalPrice = $item['price'];
        $discount = $item['discount']; // Product-specific discount percentage
        $quantity = $item['quantity'];

        // Calculate the discount amount per item and multiply by quantity
        $discountAmountPerItem = $originalPrice * ($discount / 100);
        $totalDiscountForItem = $discountAmountPerItem * $quantity;

        // Sum up the discount for each product
        $productDiscountTotal += $totalDiscountForItem;
    }

    // Calculate the cart-wide (wholesome) discount via coupon
    $subtotalAfterProductDiscounts = $this->getSubtotal() - $productDiscountTotal;
    $couponDiscount = $subtotalAfterProductDiscounts * ($this->discountRate / 100);

    // Total discount is the sum of product-specific discounts and coupon discount
    $totalDiscount = $productDiscountTotal + $couponDiscount;

    return $totalDiscount;
}

    public function calculateTax()
    {
        $subtotal = $this->getSubtotal() - $this->calculateDiscount();
        $tax = $subtotal * ($this->taxRate/100);

        return $tax;
    }

    public function getSubtotal()
    {
        $subtotal = 0;
        foreach ($this->fetchCart() as $item) {
            $subtotal += $item['total'];
        }

        return $subtotal;
    }

    public function getTotal()
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->calculateDiscount();
        $tax = $this->calculateTax();

        return $subtotal - $discount + $tax;
    }

    public function getCartSummary()
    {
        return [
            'subtotal' => $this->getSubtotal(),
            'discount' => $this->calculateDiscount(),
            'tax' => $this->calculateTax(),
            'total' => $this->getTotal(),
            'items' => $this->fetchCart(),
            'coupon' => $this->coupon
        ];
    }
}
