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
        $this->taxRate = $taxRate;
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
        // Fetch the existing cart
        $existingCart = DB::table($this->getTable())->where('user_id', $this->userId)->first();

        if ($existingCart) {
            $cartContent = json_decode($existingCart->content, true);

            if (isset($cartContent[$product->id])) {
                if ($replaceQuantity) {
                    $cartContent[$product->id]['quantity'] = $quantity;
                } else {
                    $cartContent[$product->id]['quantity'] += $quantity;
                }
                $cartContent[$product->id]['total'] = $cartContent[$product->id]['price'] * $cartContent[$product->id]['quantity'];
            } else {
                $cartContent[$product->id] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
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
                    'total' => $product->price * $quantity
                ]
            ];

            DB::table($this->getTable())->insert([
                'user_id' => $this->userId,
                'content' => json_encode($cartContent)
            ]);
        }
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
        $subtotal = $this->getSubtotal();
        $this->discount = $subtotal * ($this->discountRate / 100);

        return $this->discount;
    }

    public function calculateTax()
    {
        $subtotal = $this->getSubtotal() - $this->calculateDiscount();
        $tax = $subtotal * $this->taxRate;

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
