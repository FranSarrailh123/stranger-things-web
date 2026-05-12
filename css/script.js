// Particles
const container = document.getElementById('particles');
if (container) {
  for (let i = 0; i < 28; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `
      left: ${Math.random() * 100}%;
      bottom: ${Math.random() * 40}%;
      animation-duration: ${4 + Math.random() * 8}s;
      animation-delay: ${Math.random() * 6}s;
      width: ${1 + Math.random() * 2}px;
      height: ${1 + Math.random() * 2}px;
      opacity: 0;
    `;
    container.appendChild(p);
  }
}

// Trailer
const TRAILER_URL = 'https://www.youtube.com/embed/b9EkMc79ZSU?autoplay=1';

function openTrailer() {
  document.getElementById('trailerFrame').src = TRAILER_URL;
  document.getElementById('trailerModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeTrailer() {
  document.getElementById('trailerFrame').src = '';
  document.getElementById('trailerModal').classList.remove('open');
  document.body.style.overflow = '';
}

function closeTrailerOnOverlay(e) {
  if (e.target === document.getElementById('trailerModal')) closeTrailer();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeTrailer(); });

// Active nav on scroll
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('nav a');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      navLinks.forEach(a => a.classList.remove('active'));
      const active = document.querySelector(`nav a[href="#${entry.target.id}"]`);
      if (active) active.classList.add('active');
    }
  });
}, { threshold: 0.4 });
sections.forEach(s => observer.observe(s));
