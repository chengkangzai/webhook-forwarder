<?php

namespace App\Http\Controllers;

use App\Jobs\StoreWebhookCallJob;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        dispatch(new StoreWebhookCallJob(
            $request->all(),
            $request->url(),
            $request->headers->all()
        ));

        return new Response('Ok', 200);
    }
}
