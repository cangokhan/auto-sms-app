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

### 2. Bağımlılıklar

```bash
composer install

### 3. .env
```bash
cp .env.example .env
php artisan key:generate

### 4. .env oluştur
```bash
cp .env.example .env
php artisan key:generate
