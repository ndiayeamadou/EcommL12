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
#[Title('Modifier le Produit')]
class ProductEdit extends Component
{
    use WithFileUploads;

    public Product $product;

    // Propriétés de base
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('nullable|string|max:255')]
    public $slug = '';
    
    #[Validate('required|string')]
    public $description = '';
    
    #[Validate('nullable|string')]
    public $short_description = '';
    
    #[Validate('nullable|string|max:50')]
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
    public $newImages = [];
    
    public $imagesToDelete = [];
    public $primaryImageId = null;

    // Catégories et tags
    #[Validate('required|exists:categories,id')]
    public $category_id = '';
    
    public $secondary_categories = [];
    public $tags = [];
    public $tagInput = '';
    public $popularTags = [];

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

    // Statistiques
    public $showStats = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->availableColors = Color::active()->get();
        
        // Remplir les données du produit
        $this->fill([
            'name' => $product->name,
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'slug' => $product->slug,
            'description' => $product->description,
            'short_description' => $product->short_description,
            'sku' => $product->sku,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'manage_stock' => $product->manage_stock,
            'in_stock' => $product->in_stock,
            'is_featured' => $product->is_featured,
            'status' => $product->status,
            'tags' => $product->tags ?? [],
            'weight' => $product->weight,
            'dimensions' => $product->dimensions ?? ['length' => '', 'width' => '', 'height' => ''],
            'productAttributes' => $product->attributes ?? [],
        ]);

        // Remplir les stocks de couleurs
        foreach ($this->availableColors as $color) {
            $productColor = $product->colors()->where('color_id', $color->id)->first();
            $this->colorStocks[$color->id] = $productColor ? $productColor->pivot->quantity : 0;
        }

        // Définir l'image principale
        $primaryImage = $product->primaryImage;
        if ($primaryImage) {
            $this->primaryImageId = $primaryImage->id;
        }

        // Métadonnées SEO
        $metaData = $product->meta_data ?? [];
        $this->meta_title = $metaData['meta_title'] ?? $product->name;
        $this->meta_description = $metaData['meta_description'] ?? '';
        $this->meta_keywords = $metaData['meta_keywords'] ?? '';

        // Tags populaires
        $this->popularTags = Product::whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->take(10)
            ->values()
            ->toArray();
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
        if ($this->sale_price && $this->sale_price >= $value) {
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

    public function addPopularTag($tag)
    {
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Supprimer de la base de données
            $image->delete();
            
            // Si c'était l'image principale, réinitialiser
            if ($this->primaryImageId === $imageId) {
                $this->primaryImageId = null;
            }
            
            $this->dispatch('image-deleted', [
                'message' => 'Image supprimée avec succès!'
            ]);
        }
    }

    public function setPrimaryImage($imageId)
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            // Retirer le statut principal des autres images
            ProductImage::where('product_id', $this->product->id)
                        ->where('id', '!=', $imageId)
                        ->update(['is_primary' => false]);
            
            // Définir cette image comme principale
            $image->update(['is_primary' => true]);
            
            $this->primaryImageId = $imageId;
            
            $this->dispatch('image-updated', [
                'message' => 'Image principale définie avec succès!'
            ]);
        }
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

    public function toggleStats()
    {
        $this->showStats = !$this->showStats;
    }

    private function validateStep()
    {
        switch ($this->currentStep) {
            case 1:
                $this->validate([
                    'name' => 'required|string|max:255',
                    'category_id' => 'required|exists:categories,id',
                    'description' => 'required|string',
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

        // Mise à jour du produit
        $this->product->update([
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

        // Traitement des nouvelles images
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $index => $image) {
                $path = $image->store('products', 'public');
                
                $isPrimary = ($this->primaryImageId === null && $index === 0);
                
                ProductImage::create([
                    'product_id' => $this->product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $index
                ]);
                
                if ($isPrimary) {
                    $this->primaryImageId = ProductImage::latest()->first()->id;
                }
            }
        }

        // Gestion des stocks par couleur
        $this->product->colors()->detach();
        foreach ($this->colorStocks as $colorId => $quantity) {
            if ($quantity > 0) {
                ProductColor::create([
                    'product_id' => $this->product->id,
                    'color_id' => $colorId,
                    'quantity' => $quantity
                ]);
            }
        }

        // Suppression des images marquées pour suppression
        foreach ($this->imagesToDelete as $imageId) {
            $image = ProductImage::find($imageId);
            if ($image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        }

        $this->imagesToDelete = [];

        $this->dispatch('product-updated', [
            'message' => 'Produit mis à jour avec succès!'
        ]);
    }

    public function duplicate()
    {
        $newProduct = $this->product->replicate();
        $newProduct->name = $this->product->name . ' (Copie)';
        $newProduct->slug = Str::slug($newProduct->name);
        $newProduct->sku = 'PRD-' . strtoupper(Str::random(8));
        $newProduct->save();

        // Dupliquer les images
        foreach ($this->product->images as $image) {
            $newImagePath = 'products/' . Str::random(40) . '.' . pathinfo($image->image_path, PATHINFO_EXTENSION);
            
            // Copier le fichier image
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->copy($image->image_path, $newImagePath);
            }
            
            ProductImage::create([
                'product_id' => $newProduct->id,
                'image_path' => $newImagePath,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order
            ]);
        }

        // Dupliquer les stocks de couleurs
        foreach ($this->product->colors as $color) {
            ProductColor::create([
                'product_id' => $newProduct->id,
                'color_id' => $color->id,
                'quantity' => $color->pivot->quantity
            ]);
        }

        session()->flash('success', 'Produit dupliqué avec succès!');
        return $this->redirect(route('admin.products.edit', $newProduct), navigate: true);
    }

    public function delete()
    {
        // Supprimer les images
        foreach ($this->product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        // Supprimer les associations de couleurs
        $this->product->colors()->detach();

        // Supprimer le produit
        $this->product->delete();

        session()->flash('success', 'Produit supprimé avec succès!');
        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit', [
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }
}
