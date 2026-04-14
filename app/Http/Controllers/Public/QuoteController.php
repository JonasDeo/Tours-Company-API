<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Mail\QuoteNotification;
use App\Mail\QuoteReceived;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

class QuoteController extends Controller
{
    // POST /api/quotes
    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $quote = Quote::create($request->validated());

        // Send emails without blocking quote creation on provider issues.
        try {
            Mail::to($quote->email)->send(new QuoteReceived($quote));
        } catch (Throwable $e) {
            report($e);
        }

        try {
            Mail::to(config('mail.admin_address', env('MAIL_FROM_ADDRESS')))
                ->send(new QuoteNotification($quote));
        } catch (Throwable $e) {
            report($e);
        }

        return response()->json([
            'message' => 'Your quote request has been received. We\'ll be in touch within 24 hours.',
            'id'      => $quote->id,
        ], 201);
    }
}