# 🌐 Access Guide - Local & Live Domain URLs

## 📍 Live Production System

**Live Domain:** https://calm-mind-massage.onrender.com

### Live URLs:

| Page | URL |
|------|-----|
| 🏠 **Homepage** | https://calm-mind-massage.onrender.com |
| 📅 **Bookings** | https://calm-mind-massage.onrender.com/booking |
| 🖼️ **Gallery** | https://calm-mind-massage.onrender.com/gallery |
| 🔐 **Admin Login** | https://calm-mind-massage.onrender.com/admin |
| 📊 **Admin Dashboard** | https://calm-mind-massage.onrender.com/admin |
| 💇 **Services** | https://calm-mind-massage.onrender.com/admin/services |
| 🖼️ **Gallery Management** | https://calm-mind-massage.onrender.com/admin/gallery |
| 📖 **Bookings List** | https://calm-mind-massage.onrender.com/admin/bookings |

---

## 💻 Local Development System

### Option 1: PHP Artisan Serve (Recommended for Quick Testing)

#### Start Local Server:
```bash
cd "D:\CMD RES\Calm-Mind-Massage-and-Spa-Booking"
php artisan serve
```

**Output will show:**
```
Starting Laravel development server: http://127.0.0.1:8000
```

#### Local URLs:
| Page | URL |
|------|-----|
| 🏠 **Homepage** | http://localhost:8000 |
| 📅 **Bookings** | http://localhost:8000/booking |
| 🖼️ **Gallery** | http://localhost:8000/gallery |
| 🔐 **Admin Login** | http://localhost:8000/admin |
| 📊 **Admin Dashboard** | http://localhost:8000/admin |
| 💇 **Services** | http://localhost:8000/admin/services |
| 🖼️ **Gallery Management** | http://localhost:8000/admin/gallery |
| 📖 **Bookings List** | http://localhost:8000/admin/bookings |

**Stop Server:** Press `Ctrl+C` in terminal

---

### Option 2: Docker (Full Environment)

#### Start Docker Container:
```bash
cd "D:\CMD RES\Calm-Mind-Massage-and-Spa-Booking"
docker-compose up -d
```

#### Local URLs via Docker:
| Page | URL |
|------|-----|
| 🏠 **Homepage** | http://localhost:8080 |
| 📅 **Bookings** | http://localhost:8080/booking |
| 🖼️ **Gallery** | http://localhost:8080/gallery |
| 🔐 **Admin Login** | http://localhost:8080/admin |
| 📊 **Admin Dashboard** | http://localhost:8080/admin |
| 💇 **Services** | http://localhost:8080/admin/services |
| 🖼️ **Gallery Management** | http://localhost:8080/admin/gallery |
| 📖 **Bookings List** | http://localhost:8080/admin/bookings |

#### Check Docker Status:
```bash
docker-compose ps
```

#### View Docker Logs:
```bash
docker-compose logs app -f
```

#### Stop Docker:
```bash
docker-compose down
```

---

### Option 3: LAN Access (Access from Other Computers on Same Network)

#### 1. Find Your Computer's Local IP:
```powershell
# In PowerShell, run:
ipconfig

# Look for IPv4 Address (e.g., 192.168.x.x)
```

#### 2. Start Laravel Server:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

#### 3. Access from Another Computer:
Replace `192.168.x.x` with your computer's IP:

| Page | URL |
|------|-----|
| 🏠 **Homepage** | http://192.168.x.x:8000 |
| 🔐 **Admin Login** | http://192.168.x.x:8000/admin |
| 📊 **Admin Dashboard** | http://192.168.x.x:8000/admin |

**Example:**
```
Your IP: 192.168.1.100
Admin URL: http://192.168.1.100:8000/admin
```

---

## 🔑 Admin Credentials

### Login Information:

| Field | Value |
|-------|-------|
| **Email** | admin@example.com |
| **Password** | password |
| **Login URL** | http://localhost:8000/login (Local) |
| **Login URL** | https://calm-mind-massage.onrender.com/login (Live) |
| **Admin Dashboard** | http://localhost:8000/admin (After login) |

### How to Login:

1. **Go to login page:**
   - Local: `http://localhost:8000/login`
   - Live: `https://calm-mind-massage.onrender.com/login`

2. **Enter credentials:**
   - Email: `admin@example.com`
   - Password: `password`

3. **Click "Login"**

4. **You'll be redirected to:** `http://localhost:8000/admin`

⚠️ **Important:** Change these credentials in production!

---

## 📋 Quick Reference - All URLs

