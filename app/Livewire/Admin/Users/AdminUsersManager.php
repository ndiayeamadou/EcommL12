<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class AdminUsersManager extends Component
{
    use WithPagination;
    
    // Filtres et recherche
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public string $typeFilter = '';
    public string $statusFilter = '';
    public string $roleFilter = '';
    public string $dateFilter = '';
    public int $perPage = 15;
    
    // Modal et états
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailsModal = false;
    public bool $showRolesModal = false;
    public bool $showPermissionsModal = false;
    public ?User $selectedUser = null;
    public ?User $userToDelete = null;
    
    // Formulaire - Informations principales
    #[Validate('required|string|min:2|max:255')]
    public string $name = '';
    
    #[Validate('required|string|min:2|max:255')]
    public string $firstname = '';
    
    #[Validate('required|string|min:2|max:255')]
    public string $lastname = '';
    
    #[Validate('required|email|max:255')]
    public string $email = '';
    
    #[Validate('nullable|string|max:20')]
    public string $username = '';
    
    #[Validate('nullable|in:male,female')]
    public string $gender = '';
    
    #[Validate('nullable|date|before:today')]
    public string $birth_date = '';
    
    #[Validate('required|in:0,3,5')]
    public int $type = User::TYPE_ADMIN;
    
    #[Validate('nullable|string|min:8')]
    public string $password = '';
    
    #[Validate('nullable|string|min:8|same:password')]
    public string $password_confirmation = '';
    
    // Gestion des rôles et permissions
    public array $selectedRoles = [];
    public array $selectedPermissions = [];
    public array $availableRoles = [];
    public array $availablePermissions = [];
    
    // Statistiques
    public int $totalUsers = 0;
    public int $totalAdmins = 0;
    public int $totalProviders = 0;
    public int $activeUsers = 0;
    public int $suspendedUsers = 0;
    public int $newUsersThisMonth = 0;

    protected array $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    protected array $rules = [
        'name' => 'required|string|min:2|max:255',
        'firstname' => 'required|string|min:2|max:255',
        'lastname' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'username' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female',
        'birth_date' => 'nullable|date|before:today',
        'type' => 'required|in:0,3,5',
        'password' => 'nullable|string|min:8',
        'password_confirmation' => 'nullable|string|min:8|same:password',
    ];

    protected array $messages = [
        'name.required' => 'Le nom complet est obligatoire.',
        'firstname.required' => 'Le prénom est obligatoire.',
        'lastname.required' => 'Le nom est obligatoire.',
        'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'email.unique' => 'Cette adresse email est déjà utilisée.',
        'type.required' => 'Le type d\'utilisateur est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password_confirmation.same' => 'La confirmation du mot de passe ne correspond pas.',
    ];

    public function mount(): void
    {
        // Vérifier les permissions
        if (!auth()->user()->can('manage_system_users')) {
            abort(403, 'Accès refusé. Permissions insuffisantes.');
        }
        
        $this->loadStatistics();
        $this->loadRolesAndPermissions();
    }

    public function loadStatistics(): void
    {
        $this->totalUsers = User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_PROVIDER])->count();
        $this->totalAdmins = User::where('type', User::TYPE_ADMIN)->count();
        $this->totalProviders = User::where('type', User::TYPE_PROVIDER)->count();
        $this->activeUsers = User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_PROVIDER])
            ->whereNull('suspended_at')
            ->count();
        $this->suspendedUsers = User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_PROVIDER])
            ->whereNotNull('suspended_at')
            ->count();
        $this->newUsersThisMonth = User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_PROVIDER])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function loadRolesAndPermissions(): void
    {
        $this->availableRoles = Role::orderBy('name')->get()->toArray();
        $this->availablePermissions = Permission::orderBy('name')->get()->toArray();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingRoleFilter(): void
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

    public function showCreateUser(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function showEditUser(int $userId): void
    {
        $user = User::with(['roles', 'permissions'])->find($userId);
        
        if (!$user || $user->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Utilisateur introuvable ou accès non autorisé.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedUser = $user;
        $this->fillForm($user);
        $this->showEditModal = true;
    }

    public function showUserDetails(int $userId): void
    {
        $user = User::with(['roles', 'permissions', 'detail'])->find($userId);
        
        if (!$user || $user->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Utilisateur introuvable ou accès non autorisé.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedUser = $user;
        $this->showDetailsModal = true;
    }

    public function showManageRoles(int $userId): void
    {
        $user = User::with('roles')->find($userId);
        
        if (!$user || $user->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Utilisateur introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedUser = $user;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->showRolesModal = true;
    }

    public function showManagePermissions(int $userId): void
    {
        $user = User::with('permissions')->find($userId);
        
        if (!$user || $user->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Utilisateur introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        $this->selectedUser = $user;
        $this->selectedPermissions = $user->permissions->pluck('id')->toArray();
        $this->showPermissionsModal = true;
    }

    public function confirmDelete(int $userId): void
    {
        $user = User::find($userId);
        
        if (!$user || $user->isCustomer()) {
            $this->dispatch('notify', [
                'text' => 'Utilisateur introuvable.',
                'type' => 'error',
                'status' => 404
            ]);
            return;
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            $this->dispatch('notify', [
                'text' => 'Vous ne pouvez pas supprimer votre propre compte.',
                'type' => 'warning',
                'status' => 400
            ]);
            return;
        }

        $this->userToDelete = $user;
        $this->showDeleteModal = true;
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::find($userId);
        
        if (!$user || $user->isCustomer()) {
            return;
        }

        // Empêcher la suspension de son propre compte
        if ($user->id === auth()->id()) {
            $this->dispatch('notify', [
                'text' => 'Vous ne pouvez pas modifier le statut de votre propre compte.',
                'type' => 'warning',
                'status' => 400
            ]);
            return;
        }

        try {
            $user->update([
                'suspended_at' => $user->suspended_at ? null : now()
            ]);

            $action = $user->suspended_at ? 'suspendu' : 'réactivé';
            $this->dispatch('notify', [
                'text' => "Utilisateur {$action} avec succès.",
                'type' => 'success',
                'status' => 200
            ]);

            $this->loadStatistics();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la modification du statut: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    private function fillForm(User $user): void
    {
        $this->name = $user->name ?? '';
        $this->firstname = $user->firstname ?? '';
        $this->lastname = $user->lastname ?? '';
        $this->email = $user->email;
        $this->username = $user->username ?? '';
        $this->gender = $user->gender ?? '';
        $this->birth_date = $user->birth_date?->format('Y-m-d') ?? '';
        $this->type = $user->type;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->selectedPermissions = $user->permissions->pluck('id')->toArray();
    }

    private function resetForm(): void
    {
        $this->selectedUser = null;
        $this->name = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->username = '';
        $this->gender = '';
        $this->birth_date = '';
        $this->type = User::TYPE_ADMIN;
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
        $this->selectedPermissions = [];
        $this->resetErrorBag();
    }

    public function createUser(): void
    {
        $this->rules['email'] = 'required|email|max:255|unique:users,email';
        $this->rules['username'] = 'nullable|string|max:20|unique:users,username';
        $this->rules['password'] = 'required|string|min:8';
        $this->rules['password_confirmation'] = 'required|string|min:8|same:password';
        
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'username' => $this->username ?: null,
                'gender' => $this->gender ?: null,
                'birth_date' => $this->birth_date ?: null,
                'type' => $this->type,
                'password' => Hash::make($this->password),
            ]);

            // Assigner les rôles
            if (!empty($this->selectedRoles)) {
                $roles = Role::whereIn('id', $this->selectedRoles)->get();
                $user->assignRole($roles);
            }

            // Assigner les permissions directes
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $user->givePermissionTo($permissions);
            }

            $this->dispatch('notify', [
                'text' => 'Utilisateur créé avec succès.',
                'type' => 'success',
                'status' => 201
            ]);

            $this->closeCreateModal();
            $this->loadStatistics();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la création: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function updateUser(): void
    {
        if (!$this->selectedUser) {
            return;
        }

        $this->rules['email'] = [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->selectedUser->id)
        ];
        
        $this->rules['username'] = [
            'nullable',
            'string',
            'max:20',
            Rule::unique('users', 'username')->ignore($this->selectedUser->id)
        ];

        if (!empty($this->password)) {
            $this->rules['password'] = 'string|min:8';
            $this->rules['password_confirmation'] = 'required|string|min:8|same:password';
        }
        
        $this->validate();

        try {
            $updateData = [
                'name' => $this->name,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'username' => $this->username ?: null,
                'gender' => $this->gender ?: null,
                'birth_date' => $this->birth_date ?: null,
                'type' => $this->type,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $this->selectedUser->update($updateData);

            $this->dispatch('notify', [
                'text' => 'Utilisateur mis à jour avec succès.',
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

    public function updateUserRoles(): void
    {
        if (!$this->selectedUser) {
            return;
        }

        try {
            $roles = Role::whereIn('id', $this->selectedRoles)->get();
            $this->selectedUser->syncRoles($roles);

            $this->dispatch('notify', [
                'text' => 'Rôles mis à jour avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->closeRolesModal();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la mise à jour des rôles: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function updateUserPermissions(): void
    {
        if (!$this->selectedUser) {
            return;
        }

        try {
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
            $this->selectedUser->syncPermissions($permissions);

            $this->dispatch('notify', [
                'text' => 'Permissions mises à jour avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->closePermissionsModal();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la mise à jour des permissions: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function deleteUser(): void
    {
        if (!$this->userToDelete) {
            return;
        }

        try {
            $this->userToDelete->delete();

            $this->dispatch('notify', [
                'text' => 'Utilisateur supprimé avec succès.',
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
        $this->userToDelete = null;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedUser = null;
    }

    public function closeRolesModal(): void
    {
        $this->showRolesModal = false;
        $this->selectedUser = null;
        $this->selectedRoles = [];
    }

    public function closePermissionsModal(): void
    {
        $this->showPermissionsModal = false;
        $this->selectedUser = null;
        $this->selectedPermissions = [];
    }

    public function exportUsers(): void
    {
        $this->dispatch('notify', [
            'text' => 'Export des utilisateurs en cours...',
            'type' => 'info',
            'status' => 200
        ]);
    }

    public function render()
    {
        $query = User::query()
            ->whereIn('type', [User::TYPE_ADMIN, User::TYPE_PROVIDER])
            ->with(['roles', 'permissions'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('firstname', 'like', '%' . $this->search . '%')
                      ->orWhere('lastname', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter !== '', function ($query) {
                return $query->where('type', $this->typeFilter);
            })
            ->when($this->statusFilter === 'active', function ($query) {
                return $query->whereNull('suspended_at');
            })
            ->when($this->statusFilter === 'suspended', function ($query) {
                return $query->whereNotNull('suspended_at');
            })
            ->when($this->roleFilter, function ($query) {
                return $query->whereHas('roles', function ($q) {
                    $q->where('id', $this->roleFilter);
                });
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
        
        $users = $query->paginate($this->perPage);
        $roles = Role::orderBy('name')->get();
        
        return view('livewire.admin.users.admin-users-manager', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
