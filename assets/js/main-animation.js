(() => {
  'use strict';

  const ready = (callback) => {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', callback);
    else callback();
  };

  ready(() => {
    const header = document.querySelector('.navbar');
    const toast = (message, type = 'success') => {
      let node = document.querySelector('.site-toast');
      if (!node) {
        node = document.createElement('div');
        node.className = 'site-toast';
        node.setAttribute('role', 'status');
        document.body.appendChild(node);
      }
      node.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'}" aria-hidden="true"></i><span></span>`;
      node.querySelector('span').textContent = message;
      requestAnimationFrame(() => node.classList.add('is-visible'));
      clearTimeout(node.hideTimer);
      node.hideTimer = setTimeout(() => node.classList.remove('is-visible'), 3000);
    };
    window.showToast = toast;

    const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 12);
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const revealItems = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          currentObserver.unobserve(entry.target);
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px' });
      revealItems.forEach((item) => observer.observe(item));
    } else revealItems.forEach((item) => item.classList.add('is-visible'));

    if (!document.querySelector('#faq')) {
      const formSection = document.querySelector('#dang-ky');
      if (formSection) {
        const faq = document.createElement('section');
        faq.id = 'faq';
        faq.className = 'section';
        faq.innerHTML = `<div class="container"><div class="text-center mb-4"><p class="eyebrow text-primary">Giải đáp nhanh</p><h2 class="fw-bold">Câu hỏi thường gặp</h2></div><div class="accordion" id="faq-list"><div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-one">Thời gian hoàn thành website là bao lâu?</button></h3><div id="faq-one" class="accordion-collapse collapse"><div class="accordion-body text-secondary">Thông thường từ 7 đến 21 ngày, tùy theo phạm vi tính năng và tốc độ duyệt nội dung.</div></div></div><div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-two">Tôi có được hỗ trợ sau khi bàn giao không?</button></h3><div id="faq-two" class="accordion-collapse collapse"><div class="accordion-body text-secondary">Có. Chúng tôi hướng dẫn quản trị và hỗ trợ kỹ thuật để website vận hành ổn định sau bàn giao.</div></div></div><div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-three">Có thể yêu cầu chỉnh sửa theo thương hiệu riêng không?</button></h3><div id="faq-three" class="accordion-collapse collapse"><div class="accordion-body text-secondary">Có, mọi gói đều được tư vấn theo mục tiêu, màu sắc và nhận diện riêng của doanh nghiệp.</div></div></div></div></div>`;
        formSection.before(faq);
      }
    }

    document.addEventListener('pointerdown', (event) => {
      const target = event.target.closest('.btn, button, .zalo, .call-float');
      if (!target || target.disabled) return;
      const ripple = document.createElement('span');
      const bounds = target.getBoundingClientRect();
      const size = Math.max(bounds.width, bounds.height);
      ripple.className = 'ripple';
      ripple.style.width = `${size}px`;
      ripple.style.height = `${size}px`;
      ripple.style.left = `${event.clientX - bounds.left - size / 2}px`;
      ripple.style.top = `${event.clientY - bounds.top - size / 2}px`;
      target.appendChild(ripple);
      ripple.addEventListener('animationend', () => ripple.remove(), { once: true });
    });

    document.querySelectorAll('[data-accordion-trigger]').forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const item = trigger.closest('[data-accordion-item]');
        const answer = item?.querySelector('[data-accordion-content]');
        const isOpen = item?.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', String(Boolean(isOpen)));
        if (answer) answer.style.maxHeight = isOpen ? `${answer.scrollHeight}px` : '0px';
      });
    });

    document.querySelectorAll('[data-copy-phone]').forEach((button) => {
      button.addEventListener('click', async () => {
        const phone = button.dataset.copyPhone;
        try {
          await navigator.clipboard.writeText(phone);
          toast(`Đã copy số điện thoại ${phone}`);
        } catch {
          toast('Không thể copy tự động, hãy bôi đen số điện thoại để sao chép.', 'info');
        }
      });
    });
  });
})();
