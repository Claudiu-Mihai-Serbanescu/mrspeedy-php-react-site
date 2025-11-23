# 🔐 MrSpeedy – PHP + React Hybrid Website

A professional locksmith website built using a **hybrid architecture combining PHP, React, Vite and SCSS**.  
Designed for **SEO local**, **fast performance**, and **high conversion rate** with clear service pages and strong call-to-actions.

---

## 🌐 Live Demo

👉 **https://mrspeedy.co.uk/**  
(Website live pe serverul meu de producție, administrat și configurat manual.)

---

## 📌 Overview

MrSpeedy este un website modern pentru o firmă de locksmith din Anglia.  
Arhitectura este gândită pentru:

- rangare SEO în orașe și zone specifice
- încărcare rapidă (Vite build + optimizări CSS/JS)
- interfață curată și mobile-first
- pagini dedicate pentru servicii și locații
- integrare Google Reviews
- formular de contact rapid
- tracking pentru vizitele paginilor

Proiectul folosește un mix între:

- **PHP** (routing, template include, SEO pages, backend logic)
- **React + Vite** (interactivitate, componentizare, bundle modern)
- **SCSS + PostCSS** (design sistem, stiluri optimizate)

---

## 🧩 Main Features

### ⭐ SEO-Driven Pages

- pagini dedicate pentru “locksmith in Birmingham”, “emergency locksmith”, “lock changes” etc.
- structură optimizată pentru SERP și poziționare locală

### ⭐ Hybrid Architecture (PHP + React)

- PHP pentru routing tradițional, șabloane și SEO
- React modular pentru componente interactive
- Vite pentru build rapid și asset pipeline modern

### ⭐ Google Business Reviews Integration

- afișarea review-urilor în timp real prin API custom (`reviews.php`)

### ⭐ Page Visit Tracking

- script custom `track_page_visit.php` pentru monitorizare trafic
- date trimise spre backend pentru analiză

### ⭐ Responsive UI

- design complet optimizat pentru mobile, tabletă și desktop
- SCSS modular + PostCSS pentru stiluri curate și rapide

### ⭐ Contact & CTA

- secțiune contact cu email și telefon
- CTA-uri vizibile pentru conversii rapide

---

## ⚙️ Tech Stack

### **Backend / Pages**

- PHP 8+
- Include-based templating (footer.php, head.php, components)
- Custom tracking scripts
- Google Reviews parser

### **Frontend**

- React (parțial, componente custom)
- Vite (development server + build)
- SCSS (structură modulară)
- PostCSS (optimizare producție)
- Vanilla JS modern

### **Build Tools**

- Node.js
- npm / vite
- PostCSS plugins

---

## 📁 Project Structure (simplified)

about-locksmith/ → pagini dedicate serviciilor contact-locksmith/ → pagini contact local-locksmith/ → pagini pentru orașe/zona locksmith-birmingham/ → pagini servicii locksmith-emergency/ → pagini emergency locksmith-locks/ → pagini locks

assets/ → imagini, iconițe, fișiere media

public/ → fișiere generate de Vite

src/ construct/ → componente PHP incluse (header/footer) styles/ → main.js + styles.scss

.vite/ → cache Vite index.php → homepage PHP footer.php → template footer reviews.php → reviews Google track_page_visit.php → tracking trafic vite.php → loader pentru bundle-urile Vite

## 👤 My Role in This Project

Am lucrat integral la acest proiect, ocupându-mă de:

- arhitectura completă **PHP + React**
- configurarea mediului cu **Vite, PostCSS și SCSS**
- crearea tuturor paginilor pentru servicii și locații
- realizarea layout-ului complet și a întregului **UI/UX design**
- integrarea Google Reviews printr-un **script personalizat**
- implementarea sistemului de **tracking vizite** cu PHP
- optimizarea SEO local (meta tags, structură pagini, performanță)
- implementarea unui **responsive design mobile-first**
- deploy pe serverul de producție și configurarea **Apache**

Acest proiect demonstrează capabilitățile mele de **full-stack modern** (frontend + backend PHP + build pipeline JS), precum și experiența în **SEO** și dezvoltarea de website-uri comerciale pentru companii locale.
