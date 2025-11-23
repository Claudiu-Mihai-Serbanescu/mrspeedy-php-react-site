// ========================
// Entry & vendor imports
// ========================
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "./styles.scss";
// Swiper v11+
import Swiper from "swiper";
import { Navigation, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

// ========================
// Small utils
// ========================
const $ = (s, all = false, root = document) => (all ? [...root.querySelectorAll(s)] : root.querySelector(s));
const on = (el, ev, fn, opt) => el && el.addEventListener(ev, fn, opt);
const ready = (fn) => (document.readyState !== "loading" ? fn() : document.addEventListener("DOMContentLoaded", fn, { once: true }));

// Smooth scroll with header offset
const scrollto = (hash) => {
  const t = $(hash);
  if (!t) return;
  const hdr = $("#header");
  const off = hdr ? hdr.offsetHeight : 0;
  scrollTo({ top: t.getBoundingClientRect().top + scrollY - off, behavior: "smooth" });
};

// ========================
// Base / Endpoints (robust)
// ========================
// Regula: în producție folosim "/..."; în dev (localhost) folosim "/<primul-segment>" dacă există.
const IS_LOCAL = location.hostname === "localhost" || location.hostname === "127.0.0.1";
const LOCAL_PREFIX = (() => {
  if (!IS_LOCAL) return ""; // pe domeniu, rădăcina site-ului e "/"
  // ex: http://localhost/mrspeedy/locksmith-birmingham/...
  // => prefix = "/mrspeedy"
  const seg = location.pathname.split("/").filter(Boolean)[0];
  return seg ? `/${seg}` : "";
})();

// Construim URL absolut, indiferent de pagina curentă (nu depindem de “../../”)
const abs = (pathFromRoot) => new URL(`${pathFromRoot}`, location.origin).toString();
const ENDPOINTS = {
  reviews: abs(`${IS_LOCAL ? LOCAL_PREFIX : ""}/reviews.php`),
  track: abs(`${IS_LOCAL ? LOCAL_PREFIX : ""}/track_page_visit.php`),
};

// ========================
// TOC toggle (safe on any page)
// ========================
document.addEventListener("click", (e) => {
  const t = e.target.closest("[data-toc-toggle], .toggle-btn, .toc-toggle, #toggle-btn, #toc-toggle");
  if (!t) return;

  const section = t.closest(".toc-section");
  if (!section) return;

  const content = section.querySelector(".toc-content, #toc-content");
  const label = t.matches(".toggle-btn, #toggle-btn") ? t : section.querySelector(".toggle-btn, #toggle-btn");
  if (!content) return;

  e.preventDefault();
  const hidden = getComputedStyle(content).display === "none";
  content.style.display = hidden ? "block" : "none";
  if (label) label.textContent = hidden ? "Hide" : "Show";
});

ready(() => {
  $(".toc-section", true).forEach((section) => {
    const content = section.querySelector(".toc-content, #toc-content");
    const label = section.querySelector(".toggle-btn, #toggle-btn");
    if (!content || !label) return;
    const hidden = getComputedStyle(content).display === "none";
    label.textContent = hidden ? "Show" : "Hide";
  });
});

// ========================
// Reveal on scroll (safe)
// ========================
const revealOnScroll = () => {
  const vh = innerHeight;
  $("[data-animate]", true).forEach((el) => {
    if (el.getBoundingClientRect().top < vh - 60) el.classList.add("in-view");
  });
};

// ========================
// Areas filter (Name/Postcode) — only if present
// ========================
const initAreasFilter = () => {
  const input = $("#searchInput");
  const tbody = $("#tableBody");
  if (!input || !tbody) return;

  let nameIdx = 0,
    postIdx = 1;
  const headCells = tbody.parentElement?.tHead?.rows?.[0]?.cells || [];
  [...headCells].forEach((c, i) => {
    const t = (c.textContent || "").trim().toLowerCase();
    if (t === "name") nameIdx = i;
    if (t.includes("postcode") || t === "post code") postIdx = i;
  });

  input.addEventListener(
    "input",
    function () {
      const q = this.value.trim().toLowerCase();
      [...tbody.rows].forEach((r) => {
        const name = (r.cells[nameIdx]?.textContent || "").toLowerCase();
        const post = (r.cells[postIdx]?.textContent || "").toLowerCase();
        r.style.display = name.includes(q) || post.includes(q) ? "" : "none";
      });
    },
    { passive: true }
  );
};

// ========================
// Swiper helper (scoped)
// ========================
const makeSwiper = (selector, opts = {}) => {
  const el = $(selector);
  if (!el) return null;

  const wrapper = el.querySelector(".swiper-wrapper");
  const slideCount = wrapper ? wrapper.children.length : 0;

  const maxSPV = (() => {
    let n = typeof opts.slidesPerView === "number" ? opts.slidesPerView : 1;
    if (opts.breakpoints) {
      for (const bp of Object.values(opts.breakpoints)) {
        if (typeof bp.slidesPerView === "number") n = Math.max(n, bp.slidesPerView);
      }
    }
    return n;
  })();

  const notEnough = slideCount <= maxSPV;

  // ascunde DOAR controalele din acest swiper, nu global
  if (notEnough) {
    if (opts.navigation) {
      el.querySelector(opts.navigation.nextEl)?.classList.add("d-none");
      el.querySelector(opts.navigation.prevEl)?.classList.add("d-none");
    }
    if (opts.pagination?.el) el.querySelector(opts.pagination.el)?.classList.add("d-none");
  }

  return new Swiper(el, {
    modules: [Navigation, Autoplay],
    watchOverflow: true,
    loop: !notEnough && !!opts.loop,
    autoplay: notEnough ? false : opts.autoplay || false,
    ...opts,
  });
};

// ========================
// Google Reviews — works from ANY page (scoped pe secțiune)
// ========================
const initReviews = async () => {
  // permite una sau mai multe secțiuni per pagină
  const sections = $("#google-reviews", true);
  if (!sections.length) return;

  const MAX = 150;
  const trunc = (t) => (t && t.length > MAX ? t.slice(0, MAX).trim() + "…" : t || "");

  // 1) Fetch o singură dată
  let payload = null;
  try {
    const res = await fetch(ENDPOINTS.reviews, { credentials: "same-origin", cache: "no-store" });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    payload = await res.json();
  } catch (e) {
    console.warn("[reviews] fetch failed:", e?.message || e);
    // fallback minimal în fiecare secțiune, ca să nu „crape”
    sections.forEach((sec) => {
      const wrap = sec.querySelector("#reviews-wrapper");
      if (wrap) wrap.innerHTML = '<div class="swiper-slide"><p>No reviews available.</p></div>';

      const container = sec.querySelector(".reviews-swiper");
      if (container) {
        // navigație/paginație ca elemente, nu string
        const nextEl = container.querySelector(".swiper-button-next") || null;
        const prevEl = container.querySelector(".swiper-button-prev") || null;
        const pagEl = container.querySelector(".swiper-pagination") || null;

        new Swiper(container, {
          modules: [Navigation, Autoplay],
          watchOverflow: true,
          rewind: true,
          slidesPerView: 1,
          spaceBetween: 24,
          navigation: nextEl && prevEl ? { nextEl, prevEl } : false,
          pagination: pagEl ? { el: pagEl, clickable: true } : false,
          autoplay: false,
        });
      }
    });
    return;
  }

  // 2) Render per secțiune (scoped)
  sections.forEach((sec) => {
    const wrap = sec.querySelector("#reviews-wrapper");
    const biz = sec.querySelector("#biz-card");
    const bizCol = sec.querySelector("#biz-card-col");
    const container = sec.querySelector(".reviews-swiper");
    if (!wrap || !container) return;

    // Business card (dacă există date)
    if (payload.placeRating && payload.reviewCount && biz) {
      const stars = "★".repeat(Math.round(payload.placeRating)) + "☆".repeat(5 - Math.round(payload.placeRating));
      biz.classList.remove("d-none");
      biz.innerHTML = `
        <img src="https://lh3.googleusercontent.com/p/AF1QipM0eN-Nnj9F4KoP4HKlkDL3kyzCDU0SjvNW3C8g=s680-w680-h510-rw" class="biz-logo" alt="Business photo">
        <div class="biz-name"><a href="https://g.co/kgs/xZ7r2ou" style="text-decoration:none;color:black;" target="_blank" rel="noopener">Mr. Speedy Locksmith Birmingham</a></div>
        <p class="biz-rating-number">${Number(payload.placeRating).toFixed(1)}</p>
        <div class="biz-stars" aria-label="${payload.placeRating} out of 5 stars">${stars}</div>
        <div class="biz-count">Based on ${payload.reviewCount} reviews</div>
        <div class="google-badge">powered by Google</div>
        <a href="https://search.google.com/local/writereview?placeid=ChIJy3ZPTcalcEgRte4zbsAJ6cY" target="_blank" rel="noopener" class="btn-review">review us on <b>Google</b></a>`;
    } else {
      bizCol?.classList.add("d-none");
    }

    // Slides
    let reviews = Array.isArray(payload.reviews) ? payload.reviews.slice(0) : [];
    if (!reviews.length) {
      wrap.innerHTML = '<div class="swiper-slide"><p>No reviews available.</p></div>';
    } else {
      // opțional: „umple” pentru un slider mai fluent
      while (reviews.length < 12) reviews = reviews.concat(payload.reviews);

      const frag = document.createDocumentFragment();
      reviews.forEach((r) => {
        const full = r.text || "";
        const shortText = trunc(full);
        const showBtn = full.length > MAX;
        const stars = "★".repeat(r.rating) + "☆".repeat(5 - r.rating);
        const avatar = r.photo || "";
        const initial = r.author ? r.author[0].toUpperCase() : "U";
        const dateStr = r.time ? new Date(r.time).toLocaleDateString("en-GB") : "";

        const slide = document.createElement("div");
        slide.className = "swiper-slide";
        slide.innerHTML = `
          <div class="review-card">
            <div class="review-header">
              ${avatar ? `<img class="review-avatar" src="${avatar}" alt="${r.author || "User"}">` : `<div class="review-avatar">${initial}</div>`}
              <div>
                <p class="review-name mb-0">${r.author || "User"}</p>
                <div class="review-stars" aria-label="${r.rating} of 5 stars">${stars}</div>
              </div>
            </div>
            <div class="review-text" data-full="${encodeURIComponent(full)}">${shortText}</div>
            ${showBtn ? '<button class="read-more">Read more</button>' : ""}
            ${r.url ? `<a href="${r.url}" target="_blank" rel="noopener" class="mt-2 small text-decoration-none">View on Google</a>` : ""}
            <div class="review-date">${dateStr}</div>
          </div>`;
        frag.appendChild(slide);
      });
      wrap.appendChild(frag);
    }

    // 3) IMPORTANT: dăm Swiper **elemente** din secțiunea curentă, nu selectori globali
    const nextEl = container.querySelector(".swiper-button-next") || null;
    const prevEl = container.querySelector(".swiper-button-prev") || null;
    const pagEl = container.querySelector(".swiper-pagination") || null;

    new Swiper(container, {
      modules: [Navigation, Autoplay],
      watchOverflow: true,
      rewind: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 1,
      spaceBetween: 24,
      navigation: nextEl && prevEl ? { nextEl, prevEl } : false,
      pagination: pagEl ? { el: pagEl, clickable: true } : false,
      breakpoints: { 576: { slidesPerView: 2 }, 992: { slidesPerView: 3 } },
    });
  });

  // Delegare globală pentru „Read more”
  on(document, "click", (e) => {
    const btn = e.target.closest(".read-more");
    if (!btn) return;
    const card = btn.closest(".review-card");
    const textEl = card?.querySelector(".review-text");
    if (!textEl) return;
    const full = decodeURIComponent(textEl.dataset.full || "");
    const expanded = btn.dataset.expanded === "1";
    textEl.textContent = expanded ? (full.length > 150 ? `${full.slice(0, 150).trim()}…` : full) : full;
    btn.textContent = expanded ? "Read more" : "Show less";
    btn.dataset.expanded = expanded ? "0" : "1";
  });
};

// ========================
// Maps fallback (safe)
// ========================
const initMapFallback = () => {
  const g = $("#gmaps-embed");
  const fb = $("#map-fallback");
  if (!g || !fb) return;

  let loaded = false;
  g.addEventListener("load", () => (loaded = true), { once: true });

  setTimeout(() => {
    if (!loaded) {
      g.classList.add("d-none");
      fb.classList.remove("d-none");
    }
  }, 2000);
};

// ========================
// Optional sliders (safe)
// ========================
const initOptionalSliders = () => {
  const blogEl = $(".blogNou-slider");
  if (blogEl) {
    new Swiper(blogEl, {
      modules: [Navigation, Autoplay],
      speed: 400,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 3,
      spaceBetween: 20,
      pagination: { el: ".swiper-pagination", clickable: true },
      navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
      breakpoints: { 320: { slidesPerView: 1, spaceBetween: 10 }, 680: { slidesPerView: 2, spaceBetween: 20 }, 1140: { slidesPerView: 3, spaceBetween: 30 } },
    });
  }

  const testiEl = $(".testimonial-slider");
  if (testiEl) {
    new Swiper(testiEl, {
      modules: [Navigation, Autoplay],
      speed: 1000,
      loop: true,
      autoplay: { delay: 2500, disableOnInteraction: false },
      slidesPerView: 4,
      spaceBetween: 10,
      pagination: { el: ".swiper-pagination", clickable: true },
      navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 10 },
        480: { slidesPerView: 2, spaceBetween: 20 },
        640: { slidesPerView: 3, spaceBetween: 30 },
        992: { slidesPerView: 4, spaceBetween: 40 },
      },
    });
  }
};

