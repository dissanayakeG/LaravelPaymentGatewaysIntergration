<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TWController extends Controller
{
    private $baseURL;
    private $aToken;

    public function __construct()
    {
        $this->baseURL = 'https://api.sandbox.transferwise.tech/';
        $this->aToken = '47fbed82-4ac9-46fc-86d2-c6e079bb7667';
    }

    public function index()
    {
        //get user profile details
        $personProfile = $this->_getProfile();
        $personalAccountId = $personProfile[0]['id'];
        $businessAccountId = $personProfile[1]['id'];

        //create quote
        $quoteResponse = $this->_createQuote($personalAccountId);

        //create recipient account
        $recipientAccountDetails = $this->_createRecipientAccount($personalAccountId);

        //create transfer
        $transferDetails = $this->_createTransfer($recipientAccountDetails['id'], $quoteResponse['id']);

        //fund transfer
        $fundTransferDetails = $this->_fundTransfer($personalAccountId, $transferDetails['id']);

        dd($fundTransferDetails, $transferDetails, $recipientAccountDetails['id'], $quoteResponse['id'], $personalAccountId, $businessAccountId);
    }

    private function _getProfile()
    {
        $url = $this->baseURL . 'v1/profiles';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->aToken,
        ])->get($url);
        return json_decode($response->body(), true);
    }

    private function _createQuote($profileId)
    {
        $url = $this->baseURL . 'v2/quotes';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->aToken,
            'Content-Type' => 'application/json'
        ])
            ->post($url, [
                "sourceCurrency" => "GBP",
                "targetCurrency" => "USD",
                "sourceAmount" => 100,
                "targetAmount" => null,
                "profile" => $profileId
            ]);

        return json_decode($response->body(), true);
    }

    private function _createRecipientAccount($profileId)
    {
        $url = $this->baseURL . 'v1/accounts';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->aToken,
            'Content-Type' => 'application/json'
        ])
            ->post($url, [
                "currency" => "GBP",
                "type" => "sort_code",
                "profile" => $profileId,
                "accountHolderName" => "Ann Johnson",
                "legalType" => "PRIVATE",
                "details" => [
                    "sortCode" => "231470",
                    "accountNumber" => "28821822"
                ]
            ]);

        return json_decode($response->body(), true);
    }

    private function _createTransfer($recipientAccId, $quoteUuid)
    {
        $url = $this->baseURL . 'v1/transfers';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->aToken,
            'Content-Type' => 'application/json'
        ])
            ->post($url, [
                "targetAccount" => $recipientAccId,
                "quoteUuid" => $quoteUuid,
                "customerTransactionId" => $quoteUuid,
                "details" => [
                    "reference" => "to my friend",
                    "transferPurpose" => "verification.transfers.purpose.pay.bills",
                    "sourceOfFunds" => "verification.source.of.funds.other"
                ]
            ]);

        return json_decode($response->body(), true);

    }

    private function _fundTransfer($profileId, $transferId)
    {
        $url = $this->baseURL . 'v3/profiles/' . $profileId . '/transfers/' . $transferId . '/payments';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->aToken,
            'Content-Type' => 'application/json'
        ])
            ->post($url, [
                "type" => "BALANCE"
            ]);

        return json_decode($response->body(), true);

    }
}
