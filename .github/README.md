# GitHub Actions Workflows

This directory contains automated workflows for the logger-laravel package.

## Workflows

### 1. Tests (`test.yml`)

**Triggers:**

- Push to `main`, `master`, or `develop` branches
- Pull requests to `main`, `master`, or `develop` branches

**What it does:**

- ✅ Runs tests on PHP 8.1, 8.2, 8.3
- ✅ Tests against Laravel 10.x and 11.x
- ✅ Generates code coverage reports
- ✅ Validates composer.json
- ✅ Checks for security vulnerabilities
- ✅ Comments coverage on pull requests

**Matrix:**

```yaml
PHP: 8.1, 8.2, 8.3
Laravel: 10.*, 11.*
```

Note: PHP 8.1 is excluded from Laravel 11.x tests (compatibility).

### 2. Release (`release.yml`)

**Triggers:**

- Push tags matching `v*.*.*` (e.g., v1.0.0)
- Manual workflow dispatch with version input

**What it does:**

- ✅ Validates package (runs tests, checks files)
- ✅ Extracts changelog for the version
- ✅ Creates GitHub release with notes
- ✅ Notifies about Packagist auto-update
- ✅ Optionally creates tag (manual dispatch only)
- ✅ Verifies package installation

**Manual Release:**

```bash
# Via GitHub UI:
# 1. Go to Actions → Release Package → Run workflow
# 2. Enter version (e.g., 1.0.0)
# 3. Choose whether to create tag
```

**Automatic Release:**

```bash
# Create and push tag locally:
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0

# Workflow triggers automatically
```

### 3. Release Drafter (`release-drafter.yml`)

**Triggers:**

- Push to `main` or `master` branches
- Pull requests opened, reopened, or synchronized

**What it does:**

- ✅ Automatically drafts release notes
- ✅ Categorizes changes by type (features, fixes, etc.)
- ✅ Auto-labels PRs based on branch/title
- ✅ Suggests version bumps (major/minor/patch)

**PR Labeling:**

- `feat:` or `feature/` branches → `feature` label
- `fix:` or `fix/` branches → `fix` label
- `docs:` or `docs/` branches → `documentation` label
- `chore:` or `chore/` branches → `chore` label
- `refactor:` or `refactor/` branches → `refactor` label
- `test:` or `test/` branches → `test` label
- `security:` → `security` label
- `perf:` → `performance` label

## Configuration

### Release Drafter Config

Located at `.github/release-drafter.yml`

**Version Resolution:**

- **Major**: Breaking changes (`major`, `breaking` labels)
- **Minor**: New features (`feature`, `enhancement` labels)
- **Patch**: Bug fixes (`fix`, `bugfix`, `bug` labels)

**Categories in Release Notes:**

- 🚀 Features
- 🐛 Bug Fixes
- 🧰 Maintenance
- 📚 Documentation
- 🔒 Security
- ⚡ Performance
- 🧪 Tests
- 🔧 Refactor

## Usage Examples

### Creating a New Release

1. **Update CHANGELOG.md:**

   ```markdown
   ## [1.1.0] - 2026-02-23
   
   ### Added
   - New feature X
   
   ### Fixed
   - Bug Y
   ```

2. **Commit and push:**

   ```bash
   git add CHANGELOG.md
   git commit -m "docs: update changelog for v1.1.0"
   git push origin main
   ```

3. **Create and push tag:**

   ```bash
   git tag -a v1.1.0 -m "Release v1.1.0"
   git push origin v1.1.0
   ```

4. **Wait for workflow:**
   - Tests run automatically
   - GitHub release created
   - Packagist updates automatically (if webhook configured)

### Running Tests Locally

Before pushing, run tests locally:

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/phpunit

# Run tests with coverage
vendor/bin/phpunit --coverage-text

# Run tests in verbose mode
vendor/bin/phpunit --testdox
```

### Checking Workflow Status

1. Go to <https://github.com/rcommerz/logger-laravel/actions>
2. Click on the workflow run
3. View logs and results

## Troubleshooting

### Tests Failing in CI but Pass Locally

**Possible causes:**

- Different PHP version
- Different Laravel version
- Missing dependencies
- Environment-specific issues

**Solution:**

```bash
# Test with specific PHP version
docker run --rm -v $(pwd):/app -w /app php:8.2-cli composer install
docker run --rm -v $(pwd):/app -w /app php:8.2-cli vendor/bin/phpunit
```

### Release Not Creating GitHub Release

**Possible causes:**

- CHANGELOG.md doesn't contain version section
- Tag format incorrect (should be `v1.0.0`, not `1.0.0`)
- Workflow permissions issue

**Solution:**

```bash
# Verify tag format
git tag -l

# Check CHANGELOG has version
grep "\[1.0.0\]" CHANGELOG.md

# Ensure repository has correct permissions:
# Settings → Actions → General → Workflow permissions → Read and write
```

### Packagist Not Updating

**Possible causes:**

- Webhook not configured
- Package not submitted to Packagist
- Tag not pushed to GitHub

**Solution:**

1. Verify webhook: <https://github.com/rcommerz/logger-laravel/settings/hooks>
2. Manually update: <https://packagist.org/packages/rcommerz/logger-laravel> (click Update)
3. Check Packagist webhook: <https://packagist.org/packages/rcommerz/logger-laravel>

### Coverage Reports Not Appearing

**Possible causes:**

- Coverage reporter action not configured
- No coverage data generated

**Solution:**

```bash
# Generate coverage locally
vendor/bin/phpunit --coverage-html coverage
open coverage/index.html
```

## Workflow Badges

Add to README.md:

```markdown
[![Tests](https://github.com/rcommerz/logger-laravel/actions/workflows/test.yml/badge.svg)](https://github.com/rcommerz/logger-laravel/actions/workflows/test.yml)
[![Release](https://github.com/rcommerz/logger-laravel/actions/workflows/release.yml/badge.svg)](https://github.com/rcommerz/logger-laravel/actions/workflows/release.yml)
```

## Security

- **GITHUB_TOKEN**: Automatically provided by GitHub Actions
- **No secrets required**: Packagist pulls from public GitHub repository
- All workflows use minimal permissions

## Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Release Drafter](https://github.com/release-drafter/release-drafter)
- [Packagist Webhooks](https://packagist.org/about#how-to-update-packages)
- [Semantic Versioning](https://semver.org/)
