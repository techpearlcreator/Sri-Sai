<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= htmlspecialchars(langField($product, 'name')) ?></h1>
        <p><a href="<?= $baseUrl ?>/shop"><?= __('shop.back') ?></a></p>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Product Detail -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-product-detail">

            <!-- Product Image -->
            <div class="srisai-product-detail__image">
                <?php if (!empty($product->featured_image)): ?>
                    <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($product->featured_image) ?>" alt="<?= htmlspecialchars(langField($product, 'name')) ?>">
                <?php else: ?>
                    <div class="srisai-product-card__placeholder srisai-product-card__placeholder--large">
                        <span><?= $product->category === 'book' ? '📖' : '🪔' ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info + Order Form -->
            <div class="srisai-product-detail__info">
                <span class="srisai-product-card__category"><?= ucfirst(str_replace('_', ' ', $product->category)) ?></span>
                <h2><?= htmlspecialchars(langField($product, 'name')) ?></h2>
                <div class="srisai-product-detail__price">Rs.<?= number_format($product->price, 2) ?></div>

                <?php if ((int) $product->stock_qty > 0): ?>
                    <span class="srisai-badge srisai-badge--success"><?= __('shop.in_stock') ?> (<?= (int) $product->stock_qty ?> <?= __('shop.available') ?>)</span>
                <?php else: ?>
                    <span class="srisai-badge srisai-badge--muted"><?= __('shop.out_of_stock') ?></span>
                <?php endif; ?>

                <?php if (!empty($product->description)): ?>
                    <div class="srisai-product-detail__desc">
                        <?= nl2br(htmlspecialchars(langField($product, 'description'))) ?>
                    </div>
                <?php endif; ?>

                <hr>

                <?php if ((int) $product->stock_qty > 0): ?>
                <!-- ===== ORDER FORM ===== -->
                <h3><?= __('shop.order_title') ?></h3>
                <div id="orderAlert" class="alert" style="display:none;"></div>

                <form id="orderForm" autocomplete="off">
                    <input type="hidden" id="order_product_id" value="<?= (int) $product->id ?>">
                    <input type="hidden" id="order_product_price" value="<?= (float) $product->price ?>">
                    <input type="hidden" id="order_zone_id" value="">
                    <input type="hidden" id="order_delivery_charge" value="0">
                    <input type="hidden" id="order_lat" value="">
                    <input type="hidden" id="order_lng" value="">
                    <input type="hidden" id="order_distance_km" value="">

                    <!-- Customer Details -->
                    <div class="srisai-form-row">
                        <div class="srisai-form-group">
                            <label for="o_name"><?= __('label.name') ?> <span style="color:red;">*</span></label>
                            <input type="text" id="o_name" required placeholder="<?= __('label.fullname_ph') ?>">
                        </div>
                        <div class="srisai-form-group">
                            <label for="o_phone"><?= __('label.phone') ?> <span style="color:red;">*</span></label>
                            <input type="tel" id="o_phone" required placeholder="<?= __('label.mobile_ph') ?>">
                        </div>
                    </div>
                    <div class="srisai-form-group">
                        <label for="o_email"><?= __('label.email') ?></label>
                        <input type="email" id="o_email" placeholder="<?= __('text.optional') ?>">
                    </div>

                    <!-- Quantity -->
                    <div class="srisai-form-group">
                        <label for="o_qty"><?= __('shop.qty') ?></label>
                        <input type="number" id="o_qty" value="1" min="1" max="<?= (int) $product->stock_qty ?>">
                    </div>

                    <!-- Delivery Address -->
                    <div class="srisai-form-group">
                        <label for="o_address"><?= __('shop.delivery_address') ?> <span style="color:red;">*</span></label>
                        <textarea id="o_address" rows="3" required placeholder="<?= __('shop.address_ph') ?>"></textarea>
                    </div>

                    <!-- Map Location Picker -->
                    <div class="srisai-form-group">
                        <label><?= __('shop.map_pick_location') ?> <span style="color:red;">*</span></label>
                        <p style="font-size:0.82rem; color:var(--text-light); margin:0 0 0.5rem;">
                            <?= __('shop.map_instructions') ?>
                        </p>

                        <!-- Address Search Box -->
                        <div class="srisai-map-search">
                            <input type="text" id="mapSearchInput" placeholder="<?= __('shop.map_search_ph') ?>" autocomplete="off">
                            <button type="button" id="mapSearchBtn" class="srisai-btn srisai-btn--outline srisai-btn--sm">
                                <?= __('shop.map_search_btn') ?>
                            </button>
                        </div>
                        <div id="mapSearchResults" class="srisai-map-results" style="display:none;"></div>

                        <!-- Leaflet Map -->
                        <div id="deliveryMap" class="srisai-delivery-map"></div>

                        <!-- Check Delivery Button -->
                        <button type="button" id="checkDeliveryBtn" class="srisai-btn srisai-btn--outline srisai-btn--sm" style="margin-top:0.75rem;" disabled>
                            <?= __('shop.check_delivery') ?>
                        </button>
                        <span id="mapCoordLabel" style="font-size:0.8rem; color:var(--text-light); margin-left:0.5rem;"></span>
                    </div>

                    <!-- Delivery Zone Info (hidden until check) -->
                    <div id="deliveryInfo" style="display:none; margin-bottom:1rem;">
                        <div class="srisai-delivery-info">
                            <span class="srisai-badge srisai-badge--success" id="deliveryZoneName"></span>
                            <span style="font-size:0.82rem; color:var(--text-light);" id="deliveryDistanceLabel"></span>
                            <strong><?= __('shop.delivery_charge') ?>: <span id="deliveryChargeDisplay">Rs.0.00</span></strong>
                        </div>
                    </div>
                    <div id="deliveryError" class="alert alert-error" style="display:none;"></div>

                    <!-- Price Summary -->
                    <div class="srisai-order-summary" id="orderSummary" style="display:none;">
                        <div class="srisai-order-summary__row">
                            <span><?= __('shop.product_price') ?></span>
                            <span id="summaryProductPrice">Rs.<?= number_format($product->price, 2) ?></span>
                        </div>
                        <div class="srisai-order-summary__row">
                            <span><?= __('shop.delivery_charge') ?></span>
                            <span id="summaryDelivery">Rs.0.00</span>
                        </div>
                        <div class="srisai-order-summary__row srisai-order-summary__total">
                            <strong><?= __('shop.total') ?></strong>
                            <strong id="summaryTotal">Rs.<?= number_format($product->price, 2) ?></strong>
                        </div>
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary" id="orderBtn" disabled>
                        <?= __('shop.pay_now') ?>
                    </button>
                    <p style="font-size:0.8rem; color:#888; margin-top:0.5rem;"><?= __('shop.check_pincode_first') ?></p>
                </form>
                <!-- ===== END ORDER FORM ===== -->

                <?php else: ?>
                <div class="srisai-alert srisai-alert--warning">
                    <p><?= __('shop.out_of_stock_msg') ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ((int) $product->stock_qty > 0): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function() {
    var productPrice = <?= (float) $product->price ?>;
    var productId    = <?= (int) $product->id ?>;
    var baseUrl      = '<?= $baseUrl ?>';
    var deliveryConfirmed = false;

    // ── Map setup ────────────────────────────────────────────────
    var defaultLat = 12.9716;   // Chennai centre
    var defaultLng = 80.2209;

    var map = L.map('deliveryMap').setView([defaultLat, defaultLng], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    var marker = null;

    function placeMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                var pos = marker.getLatLng();
                setLocation(pos.lat, pos.lng);
            });
        }
        map.panTo([lat, lng]);
        setLocation(lat, lng);
    }

    function setLocation(lat, lng) {
        document.getElementById('order_lat').value        = lat.toFixed(8);
        document.getElementById('order_lng').value        = lng.toFixed(8);
        document.getElementById('order_distance_km').value = '';
        document.getElementById('order_zone_id').value    = '';
        document.getElementById('order_delivery_charge').value = '0';

        document.getElementById('mapCoordLabel').textContent =
            lat.toFixed(5) + ', ' + lng.toFixed(5);

        document.getElementById('checkDeliveryBtn').disabled = false;

        // Reset confirmed state
        deliveryConfirmed = false;
        document.getElementById('deliveryInfo').style.display  = 'none';
        document.getElementById('deliveryError').style.display = 'none';
        document.getElementById('orderSummary').style.display  = 'none';
        document.getElementById('orderBtn').disabled = true;
    }

    // Click on map to drop pin
    map.on('click', function(e) {
        placeMarker(e.latlng.lat, e.latlng.lng);
    });

    // ── Address search (Nominatim) ────────────────────────────────
    var searchInput   = document.getElementById('mapSearchInput');
    var searchBtn     = document.getElementById('mapSearchBtn');
    var searchResults = document.getElementById('mapSearchResults');

    function doSearch() {
        var q = searchInput.value.trim();
        if (!q) return;
        searchBtn.disabled = true;
        searchBtn.textContent = '...';
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';

        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=in&q=' + encodeURIComponent(q), {
            headers: { 'Accept-Language': 'en', 'User-Agent': 'SriSaiMission/1.0' }
        })
        .then(function(r) { return r.json(); })
        .then(function(results) {
            searchBtn.disabled = false;
            searchBtn.textContent = '<?= __('shop.map_search_btn') ?>';
            if (!results.length) {
                searchResults.innerHTML = '<div class="srisai-map-result-item" style="color:var(--text-light);">No results found</div>';
                searchResults.style.display = 'block';
                return;
            }
            results.forEach(function(item) {
                var div = document.createElement('div');
                div.className = 'srisai-map-result-item';
                div.textContent = item.display_name;
                div.addEventListener('click', function() {
                    placeMarker(parseFloat(item.lat), parseFloat(item.lon));
                    map.setView([parseFloat(item.lat), parseFloat(item.lon)], 15);
                    searchInput.value = item.display_name;
                    searchResults.style.display = 'none';
                });
                searchResults.appendChild(div);
            });
            searchResults.style.display = 'block';
        })
        .catch(function() {
            searchBtn.disabled = false;
            searchBtn.textContent = '<?= __('shop.map_search_btn') ?>';
        });
    }

    searchBtn.addEventListener('click', doSearch);
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
    });

    // Close search results on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#mapSearchResults') && !e.target.closest('#mapSearchBtn') && !e.target.closest('#mapSearchInput')) {
            searchResults.style.display = 'none';
        }
    });

    // ── Check Delivery ────────────────────────────────────────────
    var qtyInput     = document.getElementById('o_qty');
    var checkBtn     = document.getElementById('checkDeliveryBtn');
    var deliveryInfo = document.getElementById('deliveryInfo');
    var deliveryError= document.getElementById('deliveryError');
    var orderSummary = document.getElementById('orderSummary');
    var orderBtn     = document.getElementById('orderBtn');
    var alertEl      = document.getElementById('orderAlert');

    qtyInput.addEventListener('input', updateSummary);

    function updateSummary() {
        var qty      = parseInt(qtyInput.value) || 1;
        var delivery = parseFloat(document.getElementById('order_delivery_charge').value) || 0;
        var total    = (productPrice * qty) + delivery;

        document.getElementById('summaryProductPrice').textContent = 'Rs.' + (productPrice * qty).toFixed(2);
        document.getElementById('summaryDelivery').textContent     = 'Rs.' + delivery.toFixed(2);
        document.getElementById('summaryTotal').textContent        = 'Rs.' + total.toFixed(2);
    }

    checkBtn.addEventListener('click', function() {
        var lat = document.getElementById('order_lat').value;
        var lng = document.getElementById('order_lng').value;
        if (!lat || !lng) return;

        checkBtn.disabled = true;
        checkBtn.textContent = '<?= __('text.loading') ?>';
        deliveryError.style.display = 'none';
        deliveryInfo.style.display  = 'none';
        orderSummary.style.display  = 'none';

        fetch(baseUrl + '/shop/delivery-charge?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                checkBtn.disabled = false;
                checkBtn.textContent = '<?= __('shop.check_delivery') ?>';

                if (!data.success) {
                    deliveryError.textContent   = data.error;
                    deliveryError.style.display = 'block';
                    orderBtn.disabled = true;
                    deliveryConfirmed = false;
                    return;
                }

                document.getElementById('order_zone_id').value         = data.zone_id;
                document.getElementById('order_delivery_charge').value = data.charge;
                document.getElementById('order_distance_km').value     = data.distance_km;
                document.getElementById('deliveryZoneName').textContent = data.zone_name;
                document.getElementById('deliveryChargeDisplay').textContent = 'Rs.' + parseFloat(data.charge).toFixed(2);
                document.getElementById('deliveryDistanceLabel').textContent = '(' + data.distance_km + ' km)';

                deliveryInfo.style.display   = 'block';
                orderSummary.style.display   = 'block';
                orderBtn.disabled = false;
                deliveryConfirmed = true;
                updateSummary();
            })
            .catch(function() {
                checkBtn.disabled = false;
                checkBtn.textContent = '<?= __('shop.check_delivery') ?>';
                deliveryError.textContent   = '<?= __('text.error') ?>';
                deliveryError.style.display = 'block';
            });
    });

    // ── Submit Order ──────────────────────────────────────────────
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!deliveryConfirmed) {
            deliveryError.textContent   = '<?= __('shop.check_pincode_first') ?>';
            deliveryError.style.display = 'block';
            return;
        }

        var qty         = parseInt(qtyInput.value) || 1;
        var delivery    = parseFloat(document.getElementById('order_delivery_charge').value) || 0;
        var name        = document.getElementById('o_name').value.trim();
        var phone       = document.getElementById('o_phone').value.trim();
        var email       = document.getElementById('o_email').value.trim();
        var address     = document.getElementById('o_address').value.trim();
        var zoneId      = document.getElementById('order_zone_id').value;
        var lat         = parseFloat(document.getElementById('order_lat').value);
        var lng         = parseFloat(document.getElementById('order_lng').value);
        var distanceKm  = parseFloat(document.getElementById('order_distance_km').value);

        if (!name || !phone || !address) {
            alertEl.className     = 'alert alert-error';
            alertEl.textContent   = '<?= __('shop.fill_required') ?>';
            alertEl.style.display = 'block';
            return;
        }

        orderBtn.disabled    = true;
        orderBtn.textContent = '<?= __('text.loading') ?>';
        alertEl.style.display = 'none';

        fetch(baseUrl + '/shop/create-order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id:      productId,
                quantity:        qty,
                name:            name,
                phone:           phone,
                email:           email,
                address:         address,
                zone_id:         zoneId,
                delivery_charge: delivery,
                lat:             lat,
                lng:             lng,
                distance_km:     distanceKm
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.error) {
                alertEl.className     = 'alert alert-error';
                alertEl.textContent   = data.error;
                alertEl.style.display = 'block';
                orderBtn.disabled    = false;
                orderBtn.textContent = '<?= __('shop.pay_now') ?>';
                return;
            }

            // Open Razorpay
            var options = {
                key:         data.key,
                amount:      Math.round(data.amount * 100),
                currency:    data.currency,
                name:        data.name,
                description: data.description,
                order_id:    data.order_id,
                prefill:     data.prefill,
                handler: function(response) {
                    orderBtn.textContent = '<?= __('text.verifying') ?>';

                    fetch(baseUrl + '/shop/verify-payment', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            razorpay_order_id:   response.razorpay_order_id,
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature:  response.razorpay_signature,
                            shop_order_id:       data.shop_order_id
                        })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(verifyData) {
                        alertEl.style.display = 'block';
                        if (verifyData.success) {
                            alertEl.className   = 'alert alert-success';
                            alertEl.textContent = verifyData.message;
                            document.getElementById('orderForm').reset();
                            orderBtn.style.display = 'none';
                            deliveryInfo.style.display  = 'none';
                            orderSummary.style.display  = 'none';
                            if (marker) { map.removeLayer(marker); marker = null; }
                            document.getElementById('mapCoordLabel').textContent = '';
                        } else {
                            alertEl.className   = 'alert alert-error';
                            alertEl.textContent = verifyData.error || '<?= __('text.verification_failed') ?>';
                            orderBtn.disabled    = false;
                            orderBtn.textContent = '<?= __('shop.pay_now') ?>';
                        }
                    });
                },
                modal: {
                    ondismiss: function() {
                        orderBtn.disabled    = false;
                        orderBtn.textContent = '<?= __('shop.pay_now') ?>';
                    }
                },
                theme: { color: '#5F2C70' }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        })
        .catch(function() {
            alertEl.className     = 'alert alert-error';
            alertEl.textContent   = '<?= __('text.error') ?>';
            alertEl.style.display = 'block';
            orderBtn.disabled    = false;
            orderBtn.textContent = '<?= __('shop.pay_now') ?>';
        });
    });
})();
</script>
<?php endif; ?>
