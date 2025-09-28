<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions d'abord
        $this->createPermissions();
        
        // Créer les rôles avec leurs permissions
        $this->createRoles();
        
        // Créer les utilisateurs système
        $this->createSuperAdministrator();
        $this->createAdministrators();
        $this->createManagers();
        $this->createCashiers();
        $this->createProviders();
        
        $this->command->info('✅ Utilisateurs système créés avec succès !');
    }

    /**
     * Créer toutes les permissions nécessaires
     */
    private function createPermissions(): void
    {
        $permissions = [
            // Gestion système
            'manage_system_users' => 'Gérer les utilisateurs système',
            'manage_roles' => 'Gérer les rôles et permissions',
            'manage_settings' => 'Gérer les paramètres système',
            'view_system_logs' => 'Consulter les logs système',
            'backup_restore' => 'Sauvegarde et restauration',
            
            // Gestion des utilisateurs
            'users.view' => 'Voir les utilisateurs',
            'users.create' => 'Créer des utilisateurs',
            'users.edit' => 'Modifier les utilisateurs',
            'users.delete' => 'Supprimer les utilisateurs',
            'users.export' => 'Exporter les utilisateurs',
            
            // Gestion des commandes
            'orders.view' => 'Voir les commandes',
            'orders.create' => 'Créer des commandes',
            'orders.edit' => 'Modifier les commandes',
            'orders.delete' => 'Supprimer les commandes',
            'orders.manage_status' => 'Gérer le statut des commandes',
            'orders.export' => 'Exporter les commandes',
            'orders.generate_pdf' => 'Générer des factures PDF',
            
            // Gestion des produits
            'products.view' => 'Voir les produits',
            'products.create' => 'Créer des produits',
            'products.edit' => 'Modifier les produits',
            'products.delete' => 'Supprimer les produits',
            'products.manage_stock' => 'Gérer les stocks',
            'products.manage_categories' => 'Gérer les catégories',
            'products.manage_brands' => 'Gérer les marques',
            'products.export' => 'Exporter les produits',
            
            // Point de vente (POS)
            'pos.access' => 'Accéder au point de vente',
            'pos.create_sale' => 'Créer des ventes',
            'pos.manage_cash_register' => 'Gérer la caisse',
            'pos.view_sales_report' => 'Voir les rapports de vente',
            'pos.apply_discounts' => 'Appliquer des remises',
            'pos.process_returns' => 'Traiter les retours',
            
            // Gestion financière
            'finance.view_reports' => 'Voir les rapports financiers',
            'finance.manage_payments' => 'Gérer les paiements',
            'finance.view_analytics' => 'Voir les analyses',
            'finance.export_reports' => 'Exporter les rapports',
            
            // Gestion des clients
            'customers.view' => 'Voir les clients',
            'customers.create' => 'Créer des clients',
            'customers.edit' => 'Modifier les clients',
            'customers.delete' => 'Supprimer les clients',
            'customers.export' => 'Exporter les clients',
            
            // Gestion du contenu
            'content.manage_pages' => 'Gérer les pages',
            'content.manage_blog' => 'Gérer le blog',
            'content.manage_media' => 'Gérer les médias',
            
            // Rapports et analyses
            'reports.view_dashboard' => 'Voir le tableau de bord',
            'reports.view_sales' => 'Voir les rapports de vente',
            'reports.view_inventory' => 'Voir les rapports d\'inventaire',
            'reports.view_customers' => 'Voir les rapports clients',
            'reports.export_all' => 'Exporter tous les rapports',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'guard_name' => 'web',
                    //'description' => $description // errors
                ]
            );
        }

        $this->command->info('📝 Permissions créées : ' . count($permissions));
    }

    /**
     * Créer les rôles avec leurs permissions
     */
    private function createRoles(): void
    {
        // Super Administrateur - Tous les droits
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Administrateur'],
            ['guard_name' => 'web']
        );
        $superAdminRole->givePermissionTo(Permission::all());

        // Administrateur - Gestion complète sauf système
        $adminRole = Role::firstOrCreate(
            ['name' => 'Administrateur'],
            ['guard_name' => 'web']
        );
        $adminPermissions = [
            'users.view', 'users.create', 'users.edit', 'users.export',
            'orders.view', 'orders.create', 'orders.edit', 'orders.manage_status', 'orders.export', 'orders.generate_pdf',
            'products.view', 'products.create', 'products.edit', 'products.manage_stock', 'products.manage_categories', 'products.manage_brands', 'products.export',
            'pos.access', 'pos.create_sale', 'pos.manage_cash_register', 'pos.view_sales_report', 'pos.apply_discounts', 'pos.process_returns',
            'finance.view_reports', 'finance.manage_payments', 'finance.view_analytics', 'finance.export_reports',
            'customers.view', 'customers.create', 'customers.edit', 'customers.export',
            'content.manage_pages', 'content.manage_blog', 'content.manage_media',
            'reports.view_dashboard', 'reports.view_sales', 'reports.view_inventory', 'reports.view_customers', 'reports.export_all',
        ];
        $adminRole->givePermissionTo($adminPermissions);

        // Gestionnaire - Gestion opérationnelle
        $managerRole = Role::firstOrCreate(
            ['name' => 'Gestionnaire'],
            ['guard_name' => 'web']
        );
        $managerPermissions = [
            'orders.view', 'orders.edit', 'orders.manage_status', 'orders.generate_pdf',
            'products.view', 'products.edit', 'products.manage_stock',
            'pos.access', 'pos.create_sale', 'pos.view_sales_report', 'pos.apply_discounts',
            'customers.view', 'customers.create', 'customers.edit',
            'finance.view_reports', 'finance.view_analytics',
            'reports.view_dashboard', 'reports.view_sales', 'reports.view_inventory',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Caissier - Point de vente et ventes
        $cashierRole = Role::firstOrCreate(
            ['name' => 'Caissier'],
            ['guard_name' => 'web']
        );
        $cashierPermissions = [
            'pos.access', 'pos.create_sale', 'pos.manage_cash_register',
            'orders.view', 'orders.create',
            'customers.view', 'customers.create',
            'products.view',
            'reports.view_dashboard',
        ];
        $cashierRole->givePermissionTo($cashierPermissions);

        // Vendeur - Vente et clients
        $salesRole = Role::firstOrCreate(
            ['name' => 'Vendeur'],
            ['guard_name' => 'web']
        );
        $salesPermissions = [
            'pos.access', 'pos.create_sale',
            'orders.view', 'orders.create',
            'customers.view', 'customers.create', 'customers.edit',
            'products.view',
        ];
        $salesRole->givePermissionTo($salesPermissions);

        // Fournisseur - Gestion de ses produits
        $providerRole = Role::firstOrCreate(
            ['name' => 'Fournisseur'],
            ['guard_name' => 'web']
        );
        $providerPermissions = [
            'products.view', 'products.create', 'products.edit', 'products.manage_stock',
            'orders.view',
            'reports.view_sales',
        ];
        $providerRole->givePermissionTo($providerPermissions);

        $this->command->info('🎭 Rôles créés avec leurs permissions');
    }

    /**
     * Créer le super administrateur
     */
    private function createSuperAdministrator(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@entreprise.com'],
            [
                //'name' => 'Super Administrateur',
                'firstname' => 'Super',
                'lastname' => 'Administrateur',
                'username' => 'superadmin',
                'type' => User::TYPE_ADMIN,
                'password' => Hash::make('SuperAdmin@2025!'),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->assignRole('Super Administrateur');
        
        // Créer les détails
        $superAdmin->updateDetails([
            'phone' => '+221 77 123 45 67',
            'address' => '123 Avenue de l\'Indépendance',
            'city' => 'Dakar',
            'postal_code' => '10000',
            'country' => 'Sénégal',
        ]);

        $this->command->info('👑 Super Administrateur créé : superadmin@entreprise.com / SuperAdmin@2024!');
    }

    /**
     * Créer les administrateurs
     */
    private function createAdministrators(): void
    {
        $administrators = [
            [
                'firstname' => 'Amadou',
                'lastname' => 'DIOP',
                'email' => 'amadou.diop@entreprise.com',
                'username' => 'amadou.diop',
                'phone' => '+221 77 234 56 78',
                'gender' => 'male',
            ],
            [
                'firstname' => 'Fatou',
                'lastname' => 'NDIAYE',
                'email' => 'fatou.ndiaye@entreprise.com',
                'username' => 'fatou.ndiaye',
                'phone' => '+221 76 345 67 89',
                'gender' => 'female',
            ],
        ];

        foreach ($administrators as $adminData) {
            $admin = User::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    //'name' => $adminData['firstname'] . ' ' . $adminData['lastname'],
                    'firstname' => $adminData['firstname'],
                    'lastname' => $adminData['lastname'],
                    'username' => $adminData['username'],
                    'gender' => $adminData['gender'],
                    'type' => User::TYPE_ADMIN,
                    'password' => Hash::make('Admin@2025!'),
                    'email_verified_at' => now(),
                ]
            );

            $admin->assignRole('Administrateur');
            
            $admin->updateDetails([
                'phone' => $adminData['phone'],
                'city' => 'Dakar',
                'country' => 'Sénégal',
            ]);
        }

        $this->command->info('👨‍💼 Administrateurs créés (mot de passe: Admin@2024!)');
    }

    /**
     * Créer les gestionnaires
     */
    private function createManagers(): void
    {
        $managers = [
            [
                'firstname' => 'Ousmane',
                'lastname' => 'FALL',
                'email' => 'ousmane.fall@entreprise.com',
                'username' => 'ousmane.fall',
                'phone' => '+221 77 456 78 90',
                'gender' => 'male',
            ],
            [
                'firstname' => 'Aïssatou',
                'lastname' => 'BA',
                'email' => 'aissatou.ba@entreprise.com',
                'username' => 'aissatou.ba',
                'phone' => '+221 76 567 89 01',
                'gender' => 'female',
            ],
        ];

        foreach ($managers as $managerData) {
            $manager = User::firstOrCreate(
                ['email' => $managerData['email']],
                [
                    //'name' => $managerData['firstname'] . ' ' . $managerData['lastname'],
                    'firstname' => $managerData['firstname'],
                    'lastname' => $managerData['lastname'],
                    'username' => $managerData['username'],
                    'gender' => $managerData['gender'],
                    'type' => User::TYPE_ADMIN,
                    'password' => Hash::make('Manager@2025!'),
                    'email_verified_at' => now(),
                ]
            );

            $manager->assignRole('Gestionnaire');
            
            $manager->updateDetails([
                'phone' => $managerData['phone'],
                'city' => 'Dakar',
                'country' => 'Sénégal',
            ]);
        }

        $this->command->info('📊 Gestionnaires créés (mot de passe: Manager@2024!)');
    }

    /**
     * Créer les caissiers
     */
    private function createCashiers(): void
    {
        $cashiers = [
            [
                'firstname' => 'Mamadou',
                'lastname' => 'SECK',
                'email' => 'mamadou.seck@entreprise.com',
                'username' => 'mamadou.seck',
                'phone' => '+221 77 678 90 12',
                'gender' => 'male',
            ],
            [
                'firstname' => 'Khady',
                'lastname' => 'SARR',
                'email' => 'khady.sarr@entreprise.com',
                'username' => 'khady.sarr',
                'phone' => '+221 76 789 01 23',
                'gender' => 'female',
            ],
            [
                'firstname' => 'Ibrahima',
                'lastname' => 'GUEYE',
                'email' => 'ibrahima.gueye@entreprise.com',
                'username' => 'ibrahima.gueye',
                'phone' => '+221 77 890 12 34',
                'gender' => 'male',
            ],
            [
                'firstname' => 'Marième',
                'lastname' => 'DIALLO',
                'email' => 'marieme.diallo@entreprise.com',
                'username' => 'marieme.diallo',
                'phone' => '+221 76 901 23 45',
                'gender' => 'female',
            ],
        ];

        foreach ($cashiers as $cashierData) {
            $cashier = User::firstOrCreate(
                ['email' => $cashierData['email']],
                [
                    'name' => $cashierData['firstname'] . ' ' . $cashierData['lastname'],
                    'firstname' => $cashierData['firstname'],
                    'lastname' => $cashierData['lastname'],
                    'username' => $cashierData['username'],
                    'gender' => $cashierData['gender'],
                    'type' => User::TYPE_ADMIN,
                    'password' => Hash::make('Cashier@2025!'),
                    'email_verified_at' => now(),
                ]
            );

            $cashier->assignRole('Caissier');
            
            $cashier->updateDetails([
                'phone' => $cashierData['phone'],
                'city' => 'Dakar',
                'country' => 'Sénégal',
            ]);
        }

        $this->command->info('💰 Caissiers créés (mot de passe: Cashier@2024!)');
    }

    /**
     * Créer les fournisseurs
     */
    private function createProviders(): void
    {
        $providers = [
            [
                'firstname' => 'Cheikh',
                'lastname' => 'MBAYE',
                'email' => 'cheikh.mbaye@fournisseur1.com',
                'username' => 'cheikh.mbaye',
                'phone' => '+221 77 012 34 56',
                'gender' => 'male',
                'company' => 'MBAYE DISTRIBUTION SARL',
            ],
            [
                'firstname' => 'Bineta',
                'lastname' => 'THIAM',
                'email' => 'bineta.thiam@fournisseur2.com',
                'username' => 'bineta.thiam',
                'phone' => '+221 76 123 45 67',
                'gender' => 'female',
                'company' => 'THIAM IMPORT EXPORT',
            ],
            [
                'firstname' => 'Abdou',
                'lastname' => 'KANE',
                'email' => 'abdou.kane@fournisseur3.com',
                'username' => 'abdou.kane',
                'phone' => '+221 77 234 56 78',
                'gender' => 'male',
                'company' => 'KANE TRADING',
            ],
        ];

        foreach ($providers as $providerData) {
            $provider = User::firstOrCreate(
                ['email' => $providerData['email']],
                [
                    'name' => $providerData['firstname'] . ' ' . $providerData['lastname'],
                    'firstname' => $providerData['firstname'],
                    'lastname' => $providerData['lastname'],
                    'username' => $providerData['username'],
                    'gender' => $providerData['gender'],
                    'type' => User::TYPE_PROVIDER,
                    'password' => Hash::make('Provider@2024!'),
                    'email_verified_at' => now(),
                ]
            );

            $provider->assignRole('Fournisseur');
            
            $provider->updateDetails([
                'phone' => $providerData['phone'],
                'address' => 'Zone industrielle',
                'city' => 'Dakar',
                'country' => 'Sénégal',
            ]);
        }

        $this->command->info('🏢 Fournisseurs créés (mot de passe: Provider@2024!)');
    }
}
