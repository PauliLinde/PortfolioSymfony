# Portfolio Backend - Symfony

Det här är mitt skolprojekt till DevOps kursen. PortfolioSymfony är backend delen till mitt portfolio, som tar emot meddelanden från frontend genom API-kopplingen, sparar uppgifterna till databasen och skickar ett email via Brevo som bekräftelse på att ett meddelande har skickats. 

## Teknologier

- **PHP** - Serverspråk
- **Symfony** - PHP-ramverk
- **PostgreSQL** - Databas
- **Upsun** - Hosting platform
- **Brevo** - Email-tjänst för bekräftelsemail

## Live Demo

Backend API för att skicka meddelanden från formuläret: [https://master-7rqtwti-4e2gmrm2schue.eu-5.platformsh.site](https://master-7rqtwti-4e2gmrm2schue.eu-5.platformsh.site)

## Förutsättningar

- PHP 8.1 eller högre
- Composer
- PostgreSQL
- Symfony CLI (valfritt men rekommenderat)

## Installation

1. Klona repositoryt:
```bash
git clone https://github.com/PauliLinde/PortfolioSymfony.git
cd PortfolioSymfony
```

2. Installera dependencies:
```bash
composer install
```

3. Konfigurera miljövariabler:
```bash
cp .env .env.local
```
Uppdatera `.env.local` med dina databasuppgifter och Brevo API-nyckel:
```
DATABASE_URL="postgresql://username:password@localhost:5432/portfolio_db"
BREVO_API_KEY="din_brevo_api_nyckel"
```

4. Skapa databasen och kör migrationer:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Starta utvecklingsservern:
```bash
symfony server:start
```

## API Endpoints

### POST /newMessage
Skickar ett meddelande som sparas i databasen och skickar ett bekräftelsemail via Brevo.

**Request Body:**
```json
{
  "name": "string",
  "email": "string",
  "message": "string"
}
```

**Exempel:**
```bash
curl -X POST https://master-7rqtwti-4e2gmrm2schue.eu-5.platformsh.site/newMessage \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ditt namn",
    "email": "din@email.com",
    "message": "Ditt meddelande här"
  }'
```

## Deployment

Projektet är deployat på Upsun (Platform.sh) med PostgreSQL-databas.

## Relaterade Projekt

Frontend: [PortfolioSvelteKit](https://github.com/PauliLinde/PortfolioSvelteKit)
