<?php

namespace App\Http\Controllers;

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

   public function redeemDiscount(Request $request)
    {
        $user = Auth::user();
        return $this->discountService->redeemDiscount($user, $request->code);
    }
}
