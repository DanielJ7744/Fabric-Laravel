<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;

class StoreInboundPayloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            $token    = $this->bearerToken();
            $tokenId  = Configuration::forUnsecuredSigner()->parser()->parse($token)->claims()->get('jti');
            $client   = Token::findOrFail($tokenId)->client;
            $endpoint = $this->endpoint_slug;

            return $endpoint->service->sourceConnector()->clients()->where('oauth_clients.id', $client->id)->exists();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
