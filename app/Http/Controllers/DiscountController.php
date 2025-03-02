<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function getUserDiscounts()
    {
        $user = Auth::user();
        return response()->json($this->discountService->getAvailableDiscounts($user));
    }
    
    public function validatePromo(Request $request)
    {
        $user = Auth::user();
        $code = strtoupper($request->code);

        $userDiscounts = json_decode($user->used_discounts, true) ?? [];

        foreach ($userDiscounts as $discount) {
            if ($discount['code'] === $code && !$discount['used']) {
                return response()->json([
                    'valid' => true,
                    'discount' => $discount['discount']
                ]);
            }
        }

        return response()->json(['valid' => false, 'error' => 'Invalid or already used promo code'], 400);
    }


}
