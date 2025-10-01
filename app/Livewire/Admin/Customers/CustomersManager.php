<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use App\Models\UserDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomersManager extends Component
{
    use WithPagination;
    
    // Filtres et recherche
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public string $statusFilter = '';
    public string $dateFilter = '';
    public int $perPage = 15;
    
    // Modal et états
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailsModal = false;
    public ?User $selectedCustomer = null;
    public ?User $customerToDelete = null;
    
    // Formulaire - Informations principales
    #[Validate('required|string|min:2|max:255')]
    public string $firstname = '';
    
    #[Validate('required|string|min:2|max:255')]
    public string $lastname = '';
    
    #[Validate('nullable|email|max:255')]
    public string $email = '';
    
    #[Validate('nullable|string|max:20')]
    public string $username = '';
    
    #[Validate('nullable|in:male,female')]
    public string $gender = '';
    
    #[Validate('nullable|date|before:today')]
    public string $birth_date = '';
    
    #[Validate('nullable|string|max:20')]
    public string $ncin = '';
    
    // Formulaire - Détails
    #[Validate('nullable|string|max:20')]
    public string $phone = '';
    
    #[Validate('nullable|string|max:10')]
    public string $postal_code = '';
    
    #[Validate('nullable|string|max:500')]
    public string $address = '';
    
    #[Validate('nullable|string|max:100')]
    public string $city = '';
    
    #[Validate('nullable|string|max:100')]
    public string $country = 'Sénégal';
    
    // Statistiques
    public int $totalCustomers = 0;
    public int $activeCustomers = 0;
    public int $newCustomersThisMonth = 0;
    public float $averageOrderValue = 0;

    protected array $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    protected array $rules = [
        'firstname' => 'required|string|min:2|max:255',
        'lastname' => 'required|string|min:2|max:255',
        'email' => 'nullable|email|max:255',
        'username' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female',
        'birth_date' => 'nullable|date|before:today',
        'ncin' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'postal_code' => 'nullable|string|max:10',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
    ];

    protected array $messages = [
        'firstname.required' => 'Le prénom est obligatoire.',
        'firstname.min' => 'Le prénom doit contenir au moins 2 caractères.',
        'lastname.required' => 'Le nom est obligatoire.',
        'lastname.min' => 'Le nom doit contenir au moins 2 caractères.',
        //'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'email.unique' => 'Cette adresse email est déjà utilisée.',
        'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
        'gender.in' => 'Le genre doit être masculin ou féminin.',
    ];

    public function mount(): void
    {
        $this->loadStatistics();
    }

    public function loadStatistics(): void
    {
        $this->totalCustomers = User::customers()->count();
        $this->activeCustomers = User::customers()
            ->whereNull('suspended_at')
            ->count();
        $this->newCustomersThisMonth = User::customers()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }
    
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showCreateCustomer(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function showEditCustomer(int $customerId): void
    {
        $customer = User::with('detail')->find($customerId);
        
        if (!$customer || !$customer->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Client introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedCustomer = $customer;
        $this->fillForm($customer);
        $this->showEditModal = true;
    }

    public function showCustomerDetails(int $customerId): void
    {
        $customer = User::with(['detail', 'orders.orderItems.product'])
            ->find($customerId);
        
        if (!$customer || !$customer->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Client introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedCustomer = $customer;
        $this->showDetailsModal = true;
    }

    public function confirmDelete(int $customerId): void
    {
        $customer = User::find($customerId);
        
        if (!$customer || !$customer->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Client introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->customerToDelete = $customer;
        $this->showDeleteModal = true;
    }

    private function fillForm(User $customer): void
    {
        $this->firstname = $customer->firstname ?? '';
        $this->lastname = $customer->lastname ?? '';
        $this->email = $customer->email;
        $this->username = $customer->username ?? '';
        $this->gender = $customer->gender ?? '';
        $this->birth_date = $customer->birth_date?->format('Y-m-d') ?? '';
        $this->ncin = $customer->ncin ?? '';
        
        if ($customer->detail) {
            $this->phone = $customer->detail->phone ?? '';
            $this->postal_code = $customer->detail->postal_code ?? '';
            $this->address = $customer->detail->address ?? '';
            $this->city = $customer->detail->city ?? '';
            $this->country = $customer->detail->country ?? 'Sénégal';
        }
    }

    private function resetForm(): void
    {
        $this->selectedCustomer = null;
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->username = '';
        $this->gender = '';
        $this->birth_date = '';
        $this->ncin = '';
        $this->phone = '';
        $this->postal_code = '';
        $this->address = '';
        $this->city = '';
        $this->country = 'Sénégal';
        $this->resetErrorBag();
    }

    public function createCustomer(): void
    {
        $this->rules['email'] = 'nullable|email|max:255|unique:users,email';
        $this->rules['username'] = 'nullable|string|max:20|unique:users,username';
        $this->rules['ncin'] = 'nullable|string|max:20|unique:users,ncin';
        
        $this->validate();

        try {
            $customer = User::create([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                //'email' => $this->email,
                'email' => $this->email ?: null, // Explicitement NULL si vide
                'username' => $this->username ?: null,
                'gender' => $this->gender ?: null,
                'birth_date' => $this->birth_date ?: null,
                'ncin' => $this->ncin ?: null,
                'type' => User::TYPE_CUSTOMER,
                'password' => Hash::make(Str::random(12)),
            ]);

            if ($this->hasDetailFields()) {
                $customer->updateDetails([
                    'phone' => $this->phone ?: null,
                    'postal_code' => $this->postal_code ?: null,
                    'address' => $this->address ?: null,
                    'city' => $this->city ?: null,
                    'country' => $this->country ?: null,
                ]);
            }

            $this->dispatch('notify', [
                'text' => 'Client créé avec succès.',
                'type' => 'success',
                'status' => 201
            ]);

            $this->closeCreateModal();
            $this->loadStatistics();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la création du client: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function updateCustomer(): void
    {
        if (!$this->selectedCustomer) {
            return;
        }

        $this->rules['email'] = [
            'nullable',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->selectedCustomer->id)
        ];
        
        $this->rules['username'] = [
            'nullable',
            'string',
            'max:20',
            Rule::unique('users', 'username')->ignore($this->selectedCustomer->id)
        ];
        
        $this->rules['ncin'] = [
            'nullable',
            'string',
            'max:20',
            Rule::unique('users', 'ncin')->ignore($this->selectedCustomer->id)
        ];
        
        $this->validate();

        try {
            $this->selectedCustomer->update([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                //'email' => $this->email,
                'email' => $this->email ?: null, // Explicitement NULL si vide
                'username' => $this->username ?: null,
                'gender' => $this->gender ?: null,
                'birth_date' => $this->birth_date ?: null,
                'ncin' => $this->ncin ?: null,
            ]);

            if ($this->hasDetailFields()) {
                $this->selectedCustomer->updateDetails([
                    'phone' => $this->phone ?: null,
                    'postal_code' => $this->postal_code ?: null,
                    'address' => $this->address ?: null,
                    'city' => $this->city ?: null,
                    'country' => $this->country ?: null,
                ]);
            }

            $this->dispatch('notify', [
                'text' => 'Client mis à jour avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->closeEditModal();
            $this->loadStatistics();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function deleteCustomer(): void
    {
        if (!$this->customerToDelete) {
            return;
        }

        try {
            $this->customerToDelete->delete();

            $this->dispatch('notify', [
                'text' => 'Client supprimé avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->closeDeleteModal();
            $this->loadStatistics();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la suppression: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    private function hasDetailFields(): bool
    {
        return !empty($this->phone) || 
               !empty($this->postal_code) || 
               !empty($this->address) || 
               !empty($this->city) || 
               !empty($this->country);
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->customerToDelete = null;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedCustomer = null;
    }

    public function exportCustomers(): void
    {
        $this->dispatch('notify', [
            'text' => 'Export des clients en cours...',
            'type' => 'info',
            'status' => 200
        ]);
    }

    public function render()
    {
        $query = User::query()
            ->customers()
            ->with(['detail'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('firstname', 'like', '%' . $this->search . '%')
                      ->orWhere('lastname', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter === 'active', function ($query) {
                return $query->whereNull('suspended_at');
            })
            ->when($this->statusFilter === 'suspended', function ($query) {
                return $query->whereNotNull('suspended_at');
            })
            ->when($this->dateFilter, function ($query) {
                switch ($this->dateFilter) {
                    case 'today':
                        return $query->whereDate('created_at', today());
                    case 'week':
                        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    case 'month':
                        return $query->whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year);
                    case 'year':
                        return $query->whereYear('created_at', now()->year);
                    default:
                        return $query;
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        $customers = $query->paginate($this->perPage);
        
        return view('livewire.admin.customers.customers-manager', [
            'customers' => $customers,
        ]);
    }
}
