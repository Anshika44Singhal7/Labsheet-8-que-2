/**
 * main.js — Verdana Studio
 * Navbar scroll, hamburger menu, fade-in animations, stats counter, smooth scroll.
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ---- 1. NAVBAR scroll effect ---- */
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.style.background = window.scrollY > 60
        ? 'rgba(13,13,13,0.97)'
        : 'rgba(13,13,13,0.82)';
    }, { passive: true });
  }

  /* ---- 2. HAMBURGER MENU ---- */
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('nav-links');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      const open = navLinks.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
    });
    // Close on link click
    navLinks.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        navLinks.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });
    // Close on outside click
    document.addEventListener('click', e => {
      if (!navbar.contains(e.target)) {
        navLinks.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ---- 3. FADE-IN (IntersectionObserver) ---- */
  const fadeEls = document.querySelectorAll('.fade-in');
  if (fadeEls.length) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const siblings = entry.target.parentElement
          ? [...entry.target.parentElement.querySelectorAll('.fade-in')]
          : [];
        const delay = Math.min(siblings.indexOf(entry.target) * 80, 400);
        setTimeout(() => entry.target.classList.add('visible'), delay);
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    fadeEls.forEach(el => io.observe(el));
  }

  /* ---- 4. SMOOTH SCROLL for anchor links ---- */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const top = target.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ---- 5. ACTIVE NAV LINK on scroll ---- */
  const sections   = document.querySelectorAll('section[id]');
  const navAnchors = document.querySelectorAll('.nav-links a');
  if (sections.length && navAnchors.length) {
    new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        navAnchors.forEach(a => a.classList.remove('active'));
        const active = document.querySelector(`.nav-links a[href="#${entry.target.id}"]`);
        if (active) active.classList.add('active');
      });
    }, { rootMargin: '-30% 0px -60% 0px' }).observe
    // observe each section
    ; sections.forEach(s => {
      new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          navAnchors.forEach(a => a.classList.remove('active'));
          const active = document.querySelector(`.nav-links a[href="#${entry.target.id}"]`);
          if (active) active.classList.add('active');
        });
      }, { rootMargin: '-30% 0px -60% 0px' }).observe(s);
    });
  }

  /* ---- 6. STATS COUNTER animation ---- */
  document.querySelectorAll('.stat-num').forEach(el => {
    new IntersectionObserver(([entry], obs) => {
      if (!entry.isIntersecting) return;
      obs.unobserve(el);
      const raw    = el.textContent.trim();
      const num    = parseInt(raw.replace(/\D/g, ''), 10);
      const suffix = raw.replace(/[0-9]/g, '');
      if (isNaN(num)) return;
      let current = 0;
      const steps = 50;
      const timer = setInterval(() => {
        current += Math.ceil(num / steps);
        if (current >= num) { current = num; clearInterval(timer); }
        el.textContent = current + suffix;
      }, 1400 / steps);
    }, { threshold: 0.6 }).observe(el);
  });

});
