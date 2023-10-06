<?php

use App\Enums\Systems;
use App\Models\Fabric\DefaultPayload;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;

class DefaultPayloadSeeder extends Seeder
{
    // Structure is as follows
    // System Name => Factory Name => Entity Name => Direction
    private const FACTORY_SYSTEM_SCHEMAS = [
        'Shopify' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{ "app_id": 1966818, "billing_address": { "zip": "T0E 0M0", "city": "Drayton Valley", "name": "Christopher Gorski", "phone": "(555)555-5555", "company": null, "country": "Canada", "address1": "2259 Park Ct", "address2": "Apartment 5", "latitude": "45.41634", "province": "Alberta", "last_name": "Gorski", "longitude": "-75.6868", "first_name": "Christopher", "country_code": "CA", "province_code": "AB" }, "browser_ip": "216.191.105.146", "buyer_accepts_marketing": false, "cancel_reason": "customer", "cancelled_at": null, "cart_token": "68778783ad298f1c80c3bafcddeea", "checkout_token": "bd5a8aa1ecd019dd3520ff791ee3a24c", "client_details": { "browser_ip": "216.191.105.146", "user_agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36", "session_hash": "9ad4d1f4e6a8977b9dd98eed1e477643", "browser_width": 1280, "browser_height": 1320, "accept_language": "en-US,en;q=0.9" }, "closed_at": "2008-01-10T11:00:00-05:00", "created_at": "2008-01-10T11:00:00-05:00", "currency": "USD", "current_total_discounts": "10.00", "current_total_discounts_set": { "current_total_discounts_set": { "shop_money": { "amount": "10.00", "currency_code": "CAD" }, "presentment_money": { "amount": "5.00", "currency_code": "EUR" } } }, "current_total_duties_set": { "current_total_duties_set": { "shop_money": { "amount": "164.86", "currency_code": "CAD" }, "presentment_money": { "amount": "105.31", "currency_code": "EUR" } } }, "current_total_price": "10.00", "current_total_price_set": { "current_total_price_set": { "shop_money": { "amount": "30.00", "currency_code": "CAD" }, "presentment_money": { "amount": "20.00", "currency_code": "EUR" } } }, "current_subtotal_price": "10.00", "current_subtotal_price_set": { "current_subtotal_price_set": { "shop_money": { "amount": "30.00", "currency_code": "CAD" }, "presentment_money": { "amount": "20.00", "currency_code": "EUR" } } }, "current_total_tax": "10.00", "current_total_tax_set": { "current_total_tax_set": { "shop_money": { "amount": "30.00", "currency_code": "CAD" }, "presentment_money": { "amount": "20.00", "currency_code": "EUR" } } }, "customer": { "id": 207119551, "note": null, "tags": "loyal", "email": "bob.norman@hostmail.com", "phone": "+13125551212", "state": "disabled", "currency": "USD", "addresses": {}, "last_name": "Norman", "created_at": "2012-03-13T16:09:55-04:00", "first_name": "Bob", "tax_exempt": false, "updated_at": "2012-03-13T16:09:55-04:00", "total_spent": "0.00", "orders_count": "1", "last_order_id": 450789469, "tax_exemptions": {}, "verified_email": true, "default_address": {}, "last_order_name": "#1001", "accepts_marketing": false, "admin_graphql_api_id": "gid://shopify/Customer/207119551", "multipass_identifier": null }, "customer_locale": "en-CA", "discount_applications": { "discount_applications": [ { "type": "manual", "title": "custom discount", "value": "2.0", "value_type": "fixed_amount", "description": "customer deserved it", "target_type": "line_item", "target_selection": "explicit", "allocation_method": "across" }, { "type": "script", "value": "5.0", "value_type": "fixed_amount", "description": "my scripted discount", "target_type": "shipping_line", "target_selection": "explicit", "allocation_method": "across" }, { "code": "SUMMERSALE", "type": "discount_code", "value": "10.0", "value_type": "fixed_amount", "target_type": "line_item", "target_selection": "all", "allocation_method": "across" } ] }, "discount_codes": [ { "code": "SPRING30", "type": "fixed_amount", "amount": "30.00" } ], "email": "bob.norman@hostmail.com", "estimated_taxes": false, "financial_status": "authorized", "fulfillments": [ { "id": 255858046, "status": "failure", "order_id": 450789469, "created_at": "2012-03-13T16:09:54-04:00", "updated_at": "2012-05-01T14:22:25-04:00", "tracking_number": "1Z2345", "tracking_company": "USPS" } ], "fulfillment_status": "partial", "gateway": "shopify_payments", "id": 450789469, "landing_site": "http://www.example.com?source=abc", "line_items": [ { "id": 669751112, "sku": "IPOD-342-N", "name": "IPod Nano - Pink", "grams": 500, "price": "199.99", "title": "IPod Nano", "duties": [ { "id": "2", "tax_lines": [ { "rate": 0.1, "price": "16.486", "title": "VAT", "price_set": { "shop_money": { "amount": "16.486", "currency_code": "CAD" }, "presentment_money": { "amount": "10.531", "currency_code": "EUR" } }, "channel_liable": true } ], "shop_money": { "amount": "164.86", "currency_code": "CAD" }, "presentment_money": { "amount": "105.31", "currency_code": "EUR" }, "admin_graphql_api_id": "gid://shopify/Duty/2", "country_code_of_origin": "CA", "harmonized_system_code": "520300" } ], "vendor": "Apple", "taxable": true, "quantity": 1, "gift_card": false, "price_set": { "shop_money": { "amount": "199.99", "currency_code": "USD" }, "presentment_money": { "amount": "173.30", "currency_code": "EUR" } }, "tax_lines": [ { "rate": 0.13, "price": "25.81", "title": "HST", "price_set": { "shop_money": { "amount": "25.81", "currency_code": "USD" }, "presentment_money": { "amount": "20.15", "currency_code": "EUR" } }, "channel_liable": true } ], "product_id": 7513594, "properties": [ { "name": "custom engraving", "value": "Happy Birthday Mom!" } ], "variant_id": 4264112, "variant_title": "Pink", "total_discount": "5.00", "origin_location": { "id": 1390592786454, "zip": "V7Y 1G5", "city": "Toronto", "name": "Apple", "address1": "700 West Georgia Street", "address2": "1500", "country_code": "CA", "province_code": "ON" }, "requires_shipping": true, "fulfillment_status": "fulfilled", "total_discount_set": { "shop_money": { "amount": "5.00", "currency_code": "USD" }, "presentment_money": { "amount": "4.30", "currency_code": "EUR" } }, "fulfillment_service": "amazon", "discount_allocations": [ { "amount": "5.00", "amount_set": { "shop_money": { "amount": "5.00", "currency_code": "USD" }, "presentment_money": { "amount": "3.96", "currency_code": "EUR" } }, "discount_application_index": 2 } ], "fulfillable_quantity": 1 } ], "location_id": 49202758, "name": "#1001", "note": "Customer changed their mind.", "note_attributes": [ { "name": "custom name", "value": "custom value" } ], "number": 1, "order_number": 1001, "original_total_duties_set": { "original_total_duties_set": { "shop_money": { "amount": "164.86", "currency_code": "CAD" }, "presentment_money": { "amount": "105.31", "currency_code": "EUR" } } }, "payment_details": { "avs_result_code": "Y", "credit_card_bin": "453600", "cvv_result_code": "M", "credit_card_number": "•••• •••• •••• 4242", "credit_card_company": "Visa" }, "payment_terms": { "amount": 70, "currency": "CAD", "due_in_days": 30, "payment_schedules": [ { "amount": 70, "due_at": "2020-08-29T13:02:43-04:00", "currency": "CAD", "issued_at": "2020-07-29T13:02:43-04:00", "completed_at": "null", "expected_payment_method": "shopify_payments" } ], "payment_terms_name": "NET_30", "payment_terms_type": "NET" }, "payment_gateway_names": [ "authorize_net", "Cash on Delivery (COD)" ], "phone": "+557734881234", "presentment_currency": "CAD", "processed_at": "2008-01-10T11:00:00-05:00", "processing_method": "direct", "referring_site": "http://www.anexample.com", "refunds": [ { "id": 18423447608, "note": null, "user_id": null, "order_id": 394481795128, "created_at": "2018-03-06T09:35:37-05:00", "processed_at": "2018-03-06T09:35:37-05:00", "transactions": [], "order_adjustments": [], "refund_line_items": [] } ], "shipping_address": { "zip": "K2P0V6", "city": "Ottawa", "name": "Bob Bobsen", "phone": "555-625-1199", "company": null, "country": "Canada", "address1": "123 Amoebobacterieae St", "address2": "", "latitude": "45.41634", "province": "Ontario", "last_name": "Bobsen", "longitude": "-75.6868", "first_name": "Bob", "country_code": "CA", "province_code": "ON" }, "shipping_lines": [ { "code": "INT.TP", "price": "4.00", "title": "Small Packet International Air", "source": "canada_post", "price_set": { "shop_money": { "amount": "4.00", "currency_code": "USD" }, "presentment_money": { "amount": "3.17", "currency_code": "EUR" } }, "tax_lines": [], "discounted_price": "4.00", "carrier_identifier": "third_party_carrier_identifier", "discounted_price_set": { "shop_money": { "amount": "4.00", "currency_code": "USD" }, "presentment_money": { "amount": "3.17", "currency_code": "EUR" } }, "requested_fulfillment_service_id": "third_party_fulfillment_service_id" } ], "source_name": "web", "subtotal_price": 398, "subtotal_price_set": { "shop_money": { "amount": "141.99", "currency_code": "CAD" }, "presentment_money": { "amount": "90.95", "currency_code": "EUR" } }, "tags": "imported, vip", "tax_lines": [ { "rate": 0.06, "price": 11.94, "title": "State Tax", "channel_liable": true } ], "taxes_included": false, "test": true, "token": "b1946ac92492d2347c6235b4d2611184", "total_discounts": "0.00", "total_discounts_set": { "shop_money": { "amount": "0.00", "currency_code": "CAD" }, "presentment_money": { "amount": "0.00", "currency_code": "EUR" } }, "total_line_items_price": "398.00", "total_line_items_price_set": { "shop_money": { "amount": "141.99", "currency_code": "CAD" }, "presentment_money": { "amount": "90.95", "currency_code": "EUR" } }, "total_outstanding": "5.00", "total_price": "409.94", "total_price_set": { "shop_money": { "amount": "164.86", "currency_code": "CAD" }, "presentment_money": { "amount": "105.31", "currency_code": "EUR" } }, "total_shipping_price_set": { "shop_money": { "amount": "30.00", "currency_code": "USD" }, "presentment_money": { "amount": "0.00", "currency_code": "USD" } }, "total_tax": "11.94", "total_tax_set": { "shop_money": { "amount": "18.87", "currency_code": "CAD" }, "presentment_money": { "amount": "11.82", "currency_code": "EUR" } }, "total_tip_received": "4.87", "total_weight": 300, "updated_at": "2012-08-24T14:02:15-04:00", "user_id": 31522279, "order_status_url": { "order_status_url": "https://checkout.shopify.com/112233/checkouts/4207896aad57dfb159/thank_you_token?key=753621327b9e8a64789651bf221dfe35" } }'
                    ]
                ]
            ],
            'Customers' => [
                'Customers' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"customers":[{"id":0,"email":"","firstname":"","lastname":"","customer_tags":"","tax_exempt":false,"note":"","accepts_marketing":false,"status":false,"order_count":0,"original_created_at":"2022-05-08T15:13:01+01:00","total_spent":"0.00","last_order_name":null,"last_order_id":null,"verified_email":true,"created_at":"2022-05-08 14:13:01","updated_at":"2022-05-08 14:13:02","addresses":[{"hash":"","id":null,"customer_id":0,"company":"","fullname":"","firstname":"","lastname":"","street":"","street_two":"","city":"","region":"","postcode":"","country_code":"","telephone":""}]}]}'
                    ]
                ]
            ]
        ],
        'Peoplevox' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"SalesOrderNumber":"","Customer":{"Name":"","Reference":"","FirstName":"","LastName":"","Phone":"","Mobile":"","Email":"","CreditLimit":"","CreditStatus":"","Wholesaler":"","CustomerType":""},"CustomerPurchaseOrderReferenceNumber":"","ShippingAddressLine1":"","ShippingAddressLine2":"","ShippingAddressCity":"","ShippingAddressRegion":"","ShippingAddressPostcode":"","ShippingAddressCountry":"","ShippingAddressReference":"","InvoiceAddressLine1":"","InvoiceAddressLine2":"","InvoiceAddressCity":"","InvoiceAddressRegion":"","InvoiceAddressPostcode":"","InvoiceAddressCountry":"","InvoiceAddressReference":"","IsPartialShipment":"","Status":"","RequestedDeliveryDate":"","ShippingCost":"","Email":"","ContactName":"","TotalSale":"","Discount":"","TaxPaid":"","CreatedDate":"","PaymentMethod":"","ServiceType":"","ChannelName":"","OnHold":"","Attribute1":"","Attribute2":"","Attribute3":"","Attribute4":"","Attribute5":"","Site":"","DespatchTrackingNumber":"","Items":[{"SalesOrderNumber":"","ItemCode":"","QuantityOrdered":"","RequestedDeliveryDate":"","Line":"","Sequence":"","SalePrice":"","Attribute1":"","Attribute2":"","Attribute3":""}]}'
                    ]
                ]
            ]
        ],
        'BigCommerce' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"orders":{"0":{"id":"","customer_id":"","date_created":"","date_modified":"","date_shipped":"","status_id":"","status":"","subtotal_ex_tax":"","subtotal_inc_tax":"","subtotal_tax":"","base_shipping_cost":"","shipping_cost_ex_tax":"","shipping_cost_inc_tax":"","shipping_cost_tax":"","shipping_cost_tax_class_id":"","base_handling_cost":"","handling_cost_ex_tax":"","handling_cost_inc_tax":"","handling_cost_tax":"","handling_cost_tax_class_id":"","base_wrapping_cost":"","wrapping_cost_ex_tax":"","wrapping_cost_inc_tax":"","wrapping_cost_tax":"","wrapping_cost_tax_class_id":"","total_ex_tax":"","total_inc_tax":"","total_tax":"","items_total":"","items_shipped":"","payment_method":"","payment_provider_id":"","payment_status":"","refunded_amount":"","order_is_digital":"","store_credit_amount":"","gift_certificate_amount":"","ip_address":"","ip_address_v6":"","geoip_country":"","geoip_country_iso2":"","currency_id":"","currency_code":"","currency_exchange_rate":"","default_currency_id":"","default_currency_code":"","staff_notes":"","customer_message":"","discount_amount":"","coupon_discount":"","shipping_address_count":"","is_deleted":"","ebay_order_id":"","cart_id":"","billing_address":{"first_name":"","last_name":"","company":"","street_1":"","street_2":"","city":"","state":"","zip":"","country":"","country_iso2":"","phone":"","email":"","form_fields":{}},"is_email_opt_in":"","credit_card_type":"","order_source":"","channel_id":"","external_source":"","products":{"url":"","resource":""},"shipping_addresses":{"url":"","resource":""},"coupons":{"url":"","resource":""},"external_id":"","external_merchant_id":"","tax_provider_id":"","customer_locale":"","store_default_currency_code":"","store_default_to_transactional_exchange_rate":"","custom_status":""}}}'
                    ]
                ]
            ]
        ],
        'Magento 2' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"orders":{"0":{"adjustment_negative":0,"adjustment_positive":0,"applied_rule_ids":"string","base_adjustment_negative":0,"base_adjustment_positive":0,"base_currency_code":"string","base_discount_amount":0,"base_discount_canceled":0,"base_discount_invoiced":0,"base_discount_refunded":0,"base_grand_total":0,"base_discount_tax_compensation_amount":0,"base_discount_tax_compensation_invoiced":0,"base_discount_tax_compensation_refunded":0,"base_shipping_amount":0,"base_shipping_canceled":0,"base_shipping_discount_amount":0,"base_shipping_discount_tax_compensation_amnt":0,"base_shipping_incl_tax":0,"base_shipping_invoiced":0,"base_shipping_refunded":0,"base_shipping_tax_amount":0,"base_shipping_tax_refunded":0,"base_subtotal":0,"base_subtotal_canceled":0,"base_subtotal_incl_tax":0,"base_subtotal_invoiced":0,"base_subtotal_refunded":0,"base_tax_amount":0,"base_tax_canceled":0,"base_tax_invoiced":0,"base_tax_refunded":0,"base_total_canceled":0,"base_total_due":0,"base_total_invoiced":0,"base_total_invoiced_cost":0,"base_total_offline_refunded":0,"base_total_online_refunded":0,"base_total_paid":0,"base_total_qty_ordered":0,"base_total_refunded":0,"base_to_global_rate":0,"base_to_order_rate":0,"billing_address_id":0,"can_ship_partially":0,"can_ship_partially_item":0,"coupon_code":"string","created_at":"string","customer_dob":"string","customer_email":"string","customer_firstname":"string","customer_gender":0,"customer_group_id":0,"customer_id":0,"customer_is_guest":0,"customer_lastname":"string","customer_middlename":"string","customer_note":"string","customer_note_notify":0,"customer_prefix":"string","customer_suffix":"string","customer_taxvat":"string","discount_amount":0,"discount_canceled":0,"discount_description":"string","discount_invoiced":0,"discount_refunded":0,"edit_increment":0,"email_sent":0,"entity_id":0,"ext_customer_id":"string","ext_order_id":"string","forced_shipment_with_invoice":0,"global_currency_code":"string","grand_total":0,"discount_tax_compensation_amount":0,"discount_tax_compensation_invoiced":0,"discount_tax_compensation_refunded":0,"hold_before_state":"string","hold_before_status":"string","increment_id":"string","is_virtual":0,"order_currency_code":"string","original_increment_id":"string","payment_authorization_amount":0,"payment_auth_expiration":0,"protect_code":"string","quote_address_id":0,"quote_id":0,"relation_child_id":"string","relation_child_real_id":"string","relation_parent_id":"string","relation_parent_real_id":"string","remote_ip":"string","shipping_amount":0,"shipping_canceled":0,"shipping_description":"string","shipping_discount_amount":0,"shipping_discount_tax_compensation_amount":0,"shipping_incl_tax":0,"shipping_invoiced":0,"shipping_refunded":0,"shipping_tax_amount":0,"shipping_tax_refunded":0,"state":"string","status":"string","store_currency_code":"string","store_id":0,"store_name":"string","store_to_base_rate":0,"store_to_order_rate":0,"subtotal":0,"subtotal_canceled":0,"subtotal_incl_tax":0,"subtotal_invoiced":0,"subtotal_refunded":0,"tax_amount":0,"tax_canceled":0,"tax_invoiced":0,"tax_refunded":0,"total_canceled":0,"total_due":0,"total_invoiced":0,"total_item_count":0,"total_offline_refunded":0,"total_online_refunded":0,"total_paid":0,"total_qty_ordered":0,"total_refunded":0,"updated_at":"string","weight":0,"x_forwarded_for":"string","items":[{"additional_data":"string","amount_refunded":0,"applied_rule_ids":"string","base_amount_refunded":0,"base_cost":0,"base_discount_amount":0,"base_discount_invoiced":0,"base_discount_refunded":0,"base_discount_tax_compensation_amount":0,"base_discount_tax_compensation_invoiced":0,"base_discount_tax_compensation_refunded":0,"base_original_price":0,"base_price":0,"base_price_incl_tax":0,"base_row_invoiced":0,"base_row_total":0,"base_row_total_incl_tax":0,"base_tax_amount":0,"base_tax_before_discount":0,"base_tax_invoiced":0,"base_tax_refunded":0,"base_weee_tax_applied_amount":0,"base_weee_tax_applied_row_amnt":0,"base_weee_tax_disposition":0,"base_weee_tax_row_disposition":0,"created_at":"string","description":"string","discount_amount":0,"discount_invoiced":0,"discount_percent":0,"discount_refunded":0,"event_id":0,"ext_order_item_id":"string","free_shipping":0,"gw_base_price":0,"gw_base_price_invoiced":0,"gw_base_price_refunded":0,"gw_base_tax_amount":0,"gw_base_tax_amount_invoiced":0,"gw_base_tax_amount_refunded":0,"gw_id":0,"gw_price":0,"gw_price_invoiced":0,"gw_price_refunded":0,"gw_tax_amount":0,"gw_tax_amount_invoiced":0,"gw_tax_amount_refunded":0,"discount_tax_compensation_amount":0,"discount_tax_compensation_canceled":0,"discount_tax_compensation_invoiced":0,"discount_tax_compensation_refunded":0,"is_qty_decimal":0,"is_virtual":0,"item_id":0,"locked_do_invoice":0,"locked_do_ship":0,"name":"string","no_discount":0,"order_id":0,"original_price":0,"parent_item_id":0,"price":0,"price_incl_tax":0,"product_id":0,"product_type":"string","qty_backordered":0,"qty_canceled":0,"qty_invoiced":0,"qty_ordered":0,"qty_refunded":0,"qty_returned":0,"qty_shipped":0,"quote_item_id":0,"row_invoiced":0,"row_total":0,"row_total_incl_tax":0,"row_weight":0,"sku":"string","store_id":0,"tax_amount":0,"tax_before_discount":0,"tax_canceled":0,"tax_invoiced":0,"tax_percent":0,"tax_refunded":0,"updated_at":"string","weee_tax_applied":"string","weee_tax_applied_amount":0,"weee_tax_applied_row_amount":0,"weee_tax_disposition":0,"weee_tax_row_disposition":0,"weight":0,"parent_item":{},"product_option":{"extension_attributes":{"custom_options":[null],"bundle_options":[null],"downloadable_option":{"downloadable_links":[]},"giftcard_item_option":{},"configurable_item_options":[null]}},"extension_attributes":{"gift_message":{"gift_message_id":0,"customer_id":0,"sender":"string","recipient":"string","message":"string","extension_attributes":{}},"gw_id":"string","gw_base_price":"string","gw_price":"string","gw_base_tax_amount":"string","gw_tax_amount":"string","gw_base_price_invoiced":"string","gw_price_invoiced":"string","gw_base_tax_amount_invoiced":"string","gw_tax_amount_invoiced":"string","gw_base_price_refunded":"string","gw_price_refunded":"string","gw_base_tax_amount_refunded":"string","gw_tax_amount_refunded":"string"}}],"billing_address":{"address_type":"string","city":"string","company":"string","country_id":"string","customer_address_id":0,"customer_id":0,"email":"string","entity_id":0,"fax":"string","firstname":"string","lastname":"string","middlename":"string","parent_id":0,"postcode":"string","prefix":"string","region":"string","region_code":"string","region_id":0,"street":["string"],"suffix":"string","telephone":"string","vat_id":"string","vat_is_valid":0,"vat_request_date":"string","vat_request_id":"string","vat_request_success":0,"extension_attributes":{}},"payment":{"account_status":"string","additional_data":"string","additional_information":["string"],"address_status":"string","amount_authorized":0,"amount_canceled":0,"amount_ordered":0,"amount_paid":0,"amount_refunded":0,"anet_trans_method":"string","base_amount_authorized":0,"base_amount_canceled":0,"base_amount_ordered":0,"base_amount_paid":0,"base_amount_paid_online":0,"base_amount_refunded":0,"base_amount_refunded_online":0,"base_shipping_amount":0,"base_shipping_captured":0,"base_shipping_refunded":0,"cc_approval":"string","cc_avs_status":"string","cc_cid_status":"string","cc_debug_request_body":"string","cc_debug_response_body":"string","cc_debug_response_serialized":"string","cc_exp_month":"string","cc_exp_year":"string","cc_last4":"string","cc_number_enc":"string","cc_owner":"string","cc_secure_verify":"string","cc_ss_issue":"string","cc_ss_start_month":"string","cc_ss_start_year":"string","cc_status":"string","cc_status_description":"string","cc_trans_id":"string","cc_type":"string","echeck_account_name":"string","echeck_account_type":"string","echeck_bank_name":"string","echeck_routing_number":"string","echeck_type":"string","entity_id":0,"last_trans_id":"string","method":"string","parent_id":0,"po_number":"string","protection_eligibility":"string","quote_payment_id":0,"shipping_amount":0,"shipping_captured":0,"shipping_refunded":0,"extension_attributes":{"notification_message":"string","vault_payment_token":{"entity_id":0,"customer_id":0,"public_hash":"string","payment_method_code":"string","type":"string","created_at":"string","expires_at":"string","gateway_token":"string","token_details":"string","is_active":true,"is_visible":true}}},"status_histories":[{"comment":"string","created_at":"string","entity_id":0,"entity_name":"string","is_customer_notified":0,"is_visible_on_front":0,"parent_id":0,"status":"string","extension_attributes":{}}],"extension_attributes":{"shipping_assignments":[{"shipping":{"address":{"street":[]},"method":"string","total":{},"extension_attributes":{}},"items":[{}],"stock_id":0,"extension_attributes":{}}],"payment_additional_info":[{"key":"string","value":"string"}],"company_order_attributes":{"order_id":0,"company_id":0,"company_name":"string","extension_attributes":{}},"applied_taxes":[{"code":"string","title":"string","percent":0,"amount":0,"base_amount":0,"extension_attributes":{"rates":[null]}}],"item_applied_taxes":[{"type":"string","item_id":0,"associated_item_id":0,"applied_taxes":[{}],"extension_attributes":{}}],"converting_from_quote":true,"base_customer_balance_amount":0,"customer_balance_amount":0,"base_customer_balance_invoiced":0,"customer_balance_invoiced":0,"base_customer_balance_refunded":0,"customer_balance_refunded":0,"base_customer_balance_total_refunded":0,"customer_balance_total_refunded":0,"gift_cards":[{"id":0,"code":"string","amount":0,"base_amount":0}],"base_gift_cards_amount":0,"gift_cards_amount":0,"base_gift_cards_invoiced":0,"gift_cards_invoiced":0,"base_gift_cards_refunded":0,"gift_cards_refunded":0,"gift_message":{"gift_message_id":0,"customer_id":0,"sender":"string","recipient":"string","message":"string","extension_attributes":{"entity_id":"string","entity_type":"string","wrapping_id":0,"wrapping_allow_gift_receipt":true,"wrapping_add_printed_card":true}},"gw_id":"string","gw_allow_gift_receipt":"string","gw_add_card":"string","gw_base_price":"string","gw_price":"string","gw_items_base_price":"string","gw_items_price":"string","gw_card_base_price":"string","gw_card_price":"string","gw_base_tax_amount":"string","gw_tax_amount":"string","gw_items_base_tax_amount":"string","gw_items_tax_amount":"string","gw_card_base_tax_amount":"string","gw_card_tax_amount":"string","gw_base_price_incl_tax":"string","gw_price_incl_tax":"string","gw_items_base_price_incl_tax":"string","gw_items_price_incl_tax":"string","gw_card_base_price_incl_tax":"string","gw_card_price_incl_tax":"string","gw_base_price_invoiced":"string","gw_price_invoiced":"string","gw_items_base_price_invoiced":"string","gw_items_price_invoiced":"string","gw_card_base_price_invoiced":"string","gw_card_price_invoiced":"string","gw_base_tax_amount_invoiced":"string","gw_tax_amount_invoiced":"string","gw_items_base_tax_invoiced":"string","gw_items_tax_invoiced":"string","gw_card_base_tax_invoiced":"string","gw_card_tax_invoiced":"string","gw_base_price_refunded":"string","gw_price_refunded":"string","gw_items_base_price_refunded":"string","gw_items_price_refunded":"string","gw_card_base_price_refunded":"string","gw_card_price_refunded":"string","gw_base_tax_amount_refunded":"string","gw_tax_amount_refunded":"string","gw_items_base_tax_refunded":"string","gw_items_tax_refunded":"string","gw_card_base_tax_refunded":"string","gw_card_tax_refunded":"string","pickup_location_code":"string","notification_sent":0,"send_notification":0,"reward_points_balance":0,"reward_currency_amount":0,"base_reward_currency_amount":0}}}}'
                    ]
                ]
            ]
        ],
        'Mirakl' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"orders":{"0":{"acceptance_decision_date":"","can_cancel":false,"can_shop_ship":true,"commercial_id":"","created_date":"","currency_iso_code":"GBP","customer":{"billing_address":{"city":"","country":"","country_iso_code":"","firstname":"","lastname":"","phone":"","state":"","street_1":"","street_2":"","zip_code":""},"customer_id":"","firstname":"","lastname":"","shipping_address":{"city":"","country":"","country_iso_code":"","firstname":"","lastname":"","phone":"","state":"","street_1":"","street_2":"","zip_code":""}},"customer_debited_date":"","customer_directly_pays_seller":false,"customer_notification_email":"","fulfillment":{"center":{"code":"DEFAULT"}},"has_customer_message":false,"has_incident":false,"has_invoice":false,"id":"","last_updated_date":"","leadtime_to_ship":6,"order_additional_fields":[],"order_lines":[{"additional_fields":[],"can_refund":true,"cancelations":[],"commission":{"commission_taxes":[{"amount":"0.0000","code":"TAXDEFAULT","rate":"20.0000"}],"fee":"0.0000","tax":"0.0000","tax_rate":"0.0000","total":"0.0000"},"history":{"created_date":"","debited_date":"","last_updated_date":""},"id":"","index":1,"offer":{"id":12345,"price":"0.0000","product":{"category":{"code":"","label":""},"sku":"","title":""},"sku":"","state_code":""},"price":"0.0000","product_media":[{"media_url":"","mime_type":"JPG","type":""}],"promotions":[],"quantity":1,"refunds":[],"shipping_price":"0.0000","shipping_taxes":[],"status":{"state":""},"taxes":[],"total_price":"0.0000"}],"order_tax_mode":"","paymentType":"","payment_type":"","payment_workflow":"","price":"0.0000","promotions":{"applied_promotions":[],"total_deduced_amount":0},"shipping":{"price":"0.0000","type":{"code":"","label":""},"zone":{"code":"","label":""}},"shipping_deadline":"","status":{"state":""},"total_commission":"0.0000","total_price":"0.0000"}}}'
                    ]
                ]
            ],
            'Products' => [
                'Products' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"offers":{"offer":{"0":{"discount-price":0,"price":"0.00","product-id":"","product-id-type":"SHOP_SKU","quantity":0,"sku":"","state":"","update-delete":"Update"}}}}'
                    ]
                ]
            ],
            'Fulfilment' => [
                'Fulfilments' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"fulfilments":{"0":{"created_at":"","fulfilment_id":"","id":"","items":[{"order_id":"","quantity":1,"sku":""}],"order_id":"","order_number":"","parent_id":"-B","shipmethod":"","tracking_company":"","tracking_numbers":[""],"tracking_urls":""}}}'
                    ]
                ]
            ],
            'Refunds' => [
                'Refunds' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"refunds":{"0":{"currency_code":"GBP","id":"13877258","items":[{"amount":0,"amount_ex_tax":0,"id":"","quantity":1,"tax_amount":0}]}}}'
                    ]
                ]
            ]
        ],
        'Brightpearl' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"customer":{"id":0},"ref":"","placedOn":"2022-05-03T13:32:19+00:00","externalRef":"","delivery":{"address":{"addressFullName":"","companyName":"","addressLine1":"","addressLine2":"","postalCode":"","countryIsoCode":"","telephone":"","mobileTelephone":"","email":""}},"rows":[{"productId":0,"name":"","quantity":1,"taxCode":"T20","net":0,"tax":0}]}'
                    ]
                ]
            ],
            'Fulfilments' => [
                'Fulfilments' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"warehouses":[{"releaseDate":"2022-05-09T05:34:55+00:00","warehouseId":2,"transfer":false,"products":[{"productId":0,"salesOrderRowId":0,"quantity":"1"}]}],"priority":false,"shippingMethodId":1,"labelUri":""}'
                    ]
                ]
            ],
            'Returns' => [
                'Returns' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"customerId":0,"ref":"","placedOn":"2022-05-09T05:40:30+00:00","taxDate":"2022-05-09T05:40:30+00:00","parentId":0,"statusId":"10","warehouseId":"2","externalRef":"Patchworks Return","priceModeCode":"INC","currency":{"code":"GBP","fixedExchangeRate":true,"exchangeRate":1},"delivery":{"date":"2022-05-09T05:40:30+00:00","address":{"addressFullName":"","companyName":"","addressLine1":"","addressLine2":"","addressLine3":"","addressLine4":"","postalCode":"","countryIsoCode":"","telephone":"","mobileTelephone":"","email":""},"shippingMethodId":0},"rows":[{"productId":0,"name":"","quantity":"1","taxCode":"T20","net":1,"tax":1,"nominalCode":"2","externalRef":"2"}]}'
                    ]
                ]
            ],
            'Customers' => [
                'Customers' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"salutation":"","firstName":"","lastName":"","communication":{"emails":{"PRI":{"email":""},"SEC":{"email":""},"TER":{"email":""}},"telephones":{"PRI":""},"websites":[]},"postAddressIds":{"DEF":0,"BIL":0,"DEL":0},"contactStatus":{"current":{"contactStatusId":"2"}},"relationshipToAccount":{"isSupplier":false,"isStaff":false},"marketingDetails":{"isReceiveEmailNewsletter":false}}'
                    ]
                ]
            ],
            'Shipments' => [
                'Shipments' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"orders":[{"id":0,"parentOrderId":0,"orderTypeCode":"SO","reference":"","version":2,"state":{"tax":"READY"},"orderStatus":{"orderStatusId":1,"name":"Draft / Quote"},"tax":{"errors":[]},"orderPaymentStatus":"PAID","stockStatusCode":"SOA","allocationStatusCode":"AAA","shippingStatusCode":"ASS","placedOn":"2022-05-08T18:59:09.000+01:00","createdOn":"2022-05-08T19:01:33.000+01:00","updatedOn":"2022-05-08T19:35:14.000+01:00","createdById":0,"priceListId":0,"priceModeCode":"INC","delivery":{"shippingMethodId":0},"invoices":[{"invoiceReference":"","taxDate":"2022-05-08T00:00:00.000+01:00","dueDate":"2022-05-08T01:00:00.000+01:00"}],"currency":{"accountingCurrencyCode":"GBP","orderCurrencyCode":"GBP","exchangeRate":"1.000000","fixedExchangeRate":true},"totalValue":{"net":"","taxAmount":"","baseNet":"","baseTaxAmount":"","baseTotal":"","total":""},"assignment":{"current":{"staffOwnerContactId":0,"projectId":0,"channelId":0,"leadSourceId":0,"teamId":0}},"parties":{"customer":{"contactId":0,"addressFullName":"","companyName":"","addressLine1":"","addressLine2":"","addressLine3":"","addressLine4":"","postalCode":"","telephone":"","mobileTelephone":"","fax":"","email":"","countryId":0},"delivery":{"addressFullName":"","companyName":"","addressLine1":"","addressLine2":"","addressLine3":"","addressLine4":"","postalCode":"","country":"","telephone":"","mobileTelephone":"","fax":"","email":"","countryId":"","countryIsoCode":"","countryIsoCode3":""},"billing":{"contactId":0,"addressFullName":"","companyName":"","addressLine1":"","addressLine2":"","addressLine3":"","addressLine4":"","postalCode":"","telephone":"","mobileTelephone":"","fax":"","email":"","countryId":0}},"orderRows":[{"orderRowSequence":"10","productId":1287,"productName":"","productSku":"","quantity":{"magnitude":"1.0000"},"itemCost":{"currencyCode":"GBP","value":"0.0000"},"productPrice":{"currencyCode":"GBP","value":"0.0000"},"discountPercentage":"0.00","rowValue":{"taxRate":"","taxCode":"T20","taxCalculator":"manual","rowNet":{"currencyCode":"GBP","value":"19.9900"},"rowTax":{"currencyCode":"GBP","value":"0.0000"},"taxClassId":7},"nominalCode":"4000","composition":{"bundleParent":false,"bundleChild":false,"parentOrderRowId":0},"clonedFromId":0}],"warehouseId":0,"acknowledged":0,"costPriceListId":0,"historicalOrder":false,"orderWeighting":100}]}'
                    ]
                ]
            ],
            'Products' => [
                'Products' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"products":[{"id":0,"brandId":0,"productTypeId":1,"identity":{"sku":"","isbn":"","ean":"","upc":"","mpn":"","barcode":""},"productGroupId":1,"featured":false,"stock":{"stockTracked":true,"weight":{"magnitude":0},"dimensions":{"length":0,"height":0,"width":0,"volume":0}},"financialDetails":{"taxable":false,"taxCode":{"id":7,"code":"T20"}},"salesChannels":[{"salesChannelName":"","productName":"","productCondition":"new","categories":[{"categoryCode":"0"},{"categoryCode":"0"},{"categoryCode":"0"}],"description":{"languageCode":"en","text":"","format":"HTML_FRAGMENT"},"shortDescription":{"languageCode":"en","text":"","format":"HTML_FRAGMENT"}}],"composition":{"bundle":false},"variations":[{"optionId":0,"optionName":"Colour","optionValueId":0,"optionValue":"Red"}],"createdOn":"2019-04-16T22:40:03.000+01:00","updatedOn":"2021-03-15T14:37:46.000Z","nominalCodeStock":"0","nominalCodePurchases":"0","nominalCodeSales":"0","seasonIds":[],"reporting":[],"status":"LIVE","salesPopupMessage":"","warehousePopupMessage":"","version":1615819066000}]}'
                    ]
                ]
            ]
        ],
        Systems::SFTP => [
            'Files' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'csv',
                        'payload' => 'id,title,url,price,sku,special_price,special_price_dt_to,special_price_dt_from,is_active,is_in_stock,Image_URL,store_Id,property_1,type_1,id_1,label_1,type_2,id_2,label_2,type_3,id_3,label_3,type_4,id_4,label_4,type_5,id_5,label_5,type_6,id_6,label_6'
                    ]
                ],
                'Products' => [
                    'pull' => [
                        'type' => 'csv',
                        'payload' => 'id,title,url,price,sku,special_price,special_price_dt_to,special_price_dt_from,is_active,is_in_stock,Image_URL,store_Id,property_1,type_1,id_1,label_1,type_2,id_2,label_2,type_3,id_3,label_3,type_4,id_4,label_4,type_5,id_5,label_5,type_6,id_6,label_6'
                    ]
                ],
                'Stock' => [
                    'pull' => [
                        'type' => 'csv',
                        'payload' => 'id,title,url,price,sku,special_price,special_price_dt_to,special_price_dt_from,is_active,is_in_stock,Image_URL,store_Id,property_1,type_1,id_1,label_1,type_2,id_2,label_2,type_3,id_3,label_3,type_4,id_4,label_4,type_5,id_5,label_5,type_6,id_6,label_6'
                    ]
                ]
            ]
        ],
        'Seko API' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"SalesOrder":{"SalesInvoiceNumber":"string","DocumentIdentifier":"string","NIFNumber":"string","ShippingVAT":0,"ShippingExVAT":0,"CustomsValueTotal":0,"DiscountPriceTotal":0,"GiftWrap":true,"GiftMessage":"string","ShippingTerm":"string","ShippingTermLocation":"string","GiftMessageFrom":"string","GiftMessageTo":"string","ShippingTotal":0,"TaxTotal":0,"SubTotal":0,"OrderType":"string","CurrencyCode":"string","CarrierCustomerAccountNo":"string","CourierName":"string","CourierService":"string","DeliveryAddressLocationType":"string","DoNotPushToDC":true,"GUID":"string","LocationType":"string","Notes":"string","NotificationMethod":"string","OnHold":true,"SalesOrderDate":"2017-10-13T10:58:59.656Z","SalesOrderNumber":"string","SalesOrderReference":"string","ScheduledShipDate":"2017-10-13T10:58:59.656Z","ShipmentTerms":"string","SpecialInstructions":"string","UltimateDestination":"string"},"SalesOrderHeader":{"DCCode":"string"},"ShipToCompany":{"BranchCode":"string","CompanyCategory":"string","CompanyCode":"string","CompanyDescription":"string","LookupDeliveryAddress":true},"DeliveryDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"BillingDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"ForwardingAgent":{"City":"string","CompanyCode":"string","CompanyDescription":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"List":{"SalesOrderLineItem":[{"UnitDiscountPrice":0,"OrderLineMessage":"string","SecondaryCurrencyCode":"string","SecondaryUnitPrice":0,"SecondaryVAT":0,"ASNNumber":"string","CustomsValue":0,"Channel":"string","CountryCode":"string","CurrencyCode":"string","EAN":"string","ExternalDocumentNo":"string","GUID":"string","LineNumber":0,"LotNo":"string","ProductCode":"string","Quantity":0,"UnitPrice":0,"VAT":0}]}}'
                    ]
                ]
            ],
            'Products' => [
                'Products' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"SalesOrder":{"SalesInvoiceNumber":"string","DocumentIdentifier":"string","NIFNumber":"string","ShippingVAT":0,"ShippingExVAT":0,"CustomsValueTotal":0,"DiscountPriceTotal":0,"GiftWrap":true,"GiftMessage":"string","ShippingTerm":"string","ShippingTermLocation":"string","GiftMessageFrom":"string","GiftMessageTo":"string","ShippingTotal":0,"TaxTotal":0,"SubTotal":0,"OrderType":"string","CurrencyCode":"string","CarrierCustomerAccountNo":"string","CourierName":"string","CourierService":"string","DeliveryAddressLocationType":"string","DoNotPushToDC":true,"GUID":"string","LocationType":"string","Notes":"string","NotificationMethod":"string","OnHold":true,"SalesOrderDate":"2017-10-13T10:58:59.656Z","SalesOrderNumber":"string","SalesOrderReference":"string","ScheduledShipDate":"2017-10-13T10:58:59.656Z","ShipmentTerms":"string","SpecialInstructions":"string","UltimateDestination":"string"},"SalesOrderHeader":{"DCCode":"string"},"ShipToCompany":{"BranchCode":"string","CompanyCategory":"string","CompanyCode":"string","CompanyDescription":"string","LookupDeliveryAddress":true},"DeliveryDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"BillingDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"ForwardingAgent":{"City":"string","CompanyCode":"string","CompanyDescription":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"List":{"SalesOrderLineItem":[{"UnitDiscountPrice":0,"OrderLineMessage":"string","SecondaryCurrencyCode":"string","SecondaryUnitPrice":0,"SecondaryVAT":0,"ASNNumber":"string","CustomsValue":0,"Channel":"string","CountryCode":"string","CurrencyCode":"string","EAN":"string","ExternalDocumentNo":"string","GUID":"string","LineNumber":0,"LotNo":"string","ProductCode":"string","Quantity":0,"UnitPrice":0,"VAT":0}]}}'
                    ]
                ]
            ]
        ],
        Systems::INBOUND_API => [
            'InboundAPI' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"id":"string"}',
                    ]
                ],
                'Products' => [
                    'Products' => [
                        'pull' => [
                            'type' => 'json',
                            'payload' => '{"id":"string"}',
                        ]
                    ]
                ]
            ],
        ],
        'Seko' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"Requests":{"Request":{"SalesOrder":{"SalesInvoiceNumber":"string","DocumentIdentifier":"string","NIFNumber":"string","ShippingVAT":1.1,"ShippingExVAT":1.1,"CustomsValueTotal":1.1,"DiscountPriceTotal":1.1,"GiftWrap":true,"GiftMessage":"string","ShippingTerm":"string","ShippingTermLocation":"string","GiftMessageFrom":"string","GiftMessageTo":"string","ShippingTotal":1.1,"TaxTotal":1.1,"SubTotal":1.1,"OrderType":"string","CurrencyCode":"string","CarrierCustomerAccountNo":"string","CourierName":"string","CourierService":"string","DeliveryAddressLocationType":"string","DoNotPushToDC":true,"GUID":"string","LocationType":"string","Notes":"string","NotificationMethod":"string","OnHold":true,"SalesOrderDate":"1970-01-01T00:00:00.001Z","SalesOrderNumber":"string","SalesOrderReference":"string","ScheduledShipDate":"1970-01-01T00:00:00.001Z","ShipmentTerms":"string","SpecialInstructions":"string","UltimateDestination":"string"},"SalesOrderHeader":{"DCCode":"string"},"ShipToCompany":{"BranchCode":"string","CompanyCategory":"string","CompanyCode":"string","CompanyDescription":"string","LookupDeliveryAddress":true},"DeliveryDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"BillingDetails":{"City":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"ForwardingAgent":{"City":"string","CompanyCode":"string","CompanyDescription":"string","ContactCode":"string","CountryCode":"string","County":"string","EmailAddress":"string","FirstName":"string","LastName":"string","Line1":"string","Line2":"string","Line3":"string","Line4":"string","PhoneNumber":"string","PostcodeZip":"string","Title":"string"},"List":{"SalesOrderLineItem":{"UnitDiscountPrice":1.1,"OrderLineMessage":"string","SecondaryCurrencyCode":"string","SecondaryUnitPrice":1.1,"SecondaryVAT":1.1,"ASNNumber":"string","CustomsValue":1.1,"Channel":"string","CountryCode":"string","CurrencyCode":"string","EAN":"string","ExternalDocumentNo":"string","GUID":"string","LineNumber":1,"LotNo":"string","ProductCode":"string","Quantity":1,"UnitPrice":1.1,"VAT":1.1}}}}}'
                    ]
                ]
            ],
            'Products' => [
                'Products' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"Requests":{"Request":{"ProductMaster":{"CountryOfOrigin":"string","Brand":"string","Colour":"string","Size":"string","GroupIdentifier":"string","QCControlled":true,"QCPercentage":1,"SerialNumberControlled":true,"InwardProcessing":true,"WetBond":true,"UnitPrice":1.1,"LeadTime":1,"Category1":"string","Category2":"string","MOQ":1,"Currency":"string","ProductUse":"string","ClientProductStatus":"string","Business":"string","Bonded":true,"SecondaryCurrencyCode":"string","SecondaryPrice":1.1,"StyleCode":"string","EAN":"string","HTSCode":"string","ProductDescription":"string","DateControlled":true,"GrossWeight":1.1,"GrossWeightUOM":"string","LotControlled":true,"Hazardous":true,"ProductCode":"string","ProductLongDescription":"string"},"List":{"Image":{"ImageData":"string","DefaultImage":true},"SupplierMapping":{"SupplierCode":"string","SupplierDescription":"string","UOM":1},"ShipToCompanyMapping":{"BranchCode":"string","CompanyCode":"string","MappedCode":"string"},"ProductCategory":{"CategoryCode":"string"},"ProductIdentifier":{"Name":"string","Values":"string"}}}}}'
                    ]
                ]
            ]
        ],
        'CommerceTools' => [
            'Orders' => [
                'Orders' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"orders":{"0":{"type":"","id":"","version":0,"lastMessageSequenceNumber":0,"createdAt":"","lastModifiedAt":"","lastModifiedBy":{"clientId":"","isPlatformClient":false},"createdBy":{"clientId":"","isPlatformClient":false},"orderNumber":"","customerId":"","customerEmail":"","customerGroup":{"typeId":"","id":""},"locale":"","totalPrice":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"taxedPrice":{"totalNet":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"totalGross":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"taxPortions":[{"rate":0,"amount":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"name":""}],"totalTax":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},"orderState":"","syncInfo":[],"returnInfo":[],"shippingInfo":{"shippingMethodName":"","price":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"shippingRate":{"price":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"freeAbove":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"tiers":[]},"taxRate":{"name":"","amount":0,"includedInPrice":true,"country":"","id":"","subRates":[]},"taxCategory":{"typeId":"","id":""},"deliveries":[],"shippingMethod":{"typeId":"","id":""},"taxedPrice":{"totalNet":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"totalGross":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"totalTax":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},"shippingMethodState":""},"state":{"typeId":"","id":""},"taxMode":"","inventoryMode":"","taxRoundingMode":"","taxCalculationMode":"","origin":"","lineItems":[{"id":"","productId":"","productKey":"","name":{"en-GB":"","en-US":"","de-DE":""},"productType":{"typeId":"","id":"","version":0},"productSlug":{"en-GB":"","en-US":"","de-DE":""},"variant":{"id":0,"sku":"","prices":[{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}}],"images":[{"url":"","label":"","dimensions":{"w":0,"h":0}},{"url":"","label":"","dimensions":{"w":0,"h":0}}],"attributes":[{"name":"","value":{"en-GB":"","en-US":"","de-DE":""}}],"assets":[],"availability":{"channels":{"e83b4d9d-8dd4-40b1-bd05-86c506197169":{"isOnStock":true,"availableQuantity":0,"version":0,"id":""}}}},"price":{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},"quantity":0,"discountedPricePerQuantity":[],"supplyChannel":{"typeId":"","id":""},"taxRate":{"name":"","amount":0,"includedInPrice":true,"country":"","id":"","subRates":[]},"addedAt":"","lastModifiedAt":"","state":[{"quantity":0,"state":{"typeId":"","id":""}}],"priceMode":"","totalPrice":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"taxedPrice":{"totalNet":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"totalGross":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"totalTax":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},"custom":{"type":{"typeId":"","id":""},"fields":{"isMatch":false,"skinId":"","isSkincareMatch":false,"imageUrl":"","customsValue":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"source":"","utmTags":[],"stackHash":"","createdAt":"","emailImageUrl":"","skipFulfillment":false,"linkedHash":"","title":"","type":""}},"lineItemMode":""}],"customLineItems":[],"transactionFee":true,"discountCodes":[],"directDiscounts":[],"cart":{"typeId":"","id":""},"custom":{"type":{"typeId":"","id":""},"fields":{"snapFulfilShippingMethodCode":"","lastAction":"","hasToValidate":false,"shopifyId":"","discountedItemTotal":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"siteId":"","snapFulfilShippingMethodNetSuiteID":"","utmTags":[],"orderNumber":""}},"paymentInfo":{"payments":[{"typeId":"","id":""}]},"shippingAddress":{"id":"","title":"","salutation":"","firstName":"","lastName":"","streetName":"","streetNumber":"","additionalStreetInfo":"","postalCode":"","city":"","region":"","state":"","country":"","company":"","department":"","building":"","apartment":"","pOBox":"","phone":"","mobile":"","email":"","fax":"","additionalAddressInfo":""},"billingAddress":{"id":"","title":"","salutation":"","firstName":"","lastName":"","streetName":"","streetNumber":"","additionalStreetInfo":"","postalCode":"","city":"","region":"","state":"","country":"","company":"","department":"","building":"","apartment":"","pOBox":"","phone":"","mobile":"","email":"","fax":"","additionalAddressInfo":""},"itemShippingAddresses":[],"refusedGifts":[]}}}'
                    ]
                ]
            ],
            'Products' => [
                'Products' => [
                    'pull' => [
                        'type' => 'json',
                        'payload' => '{"products":{"0":{"id":"","version":0,"lastMessageSequenceNumber":0,"createdAt":"","lastModifiedAt":"","lastModifiedBy":{"isPlatformClient":true,"user":{"typeId":"","id":""}},"createdBy":{"isPlatformClient":true,"user":{"typeId":"","id":""}},"productType":{"typeId":"","id":""},"masterData":{"current":{"name":{"en-GB":""},"categories":[],"categoryOrderHints":{},"slug":{"en-GB":""},"metaTitle":{"en-GB":"","en-US":"","de-DE":""},"metaDescription":{"en-GB":"","en-US":"","de-DE":""},"masterVariant":{"id":0,"sku":"","prices":[{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}}],"images":[],"attributes":[],"assets":[],"availability":{"isOnStock":true,"availableQuantity":0,"version":0,"id":""}},"variants":[],"searchKeywords":{}},"staged":{"name":{"en-GB":""},"categories":[],"categoryOrderHints":{},"slug":{"en-GB":""},"metaTitle":{"en-GB":"","en-US":"","de-DE":""},"metaDescription":{"en-GB":"","en-US":"","de-DE":""},"masterVariant":{"id":0,"sku":"","prices":[{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0},"channel":{"typeId":"","id":""}},{"id":"","value":{"type":"","currencyCode":"","centAmount":0,"fractionDigits":0}}],"images":[],"attributes":[],"assets":[],"availability":{"isOnStock":true,"availableQuantity":0,"version":0,"id":""}},"variants":[],"searchKeywords":{}},"published":true,"hasStagedChanges":false},"key":"","taxCategory":{"typeId":"","id":""},"lastVariantId":0}}}'
                    ]
                ]
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::FACTORY_SYSTEM_SCHEMAS as $system => $factories) {
            $matchingSystem = System::firstWhere('name', $system);
            if (is_null($matchingSystem)) {
                continue;
            }

            foreach ($factories as $factory => $entities) {
                $matchingFactory = Factory::firstWhere('name', $factory);
                if (is_null($matchingFactory)) {
                    continue;
                }

                foreach ($entities as $entity => $directions) {
                    $matchingEntity = Entity::firstWhere('name', $entity);
                    if (is_null($matchingEntity)) {
                        continue;
                    }

                    foreach ($directions as $direction => $schemaInfo) {
                        $matchingFactorySystem = FactorySystem::where('system_id', $matchingSystem->id)
                            ->where('factory_id', $matchingFactory->id)
                            ->where('entity_id', $matchingEntity->id)
                            ->where('direction', $direction)
                            ->first();
                        if (is_null($matchingFactorySystem)) {
                            continue;
                        }

                        $factorySystemSchema = FactorySystemSchema::where('factory_system_id', $matchingFactorySystem->id)->where('integration_id', null)->first();
                        if (is_null($factorySystemSchema)) {
                            continue;
                        }

                        $attributes = array_merge(['factory_system_schema_id' => $factorySystemSchema->id], $schemaInfo);
                        DefaultPayload::updateOrCreate(
                            [
                                'factory_system_schema_id' => $factorySystemSchema->id
                            ],
                            $attributes
                        );
                    }
                }
            }
        }
    }
}
