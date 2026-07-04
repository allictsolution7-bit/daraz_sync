<script>
(function () {
  var endpoint = 'https://track.pixelfly.io/e';
  var apiKey = '{{PF API Key}}';

  var dlEvent = '{{Event}}';
  if (!dlEvent) dlEvent = 'page_view';

  // Get event ID - use the resolved value directly
  var eventId = '{{event id Variable}}';

  // Only null it out if it's literally undefined/empty
  if (!eventId || eventId === 'undefined' || eventId === '') {
    eventId = null;
  }

  var metaMap = {
    page_view: 'PageView',
    view_item: 'ViewContent',
    add_to_cart: 'AddToCart',
    view_cart: 'ViewCart',
    begin_checkout: 'InitiateCheckout',
    add_payment_info: 'AddPaymentInfo',
    add_shipping_info: 'AddShippingInfo',
    purchase: 'Purchase',
    generate_lead: 'Lead',
    sign_up: 'CompleteRegistration',
    search: 'Search'
  };

  // Helper to check if value is valid (not empty)
  function isValidValue(value) {
    if (value === null || value === undefined) return false;
    var strValue = String(value);
    if (strValue === '' || strValue === 'undefined' || strValue === 'null') return false;
    return true;
  }

  function addUserField(userData, key, value) {
    if (isValidValue(value)) {
      userData[key] = String(value);
    }
  }

  var payload = {
    event: metaMap[dlEvent] || dlEvent,
    value: Number('{{DLV - GA4 - value}}') || 0,
    currency: '{{DLV - GA4 - currency}}' || 'BDT',
    custom_data: {
      url: '{{Page URL}}',
      content_type: 'product'
    },
    user_data: {}
  };

  // Add Facebook browser IDs - try dataLayer first, then cookie
  var fbpValue = '{{DLV - fbp}}' || '{{fb - first party cookie - fbp}}';
  var fbcValue = '{{DLV - fbc}}' || '{{fb - first party cookie - fbc}}';
  addUserField(payload.user_data, 'fbp', fbpValue);
  addUserField(payload.user_data, 'fbc', fbcValue);

  // Add event ID if available (for deduplication)
  if (eventId) {
    payload.event_id = eventId;
  }

  // Add advanced matching data - using Meta's standard field names
  addUserField(payload.user_data, 'fn', '{{DLV - Customer First Name}}');
  addUserField(payload.user_data, 'ln', '{{DLV - Customer Last Name}}');
  addUserField(payload.user_data, 'em', '{{DLV - Customer Email}}');
  addUserField(payload.user_data, 'ph', '{{DLV - Customer Phone}}');
  addUserField(payload.user_data, 'ct', '{{DLV - Customer City}}');
  addUserField(payload.user_data, 'country', '{{DLV - Customer Country}}');
  addUserField(payload.user_data, 'external_id', '{{DLV - External ID}}');

  // Add ecommerce items - GTM returns this as an array, not a string
  var ecommerceItems = {{DLV - GA4 - ecommerce item}};

  // Handle the items array
  if (ecommerceItems && typeof ecommerceItems === 'object') {
    var items = Array.isArray(ecommerceItems) ? ecommerceItems : [ecommerceItems];

    if (items.length > 0) {
      payload.custom_data.content_ids = items.map(function(item) {
        return String(item.item_id || item.id || '');
      }).filter(Boolean);

      payload.custom_data.contents = items.map(function(item) {
        return {
          id: String(item.item_id || item.id || ''),
          quantity: Number(item.quantity) || 1,
          item_price: Number(item.price) || 0
        };
      }).filter(function(item) { return item.id; });

      payload.custom_data.num_items = items.reduce(function(sum, item) {
        return sum + (Number(item.quantity) || 1);
      }, 0);
    }
  }

  // Add additional custom data
  var contentName = '{{DLV - FB - Content Name}}';
  if (isValidValue(contentName)) {
    payload.custom_data.content_name = contentName;
  }

  var contentCategory = '{{DLV - FB - Content Category}}';
  if (isValidValue(contentCategory)) {
    payload.custom_data.content_category = contentCategory;
  }

  var transactionId = '{{DLV - GA4 - transaction_id}}';
  if (isValidValue(transactionId)) {
    payload.custom_data.order_id = transactionId;
  }

  var shippingCost = '{{DLV - GA4 - shipping cost}}';
  if (isValidValue(shippingCost)) {
    payload.custom_data.shipping = Number(shippingCost);
  }

  // Send to PixelFly
  fetch(endpoint, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-PF-Key': apiKey },
    body: JSON.stringify(payload),
    keepalive: true,
    signal: AbortSignal.timeout(5000)
  }).catch(function() {});
})();
</script>
