{
  event: "select_item",
  gtm: {uniqueEventId: 7, start: 1765254508832},
  page: {
    title: "Bazarei - Your Ultimate Online Shop",
    location: "https://bazarei.com/?gtm_debug=1765254508330",
    path: "/"
  },
  ecommerce: {
    item_list_id: "/",
    item_list_name: "Bazarei - Your Ultimate Online Shop",
    items: [
      {
        item_id: "194",
        item_name: "Sunglass",
        price: 500,
        item_category: "",
        index: 29,
        quantity: 1
      }
    ]
  },
  timestamp: "2025-12-09T04:28:47.652Z"
}

{
  event: "view_item",
  gtm: {uniqueEventId: 5, start: 1765254530061},
  page: {
    title: "Sunglass - Bazarei",
    location: "https://bazarei.com/product/194/sunglass",
    path: "/product/194/sunglass"
  },
  ecommerce: {
    currency: "BDT",
    value: 500,
    items: [
      {
        item_id: "194",
        item_name: "Sunglass",
        price: 500,
        item_category: "Man",
        item_variant: "",
        quantity: 1
      }
    ]
  },
  timestamp: "2025-12-09T04:28:50.149Z"
}

{
  event: "begin_checkout",
  gtm: {uniqueEventId: 5, start: 1765254537015},
  page: {
    title: "Bazarei - Your Ultimate Online Shop",
    location: "https://bazarei.com/checkout",
    path: "/checkout"
  },
  ecommerce: {
    currency: "BDT",
    value: 500,
    coupon: "",
    items: [
      {
        item_id: "194",
        item_name: "Sunglass",
        price: 500,
        item_category: "Man",
        item_variant: `Quantity: 1
                                     • Unit Price: 500৳ each`,
        quantity: 1,
        index: 1
      }
    ]
  },
  timestamp: "2025-12-09T04:28:57.077Z"
}

{
  event: "submit_purchase",
  gtm: {uniqueEventId: 8, start: 1765254537015},
  page: {
    title: "Bazarei - Your Ultimate Online Shop",
    location: "https://bazarei.com/checkout",
    path: "/checkout"
  },
  ecommerce: {
    currency: "BDT",
    value: 500,
    coupon: "",
    items: [
      {
        item_id: "194",
        item_name: "Sunglass",
        price: 500,
        item_category: "Man",
        item_variant: `Quantity: 1
                                     • Unit Price: 500৳ each`,
        quantity: 1,
        index: 1
      }
    ],
    transaction_id: "PENDING-1765254538761",
    tax: 0,
    shipping: 0
  },
  timestamp: "2025-12-09T04:28:58.761Z"
}

{
  event: "purchase",
  gtm: {uniqueEventId: 5, start: 1765254547529},
  page: {
    title: "Bazarei - Your Ultimate Online Shop",
    location: "https://bazarei.com/thank-you/943",
    path: "/thank-you/943"
  },
  ecommerce: {
    transaction_id: "943",
    value: 100,
    shipping: 80,
    currency: "BDT",
    items: [
      {
        item_id: "189",
        item_name: "Colorful Golf Interactive Ball with feature Cat T" +
                   "oy 1pcs",
        item_category: "Women",
        price: 100,
        item_variant: "Quantity: 1 × ৳100.00",
        quantity: 1,
        index: 1
      }
    ]
  },
  content_ids: ["189"],
  user_data: {
    name: "Md Mahedi Hasan",
    address: "Bangladesh, jashore, Jashore, Jashore, Jashore",
    upazila: "",
    city: "",
    country: "Bangladesh",
    phone: "01779542051"
  },
  timestamp: "2025-12-09T04:29:07.538Z"
}