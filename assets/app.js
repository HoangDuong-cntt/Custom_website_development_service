/*
 * File: assets/app.js
 * Mục đích: Xử lý giao diện frontend như theme toggle, lọc mẫu web và submit form lead bằng Ajax.
 * Phần: Frontend interaction / public UI behavior.
 */
// Áp dụng theme đã lưu trước khi các tương tác khác chạy để tránh nháy CSS.
(() => {
	const root = document.documentElement;
	const toggle = document.querySelector('[data-theme-toggle]');
	const applyTheme = (theme, animate = false) => {
		if (animate && document.startViewTransition) {
			document.startViewTransition(() => {
				root.dataset.theme = theme;
				root.classList.toggle('dark', theme === 'dark');
			});
		} else {
			root.dataset.theme = theme;
			root.classList.toggle('dark', theme === 'dark');
		}
		localStorage.setItem('hdt-theme', theme);
		if (toggle) {
			const dark = theme === 'dark';
			toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
			toggle.setAttribute('aria-label', dark ? 'Bật chế độ sáng' : 'Bật chế độ tối');
		}
	};
	if (toggle && !toggle.hasAttribute('onclick')) {
		applyTheme(root.dataset.theme || 'light');
		toggle.addEventListener('click', () => applyTheme(root.dataset.theme === 'dark' ? 'light' : 'dark', true));
	} else if (toggle) {
		const dark = root.dataset.theme === 'dark';
		toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
		toggle.setAttribute('aria-label', dark ? 'Bật chế độ sáng' : 'Bật chế độ tối');
	}
})();

// Lọc template theo category khi người dùng bấm nút danh mục.
document.querySelectorAll('[data-category]').forEach(btn=>btn.addEventListener('click',()=>{document.querySelectorAll('[data-category]').forEach(x=>x.classList.remove('active'));btn.classList.add('active');document.querySelectorAll('.template-item').forEach(x=>x.style.display=(btn.dataset.category==='all'||x.dataset.category===btn.dataset.category)?'block':'none')}));
// Submit form lead bằng AJAX để người dùng không phải reload trang và có phản hồi ngay.
const form=document.querySelector('#lead-form');if(form)form.addEventListener('submit',async e=>{e.preventDefault();const out=document.querySelector('#form-result');try{const r=await fetch(form.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:new FormData(form)}),d=await r.json();if(d.ok){window.showToast?.('Đã gửi yêu cầu thành công!');setTimeout(()=>window.location.replace(d.redirect||'thank-you.php'),450);return}out.className='mt-3 alert alert-danger';out.textContent=d.message||'Không thể gửi yêu cầu.'}catch{out.className='mt-3 alert alert-danger';out.textContent='Có lỗi kết nối, vui lòng thử lại.'}});
