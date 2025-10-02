<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CompanyIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $parentFilter = '';

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;

    public $companyId;
    public $name;
    public $legal_name;
    public $registration_number;
    public $tax_number;
    public $email;
    public $phone;
    public $website;
    public $logo;
    public $existingLogo;
    public $description;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country = 'Sénégal';
    public $parent_id;
    public $type = 'subsidiary';
    public $founded_date;
    public $employee_count = 0;
    public $annual_revenue;
    public $industry;
    public $is_active = true;
    public $is_verified = false;
    public $contact_person_name;
    public $contact_person_phone;
    public $contact_person_email;
    public $contact_person_position;

    protected $queryString = ['search', 'typeFilter', 'statusFilter'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255|unique:companies,registration_number,' . $this->companyId,
            'tax_number' => 'nullable|string|max:255|unique:companies,tax_number,' . $this->companyId,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:companies,id',
            'type' => 'required|in:holding,subsidiary,branch',
            'founded_date' => 'nullable|date',
            'employee_count' => 'nullable|integer|min:0',
            'annual_revenue' => 'nullable|numeric|min:0',
            'industry' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_position' => 'nullable|string|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        
        $this->companyId = $company->id;
        $this->name = $company->name;
        $this->legal_name = $company->legal_name;
        $this->registration_number = $company->registration_number;
        $this->tax_number = $company->tax_number;
        $this->email = $company->email;
        $this->phone = $company->phone;
        $this->website = $company->website;
        $this->existingLogo = $company->logo;
        $this->description = $company->description;
        $this->address = $company->address;
        $this->city = $company->city;
        $this->state = $company->state;
        $this->postal_code = $company->postal_code;
        $this->country = $company->country;
        $this->parent_id = $company->parent_id;
        $this->type = $company->type;
        $this->founded_date = $company->founded_date?->format('Y-m-d');
        $this->employee_count = $company->employee_count;
        $this->annual_revenue = $company->annual_revenue;
        $this->industry = $company->industry;
        $this->is_active = $company->is_active;
        $this->is_verified = $company->is_verified;
        $this->contact_person_name = $company->contact_person_name;
        $this->contact_person_phone = $company->contact_person_phone;
        $this->contact_person_email = $company->contact_person_email;
        $this->contact_person_position = $company->contact_person_position;
        
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'registration_number' => $this->registration_number,
            'tax_number' => $this->tax_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'founded_date' => $this->founded_date,
            'employee_count' => $this->employee_count,
            'annual_revenue' => $this->annual_revenue,
            'industry' => $this->industry,
            'is_active' => $this->is_active,
            'is_verified' => $this->is_verified,
            'contact_person_name' => $this->contact_person_name,
            'contact_person_phone' => $this->contact_person_phone,
            'contact_person_email' => $this->contact_person_email,
            'contact_person_position' => $this->contact_person_position,
        ];

        if ($this->logo) {
            if ($this->isEditing && $this->existingLogo) {
                Storage::disk('public')->delete($this->existingLogo);
            }
            $data['logo'] = $this->logo->store('companies/logos', 'public');
        }

        if ($this->isEditing) {
            $company = Company::findOrFail($this->companyId);
            $company->update($data);
            session()->flash('message', 'Entreprise modifiée avec succès !');
        } else {
            Company::create($data);
            session()->flash('message', 'Entreprise créée avec succès !');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->companyId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $company = Company::findOrFail($this->companyId);
        
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        
        $company->delete();
        
        $this->showDeleteModal = false;
        session()->flash('message', 'Entreprise supprimée avec succès !');
    }

    public function toggleStatus($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['is_active' => !$company->is_active]);
        
        session()->flash('message', 'Statut modifié avec succès !');
    }

    private function resetForm()
    {
        $this->companyId = null;
        $this->name = '';
        $this->legal_name = '';
        $this->registration_number = '';
        $this->tax_number = '';
        $this->email = '';
        $this->phone = '';
        $this->website = '';
        $this->logo = null;
        $this->existingLogo = null;
        $this->description = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->country = 'Sénégal';
        $this->parent_id = null;
        $this->type = 'subsidiary';
        $this->founded_date = null;
        $this->employee_count = 0;
        $this->annual_revenue = null;
        $this->industry = '';
        $this->is_active = true;
        $this->is_verified = false;
        $this->contact_person_name = '';
        $this->contact_person_phone = '';
        $this->contact_person_email = '';
        $this->contact_person_position = '';
    }

    public function render()
    {
        $companies = Company::query()
            ->with('parent', 'subsidiaries')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('legal_name', 'like', '%' . $this->search . '%')
                      ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->when($this->parentFilter, function ($query) {
                $query->where('parent_id', $this->parentFilter);
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $parentCompanies = Company::whereNull('parent_id')
            ->orWhere('type', 'holding')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.company.company-index', [
            'companies' => $companies,
            'parentCompanies' => $parentCompanies,
        ]);
    }
}
