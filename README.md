## Informacje ogólne

Aplikacja nie posiada mechanizmu autoryzacji, dlatego w żądaniach na pobranie listy emaili oraz tworzenie nowego emaila należy przekazać `userId`.
- Jeśli `userId` nie zostanie podany w zapytaniu o listę, zwrócone zostaną rekordy emaili wszystkich użytkowników.
- Operacje usuwania i aktualizacji emaili wymagają tylko podania identyfikatora email (`id`).

Unikalność adresu email jest wymagana tylko w kontekście pojedynczego użytkownika — ten sam email może wystąpić u różnych użytkowników.

Szablon dla emaila nie został dostosowany, ponieważ w zadaniu nie było takiego wymogu.

## Mechanizm wysyłki emaili

Generacja i wysyłka emaili odbywa się w osobnych workerach (kolejkach).  
Dzięki temu:
- Wysyłka jednego emaila nie blokuje innych.
- Istnieje możliwość powtórzenia próby wysyłki (retry).
- System jest bardziej skalowalny i odporny na duże obciążenie.
- Wysyłanie emaili nie blokuje ich generowania, co pozwala obsłużyć wielu użytkowników równocześnie.

## Uruchomienie projektu

Należy uruchomić następujące polecenia:
```bash
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

## Podgląd wysłanych emaili
Aby zobaczyć wysłane emaile, należy otworzyć w przeglądarce adres: http://localhost:8025/

## Przykłady zapytań CURL
- Pobranie listy emaili:
```bash
curl --location --request GET 'http://127.0.0.1/api/emails' \
--header 'Accept: application/json'
```

- Pobranie listy emaili dla konkretnego użytkownika:
```bash
curl --location --request GET 'http://127.0.0.1/api/emails?userId=1' \
--header 'Accept: application/json'
```

- Tworzenie nowego emaila:
```bash
curl --location 'http://127.0.0.1/api/emails' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
"userId": 1,
"email": "test@test.com"
}'
```

- Pobranie emaila po id:
```bash
curl --location --request GET 'http://127.0.0.1/api/emails/1' \
--header 'Accept: application/json'
```

- Aktualizacja emaila (po id):
```bash
curl --location --request PUT 'http://127.0.0.1/api/emails/1' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
"email": "test1@test.com"
}'
```

- Usuwanie emaila (po id):
```bash
curl --location --request DELETE 'http://127.0.0.1/api/emails/1' \
--header 'Accept: application/json'
```

- Wysyłka emaili dla użytkownika:
```bash
curl --location 'http://127.0.0.1/api/users/10/send-emails' \
--header 'Accept: application/json'
```
