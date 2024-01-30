# <3 **[@eutheuzin7](https://github.com/eutheuzin7)**

<h4 align="center">Este é um simples código que gera pix qrcode com api do mercadopago em php puro</h4>
<h4 align="center">AJUSTE O CÓDIGO CONFORME O NECESSÁRIO EM SEU SISTEMA! 😉</h4>

<h2 align="center"><strong>PIX QRCODE IMG 🖼</strong></h2>

```php
$imageData = $getPay['point_of_interaction']['transaction_data']['qr_code_base64']; 
$im = imageCreateFromString(base64_decode($imageData));
echo imagepng($im, "foto.png", 0);
```
<h6 align="center">Este pequeno trecho de código deve ser implementado embaixo da variável "$pix" na linha 53/54. Ele irá baixar a imagem do QR Code que vem da resposta em base64.</h6>

<h2 align="center"><strong>Instalação 💻</strong></h2>

```shell script
apt-get update && apt-get upgrade -y
apt-get install php
git clone https://github.com/eutheuzin7/pixmercadopago/
```

<h2 align="center"><strong>Execução 📂</strong></h2>

```shell script
cd pixmercadopago
php mercadopago.php
```

<h3 align="center"><i>Termux, cmd, Powershell, Bash ✔️</i></h3>