// ========================
// Header / Navbar (safe)
// ========================
const headerFixed = () => {
  const h = $("#header"),
    next = h?.nextElementSibling;
  if (!h) return;
  if (scrollY >= (h.offsetTop || 0)) {
    h.classList.add("fixed-top");
    next && next.classList.add("scrolled-offset");
  } else {
    h.classList.remove("fixed-top");
    next && next.classList.remove("scrolled-offset");
  }
};

const navbarLinksActive = () => {
  const pos = scrollY + 200;
  $("#navbar .scrollto", true).forEach((a) => {
    const sec = a.hash ? $(a.hash) : null;
    if (!sec) return;
    a.classList.toggle("active", pos >= sec.offsetTop && pos <= sec.offsetTop + sec.offsetHeight);
  });
};

const initNav = () => {
  const nav = $("#navbar"),
    header = $("#header"),
    toggleBtn = $(".mobile-nav-toggle");
  if (!nav || !toggleBtn || !header) return;

  const icon = toggleBtn.classList.contains("fas") ? toggleBtn : toggleBtn.querySelector("i");
  const setIcon = (open) => {
    if (!icon) return;
    icon.classList.toggle("fa-bars", !open);
    icon.classList.toggle("fa-times", open);
  };
  const openMobile = () => (nav.classList.add("navbar-mobile"), setIcon(true));
  const closeMobile = () => (nav.classList.remove("navbar-mobile"), setIcon(false));

  on(toggleBtn, "click", (e) => {
    e.preventDefault();
    nav.classList.contains("navbar-mobile") ? closeMobile() : openMobile();
  });

  on(document, "click", (e) => {
    const trg = e.target.closest(".navbar .dropdown > a");
    if (trg && nav.classList.contains("navbar-mobile")) {
      e.preventDefault();
      trg.nextElementSibling?.classList.toggle("dropdown-active");
    }
  });

  on(document, "click", (e) => {
    const link = e.target.closest(".scrollto");
    if (!link) return;
    const hash = link.hash || link.getAttribute("href");
    if (hash && hash.startsWith("#") && $(hash)) {
      e.preventDefault();
      const off = header.offsetHeight || 0;
      scrollTo({ top: $(hash).offsetTop - off, behavior: "smooth" });
      if (nav.classList.contains("navbar-mobile")) closeMobile();
    }
  });
};
document.addEventListener("DOMContentLoaded", function () {
  const nav = document.getElementById("navbar");
  const toggle = document.querySelector(".mobile-nav-toggle");

  if (!nav || !toggle) return;

  // ia iconul din interior (fa-bars / fa-times)
  const icon = toggle.querySelector("i");

  toggle.addEventListener("click", function (e) {
    e.preventDefault();
    nav.classList.toggle("navbar-mobile");

    if (icon) {
      icon.classList.toggle("fa-bars");
      icon.classList.toggle("fa-times");
    }
  });

  // activează dropdownurile doar pe mobil
  nav.querySelectorAll(".dropdown > a").forEach((link) => {
    link.addEventListener("click", function (e) {
      if (nav.classList.contains("navbar-mobile")) {
        e.preventDefault();
        this.nextElementSibling?.classList.toggle("dropdown-active");
      }
    });
  });
});
// ========================
// Boot
// ========================
ready(() => {
  initNav();
  initAreasFilter();
  initOptionalSliders();
  initReviews(); // <- merge pe ORICE pagină, în ORICE folder
  initMapFallback();

  revealOnScroll();
  headerFixed();
  navbarLinksActive();
});

on(
  window,
  "scroll",
  () => {
    revealOnScroll();
    headerFixed();
    navbarLinksActive();
  },
  { passive: true }
);
