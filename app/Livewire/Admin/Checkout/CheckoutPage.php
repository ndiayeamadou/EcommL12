<?php

namespace App\Livewire\Admin\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class CheckoutPage extends Component
{
    public $cart;
    public $totalPrice = 0;
    
    // Informations client
    public $customerId = null;
    public $selectedCustomer = null;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    
    // Adresse de livraison
    public $address;
    public $city;
    public $postalCode;
    public $country = 'Sénégal';
    
    // Méthode de paiement
    public $paymentMethod = 'cash';
    public $termsAccepted = false;
    
    // Recherche de client
    public $customerSearch = '';
    public $searchResults = [];
    public $customers = [];
    
    protected $rules = [
        'firstName' => 'required|min:2',
        'lastName' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'postalCode' => 'required',
        'country' => 'required',
        'termsAccepted' => 'accepted',
    ];
    
    protected $messages = [
        'firstName.required' => 'Le prénom est obligatoire.',
        'lastName.required' => 'Le nom est obligatoire.',
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'Veuillez entrer un email valide.',
        'phone.required' => 'Le téléphone est obligatoire.',
        'address.required' => 'L\'adresse est obligatoire.',
        'city.required' => 'La ville est obligatoire.',
        'postalCode.required' => 'Le code postal est obligatoire.',
        'termsAccepted.accepted' => 'Vous devez accepter les conditions générales.',
    ];
    
    public function mount()
    {
        // Charger tous les clients (pour les admins)
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->customers = User::customers()
                ->orderBy('firstname')
                ->orderBy('lastname')
                ->get();
        }
        
        // Si l'utilisateur est un client (type 0), pré-remplir avec ses infos
        if (Auth::check() && Auth::user()->isCustomer()) {
            $user = Auth::user();
            $this->firstName = $user->firstname;
            $this->lastName = $user->lastname;
            $this->email = $user->email;
            
            // Charger les détails si ils existent
            if ($user->detail) {
                $this->phone = $user->detail->phone;
                $this->address = $user->detail->address;
                $this->city = $user->detail->city;
                $this->postalCode = $user->detail->postal_code;
                $this->country = $user->detail->country ?? 'Sénégal';
            }
        }
        
