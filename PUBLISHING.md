# Publishing RCOMMERZ Logger for Laravel to Packagist

This guide walks you through publishing the `rcommerz/logger-laravel` package to Packagist.

> 📚 **For detailed Packagist automation setup**, see [PACKAGIST_SETUP.md](PACKAGIST_SETUP.md)

## Prerequisites

✅ Package is complete with all files:
- [x] LICENSE
- [x] README.md
- [x] CHANGELOG.md
- [x] CONTRIBUTING.md
- [x] composer.json
- [x] All tests passing (31/31)

✅ You need:
- GitHub account
- Packagist account (sign up at https://packagist.org)
- Git repository for the package

## Step-by-Step Publishing Guide

### 1. Create GitHub Repository

```bash
# Navigate to package directory
cd /home/miaki-maruf/workspace/personal/rcommerz/packages/logger-laravel

# Initialize git (if not already done)
git init

# Add all files
git add .

# Create initial commit
git commit -m "feat: initial release v1.0.0

- Structured JSON logging with ECS-compatible format
- OpenTelemetry integration
- HTTP middleware
- 31 tests passing
- Comprehensive documentation"

# Add remote (create repository on GitHub first)
# Go to https://github.com/new and create 'logger-laravel' repository
git remote add origin https://github.com/rcommerz/logger-laravel.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### 2. Create First Release Tag

```bash
# Create annotated tag for v1.0.0
git tag -a v1.0.0 -m "Release v1.0.0

Initial production-ready release with:
- Structured JSON logging
- OpenTelemetry integration  
- HTTP middleware
- Auto-discovery support
- Full test coverage"

# Push tag to GitHub
git push origin v1.0.0
```

### 3. Create GitHub Release

1. Go to https://github.com/rcommerz/logger-laravel/releases
2. Click "Create a new release"
3. Select tag: `v1.0.0`
4. Release title: `v1.0.0 - Initial Release`
5. Copy content from CHANGELOG.md for description
6. Click "Publish release"

### 4. Submit to Packagist

1. **Go to Packagist**: https://packagist.org
2. **Login/Register**: Click "Login" and authenticate with GitHub
3. **Submit Package**:
   - Click "Submit" in top navigation
   - Enter repository URL: `https://github.com/rcommerz/logger-laravel`
   - Click "Check"
   - Review package details
   - Click "Submit"

### 5. Setup Auto-Update Hook (Recommended)

> 🚀 **GitHub Actions Integration**: The package includes workflows that automatically trigger Packagist updates on releases. See [PACKAGIST_SETUP.md](PACKAGIST_SETUP.md) for details.

#### Option A: GitHub Service Hook (Easiest - Recommended)

1. On Packagist package page, find "GitHub Service Hook" section
2. Click "Enable" - this automatically configures webhook
3. ✅ Done! Packagist will now update automatically on new tags

**Benefits:** Zero maintenance, updates within seconds, no secrets needed.

#### Option B: API Token (Backup/Alternative)

Use workflow-triggered updates via Packagist API:

1. Get API token from https://packagist.org/profile/
2. Add to GitHub: Settings → Secrets → Actions → New secret
   - Name: `PACKAGIST_TOKEN`
   - Value: [your token]
3. ✅ Workflows will use token automatically

**Benefits:** Works if webhook fails, manual trigger option available.

See [PACKAGIST_SETUP.md](PACKAGIST_SETUP.md) for detailed configuration.

#### Option C: Manual Webhook (Advanced)

1. Go to your GitHub repository: https://github.com/rcommerz/logger-laravel
2. Click "Settings" → "Webhooks" → "Add webhook"
3. Configure:
   - **Payload URL**: `https://packagist.org/api/github?username=rcommerz`
   - **Content type**: `application/json`
   - **Secret**: Leave empty or get from Packagist
   - **Events**: Just the push event
   - **Active**: ✓ Checked
4. Click "Add webhook"

### 6. Verify Installation

Test that your package installs correctly:

```bash
# Create test Laravel project
composer create-project laravel/laravel test-app
cd test-app

# Install your package
composer require rcommerz/logger-laravel

# Test it works
php artisan tinker
```

```php
// In tinker:
$logger = \Rcommerz\Logger\Logger::getInstance();
$logger->info('Test from packagist!');
exit
```

## Post-Publication Checklist

- [ ] Package appears on Packagist: https://packagist.org/packages/rcommerz/logger-laravel
- [ ] Auto-update webhook configured
- [ ] Test installation in clean Laravel project
- [ ] Add Packagist badge to README.md
- [ ] Share on social media / Laravel News

## Adding Badges to README

Once published, update README.md with real badges:

```markdown
[![Latest Version](https://img.shields.io/packagist/v/rcommerz/logger-laravel)](https://packagist.org/packages/rcommerz/logger-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/rcommerz/logger-laravel)](https://packagist.org/packages/rcommerz/logger-laravel)
[![License](https://img.shields.io/packagist/l/rcommerz/logger-laravel)](https://github.com/rcommerz/logger-laravel/blob/main/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/rcommerz/logger-laravel)](https://packagist.org/packages/rcommerz/logger-laravel)
```

## Future Releases

### Semantic Versioning

- **Patch (1.0.x)**: Bug fixes
- **Minor (1.x.0)**: New features (backward compatible)
- **Major (x.0.0)**: Breaking changes

### Release Process

```bash
# Make your changes
git add .
git commit -m "feat: add new feature"

# Update CHANGELOG.md with new changes

# Create new version tag
git tag -a v1.1.0 -m "Release v1.1.0"
git push origin main
git push origin v1.1.0

# Packagist auto-updates via webhook
# Create GitHub release for visibility
```

## Troubleshooting

### Package not appearing on Packagist

- Verify `composer.json` has correct `name` field: `"rcommerz/logger-laravel"`
- Ensure repository is public
- Check Packagist for error messages

### Auto-update not working

- Verify webhook is configured and active
- Check webhook deliveries in GitHub Settings → Webhooks
- Manually update on Packagist: Click "Update" button on package page

### Installation fails

- Ensure version constraints are correct in `composer.json`
- Verify all dependencies are available
- Check minimum PHP version compatibility

## Support

- **Packagist**: https://packagist.org/packages/rcommerz/logger-laravel
- **GitHub**: https://github.com/rcommerz/logger-laravel
- **Issues**: https://github.com/rcommerz/logger-laravel/issues

## Resources

- [Packagist Documentation](https://packagist.org/about)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Semantic Versioning](https://semver.org/)
- [Keep a Changelog](https://keepachangelog.com/)

---

**Ready to publish! 🚀**

Follow steps 1-6 above to make your package available to the Laravel community.
