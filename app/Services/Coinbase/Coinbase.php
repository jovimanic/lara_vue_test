<?php

namespace App\Services\Coinbase;

class Coinbase
{
    /**
     * Singleton ??
     *
     * @var Coinbase|null
     */
    static ?Coinbase $i = null;

    /**
     * Публичный ключ API
     *
     * @var string
     */
    private string $publicKey;

    /**
     * Секретный ключ API
     *
     * @var string
     */
    private string $secretKey;

    /**
     * Какое-то слово для восстановления чего-то =))
     *
     * @var string
     */
    private string $passPhrase;

    /**
     * Aдрес API
     *
     * @var string
     */
    private string $apiUrl;

    /**
     * Guzzle client
     *
     * @var null
     */
    private $client = null;

    /**
     * Метод API
     *
     * @var string
     */
    private string $methodApi;

    /**
     * Тип запроса GET/POST
     *
     * @var string
     */
    private string $requestMethod;

    /**
     * У боевого появляется /v2
     *
     * @var string
     */
    private string $prefUrl;

    /**
     * Время на сервере API
     *
     * @var string
     */
    private string $timeOnServerApi;

    /**
     * Тело запроса
     *
     * @var array|null
     */
    private array|null $requestBody = null;

    /**
     * Последняя синхронизация времени с API сервером
     *
     * @var null
     */
    private $lastUpdate = null;

    /**
     * Время на API сервере
     *
     * @var string
     */
    private string $apiServerTime;

    /**
     * Конструктор
     *
     * @param bool $dev
     * @param string $passPhrase
     */
    public function __construct(private bool $dev)
    {
        if ($this->dev) {
            $this->apiUrl = 'https://api-public.sandbox.exchange.coinbase.com';
            $this->prefUrl = '';
        } else {
            $this->apiUrl = 'https://api.coinbase.com/v2';
            $this->prefUrl = '/v2';
        }
    }

    /**
     * Заполним публичный ключ
     *
     * @param string $value
     */
    public function SetPublicKey(string $value)
    {
        $this->publicKey = $value;
    }

    /**
     * Заполним секретный ключ
     *
     * @param string $value
     */
    public function SetSecretKey(string $value)
    {
        $this->secretKey = $value;
    }

    /**
     * Заполним секретное слово
     *
     * @param string $value
     */
    public function SetPassPhrase(string $value)
    {
        $this->passPhrase = $value;
    }

    /**
     * Установить доступы пачкой
     *
     * @param object $value
     */
    public function SetAccesses(object $values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Статический контруктор
     *
     * @param bool $devMode
     * @return Coinbase
     */
    public static function Create(bool $devMode = true): Coinbase
    {
        if (!Coinbase::$i) {
            Coinbase::$i = new Coinbase($devMode);
        }
        return Coinbase::$i;
    }

    /**
     * Берем время с API сервера
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function TimeOnApiServer(): string
    {
        // Не будем ломать API сервера своими частыми запросами
        if ($this->lastUpdate === null || $this->lastUpdate + 25 < now()->timestamp) {

            $this->lastUpdate = now()->timestamp;
            $response = $this->client->request('GET', $this->apiUrl . '/time', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $this->apiServerTime =  json_decode($response->getBody())->epoch;
        }

        return $this->apiServerTime;
    }

    /**
     * Отправляем запрос
     *
     * @param bool $auth
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function Send()
    {
        $this->client = $this->client ?? new \GuzzleHttp\Client();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->timeOnServerApi = $this->TimeOnApiServer();

        $headers['CB-ACCESS-TIMESTAMP'] = $this->timeOnServerApi;
        $headers['CB-ACCESS-KEY'] = $this->publicKey;
        $headers['CB-ACCESS-SIGN'] = $this->MakeSign();

        if ($this->dev) {
            $headers['CB-ACCESS-PASSPHRASE'] = $this->passPhrase;
        }

        $data = [];
        $data['headers'] = $headers;
        if ($this->requestBody) {
            $data['body'] = json_encode($this->requestBody);
            $this->requestBody = null;
        }

        try {
            $response = $this->client->request($this->requestMethod, $this->apiUrl . $this->methodApi, $data);
        } catch (\Exception $e) {
            // отправляем разрабам уведомление о поломке
            return (object)['error' => true, 'message' => 'Error API'];
        }

        return json_decode($response->getBody());
    }

    /**
     * Генерим подпись
     *
     * @return string
     */
    private function MakeSign(): string
    {
        $data = $this->timeOnServerApi . strtoupper($this->requestMethod) . $this->prefUrl . $this->methodApi . ($this->requestBody ? json_encode($this->requestBody) : '');
        return base64_encode(hash_hmac('sha256', $data, base64_decode($this->secretKey), true));
    }

    /**
     * Профили
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetProfiles()
    {
        $this->methodApi = '/profiles';
        $this->requestMethod = 'GET';
        return $this->Send();
    }

    /**
     * Создать профиль
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CreateProfile($name)
    {
        $this->methodApi = '/profiles';
        $this->requestMethod = 'POST';
        $this->requestBody = [
            'name' => $name
        ];
        return $this->Send();
    }

    /**
     * Переименовать профиль
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function RenameProfile($profileId, $name)
    {
        $this->methodApi = '/profiles/' . $profileId;
        $this->requestMethod = 'POST';
        $this->requestBody = [
            'name' => $name
        ];
        return $this->Send();
    }

    /**
     * Аккаунты профиля
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetAccounts()
    {
        $this->methodApi = '/accounts';
        $this->requestMethod = 'GET';
        return $this->Send();
    }

    /**
     * Методы оплаты
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function PaymentMethods()
    {
        $this->methodApi = '/payment-methods';
        $this->requestMethod = 'GET';
        return $this->Send();
    }

    /**
     * Пополнить
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function DepositFromPaymentMethods($profileId, $amount, $paymentMethodId, $currency)
    {
        $this->methodApi = '/deposits/payment-method';
        $this->requestMethod = 'POST';
        $this->requestBody = [
            'profile_id' => $profileId,
            'amount' => $amount,
            'payment_method_id' => $paymentMethodId,
            'currency' => $currency,
        ];
        return $this->Send();
    }

    /**
     * Списать
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function WithdrawalsFromPaymentMethods($profileId, $amount, $paymentMethodId, $currency)
    {
        $this->methodApi = '/withdrawals/payment-method';
        $this->requestMethod = 'POST';
        $this->requestBody = [
            'profile_id' => $profileId,
            'amount' => $amount,
            'payment_method_id' => $paymentMethodId,
            'currency' => $currency,
        ];
        return $this->Send();
    }

    /**
     * Трансферы
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Transfers($profileId)
    {
        $this->methodApi = '/transfers';
        $this->requestMethod = 'GET';
        $this->requestBody = [
            'profile_id' => $profileId,
        ];
        return $this->Send();
    }

}