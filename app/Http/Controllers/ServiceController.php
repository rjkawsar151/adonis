<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('services', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Load related models
        $causeBullets = $service->causeBullets()->get();
        $treatmentBullets = $service->treatmentBullets()->get();
        $steps = $service->steps()->where('status', 'active')->get();
        $faqs = $service->faqs()->where('status', 'active')->get();

        return view('services.show', compact('service', 'causeBullets', 'treatmentBullets', 'steps', 'faqs'));
    }
}
