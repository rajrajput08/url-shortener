# URL Shortener

A Laravel app where companies create short links. A SuperAdmin invites Admins into new companies, Admins invite Admins or Members into their own company, and anyone can open a short link to be redirected to the original URL.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL

## Setup

1. Clone the repo and install dependencies:

   ```bash
   git clone https://github.com/rajrajput08/url-shortener
   cd url-shortener
   composer install
   npm install
   npm run build
   ```

2. Create the env file and app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Create an empty MySQL database named `url_shortener` and update the `DB_*` values in `.env` if your MySQL user or password is different:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=url_shortener
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Run migrations and seeders (this creates the roles, permissions and the SuperAdmin):

   ```bash
   php artisan migrate --seed
   ```

## SuperAdmin login

- Email: `superadmin@example.com`
- Password: `password`

1. As SuperAdmin, go to Invitations → Invite User, enter a company name and email.
2. Open the accept link in a private window, set a password. You're now that company's Admin.
3. As Admin, invite a Member the same way.
4. As Admin or Member, create a short URL under Short URLs.

## AI Tools I Used

Chat GPT for html and css
And help in some logic 
