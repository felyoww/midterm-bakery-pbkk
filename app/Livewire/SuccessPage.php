<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success - Atherial Bakery')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $latest_order = Order::with('customer')->where('user_id', auth()->user()->id)->latest()->first();

        if ($this->session_id) {
           
            $session_info = Session::retrieve($this->session_id);

            if ($session_info->payment_status != 'paid') {
                $latest_order->payment_status = 'failed';
                $latest_order->save();
                return redirect()->route('cancel');
            } elseif ($session_info->payment_status == 'paid') {
                $latest_order->payment_status = 'paid';
                $latest_order->save();
            }
        }
       

        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
