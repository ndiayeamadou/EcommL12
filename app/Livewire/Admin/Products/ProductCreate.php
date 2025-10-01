<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductColor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

//#[Layout('layouts.admin')]
#[Title('Créer un Produit')]
class ProductCreate extends Component
{
    use WithFileUploads;

    // Propriétés de base
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('nullable|string|max:255|unique:products,slug')]
    public $slug = '';
    
    #[Validate('nullable|string')]
    public $description = '';
    
    #[Validate('nullable|string')]
    public $short_description = '';
    
    #[Validate('nullable|string|max:50|unique:products,sku')]
    public $sku = '';
    
    #[Validate('required|numeric|min:0')]
    public $price = '';
    
    #[Validate('nullable|numeric|min:0')]
    public $sale_price = '';
    
    #[Validate('required|integer|min:0')]
    public $stock_quantity = 0;
    
    #[Validate('boolean')]
    public $manage_stock = true;
    
    #[Validate('boolean')]
    public $in_stock = true;
    
    #[Validate('boolean')]
    public $is_featured = false;
    
    #[Validate('required|in:active,inactive,draft')]
    public $status = 'active';
    
    #[Validate('nullable|exists:brands,id')]
    public $brand_id = '';

    // Images et médias
    #[Validate('nullable|array|max:10')]
    public $images = [];
    
    public $uploadedImages = [];
    public $primaryImageIndex = 0;

    // Catégories et tags
    #[Validate('required|exists:categories,id')]
    public $category_id = '';
    
    public $tags = [];
    public $tagInput = '';

    // Gestion des couleurs
    public $colorStocks = [];
    public $availableColors = [];

    // Propriétés physiques
    #[Validate('nullable|numeric|min:0')]
    public $weight = '';
    
    public $dimensions = [
        'length' => '',
        'width' => '',
        'height' => '',
    ];

    // Attributs et variations
    public $productAttributes = [];
    public $variations = [];
    public $currentStep = 1;
    public $totalSteps = 4;

    // Métadonnées SEO
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    public function mount()
    {
        $this->sku = 'PRD-' . strtoupper(Str::random(8));
        $this->availableColors = Color::active()->get();
        
        // Initialiser les stocks de couleurs
        foreach ($this->availableColors as $color) {
            $this->colorStocks[$color->id] = 0;
        }
    }

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
        
        if (empty($this->meta_title)) {
            $this->meta_title = $value;
        }
    }

    public function updatedPrice($value)
    {
        if (empty($this->sale_price) || $this->sale_price >= $value) {
            $this->sale_price = null;
        }
    }

    public function addTag()
    {
        if (!empty($this->tagInput) && !in_array($this->tagInput, $this->tags)) {
            $this->tags[] = trim($this->tagInput);
            $this->tagInput = '';
        }
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function addAttribute()
    {
        $this->productAttributes[] = [
            'name' => '',
            'values' => [],
            'value_input' => ''
        ];
    }

    public function removeAttribute($index)
    {
        unset($this->productAttributes[$index]);
        $this->productAttributes = array_values($this->productAttributes);
    }

    public function addAttributeValue($attributeIndex)
    {
        $value = trim($this->productAttributes[$attributeIndex]['value_input']);
        if (!empty($value) && !in_array($value, $this->productAttributes[$attributeIndex]['values'])) {
            $this->productAttributes[$attributeIndex]['values'][] = $value;
            $this->productAttributes[$attributeIndex]['value_input'] = '';
        }
    }

    public function removeAttributeValue($attributeIndex, $valueIndex)
    {
        unset($this->productAttributes[$attributeIndex]['values'][$valueIndex]);
        $this->productAttributes[$attributeIndex]['values'] = array_values($this->productAttributes[$attributeIndex]['values']);
    }

    public function removeImage($index)
    {
        unset($this->uploadedImages[$index]);
        $this->uploadedImages = array_values($this->uploadedImages);
        
        if ($this->primaryImageIndex >= count($this->uploadedImages)) {
            $this->primaryImageIndex = 0;
        }
    }

    public function setPrimaryImage($index)
    {
        $this->primaryImageIndex = $index;
    }

    public function nextStep()
    {
        $this->validateStep();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step <= $this->currentStep || $step == 1) {
            $this->currentStep = $step;
        }
    }

    private function validateStep()
    {
        switch ($this->currentStep) {
            case 1:
                $this->validate([
                    'name' => 'required|string|max:255',
                    'category_id' => 'required|exists:categories,id',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                ]);
                break;
            case 2:
                // Validation des images si nécessaire
                break;
            case 3:
                // Validation des attributs si nécessaire
                break;
        }
    }

    public function save()
    {
        $this->validate();

        // Convert empty strings to null for decimal fields
        $weight = $this->weight === '' ? null : $this->weight;
        $salePrice = $this->sale_price === '' ? null : $this->sale_price;

        // Création du produit
        $product = Product::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id ?: null,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'short_description' => $this->short_description,
            'sku' => $this->sku,
            'price' => $this->price,
            'sale_price' => $salePrice,
            'stock_quantity' => $this->stock_quantity,
            'manage_stock' => $this->manage_stock,
            'in_stock' => $this->in_stock,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'tags' => $this->tags,
            'weight' => $weight,
            'dimensions' => array_filter($this->dimensions),
            'attributes' => array_filter($this->productAttributes, function($attr) {
                return !empty($attr['name']) && !empty($attr['values']);
            }),
            'meta_data' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
            ],
        ]);

        // Traitement des images
        if (!empty($this->images)) {
            foreach ($this->images as $index => $image) {
                $path = $image->store('products', 'public');
                
                $isPrimary = ($index === $this->primaryImageIndex);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $index
                ]);
            }
        }

        // Gestion des stocks par couleur
        foreach ($this->colorStocks as $colorId => $quantity) {
            if ($quantity > 0) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'quantity' => $quantity
                ]);
            }
        }

        session()->flash('success', 'Produit créé avec succès!');
        //return $this->redirect(route('admin.products.edit', $product), navigate: true);
        return $this->redirect(route('admin.products.index', $product), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.products.product-create', [
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }
}
