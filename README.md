# otomatik Mesaj Gönderim Sistemi

Laravel 10.x ile geliştirilmiştir.

---

##  Proje Akışı

1. `messages` tablosuna pending mesajlar eklenir.
2. `php artisan messages:dispatch` komutu ile **pending mesajlar queue’ya atılır**.
3. Queue worker (`php artisan queue:work`) job’ları alır ve `MessageService::send()` ile gönderim yapar.
4. Mesaj gönderimi başarılı olursa:
   - DB’de `status = sent`, `external_message_id` ve response kaydedilir.
   - Redis cache’e external ID ve gönderim zamanı eklenir.
5. Gönderim başarısız olursa mesaj `failed` olarak işaretlenir.
6. Gönderilen mesajlar API ile listelenir.

---

## Kurulum

### 1. Depoyu klonla

```bash
git clone https://github.com/cangokhan/auto-sms-app.git
cd auto-sms-app
```

### 2. Bağımlılıklar

```bash
composer install
```

### 3. .env
```bash
cp .env.example .env
php artisan key:generate
```

### 4. .env oluştur
```bash
cp .env.example .env
php artisan key:generate
```

### 5. .env ayarları 
```env
MESSAGE_WEBHOOK_URL=https://webhook.site/216ffc24-73ff-4f2c-82e1-2953ae64ad03
MESSAGE_AUTH_KEY=INS.me1x9uMcyYGlhKKQVPoc.bO3j9aZwRTOcA2Ywo
MESSAGE_CHAR_LIMIT=160
MESSAGE_SEND_INTERVAL=5
MESSAGE_BATCH_SIZE=2
L5_SWAGGER_GENERATE_ALWAYS=true

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
```