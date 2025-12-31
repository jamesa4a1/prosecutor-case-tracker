# Email Chatbox Feature - MailHog Configuration

## MailHog Setup for Testing Emails

MailHog is an email testing tool that catches emails sent by your application without actually sending them.

### .env Configuration

Add these settings to your `.env` file:

```env
# Mail Configuration for MailHog (Local Testing)
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@prosecutor.local"
MAIL_FROM_NAME="${APP_NAME}"
```

### Starting MailHog

#### Option 1: Using Docker (Recommended)
```bash
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

#### Option 2: Direct Installation
Download from: https://github.com/mailhog/MailHog/releases
Then run: `./MailHog`

### Accessing MailHog Web Interface
Open your browser and go to: **http://localhost:8025**

All emails sent by your application will appear here!

### Customizing the Recipient Email

To change who receives the emails, edit:
`app/Http/Controllers/ProsecutorController.php`

In the `sendMessage()` method, find this line:
```php
$recipientEmail = $prosecutor->email; // Or hardcode: 'actor@example.com'
```

Change it to any email address you want to test with.

### Testing the Feature

1. Start MailHog
2. Go to a prosecutor's profile page
3. Click "Send Email" button
4. Type a message in the chatbox
5. Click "Send"
6. Check MailHog at http://localhost:8025 to see the email!

### Troubleshooting

**Issue:** "Connection refused" error
**Solution:** Make sure MailHog is running on port 1025

**Issue:** Emails not appearing in MailHog
**Solution:** 
- Check that MAIL_PORT=1025 in .env
- Run `php artisan config:clear`
- Restart your Laravel development server

### Production Configuration

When deploying to production, update your .env to use a real mail service:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your email provider
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="${APP_NAME}"
```
