<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #2d3748;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c5aa0;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 3px;
        }
        
        .company-details {
            font-size: 8px;
            color: #4a5568;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .invoice-date {
            font-size: 9px;
            color: #718096;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .status-processing { background: #fef5e7; color: #c05621; }
        .status-confirmed { background: #ebf8ff; color: #2c5aa0; }
        .status-shipped { background: #e6fffa; color: #234e52; }
        .status-delivered { background: #f0fff4; color: #22543d; }
        .status-completed { background: #e6fffa; color: #234e52; }
        .status-cancelled { background: #fff5f5; color: #c53030; }
        
        .client-section {
            display: flex;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .billing-address, .shipping-address {
            flex: 1;
            padding: 10px;
            background: #f7fafc;
            border-radius: 5px;
        }
        
        .shipping-address {
            margin-left: 10px;
        }
        
        .address-title {
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .items-table th {
            background: #2c5aa0;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .items-table tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .item-image {
            width: 30px;
            height: 30px;
            border-radius: 3px;
            object-fit: cover;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-details {
            color: #718096;
            font-size: 8px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals-section {
            display: flex;
            margin-bottom: 15px;
        }
        
        .payment-info {
            flex: 1;
            padding: 10px;
            background: #f0fff4;
            border-radius: 5px;
            border-left: 3px solid #38a169;
            font-size: 9px;
        }
        
        .payment-title {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .payment-method {
            background: #c6f6d5;
            padding: 3px 6px;
            border-radius: 8px;
            font-size: 8px;
            display: inline-block;
            margin-top: 3px;
        }
        
        .totals-table {
            width: 40%;
            margin-left: 10px;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        .totals-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-table tr:last-child td {
            background: #2c5aa0;
            color: white;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 8px;
        }
        
        .summary {
            display: flex;
            justify-content: space-between;
            background: #f7fafc;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-value {
            font-weight: bold;
            color: #2c5aa0;
        }
        
        .thank-you {
            text-align: center;
            padding: 8px;
            background: #ebf8ff;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(44, 90, 160, 0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .mb-1 {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="watermark">FACTURE</div>
    
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">VOTRE ENTREPRISE</div>
                <div class="company-details">
                    123 Rue de l'Commerce, Dakar, Sénégal<br>
                    Tél: +221 33 XXX XX XX • Email: contact@entreprise.com<br>
                    NINEA: 123456789
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-number">N° {{ $order->id }}</div>
                <div class="invoice-date">Date: {{ $order->created_at->format('d/m/Y') }}</div>
                <div class="invoice-date">Heure: {{ $order->created_at->format('H:i') }}</div>
                
                @php
                    $statusClass = match($order->status_message) {
                        'En cours de traitement' => 'status-processing',
                        'Confirmé' => 'status-confirmed',
                        'Expédié' => 'status-shipped',
                        'Livré' => 'status-delivered',
                        'Terminé' => 'status-completed',
                        'Annulé' => 'status-cancelled',
                        default => 'status-processing'
                    };
                @endphp
                <div class="status-badge {{ $statusClass }}">{{ $order->status_message }}</div>
            </div>
        </div>
        
        <!-- Informations client -->
        <div class="client-section">
            <div class="billing-address">
                <div class="address-title">FACTURÉ À</div>
                <div class="font-bold mb-1">{{ $order->fullname }}</div>
                @if($order->email)
                    <div class="mb-1">{{ $order->email }}</div>
                @endif
                @if($order->phone)
                    <div class="mb-1">{{ $order->phone }}</div>
                @endif
                @if($order->user && $order->user->customer_number)
                    <div>N° Client: {{ $order->user->customer_number }}</div>
                @endif
            </div>
            
            <div class="shipping-address">
                <div class="address-title">LIVRÉ À</div>
                <div class="font-bold mb-1">{{ $order->fullname }}</div>
                @if($order->address)
                    <div class="mb-1">{{ $order->address }}</div>
                @endif
                @if($order->city || $order->postal_code)
                    <div class="mb-1">
                        {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}
                    </div>
                @endif
                <div>Sénégal</div>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="summary">
            <div class="summary-item">
                <div>Articles</div>
                <div class="summary-value">{{ $totalItems }}</div>
            </div>
            <div class="summary-item">
                <div>Mode de paiement</div>
                <div class="summary-value">
                    @php
                        $paymentLabels = [
                            'cash' => 'Espèces',
                            'card' => 'Carte',
                            'mobile' => 'Mobile Money',
                            'bank_transfer' => 'Virement',
                        ];
                    @endphp
                    {{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}
                </div>
            </div>
            <div class="summary-item">
                <div>Suivi</div>
                <div class="summary-value">{{ $order->tracking_no ?? 'N/A' }}</div>
            </div>
        </div>
        
        <!-- Articles -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">Image</th>
                    <th style="width: 45%;">Article</th>
                    <th style="width: 15%;" class="text-center">Qté</th>
                    <th style="width: 20%;" class="text-right">Prix Unit.</th>
                    <th style="width: 20%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td class="text-center">
                            @if($item->product && $item->product->images->count() > 0)
                                <img src="{{ public_path('storage/' . $item->product->images->first()->image_path) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="item-image">
                            @else
                                <div style="width: 30px; height: 30px; background: #e2e8f0; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: #a0aec0;">
                                    📦
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="item-name">{{ $item->product ? $item->product->name : 'Produit supprimé' }}</div>
                            <div class="item-details">
                                @if($item->product)
                                    SKU: {{ $item->product->sku }}
                                @endif
                                @if($item->productColor && $item->productColor->color)
                                    • {{ $item->productColor->color->name }}
                                @endif
                            </div>
                        </td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} F</td>
                        <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totaux et Paiement -->
        <div class="totals-section">
            <div class="payment-info">
                <div class="payment-title">INFORMATIONS DE PAIEMENT</div>
                <div class="payment-method">{{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}</div>
                
                @if($order->payment_id)
                    <div class="mb-1">
                        <strong>ID Transaction:</strong> {{ $order->payment_id }}
                    </div>
                @endif
                
                <div>
                    <strong>Date de paiement:</strong> {{ $order->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
            
            <table class="totals-table">
                <tr>
                    <td>Sous-total:</td>
                    <td class="text-right">{{ number_format($orderTotal, 0, ',', ' ') }} F</td>
                </tr>
                <tr>
                    <td>Frais de livraison:</td>
                    <td class="text-right">Gratuit</td>
                </tr>
                <tr>
                    <td><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>{{ number_format($orderTotal, 0, ',', ' ') }} F</strong></td>
                </tr>
            </table>
        </div>
        
        <!-- Message de remerciement -->
        <div class="thank-you">
            <div class="font-bold">Merci pour votre confiance !</div>
            <div>Nous espérons que vous serez satisfait(e) de vos achats.</div>
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <div class="font-bold">VOTRE ENTREPRISE</div>
            <div>
                NINEA: 123456789 • www.votre-site.com • contact@entreprise.com
            </div>
            <div style="margin-top: 5px;">
                Document généré le {{ $generatedAt }} • Valable sans signature
            </div>
        </div>
    </div>
</body>
</html>