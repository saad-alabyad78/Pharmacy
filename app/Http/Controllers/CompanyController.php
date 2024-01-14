<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\MedicineResource;

class CompanyController extends Controller
{

    public function medicinesByCompany(Company $company)
    {
        return MedicineResource::collection($company->medicines);
    }

    //CRUD
    public function index()
    {
        return CompanyResource::collection(Company::paginate()->items());
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return new CompanyResource($company);
    }

    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return new CompanyResource($company) ;
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json(null , 204) ;
    }
}
