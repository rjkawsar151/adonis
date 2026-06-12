<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('service')->orderBy('sort_order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $services = Service::where('status', 'active')->orderBy('sort_order')->get();
        return view('admin.faqs.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'service_id' => 'nullable|exists:services,id',
            'sort_order' => 'required|integer',
            'status'   => 'required|in:active,inactive',
        ]);

        Faq::create($request->only(['question','answer','service_id','sort_order','status']));

        return redirect('/admin/faqs')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $services = Service::where('status', 'active')->orderBy('sort_order')->get();
        return view('admin.faqs.edit', compact('faq', 'services'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'service_id' => 'nullable|exists:services,id',
            'sort_order' => 'required|integer',
            'status'   => 'required|in:active,inactive',
        ]);

        $faq->update($request->only(['question','answer','service_id','sort_order','status']));

        return redirect('/admin/faqs')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect('/admin/faqs')->with('success', 'FAQ deleted.');
    }
}
