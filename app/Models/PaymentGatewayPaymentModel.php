<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayPaymentModel extends Model
{
	protected $table = 'app_payment_gateway_payment_list';
    public $primaryKey = 'pgpl_id';
    public $timestamps = true;

    protected $fillable = [
    	'booking_token', 'transaction_id', 'currency', 'total_amount', 'sessionkey', 'initiate_status',
    	'ssl_payment_status', 'ssl_validation_id', 'ssl_store_amount', 'ssl_card_type', 'ssl_card_number', 'ssl_bank_transaction_id', 'ssl_card_issuer', 'ssl_card_brand', 'ssl_card_issuer_country', 'ssl_card_issuer_country_code', 'ssl_currency_type', 'ssl_currency_amount', 'ssl_verify_sign', 'ssl_verify_key', 'ssl_risk_level', 'ssl_risk_title', 'ssl_transaction_time',
    	'customer_name', 'customer_email', 'customer_add1', 'customer_city', 'customer_postcode', 'customer_country', 'customer_phone',
    	'status', 'existence',
       'added_by', 'activity_token'
    ];

    public function bookingDetails(){
    	return $this->belongsTo('App\Models\TripBookingDetailsModel', 'booking_token', 'token');
    }

}
