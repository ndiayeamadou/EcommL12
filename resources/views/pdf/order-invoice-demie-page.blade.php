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
            font-size: 8px;
            line-height: 1.15;
            color: #2d3748;
            background: #fff;
            height: 595px;
        }
        
        .half-page {
            height: 297.5px;
            max-width: 800px;
            margin: 0 auto;
            padding: 8px 10px;
            position: relative;
        }
        
        /* Ligne de séparation horizontale pointillée */
        .page-separator {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 1px;
            background: repeating-linear-gradient(
                90deg,
                transparent,
                transparent 3px,
                #cbd5e0 3px,
                #cbd5e0 6px
            );
            z-index: 10;
        }
        
        /* Header compact */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #2c5aa0;
        }
        
        .company-brand {
            flex: 1;
        }
        
        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 1px;
        }
        
        .company-details {
            font-size: 6.5px;
            color: #4a5568;
            line-height: 1.1;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 1px;
        }
        
        .invoice-number {
            font-size: 9px;
            font-weight: bold;
        }
        
        .invoice-date {
            font-size: 7px;
            color: #718096;
        }
        
        .status-badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 6px;
            font-size: 6.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
        }
        
        .status-processing { background: #fef5e7; color: #c05621; border: 0.5px solid #ed8936; }
        .status-confirmed { background: #ebf8ff; color: #2c5aa0; border: 0.5px solid #4299e1; }
        .status-shipped { background: #e6fffa; color: #234e52; border: 0.5px solid #38b2ac; }
        .status-delivered { background: #f0fff4; color: #22543d; border: 0.5px solid #48bb78; }
        .status-completed { background: #e6fffa; color: #234e52; border: 0.5px solid #38b2ac; }
        .status-cancelled { background: #fff5f5; color: #c53030; border: 0.5px solid #f56565; }
        
        /* NOUVELLE SECTION CLIENT - COMPACTÉE HORIZONTALEMENT */
        .client-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            gap: 4px;
        }
        
        .client-card {
            flex: 1;
            background: #f8fafc;
            padding: 4px;
            border-radius: 3px;
            border-left: 2px solid;
            min-height: 35px;
        }
        
        .billing-card {
            border-left-color: #2c5aa0;
        }
        
        .shipping-card {
            border-left-color: #38a169;
        }
        
        .tracking-card {
            border-left-color: #d69e2e;
        }
        
        .card-title {
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 2px;
            font-size: 7px;
            display: flex;
            align-items: center;
        }
        
        .card-title::before {
            content: "■";
            margin-right: 2px;
            font-size: 6px;
        }
        
        .client-data {
            font-size: 6.5px;
            line-height: 1.1;
        }
        
        .client-name {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 1px;
        }
        
        .tracking-code {
            font-family: 'Courier New', monospace;
            background: #edf2f7;
            padding: 1px 2px;
            border-radius: 2px;
            font-size: 6px;
            border: 0.5px dashed #cbd5e0;
        }
        
        /* Table compacte */
        .compact-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 7px;
        }
        
        .compact-table th {
            background: #2c5aa0;
            color: white;
            padding: 3px 2px;
            text-align: left;
            font-weight: bold;
            font-size: 6.5px;
        }
        
        .compact-table td {
            padding: 3px 2px;
            border-bottom: 0.5px solid #e2e8f0;
            vertical-align: top;
        }
        
        .compact-table tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 7px;
        }
        
        .item-details {
            color: #718096;
            font-size: 6px;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .quantity-badge {
            background: #ebf8ff;
            color: #2c5aa0;
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 6.5px;
            font-weight: bold;
            border: 0.5px solid #bee3f8;
        }
        
        /* Section totaux compacte */
        .totals-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            margin-top: 4px;
        }
        
        .payment-summary {
            background: #f0fff4;
            padding: 5px;
            border-radius: 3px;
            border-left: 2px solid #38a169;
        }
        
        .payment-method {
            background: #c6f6d5;
            padding: 1px 3px;
            border-radius: 4px;
            font-size: 6.5px;
            display: inline-block;
            margin-bottom: 2px;
            font-weight: bold;
        }
        
        .totals-summary {
            background: #ebf8ff;
            padding: 5px;
            border-radius: 3px;
            border-left: 2px solid #4299e1;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-size: 7px;
        }
        
        .grand-total {
            font-weight: bold;
            color: #2c5aa0;
            border-top: 0.5px solid #cbd5e0;
            padding-top: 1px;
            margin-top: 1px;
        }
        
        /* Footer compact */
        .invoice-footer {
            margin-top: 5px;
            padding-top: 3px;
            border-top: 0.5px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 6px;
        }
        
        .thank-you-note {
            background: linear-gradient(135deg, #ebf8ff, #e6fffa);
            padding: 3px;
            border-radius: 2px;
            margin-bottom: 3px;
            font-size: 6.5px;
            text-align: center;
            border: 0.5px solid #bee3f8;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 50px;
            color: rgba(44, 90, 160, 0.03);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
        
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 2px; }
    </style>
</head>
<body>
    <div class="half-page">
        <!-- Ligne de séparation pointillée -->
        <div class="page-separator"></div>
        
        <div class="watermark">FACTURE</div>
        
        <!-- En-tête compact -->
        <div class="invoice-header">
            <div class="company-brand">
                <div class="company-name">BEAUTE AFRICAINE SHOP</div>
                <div class="company-details">
                    {{-- 123 Rue de l'Commerce, Dakar<br> --}}
                    Marché Central, Mbour, en face village artisanal<br>
                    {{-- Tél: +221 33 XXX XX XX • NINEA: 123456789 --}}
                    Tél: +221 76 631 23 88 • NINEA: 012402351
                </div>
            </div>
            <div class="invoice-meta">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-number">N° {{ $order->id }} • 
                    <span class="invoice-date">{{ $order->tracking_no }}</span>
                </div>
                <div class="invoice-date">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                
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
        
        <!-- NOUVELLE VERSION - INFORMATIONS CLIENT COMPACTÉES HORIZONTALEMENT -->
        <div class="client-grid">
            <!-- Côté gauche - FACTURATION -->
            <div class="client-card billing-card">
                <div class="card-title">FACTURATION</div>
                <div class="client-data">
                    <div class="client-name">{{ $order->fullname }}</div>
                    @if($order->email)
                        <div class="mb-1">{{ $order->email }}</div>
                    @endif
                    @if($order->phone)
                        <div>{{ $order->phone }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Au milieu - LIVRAISON -->
            <div class="client-card shipping-card">
                <div class="card-title">LIVRAISON</div>
                <div class="client-data">
                    <div class="client-name">{{ $order->fullname }}</div>
                    @if($order->address)
                        <div class="mb-1">{{ $order->address }}</div>
                    @endif
                    @if($order->city || $order->postal_code)
                        <div>{{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}, Sénégal</div>
                    @endif
                </div>
            </div>
            
            <!-- Côté droit - SUIVI -->
            <div class="client-card tracking-card">
                <div class="card-title">SUIVI</div>
                <div class="client-data">
                    @if($order->tracking_no)
                        <div class="tracking-code">{{ $order->tracking_no }}</div>
                    @else
                        <div>En préparation</div>
                    @endif
                    @if($order->user && $order->user->customer_number)
                        <div class="mt-1">Client: {{ $order->user->customer_number }}</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tableau des articles compact -->
        <table class="compact-table">
            <thead>
                <tr>
                    <th style="width: 25px;" class="text-center">#</th>
                    <th>Article</th>
                    <th style="width: 25px;" class="text-center">Qté</th>
                    <th style="width: 50px;" class="text-right">Prix</th>
                    <th style="width: 60px;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                    <tr>
                        <td class="text-center">
                            <span class="icon-bullet">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <div class="item-name">{{ $item->product ? $item->product->name : 'Produit supprimé' }}</div>
                            <div class="item-details">
                                @if($item->product)
                                    Ref: {{ $item->product->sku }}
                                @endif
                                @if($item->productColor && $item->productColor->color)
                                    • {{ $item->productColor->color->name }}
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="quantity-badge">{{ $item->quantity }}</span>
                        </td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} F</td>
                        <td class="text-right font-bold">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Section totaux et paiement -->
        <div class="totals-container">
            <div class="payment-summary">
                <div class="card-title">PAIEMENT</div>
                <div>
                    @php
                        $paymentLabels = [
                            'cash' => 'ESPÈCES',
                            'card' => 'CARTE', 
                            'mobile' => 'MOBILE MONEY',
                            'bank_transfer' => 'VIREMENT',
                        ];
                    @endphp
                    <div class="payment-method">
                        {{ $paymentLabels[$order->payment_mode] ?? strtoupper($order->payment_mode) }}
                    </div>
                    
                    @if($order->payment_id)
                        <div class="mb-1">
                            <strong>Ref:</strong> <span class="tracking-code">{{ $order->payment_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="totals-summary">
                <div class="card-title">MONTANT</div>
                <div>
                    <div class="total-row">
                        <span>Sous-total:</span>
                        <span>{{ number_format($orderTotal, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="total-row">
                        <span>Livraison:</span>
                        <span>Gratuit</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>TOTAL TTC:</span>
                        <span>{{ number_format($orderTotal, 0, ',', ' ') }} F</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message de remerciement -->
        <div class="thank-you-note">
            <span class="font-bold">Merci pour votre confiance !</span> 
            • Service: +221 76 631 23 88 
            • www.beauteafricaine.com
        </div>
        
        <!-- Pied de page -->
        <div class="invoice-footer">
            <div>Facture générée le {{ $generatedAt }} • Valable sans signature</div>
        </div>
    </div>
</body>
</html>