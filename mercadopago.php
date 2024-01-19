<?php
$accessToken = "APP_USR-SEUTOKEN";

function generateUuidV4(){
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$uuid = generateUuidV4();

$data = [
    "transaction_amount" => 1,
    "description" => "PAGAR COM PIX", 
    //"installments" => $_POST['installments'],
    "payment_method_id" => "pix",
    "payer" => [
        "email" => "felopesdosantos@gmail.com",
        "first_name" => "Fernanda",
        "last_name" => "Lopes",
        "identification" => [
            "type" => "CPF",
            "number" => "191191191-00"
        ]
    ]
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken,
    "X-Idempotency-Key: " . $uuid
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

curl_close($ch);
$getPay = json_decode($response, true);
echo $pix = $getPay['point_of_interaction']['transaction_data']['qr_code']; #codigo pix
$idPix = $getPay["id"];


//função para verificar pix
function verifyPaymentStatus($idPix, $accessToken)
{
    try {
        $url = "https://api.mercadopago.com/v1/payments/{$idPix}";

        $headers = [
            "Authorization: Bearer {$accessToken}",
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode == 200) {
            $getPay = json_decode($response, true);
            $status = $getPay["status"];

            if ($status === "approved") {
                $paymentStatus = "\nPAGAMENTO APROVADO";

            } elseif ($status === "pending") {
                $paymentStatus = "\nESPERANDO PAGAMENTO";
                
            } elseif ($status === "cancelled") {
                $paymentStatus = "\nPAGAMENTO CANCELADO";
                
            }

            return $paymentStatus;
        } else {
            echo "Erro HTTP: " . $httpCode;
            return "ERRO";
        }
    } catch (Exception $e) {
        echo "Erro na verificação do pagamento: " . $e->getMessage();
        return "ERRO";
    }
}

while (true) {
$status = verifyPaymentStatus($idPix, $accessToken);
echo $status;
sleep(300);
}

#@EUTHEUZIN