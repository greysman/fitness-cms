<?php

namespace App\Models\Payment;

use YooKassa\Client;

class YooKassaGateway
{
    public $client;

    public function __construct()
    {
        $this->client = new Client(
            config('yookassa.credentials.store_id'), 
            config('yookassa.credentials.token')
        );
    }


    public function paymentRequest($order, $contact)
    {
        try {
            $idempotenceKey = uniqid('', true);

            $response = $this->client->createPayment([
                'amount' => [
                    'value' => $order->total_amount,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'local' => 'ru_RU',
                    'return_url' => route('yookassa-payment', ['hash', $order->uid])
                ],
                'capture' => true,
                'description' => 'Заказ #' . $order->id,
                'metadata' => [
                    'orderNumber' => $order->id,
                ],
                'receipt' => [
                    'customer' => $this->getCustomer($contact),
                    'items' => $this->getOrderItems($order)
                ]
            ], $idempotenceKey);
        } catch (\Exception $e) {
            $response['error'] = $e;
        }

        return $response;
    }


    protected function getCustomer($contact): array
    {
        if (!$contact->phone && !$contact->email) {
            throw new \Exception("Не указаны контактные данные покупателя", 1);
        }
        $customer = ['fullname' => $contact->fullname];

        if ($contact->phone) {
            $customer['phone'] = $contact->phone;
        }
        if ($contact->email) {
            $customer['email'] = $contact->email;
        }

        return $customer;
    }


    /**
     * Forming array of order's elements
     * 
     *  vat_code: Ставка НДС (тег в 54 ФЗ — 1199).
     *  Для чеков по 54-ФЗ: возможные значения — числа от 1 до 6.
     *      1 - Без НДС
     *      2 - НДС по ставке 0%
     *      3 - НДС по ставке 10%
     *      4 - НДС чека по ставке 20%
     *      5 - НДС чека по расчетной ставке 10/110
     *      6 - НДС чека по расчетной ставке 20/120
     */
    protected function getOrderItems($order): array
    {
        $items = [];

        foreach ($order->items as $key => $item) {
            $items[] = [
                'description' => $item->title,
                'quantity' => $item->quantity,
                'amount' => [
                    'value' => $item->price * $item->quantity,
                    'currency' => 'RUB',
                ],
                'vat_code' => '4',
            ];
        }

        return $items;
    }
}
