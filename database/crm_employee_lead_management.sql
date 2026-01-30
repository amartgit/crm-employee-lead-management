
### ⚠️ Important Note
- This file contains **only table structure and sample data**
- No sensitive credentials are included
- You must update `.env` with your own database credentials

---

## ⚙️ Installation Steps

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
