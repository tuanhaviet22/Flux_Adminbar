# Magento 2 Admin Bar Module

A lightweight Admin Bar for Magento 2 — inspired by WordPress, optimized for Hyvä Theme.

## 🚀 Features

- **Hyvä Theme Support** (focus on performance)
- Context-aware quick actions:
  - On **Product Page (PDP)**: Link to **Edit Product** in Admin.
  - On **CMS Page**: Link to **Edit CMS Page** in Admin.
- Inline CSS, no external JS — zero impact on frontend speed.
- Auto-hide for guest users & production mode.

## 🎯 Target Themes

| Theme      | Status  |
|------------|---------|
| Hyvä       | ✅ MVP  |
| Luma       | ✅ MVP  |

## ⚙️ How It Works

- Injected via after.body.start.
- Checks admin session before rendering.
- Compatible with Magento FPC.

## 📦 Installation

### Manual Installation

1. Copy the module to `app/code/Flux/AdminBar`
2. Enable the module:
   ```bash
   bin/magento module:enable Flux_AdminBar
   bin/magento setup:upgrade
   ```
3. Clear cache:
   ```bash
   bin/magento cache:clean
   ```

### Composer Installation (Coming Soon)

```bash
composer require flux/module-adminbar
bin/magento setup:upgrade
bin/magento cache:clean
```

## 💡 Roadmap

- Category Page edit link.
- Cache flush button.
- Dev Mode toggle.
- Admin config settings.

## 📝 Performance Notes

- No additional HTTP requests.
- Inline styling only.
- Safe with caching layers.
- Recommended for dev/staging environments.

### 2. 📂 **Cursor Code Rule File** (`cursor-rules.md`)

```markdown
# Cursor IDE - Magento 2 Coding Rules
**Project:** Sutunam Magento 2  
**Role:** Cursor IDE (Senior Dev)  
**Assigned by:** PM

## 🎯 Objective
Ensure all code contributions follow Magento 2 standards & project-specific guidelines for the `Vendor_AdminBar` module.

---

## 1. 📝 General Rules

- Follow **Magento 2 Coding Standards** (PSR-2, Magento2).
- Use PHPCS for auto-formatting where applicable.
- No unnecessary dependencies — keep module lightweight.

---

## 2. ⚡ Performance Requirements

- No external CSS or JS files.
- Use **inline CSS** within `.phtml` templates.
- Avoid KnockoutJS, RequireJS unless absolutely required.
- Ensure compatibility with **Hyvä Theme** (TailwindCSS awareness but avoid injecting global styles).

---

## 3. 🛠 Code Structure

- Namespace: `Vendor\AdminBar`
- Use Magento Dependency Injection properly.
- All strings visible to users must be wrapped with `__()`.
- PHPDoc required for:
  - All public methods.
  - Complex logic blocks.
- Use `final` or `declare(strict_types=1)` where applicable.