        $this->loadCart();
    }
    
    public function loadCart()
    {
        if (Auth::check()) {
            $this->cart = Cart::where('user_id', auth()->id())
                ->with(['product', 'productColor'])
                ->get();
        } else {
            $this->cart = collect();
        }
        $this->calculateTotal();
    }
    
    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $this->totalPrice += $price * $item->quantity;
        }
    }
    
    // Recherche de clients (pour les admins)
    public function searchCustomers()
    {
        if (strlen($this->customerSearch) < 2) {
            $this->searchResults = [];
            return;
        }
        
        $this->searchResults = User::customers()
            ->where(function($query) {
                $query->where('firstname', 'like', '%' . $this->customerSearch . '%')
                    ->orWhere('lastname', 'like', '%' . $this->customerSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->customerSearch . '%')
                    ->orWhere('customer_number', 'like', '%' . $this->customerSearch . '%');
            })
            ->limit(10)
            ->get();
    }
    
    // Sélection d'un client existant
    public function selectCustomer($customerId)
    {
        $customer = User::customers()->find($customerId);
        
        if ($customer) {
            $this->customerId = $customer->id;
            $this->selectedCustomer = $customer;
            $this->firstName = $customer->firstname;
            $this->lastName = $customer->lastname;
            $this->email = $customer->email;
            
            if ($customer->detail) {
                $this->phone = $customer->detail->phone;
                $this->address = $customer->detail->address;
                $this->city = $customer->detail->city;
                $this->postalCode = $customer->detail->postal_code;
                $this->country = $customer->detail->country ?? 'Sénégal';
            }
            
            $this->searchResults = [];
            $this->customerSearch = '';
        }
    }
    
    // Effacer la sélection du client
    public function clearCustomerSelection()
    {
        $this->customerId = null;
        $this->selectedCustomer = null;
        $this->firstName = '';
        $this->lastName = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->city = '';
        $this->postalCode = '';
        $this->country = 'Sénégal';
    }
    
    // Charger les données d'un client sélectionné depuis la liste déroulante
    public function loadCustomerData()
    {
        if ($this->customerId) {
            $this->selectCustomer($this->customerId);
        } else {
            $this->clearCustomerSelection();
        }
    }
    
    public function completeOrder()
    {
        $this->validate();
        
        // Déterminer l'utilisateur qui passe la commande
        $currentUser = Auth::user();
        $customer = null;
        
        // Si un admin passe commande pour un client existant
        if ($currentUser && $currentUser->isAdmin() && $this->customerId) {
            // Utiliser le client sélectionné
            $customer = User::customers()->find($this->customerId);
            
            // Mettre à jour les informations du client
            $customer->update([
                'firstname' => $this->firstName,
                'lastname' => $this->lastName,
                'name' => $this->firstName . ' ' . $this->lastName,
                'email' => $this->email,
            ]);
        } 
        // Si un client passe commande pour lui-même
        elseif ($currentUser && $currentUser->isCustomer()) {
            $customer = $currentUser;
        }
        // Si nouvel utilisateur (non connecté ou admin créant un nouveau client)
        else {
            // Vérifier si l'email existe déjà
            $existingCustomer = User::customers()->where('email', $this->email)->first();
            
            if ($existingCustomer) {
                // Si l'admin essaie de créer un client qui existe déjà
                if ($currentUser && $currentUser->isAdmin()) {
                    $this->addError('email', 'Un client existe déjà avec cet email. Veuillez le sélectionner dans la recherche.');
                    return;
                }
                
                // Pour les clients normaux, demander de se connecter
                $this->addError('email', 'Un compte existe déjà avec cet email. Veuillez vous connecter.');
                return;
            }
            
            // Créer un nouveau client
            $customer = User::create([
                'firstname' => $this->firstName,
                'lastname' => $this->lastName,
                'name' => $this->firstName . ' ' . $this->lastName,
                'email' => $this->email,
                'type' => User::TYPE_CUSTOMER,
                'password' => Hash::make(Str::random(12)),
            ]);
            
            // Connecter automatiquement le nouveau client (sauf si c'est un admin qui crée)
            if (!$currentUser || !$currentUser->isAdmin()) {
                Auth::login($customer);
            }
        }
        
        // Mettre à jour ou créer les détails du client
        $customer->updateDetails([
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
        ]);
        
        // Créer la commande
        $orderData = [
            'user_id' => $customer->id,
            'tracking_no' => 'ORD' . strtoupper(uniqid()),
            'fullname' => $this->firstName . ' ' . $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'status_message' => 'En cours de traitement',
            'payment_mode' => $this->paymentMethod,
            'payment_id' => $this->paymentMethod === 'card' ? 'pay_' . Str::random(10) : null,
        ];
        
        // Si un admin passe la commande, enregistrer son ID
        if ($currentUser && $currentUser->isAdmin()) {
            $orderData['agent_id'] = $currentUser->id;
            $orderData['back'] = 0; // Commande passée depuis le backoffice
        }
        
        $order = Order::create($orderData);
        
        // Créer les éléments de la commande
        foreach ($this->cart as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_color_id' => $item->product_color_id,
                'quantity' => $item->quantity,
                'price' => $price,
            ]);
            
            // Mettre à jour le stock
            if ($item->product_color_id) {
                $item->product->updateColorStock($item->product_color_id, $item->quantity, 'decrease');
            } else {
                $item->product->updateStock($item->quantity, 'decrease');
            }
        }
        
        // Vider le panier (seulement si l'utilisateur actuel est le client)
        if (Auth::check() && Auth::id() === $customer->id) {
            Cart::where('user_id', auth()->id())->delete();
        }
        
        // Émettre un événement pour mettre à jour le compteur du panier
        $this->dispatch('cart-updated');
        
        // Rediriger vers la page de confirmation
        return redirect()->route('order.confirmation', $order->id)
            ->with('success', 'Votre commande a été passée avec succès!');
    }
    
    public function render()
    {
        return view('livewire.admin.checkout.checkout-page', [
            'isAdmin' => Auth::check() && Auth::user()->isAdmin(),
        ]);
    }
}