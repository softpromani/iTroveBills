<?php

namespace App\Http\Controllers;

use App\Models\SellerSubscription;
use App\Models\Status;
use App\Models\SubscriptionPack;
use App\Models\User;
use Doctrine\Inflector\Rules\Substitution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionPackController extends Controller
{
    public function index()
    {
        $subscriptionPacks = SubscriptionPack::get();
        $usersubscription = SellerSubscription::where("seller_id", auth()->user()->id)->first()->subscription_id??false;
        return Inertia::render('Subscription', [
            'subscriptions' => $subscriptionPacks,
            'user_subscription_id' => $usersubscription,
        ]);
    }

    public function subscription(Request $request){
         $subscriptionPack = SubscriptionPack::find($request->subscriptin_id);
         $response = SellerSubscription::create([
            'seller_id' => auth()->user()->id,
            'subscription_id' => $request->subscriptin_id,
            'order_ammount' => $subscriptionPack->mrp,
            'paid_ammount' => $subscriptionPack->mrp,
            'purchase_date' => now(),
            'start_date' => now(),
            'end_date' => $subscriptionPack->month?now()->addMonth($subscriptionPack->month):NULL,
            'status' =>Status::moduleStatusId('Subscription','active'),
         ]);

         if($response){
            auth()->user()->assignRole(User::$role_seller);
            return redirect()->back()->with('success', 'Plan bought Successfully!');
         }
    }
}
