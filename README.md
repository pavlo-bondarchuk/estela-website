# Estela LLC One-Page Website

One-page website for Estela LLC, built from the supplied Google Docs content.

## Structure

- `index.html` - page content, service sections, contact form, legal dialogs.
- `contact.php` - contact form handler, email sending, and thank-you/error page.
- `styles.css` - dark modern visual system and responsive layout.
- `script.js` - floating navigation state, mobile menu, and dialogs.

## Preview

For visual preview, run a lightweight local server:

```bash
python3 -m http.server 5173
```

Then visit `http://localhost:5173`.

## Contact Form

The form posts to `contact.php` and sends messages to `contacts@estela-partner.com` using PHP `mail()`.

Deploy on hosting with PHP enabled and domain email configured for `estela-partner.com`. Static-only hosting will show the page but will not send form messages.
