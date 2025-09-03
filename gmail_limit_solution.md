# SOLUSI GMAIL DAILY LIMIT EXCEEDED

## 🚨 Problem
```
550-5.4.5 Daily user sending limit exceeded
```

Gmail memiliki batas pengiriman email harian:
- **Gmail gratis**: 100 email/hari
- **Google Workspace**: 2000 email/hari

## 🛠️ Solusi

### **Option 1: Tunggu 24 Jam**
- Gmail reset limit setiap 24 jam
- Limit akan normal kembali besok

### **Option 2: Gunakan Email Service Alternatif**

#### **A. Mailgun (Recommended)**
1. Daftar di https://mailgun.com (10,000 email gratis/bulan)
2. Update .env production:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=key-xxxxxxxxx
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

#### **B. SendGrid**
1. Daftar di https://sendgrid.com (100 email gratis/hari)
2. Update .env:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

#### **C. Mailtrap (Development)**
1. Daftar di https://mailtrap.io
2. Update .env:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

### **Option 3: Multiple Gmail Accounts**
Buat beberapa akun Gmail dan rotate:

1. **Account 1**: `presensi1.smkn1sby@gmail.com`
2. **Account 2**: `presensi2.smkn1sby@gmail.com`
3. **Account 3**: `presensi3.smkn1sby@gmail.com`

Update .env untuk switch antar akun.

### **Option 4: Disable Email Temporarily**
Update EmailAuthController untuk skip email saat limit:

```php
// Temporary: Allow login without email during limit
if (/* check if Gmail limit exceeded */) {
    return redirect()->route('absensi.create')->with('info', 'Login langsung (email service maintenance)');
}
```

## 🔧 Implementation for Production

### **Quick Fix - Mailgun Setup**

1. **Register Mailgun**:
   - Go to https://mailgun.com
   - Sign up (free 10,000 emails/month)
   - Verify domain or use sandbox

2. **Update Production .env**:
```bash
cd /home/semkanisa/htdocs/presensi.semkanisa.my.id
nano .env
```

Add/update:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=sandboxXXXXX.mailgun.org
MAILGUN_SECRET=key-XXXXXXXXXXXXXXX
MAIL_FROM_ADDRESS=noreply@presensi.semkanisa.my.id
MAIL_FROM_NAME="Presensi SMKN 1 Surabaya"
```

3. **Install Mailgun Package**:
```bash
composer require mailgun/mailgun-php
```

4. **Clear Config**:
```bash
php artisan config:clear
```

## 📊 Gmail Limits Reference

| Service | Free Limit | Paid Limit |
|---------|------------|------------|
| Gmail | 100/day | 2000/day (Workspace) |
| Mailgun | 10,000/month | Pay as use |
| SendGrid | 100/day | Pay as use |
| AWS SES | 200/day | Pay as use |

## 🎯 Recommendation

**Best Practice**: 
1. **Development**: Use Mailtrap
2. **Production**: Use Mailgun or SendGrid
3. **Backup**: Keep Gmail as fallback

**Current Issue**: Switch to Mailgun immediately to resolve the limit issue.
