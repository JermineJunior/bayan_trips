<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerQuickStoreRequest;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a paginated listing of the customers.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Customer::class);

        $customers = Customer::search($request->query('search'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        $this->authorize('create', Customer::class);

        return view('admin.customers.create', [
            'customer' => null,
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        $this->authorize('create', Customer::class);

        Customer::create($request->validated());

        return redirect()
            ->route('admin.customers.index')
            ->with('status', 'تمت إضافة العميل.');
    }

    /**
     * Show the form for editing the given customer.
     */
    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);

        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the given customer.
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $customer->update($request->validated());

        return redirect()
            ->route('admin.customers.index')
            ->with('status', 'تم تحديث العميل.');
    }

    /**
     * Remove the given customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('status', 'تم حذف العميل.');
    }

    /**
     * JSON endpoint for the quick-add customer modal on the trip form.
     *
     * Returns the freshly created customer so the caller can append it to
     * its customer select. Validation failures surface as 422 with the
     * standard Laravel JSON error shape.
     */
    public function quickStore(CustomerQuickStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Customer::class);

        $customer = Customer::create($request->validated());

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
        ]);
    }
}