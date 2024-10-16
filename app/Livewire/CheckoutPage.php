<?php

namespace App\Livewire;

use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Mail;

#[Title('Checkout')]
class CheckoutPage extends Component
{

    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $zip_code;
    public $province;
    public $payment_method;

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromCookie();
        if(count($cart_items) == 0){
            return redirect('/products');
        }
    }

    public function placeOrder(){
        
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required'
        ]);
        //dump("hit");
    
        $cart_items = CartManagement::getCartItemsFromCookie();

        // Use auth()->user() properly
        $order = new Order();
        $order->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'idr';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed by ' . \Illuminate\Support\Facades\Auth::user()->name;
    
        $customer = new Customer();
        $customer->first_name = $this->first_name;
        $customer->last_name = $this->last_name;
        $customer->phone = $this->phone;
        $customer->street_address = $this->street_address;
        $customer->city = $this->city;
        $customer->province = $this->province;
        $customer->zip_code = $this->zip_code;
    
        $order->save();
        $customer->order_id = $order->id;
        $customer->save();
        $order->items()->createMany($cart_items);
        CartManagement::clearCartItems();
        Mail::to(request()->user())->send(new OrderPlaced($order));
        

        return redirect(route('success'));
        

    }
    
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
