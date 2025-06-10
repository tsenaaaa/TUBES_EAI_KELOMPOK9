<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // GET all customers
    public function index()
    {
        return response()->json(Customer::orderBy('id')->get());
    }

    // POST: create new customer
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = Customer::create($validator->validated());

        return response()->json($customer, 201);
    }

    // GET single customer
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    // PUT/PATCH: update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer->update($validator->validated());

        return response()->json($customer);
    }

    // DELETE customer
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // DELETE ALL + RESET ID + RELASI
    public function reset()
    {
        DB::beginTransaction();
        try {
            // Hapus semua relasi bookings terlebih dahulu
            DB::table('bookings')->delete();

            // Hapus semua customers
            DB::table('customers')->delete();

            // Reset sequence PostgreSQL
            DB::statement("ALTER SEQUENCE customers_id_seq RESTART WITH 1;");
            DB::statement("ALTER SEQUENCE bookings_id_seq RESTART WITH 1;");

            DB::commit();

            return response()->json(['message' => 'Semua customer dan booking dihapus dan ID direset']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Reset gagal', 'details' => $e->getMessage()], 500);
        }
    }

    // Reorder ID supaya selalu urut kembali
    public function reorderIds()
    {
        DB::beginTransaction();
        try {
            $customers = Customer::orderBy('id')->get();
            $i = 1;
            $idMap = [];

            foreach ($customers as $customer) {
                $idMap[$customer->id] = $i;
                DB::table('customers')->where('id', $customer->id)->update(['id' => $i]);
                $i++;
            }

            foreach ($idMap as $oldId => $newId) {
                DB::table('bookings')->where('customer_id', $oldId)->update(['customer_id' => $newId]);
            }

            DB::statement("SELECT setval('customers_id_seq', $i, false)");

            DB::commit();
            return response()->json(['message' => 'ID berhasil direstrukturisasi dan diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal reset ID', 'details' => $e->getMessage()], 500);
        }
    }
}
