<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Commande #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .logo-section {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .invoice-section {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            text-align: right;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        
        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 11px;
            color: #666;
        }
        
        .addresses {
            display: table;
            width: 100%;
            margin: 30px 0;
        }
        
        .address-block {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .address-block + .address-block {
            margin-left: 20px;
        }
        
        .address-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        
        .address-content {
            font-size: 11px;
            line-height: 1.4;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }
        
        .status-processing { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1d4ed8; }
        .status-shipped { background: #e0e7ff; color: #5b21b6; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .items-table tr:hover {
            background: #f3f4f6;
        }
        
        .item-image {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        
        .item-name {
            font-weight: bold;
            color: #374151;
            margin-bottom: 3px;
        }
        
        .item-sku {
            font-size: 9px;
            color: #6b7280;
        }
        
        .color-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid #d1d5db;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        .quantity-badge {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .price {
            font-weight: bold;
            color: #059669;
        }
        
        .totals-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        
        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        
        .payment-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #059669;
        }
        
        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }
        
        .payment-details {
            font-size: 11px;
            line-height: 1.4;
        }
        
        .payment-method {
            background: #ecfdf5;
            padding: 6px 12px;
            border-radius: 15px;
            color: #065f46;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
            margin-top: 5px;
        }
        
        .totals-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .totals-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }
        
        .totals-table tr:last-child td {
            border-bottom: none;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        
        .subtotal-row {
            color: #6b7280;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .footer-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .thank-you {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
            border: 1px solid #93c5fd;
        }
        
        .thank-you-title {
            font-size: 16px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 5px;
        }
        
        .thank-you-text {
            font-size: 11px;
            color: #1e40af;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(37, 99, 235, 0.03);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
    </style>
</head>
<body>
    <div class="watermark">FACTURE</div>
    
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="logo-section">
                <div class="company-name">VOTRE ENTREPRISE</div>
                <div class="company-info">
                    123 Rue de l'Commerce<br>
                    Dakar, Sénégal<br>
                    Tél: +221 33 XXX XX XX<br>
                    Email: contact@entreprise.com<br>
                    NINEA: 123456789
                </div>
            </div>
            <div class="invoice-section">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-details">
                    <div class="invoice-number">N° {{ $order->id }}</div>
                    @if($order->tracking_no)
                        <div class="invoice-date">Suivi: {{ $order->tracking_no }}</div>
                    @endif
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
        </div>
        
        <!-- Adresses -->
        <div class="addresses">
            <div class="address-block">
                <div class="address-title">📧 FACTURÉ À</div>
                <div class="address-content">
                    <div class="font-bold mb-2">{{ $order->fullname }}</div>
                    @if($order->email)
                        <div>✉️ {{ $order->email }}</div>
                    @endif
                    @if($order->phone)
                        <div>📞 {{ $order->phone }}</div>
                    @endif
                    @if($order->user && $order->user->customer_number)
                        <div class="mt-2">N° Client: {{ $order->user->customer_number }}</div>
                    @endif
                </div>
            </div>
            
            <div class="address-block">
                <div class="address-title">🚚 LIVRÉ À</div>
                <div class="address-content">
                    <div class="font-bold mb-2">{{ $order->fullname }}</div>
                    @if($order->address)
                        <div>📍 {{ $order->address }}</div>
                    @endif
                    @if($order->city || $order->postal_code)
                        <div>
                            {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}
                        </div>
                    @endif
                    <div class="mt-2">🌍 Sénégal</div>
                </div>
            </div>
        </div>
        
        <!-- Articles -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Image</th>
                    <th style="width: 40%;">Article</th>
                    <th style="width: 15%;" class="text-center">Quantité</th>
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
                                <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                    📦
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="item-name">{{ $item->product ? $item->product->name : 'Produit supprimé' }}</div>
                            @if($item->product)
                                <div class="item-sku">SKU: {{ $item->product->sku }}</div>
                            @endif
                            @if($item->productColor && $item->productColor->color)
                                <div style="margin-top: 5px; font-size: 10px; color: #6b7280;">
                                    <span class="color-indicator" style="background-color: {{ $item->productColor->color->hex_code }}"></span>
                                    {{ $item->productColor->color->name }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="quantity-badge">{{ number_format($item->quantity) }}</span>
                        </td>
                        <td class="text-right price">{{ number_format($item->price, 0, ',', ' ') }} F</td>
                        <td class="text-right price">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totaux et Paiement -->
        <div class="totals-section">
            <div class="totals-left">
                <div class="payment-info">
                    <div class="payment-title">💳 Informations de Paiement</div>
                    <div class="payment-details">
                        <div><strong>Mode de paiement:</strong></div>
                        @php
                            $paymentLabels = [
                                'cash' => '💵 Espèces',
                                'card' => '💳 Carte bancaire',
                                'mobile' => '📱 Mobile Money',
                                'bank_transfer' => '🏦 Virement bancaire',
                            ];
                        @endphp
                        <div class="payment-method">{{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}</div>
                        
                        @if($order->payment_id)
                            <div class="mt-3">
                                <strong>ID Transaction:</strong><br>
                                <span style="font-family: monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">{{ $order->payment_id }}</span>
                            </div>
                        @endif
                        
                        <div class="mt-3">
                            <strong>Date de paiement:</strong><br>
                            {{ $order->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="totals-right">
                <table class="totals-table">
                    <tr class="subtotal-row">
                        <td><strong>Sous-total HT:</strong></td>
                        <td class="text-right"><strong>{{ number_format($orderTotal, 0, ',', ' ') }} F</strong></td>
                    </tr>
                    <tr class="subtotal-row">
                        <td>TVA (0%):</td>
                        <td class="text-right">0 F</td>
                    </tr>
                    <tr class="subtotal-row">
                        <td>Frais de livraison:</td>
                        <td class="text-right">Gratuit</td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL TTC:</strong></td>
                        <td class="text-right"><strong>{{ number_format($orderTotal, 0, ',', ' ') }} F</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Message de remerciement -->
        <div class="thank-you">
            <div class="thank-you-title">🙏 Merci pour votre confiance !</div>
            <div class="thank-you-text">
                Nous apprécions votre commande et espérons que vous serez entièrement satisfait(e) de vos achats.
            </div>
        </div>
        
        <!-- Informations supplémentaires -->
        <div style="margin-top: 30px; display: table; width: 100%;">
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-right: 20px;">
                <div style="background: #fef3c7; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <div style="font-size: 12px; font-weight: bold; color: #92400e; margin-bottom: 8px;">
                        📋 Informations importantes
                    </div>
                    <div style="font-size: 10px; color: #92400e; line-height: 1.4;">
                        • Conservez cette facture pour tout échange ou retour<br>
                        • Délai de livraison: 2-5 jours ouvrables<br>
                        • Service client: +221 33 XXX XX XX<br>
                        • Retours possibles sous 14 jours
                    </div>
                </div>
            </div>
            
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <div style="background: #ecfdf5; padding: 15px; border-radius: 8px; border-left: 4px solid #059669;">
                    <div style="font-size: 12px; font-weight: bold; color: #065f46; margin-bottom: 8px;">
                        📞 Besoin d'aide ?
                    </div>
                    <div style="font-size: 10px; color: #065f46; line-height: 1.4;">
                        Notre équipe est à votre disposition :<br>
                        📧 support@entreprise.com<br>
                        📞 +221 33 XXX XX XX<br>
                        🕒 Lun-Ven: 8h-18h, Sam: 9h-13h
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Récapitulatif de la commande -->
        <div style="margin-top: 30px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <div style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 15px; text-align: center; border-bottom: 1px solid #d1d5db; padding-bottom: 8px;">
                📊 RÉCAPITULATIF DE LA COMMANDE
            </div>
            
            <div style="display: table; width: 100%; font-size: 11px;">
                <div style="display: table-row;">
                    <div style="display: table-cell; width: 33%; padding: 5px 0; color: #6b7280;">
                        <strong>Nombre d'articles:</strong>
                    </div>
                    <div style="display: table-cell; width: 33%; padding: 5px 0; text-align: center; color: #6b7280;">
                        <strong>Date de commande:</strong>
                    </div>
                    <div style="display: table-cell; width: 34%; padding: 5px 0; text-align: right; color: #6b7280;">
                        <strong>Statut actuel:</strong>
                    </div>
                </div>
                <div style="display: table-row;">
                    <div style="display: table-cell; padding: 2px 0; font-weight: bold; color: #1f2937;">
                        {{ $totalItems }} article{{ $totalItems > 1 ? 's' : '' }}
                    </div>
                    <div style="display: table-cell; padding: 2px 0; text-align: center; font-weight: bold; color: #1f2937;">
                        {{ $order->created_at->format('d/m/Y') }}
                    </div>
                    <div style="display: table-cell; padding: 2px 0; text-align: right; font-weight: bold; color: #1f2937;">
                        {{ $order->status_message }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-title">VOTRE ENTREPRISE</div>
            <div>
                Numéro NINEA: 123456789 | Registre du Commerce: RC/DKR/2023/B/XXX<br>
                Capital social: 1.000.000 F CFA | TVA non applicable, art. 293 B du CGI<br>
                <strong>www.votre-site.com</strong> | contact@entreprise.com
            </div>
            <div style="margin-top: 15px; font-size: 9px; color: #9ca3af;">
                Document généré automatiquement le {{ $generatedAt }}<br>
                Cette facture a été créée électroniquement et est valable sans signature.
            </div>
        </div>
        
        <!-- Code QR ou code-barres (optionnel) -->
        <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <div style="font-size: 9px; color: #6b7280; margin-bottom: 5px;">
                Scannez pour suivre votre commande
            </div>
            <!-- Ici vous pouvez ajouter un QR code généré dynamiquement -->
            <div style="font-family: monospace; font-size: 10px; color: #4b5563; background: #f9fafb; padding: 5px 10px; display: inline-block; border-radius: 4px;">
                {{ $order->tracking_no ?? 'CMD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>
</body>
</html>