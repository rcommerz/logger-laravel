# GitHub Webhook Setup for Packagist Auto-Update

This guide shows how to configure GitHub webhook for automatic Packagist updates.

## Method 1: GitHub Webhook (Recommended - No Token Needed)

### Step 1: Add Webhook in GitHub Repository

1. Go to your GitHub repository: **https://github.com/rcommerz/logger-laravel**
2. Click **Settings** → **Webhooks** → **Add webhook**
3. Configure with these values:

   ```
   Payload URL: https://packagist.org/api/github?username=islam_maruf
   Content type: application/json
   Secret: (leave empty)
   SSL verification: Enable SSL verification
   Which events: Just the push event
   Active: ✓ Checked
   ```

4. Click **Add webhook**

### Step 2: Verify Webhook

After creating the webhook:

1. Push a test commit or tag
2. Go to **Settings** → **Webhooks** → Click on the webhook
3. Check **Recent Deliveries** tab
4. Should see successful deliveries (green checkmark)

## Method 2: API Token (Backup Method)

If webhook doesn't work or you want more control, use API token:

### Step 1: Get Packagist API Token

1. Go to **https://packagist.org/profile/**
2. Scroll to "API Token" section
3. Click "Show API Token" or "Create Token"
4. Copy the token

### Step 2: Add Token to GitHub Secrets

1. Go to **https://github.com/rcommerz/logger-laravel/settings/secrets/actions**
2. Click **New repository secret**
3. Name: `PACKAGIST_TOKEN`
4. Value: [paste your API token]
5. Click **Add secret**

### Step 3: Workflows Use Token Automatically

The GitHub Actions workflows are already configured to use this token:

- **release.yml** - Triggers Packagist update on tag push
- **packagist-update.yml** - Manual trigger option

## How Auto-Update Works

### With Webhook (Method 1)

```
1. You push code/tag to GitHub
   ↓
2. GitHub triggers webhook to Packagist
   ↓
3. Packagist pulls latest code from GitHub
   ↓
4. Package updated on Packagist (within seconds)
```

### With API Token (Method 2)

```
1. You push tag to GitHub (e.g., v1.0.0)
   ↓
2. GitHub Actions "release" workflow runs
   ↓
3. Workflow calls Packagist API with token
   ↓
4. Packagist updates package
```

## Testing the Setup

### Test Webhook

```bash
# Create test commit
echo "# Test" >> README.md
git add README.md
git commit -m "test: webhook trigger"
git push origin main

# Check webhook delivery
# GitHub → Settings → Webhooks → Recent Deliveries
# Should see green checkmark with 200 response
```

### Test API Token

```bash
# Manual trigger via GitHub Actions
# Go to: Actions → Update Packagist → Run workflow
# Or create a release tag:

git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0

# Workflow automatically triggers Packagist update
```

## Verify Package Updates

After setup, check if package updates automatically:

```bash
# 1. Make a change and push
git commit -am "feat: new feature"
git push

# 2. Check Packagist (should update within 10-30 seconds)
curl -s https://packagist.org/packages/rcommerz/logger-laravel.json | grep -o '"time":"[^"]*"' | head -1

# 3. Verify updated timestamp matches recent push
```

## Troubleshooting

### Webhook Not Working

**Check webhook deliveries:**

1. GitHub → Settings → Webhooks → Click webhook
2. "Recent Deliveries" tab
3. Look for errors (red X)

**Common issues:**

- **404 Error**: Wrong username in payload URL
  - Fix: Use `username=islam_maruf`
- **401/403**: SSL verification issue
  - Fix: Ensure "Enable SSL verification" is checked
- **No deliveries**: Webhook not receiving events
  - Fix: Ensure "Just the push event" is selected

**Solution:**

```bash
# Delete and recreate webhook with correct settings:
# Payload URL: https://packagist.org/api/github?username=islam_maruf
# Content type: application/json
```

### API Token Not Working

**Error: "Authentication failed"**

```bash
# Test token manually:
curl -X POST \
  "https://packagist.org/api/update-package?username=islam_maruf&apiToken=YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"repository":{"url":"https://github.com/rcommerz/logger-laravel"}}'

# Should return: {"status":"success"}
```

**Error: "Package not found"**

- Ensure package is submitted to Packagist first
- Go to: https://packagist.org/packages/submit
- Submit: https://github.com/rcommerz/logger-laravel

### Workflow Not Triggering

**Check workflow file:**

```bash
# Verify workflow exists and is valid
cat .github/workflows/release.yml | grep "username=islam_maruf"

# Should see: username=islam_maruf in the API URL
```

## Current Configuration

### ✅ Updated Workflows

Both workflows now use correct Packagist username:

- **packagist-update.yml**: `username=islam_maruf`
- **release.yml**: `username=islam_maruf`

### Webhook Configuration

```yaml
Payload URL: https://packagist.org/api/github?username=islam_maruf
Content Type: application/json
Events: Push events
SSL: Enabled
Status: Should be green checkmark after first push
```

## Complete Release Flow

```bash
# 1. Make changes
git add .
git commit -m "feat: new feature"
git push origin main

# 2. Create release tag
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0

# 3. Automatic process:
#    ✅ GitHub webhook triggers Packagist (webhook method)
#    OR
#    ✅ GitHub Actions workflow triggers Packagist (token method)
#    
#    ✅ GitHub Actions creates release
#    ✅ Packagist updates package
#    ✅ Package available for installation

# 4. Verify
composer require rcommerz/logger-laravel:^1.0.0
```

## Recommendations

**For Best Results:**

1. ✅ **Use both methods** for redundancy
   - Set up webhook (primary)
   - Add API token (backup)

2. ✅ **Monitor first few releases**
   - Check webhook deliveries
   - Verify Packagist updates
   - Test installation

3. ✅ **Keep token secure**
   - Never commit token to code
   - Store only in GitHub Secrets
   - Rotate periodically

## Quick Setup Checklist

- [ ] Webhook added in GitHub (Settings → Webhooks)
- [ ] Payload URL: `https://packagist.org/api/github?username=islam_maruf`
- [ ] Content type: `application/json`
- [ ] Events: "Just the push event" selected
- [ ] Webhook active and tested (green checkmark)
- [ ] Optional: PACKAGIST_TOKEN added to GitHub Secrets
- [ ] Workflows updated with correct username (✅ Done)
- [ ] Test push to verify auto-update works

---

**Status**: ✅ Workflows configured with username `islam_maruf`

**Next Step**: Add webhook in GitHub repository settings

**Test**: Push a commit and check if Packagist updates automatically!
