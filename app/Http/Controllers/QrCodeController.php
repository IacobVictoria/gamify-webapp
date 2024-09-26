<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQRCodeRequest;
use App\Models\Product;
use App\Models\QrCode as QrCodeModel;
use Illuminate\Http\Request;
use Faker\Provider\Uuid;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;


class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.   the qrcodes for a product
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenerateQRCodeRequest $request)
    {

        $productId = $request->input('idProd');
        $nrQrCodes = (int) $request->input('nrQrCodes');

        $product = Product::findOrFail($productId);

        $directory = public_path('images' . DIRECTORY_SEPARATOR . 'qrCodes' . DIRECTORY_SEPARATOR . $product->id);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        for ($i = 0; $i < $nrQrCodes; $i++) {
            $code = Uuid::uuid();

            $qrCode = new QrCodeModel();
            $qrCode->code = $code;
            $qrCode->id = Uuid::uuid();
            $qrCode->product_id = $productId;

            $image = QrCodeGenerator::format('png')
                ->size(300)
                ->color(255, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->margin(1)
                ->generate($code);


            $imagePath = $directory . DIRECTORY_SEPARATOR . $code . '.png';
            file_put_contents($imagePath, $image);

            $qrCode->image_url = asset('images' . DIRECTORY_SEPARATOR . 'qrCodes' . DIRECTORY_SEPARATOR . $product->id . DIRECTORY_SEPARATOR . $code . '.png');
            $qrCode->save();
        }


        return redirect()->back()
            ->with('success', 'QrCodes generated');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $productId)
    {
        $qrCodes = QrCodeModel::where('product_id', $productId)->get();
        $product = Product::findOrFail($productId);

        return Inertia::render('QrCodes/Show', [
            'qrCodes' => $qrCodes,
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