### Local (http://localhost:8000)
```
Homepage:           http://localhost:8000
Booking:            http://localhost:8000/booking
Gallery:            http://localhost:8000/gallery
Admin:              http://localhost:8000/admin
Services Admin:     http://localhost:8000/admin/services
Gallery Admin:      http://localhost:8000/admin/gallery
Bookings Admin:     http://localhost:8000/admin/bookings
```

### Live (https://calm-mind-massage.onrender.com)
```
Homepage:           https://calm-mind-massage.onrender.com
Booking:            https://calm-mind-massage.onrender.com/booking
Gallery:            https://calm-mind-massage.onrender.com/gallery
Admin:              https://calm-mind-massage.onrender.com/admin
Services Admin:     https://calm-mind-massage.onrender.com/admin/services
Gallery Admin:      https://calm-mind-massage.onrender.com/admin/gallery
Bookings Admin:     https://calm-mind-massage.onrender.com/admin/bookings
```

---

## 🛠️ Setup Instructions

### First Time Setup (Local):

```bash
# 1. Install dependencies
composer install

# 2. Create .env file (copy from .env.example)
copy .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Setup database (fresh migrations)
php artisan migrate:fresh --force

# 5. Create admin user
php artisan app:create-admin-user admin@example.com password

# 6. Start server
php artisan serve
```

**Output will show:**
```
✅ Admin user created: admin@example.com
   Password: password
🌐 Login at: http://localhost:8000/login
```

### Database Setup:

```bash
# Migrate database
php artisan migrate

# Seed sample data
php artisan db:seed

# Create admin user
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true
])
```

---

## 🔍 Testing Checklist

### Local Testing:

- [ ] Start `php artisan serve`
- [ ] Visit `http://localhost:8000` - Homepage loads
- [ ] Visit `http://localhost:8000/gallery` - Gallery displays
- [ ] Visit `http://localhost:8000/booking` - Booking form works
- [ ] Visit `http://localhost:8000/admin` - Admin login page
- [ ] Login with admin credentials
- [ ] Services page loads - `http://localhost:8000/admin/services`
- [ ] Gallery page loads - `http://localhost:8000/admin/gallery`
- [ ] Test responsive design on mobile (DevTools)

### Live Testing:

- [ ] Visit https://calm-mind-massage.onrender.com
- [ ] Check homepage responsiveness
- [ ] Test gallery page
- [ ] Test booking form
- [ ] Verify admin login works
- [ ] Check admin functions

---

## 🔧 Environment Variables

### Development (.env for local):
```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=cmd_spa_local
```

### Production (.env on Render):
```
APP_ENV=production
APP_DEBUG=true
APP_URL=https://calm-mind-massage.onrender.com
DB_CONNECTION=mysql
DB_HOST=[Render MySQL Host]
DB_DATABASE=cmd_spa
```

---

## 🚀 Common Commands

### Start Development:
```bash
php artisan serve
```

### Run Migrations:
```bash
php artisan migrate
```

### Clear Cache:
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Tinker (Database CLI):
```bash
php artisan tinker
```

### Compile Frontend:
```bash
npm run dev      # Development with watch
npm run build    # Production build
```

---

## 📊 System Architecture

```
┌─────────────────────────────────────┐
│   Calm Mind Massage & Spa App      │
├─────────────────────────────────────┤
│                                     │
│  Local Development                  │
│  └─ http://localhost:8000           │
│     ├─ Database: MySQL (local)      │
│     └─ Storage: Local files         │
│                                     │
│  Live Production                    │
│  └─ https://calm-mind-massage...    │
│     ├─ Host: Render.com             │
│     ├─ Database: MySQL (Render)     │
│     └─ Storage: Cloud storage       │
│                                     │
└─────────────────────────────────────┘
```

---

## 📞 Troubleshooting

| Issue | Solution |
|-------|----------|
| **Port 8000 already in use** | Use different port: `php artisan serve --port=8001` |
| **Database connection failed** | Check `.env` DB credentials match your MySQL setup |
| **Admin login fails** | Verify user exists: `php artisan tinker` then `User::all()` |
| **Images not showing** | Run: `php artisan storage:link` |
| **Migrations fail** | Check database exists and credentials are correct |
| **Live site shows error** | Check Render logs for detailed error messages |

---

## 💡 Pro Tips

1. **Switch between local and live quickly:**
   - Edit `.env` to change `APP_URL`
   - Clear cache: `php artisan cache:clear`

2. **Test admin functions locally first:**
   - Create test services
   - Upload test images
   - Test bookings

3. **Keep live and local in sync:**
   - Push code changes to GitHub
   - Pull latest code when starting local dev
   - Test locally before deploying

4. **Monitor live system:**
   - Check Render dashboard for errors
   - View logs regularly
   - Test critical functions weekly

---

**Ready to test? Start with:** `php artisan serve` then visit http://localhost:8000

