<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\User;
use App\Services\Coinbase\Coinbase;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Платежи пользователя
     *
     * @param $id
     * @return array
     */
    public function index($id)
    {
        $user = User::find($id);
        $payments = $user->Payments->toArray();
        return array_reverse($payments);
    }

    /**
     * Создать платеж
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id, Request $request)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
        ]);

        $payment = new Payments([
            'user_id' => $id,
            'type' => $request->input('type'),
            'amount' => $request->input('type') == Payments::TYPE_TEXT['writeOff'] ? '-' . $request->input('amount') : $request->input('amount'),
        ]);

        try {
            $payment->save();
            return response()->json(['message' => 'Payment created!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment creation error!'], 409);
        }
    }

    /**
     * Удалить платеж
     *
     * @param $id
     * @param $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, $paymentId)
    {
        $payment = Payments::find($paymentId);
        $payment->delete();
        return response()->json('Payment deleted!');
    }

    /**
     * Проводим через Coinbase
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CreateCoinbasePayment($id, Request $request)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
        ]);

        Coinbase::$i->SetAccesses((object)[
            'publicKey' => '76324ae2e056733cf41bdba67324194d',
            'secretKey' => '2vLI+v7i7+s3cfZ8Hjem9YphColPLBWordDuLXDQIUN0ANQwPyc4euDpg/i5wXAamCaGJoIKAQVEvtTNCCXK7w==',
            'passPhrase' => 'api'
        ]);

        try {
            if ($request->input('type') == Payments::TYPE_TEXT['writeOff']) {
                Coinbase::$i->WithdrawalsFromPaymentMethods('8e234d4c-a6a9-4d55-84c4-9168be414cc2', $request->input('amount'), '6a23926d-74b6-4373-8434-9d437c2bafb2', 'USD');
            } else {
                Coinbase::$i->DepositFromPaymentMethods('8e234d4c-a6a9-4d55-84c4-9168be414cc2', $request->input('amount'), '6a23926d-74b6-4373-8434-9d437c2bafb2', 'USD');
            }

            $this->store($id, $request);
            return response()->json(['message' => 'Payment created!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment creation error!'], 409);
        }
    }
}