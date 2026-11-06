/* New Year page only. Submission goes through the existing Contact Form 7 form. */
(() => {
  'use strict';
  const root = document.querySelector('[data-asny-root]');
  if (!root) return;
  // The footer accepts either email or phone; telephone-only fields always use +7.
  const phoneInputs = document.querySelectorAll('input[type="tel"], .asny-footer-form input[name="text-944"]');
  function formatRussianPhone(raw) {
    let digits = raw.replace(/\D/g, '');
    if (!digits) return '';
    if (/^[78]/.test(digits)) digits = digits.slice(1);
    digits = digits.slice(0, 10);
    let result = '+7';
    if (digits.length) result += ' (' + digits.slice(0, 3);
    if (digits.length >= 3) result += ')';
    if (digits.length > 3) result += ' ' + digits.slice(3, 6);
    if (digits.length > 6) result += '-' + digits.slice(6, 8);
    if (digits.length > 8) result += '-' + digits.slice(8, 10);
    return result;
  }
  phoneInputs.forEach(input => {
    const mixedContact = input.type !== 'tel';
    const isEmail = raw => mixedContact && /[a-zа-яё@]/i.test(raw);
    function applyPhoneMask() {
      const raw = input.value;
      if (isEmail(raw)) return;
      const caret = input.selectionStart ?? raw.length;
      const atEnd = caret === raw.length;
      const prefixAdded = /^[78]/.test(raw.replace(/\D/g, '')) ? 0 : 1;
      const digitPosition = raw.slice(0, caret).replace(/\D/g, '').length + prefixAdded;
      const formatted = formatRussianPhone(raw);
      input.value = formatted;
      let position = formatted.length;
      if (!atEnd && formatted) {
        let count = 0;
        position = 0;
        while (position < formatted.length && count < digitPosition) {
          if (/\d/.test(formatted[position])) count += 1;
          position += 1;
        }
      }
      input.setSelectionRange(position, position);
    }
    if (!mixedContact) {
      input.placeholder = '+7 (___) ___-__-__';
      input.maxLength = 18;
      input.autocomplete = 'tel';
      input.inputMode = 'tel';
    }
    input.addEventListener('input', applyPhoneMask);
    input.addEventListener('change', applyPhoneMask);
    input.addEventListener('keydown', event => {
      if (!['Backspace', 'Delete'].includes(event.key) || event.ctrlKey || event.metaKey || event.altKey || isEmail(input.value)) return;
      const start = input.selectionStart;
      const end = input.selectionEnd;
      if (start !== end || start === null) return;
      const direction = event.key === 'Backspace' ? -1 : 1;
      let index = direction < 0 ? start - 1 : start;
      if (index < 2 || index >= input.value.length || /\d/.test(input.value[index])) return;
      while (index >= 2 && index < input.value.length && !/\d/.test(input.value[index])) index += direction;
      event.preventDefault();
      if (index < 2 || index >= input.value.length) return;
      input.value = input.value.slice(0, index) + input.value.slice(index + 1);
      input.setSelectionRange(index, index);
      applyPhoneMask();
      input.dispatchEvent(new Event('input', { bubbles: true }));
    });
    if (input.value) applyPhoneMask();
  });
  const header = document.querySelector('[data-asny-header]');
  if (header) {
    const toggle = header.querySelector('[data-asny-menu-toggle]');
    const navigation = header.querySelector('nav');
    const disclosures = Array.from(header.querySelectorAll('[data-asny-header-disclosure]'));
    const compactHeader = window.matchMedia('(max-width: 1199px)');
    function closeHeaderMenu() {
      header.removeAttribute('data-menu-open');
      toggle.setAttribute('aria-expanded', 'false');
      header.querySelector('[data-asny-menu-label]').textContent = 'Меню';
      disclosures.forEach(item => { item.open = false; });
    }
    header.setAttribute('data-menu-ready', '');
    toggle.hidden = false;
    toggle.addEventListener('click', () => {
      const open = !header.hasAttribute('data-menu-open');
      if (!open) { closeHeaderMenu(); return; }
      header.setAttribute('data-menu-open', '');
      toggle.setAttribute('aria-expanded', 'true');
      header.querySelector('[data-asny-menu-label]').textContent = 'Закрыть';
    });
    disclosures.forEach(item => item.addEventListener('toggle', () => {
      if (item.open) disclosures.forEach(other => { if (other !== item) other.open = false; });
    }));
    document.addEventListener('click', event => {
      if (!header.contains(event.target)) closeHeaderMenu();
    });
    header.addEventListener('keydown', event => {
      if (event.key !== 'Escape') return;
      const open = disclosures.find(item => item.open);
      if (open) { open.open = false; open.querySelector('summary').focus(); }
      else if (compactHeader.matches && header.hasAttribute('data-menu-open')) {
        closeHeaderMenu();
        toggle.focus();
      }
    });
    navigation.addEventListener('click', event => {
      if (event.target.closest('a') && compactHeader.matches) closeHeaderMenu();
    });
    compactHeader.addEventListener('change', () => {
      if (navigation.contains(document.activeElement)) toggle.focus();
      closeHeaderMenu();
    });
  }
  const quiz = root.querySelector('[data-asny-form="quiz"]');
  const bridge = root.querySelector('[data-asny-bridge] .wpcf7');
  const contact = bridge?.querySelector('form');
  const contactPhone = contact?.querySelector('[name="text-944"]');
  const contactMessage = contact?.querySelector('[name="textarea-144"]');
  const contactSubmit = contact?.querySelector('[type="submit"]');
  const ready = !!(contactPhone && contactMessage && contactSubmit && window.wpcf7);
  const steps = Array.from(quiz.querySelectorAll('[data-asny-step]'));
  const dialogs = Array.from(root.querySelectorAll('[data-asny-dialog]'));
  const quizDialog = root.querySelector('[data-asny-dialog="quiz"]');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const recipients = { personal: 'Для себя или близких', business: 'Для сотрудников и клиентов', event: 'Для мероприятия', team: 'Для целой команды' };
  const quantities = { one: '1 шт.', few: '2–10 шт.', medium: '11–50 шт.', many: 'Более 50 шт.' };
  const gifts = { mugs: 'Кружки с вашим дизайном', clothing: 'Одежда с принтом', calendars: 'Календари на 2027 год', canvas: 'Фото или портрет на холсте', portraits: 'Портреты на холсте', help: 'Нужна помощь с выбором' };
  let step = 0;
  let pending = null;
  let requestTimeout = null;
  let engaged = false;

  function field(form, name) { return form.elements.namedItem(name); }
  function value(form, name) { return field(form, name)?.value || ''; }
  function selectedGift(form) {
    const gift = value(form, 'gift');
    const detail = value(form, 'service_detail');
    return detail === 'portraits' && gift === 'canvas' ? 'Портреты на холсте' : detail === 'canvas' && gift === 'canvas' ? 'Фотографии на холсте' : gifts[gift] || 'Нужна помощь с выбором';
  }
  function setError(form, message) {
    const box = form.querySelector('[data-asny-error]');
    box.textContent = message;
    box.hidden = !message;
  }
  function updateSummary() {
    quiz.querySelector('[data-asny-summary]').textContent = [recipients[value(quiz, 'recipient')], quantities[value(quiz, 'quantity')], selectedGift(quiz)].filter(Boolean).join(' · ');
  }
  function showStep(next, focus = true) {
    step = Math.max(0, Math.min(next, steps.length - 1));
    steps.forEach((section, index) => {
      section.hidden = index !== step;
      // Hidden steps must not trap native form validation on submission.
      section.querySelectorAll('input').forEach(input => { input.disabled = index !== step; });
    });
    quiz.querySelector('[data-asny-back]').hidden = step === 0;
    quiz.querySelector('[data-asny-next]').hidden = step === steps.length - 1;
    quiz.querySelector('[data-asny-submit]').hidden = step !== steps.length - 1;
    quiz.querySelector('progress').value = step + 1;
    quiz.querySelector('[data-asny-step-label]').textContent = `Шаг ${step + 1} из ${steps.length}`;
    setError(quiz, '');
    if (step === 3) updateSummary();
    if (focus) steps[step].querySelector('legend').focus({ preventScroll: true });
  }
  function stepValid(index) {
    const inputs = Array.from(steps[index].querySelectorAll('input'));
    const radio = inputs.find(input => input.type === 'radio');
    if (radio && !value(quiz, radio.name)) {
      setError(quiz, 'Выберите один из вариантов, чтобы продолжить.');
      steps[index].querySelector('input').focus();
      return false;
    }
    return true;
  }
  quiz.querySelector('[data-asny-next]').addEventListener('click', () => { if (stepValid(step)) showStep(step + 1); });
  quiz.querySelector('[data-asny-back]').addEventListener('click', () => showStep(step - 1));
  quiz.addEventListener('change', event => {
    engaged = true;
    setError(quiz, '');
    if (event.target.name === 'gift' && event.target.value !== 'canvas') field(quiz, 'service_detail').value = '';
  });
  quiz.addEventListener('keydown', event => {
    if (event.key === 'Enter' && step < 3 && event.target.tagName !== 'BUTTON') {
      event.preventDefault();
      if (stepValid(step)) showStep(step + 1);
    }
  });

  function resetSuccess(kind) {
    const form = root.querySelector(`[data-asny-form="${kind}"]`);
    root.querySelector(`[data-asny-success="${kind}"]`).hidden = true;
    form.hidden = false;
  }
  root.querySelector('[data-asny-restart]').addEventListener('click', () => {
    resetSuccess('quiz');
    quiz.reset();
    field(quiz, 'service_detail').value = '';
    showStep(0);
  });

  root.querySelectorAll('[data-asny-start]').forEach(link => {
    link.addEventListener('click', event => {
      engaged = true;
      if (!ready) { event.preventDefault(); openDialog(quizDialog); return; }
      event.preventDefault();
      if (pending) {
        openDialog(quizDialog);
        quiz.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'center' });
        return;
      }
      resetSuccess('quiz');
      // Every entry point starts a clean brief, so a previous B2B selection cannot leak.
      quiz.reset();
      field(quiz, 'source').value = link.dataset.asnySource || 'Страница Новый год';
      field(quiz, 'service_detail').value = link.dataset.asnyService || '';
      if (link.dataset.asnyService) field(quiz, 'gift').value = link.dataset.asnyService === 'portraits' ? 'canvas' : link.dataset.asnyService;
      if (link.dataset.asnyRecipient) field(quiz, 'recipient').value = link.dataset.asnyRecipient;
      showStep(link.dataset.asnyRecipient ? 1 : 0, false);
      openDialog(quizDialog);
      quizDialog.scrollTop = 0;
      steps[step].querySelector('legend').focus({ preventScroll: true });
    });
  });

  function closeDialog(dialog) { if (dialog.open) dialog.close(); }
  let modalScrollY = 0;
  function lockModalPage() {
    if (document.body.classList.contains('asny-modal-open')) return;
    modalScrollY = window.scrollY;
    document.body.style.setProperty('--asny-scroll-offset', -modalScrollY + 'px');
    document.body.style.setProperty('--asny-scrollbar-gap', Math.max(0, window.innerWidth - document.documentElement.clientWidth) + 'px');
    document.body.classList.add('asny-modal-open');
  }
  function unlockModalPage() {
    if (!document.body.classList.contains('asny-modal-open')) return;
    document.body.classList.remove('asny-modal-open');
    document.body.style.removeProperty('--asny-scroll-offset');
    document.body.style.removeProperty('--asny-scrollbar-gap');
    window.scrollTo({ top: modalScrollY, behavior: 'instant' });
  }
  function openDialog(dialog) {
    if (!dialog || typeof dialog.showModal !== 'function') return false;
    dialogs.forEach(other => { if (other !== dialog) closeDialog(other); });
    lockModalPage();
    dialog.showModal();
    dialog.scrollTop = 0;
    return true;
  }
  dialogs.forEach(dialog => {
    const panel = document.createElement('div');
    panel.className = 'asny-modal-panel';
    panel.append(...dialog.childNodes);
    dialog.append(panel);
    dialog.querySelector('[data-asny-close]').addEventListener('click', () => closeDialog(dialog));
    dialog.addEventListener('close', () => {
      if (!dialogs.some(item => item.open)) unlockModalPage();
    });
    dialog.addEventListener('click', event => {
      if (event.target === dialog) closeDialog(dialog);
    });
  });
  root.querySelectorAll('[data-asny-lightbox]').forEach(link => link.addEventListener('click', event => {
    const dialog = root.querySelector('[data-asny-dialog="lightbox"]');
    if (typeof dialog.showModal !== 'function') return;
    event.preventDefault();
    const image = dialog.querySelector('[data-asny-large-image]');
    image.src = link.href;
    image.alt = link.querySelector('img').alt;
    dialog.querySelector('[data-asny-large-caption]').textContent = image.alt;
    openDialog(dialog);
  }));
  const ideasDialog = root.querySelector('[data-asny-dialog="ideas"]');
  const ideasTrigger = root.querySelector('[data-asny-open="ideas"]');
  ideasTrigger.addEventListener('click', () => { engaged = true; openDialog(ideasDialog); });

  function busy(form, state) {
    form.setAttribute('aria-busy', String(state));
    form.querySelectorAll('button').forEach(button => { button.disabled = state; });
    const submit = form.querySelector('[data-asny-submit]');
    if (!submit.dataset.originalLabel) submit.dataset.originalLabel = submit.innerHTML;
    submit.innerHTML = state ? 'Отправляем…' : submit.dataset.originalLabel;
    // Freeze the brief while CF7 sends it; its success refers to this exact selection.
    form.querySelectorAll('fieldset').forEach(section => { section.disabled = state; });
    form.querySelectorAll('input:not([type="hidden"]),select').forEach(input => {
      if (!input.closest('[data-asny-step]')) input.disabled = state;
    });
  }
  function submissionFailed(message) {
    if (!pending) return;
    clearTimeout(requestTimeout);
    const form = pending;
    pending = null;
    busy(form, false);
    setError(form, message);
  }
  root.querySelectorAll('[data-asny-form]').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      engaged = true;
      if (pending) {
        setError(form, 'Предыдущая заявка ещё отправляется. Подождите, пожалуйста.');
        return;
      }
      setError(form, '');
      if (form === quiz) {
        for (let index = 0; index < 3; index += 1) {
          if (!stepValid(index)) { showStep(index); setError(form, 'Выберите один из вариантов, чтобы продолжить.'); return; }
        }
      }
      if (!form.reportValidity()) return;
      const phone = value(form, 'phone').trim();
      const digits = phone.replace(/\D/g, '');
      if (!/^7\d{10}$/.test(digits)) {
        setError(form, 'Проверьте номер телефона: укажите от 10 до 15 цифр.');
        field(form, 'phone').focus();
        return;
      }
      if (!ready || !window.wpcf7) {
        setError(form, 'Форма временно недоступна. Позвоните: 8-951-936-94-90.');
        return;
      }
      const message = [
        'Заявка: новогодние подарки с вашим дизайном в Перми',
        `Услуга: ${selectedGift(form)}`,
        `Для кого: ${recipients[value(form, 'recipient')] || 'Не указано'}`,
        `Количество: ${quantities[value(form, 'quantity')] || 'Уточнить при звонке'}`,
        `Телефон: ${phone}`,
        `Источник: ${value(form, 'source')}`,
        'Согласие на обработку персональных данных: получено через обязательный флажок формы.',
        `Страница: ${location.origin}${location.pathname}`
      ].join('\n');
      contactPhone.value = phone;
      contactMessage.value = message;
      [contactPhone, contactMessage].forEach(input => {
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
      pending = form;
      busy(form, true);
      // Keep the form locked on timeout: the server may still deliver a slow request.
      requestTimeout = setTimeout(() => {
        if (pending === form) setError(form, 'Отправка занимает больше времени, чем обычно. Дождитесь ответа или позвоните: 8-951-936-94-90.');
      }, 30000);
      try { contact.requestSubmit(contactSubmit); }
      catch (_) { submissionFailed('Не удалось отправить заявку. Попробуйте ещё раз или позвоните: 8-951-936-94-90.'); }
    });
  });
  if (bridge) {
    bridge.addEventListener('wpcf7mailsent', () => {
      if (!pending) return;
      clearTimeout(requestTimeout);
      const form = pending;
      pending = null;
      busy(form, false);
      form.hidden = true;
      const success = root.querySelector(`[data-asny-success="${form.dataset.asnyForm}"]`);
      success.hidden = false;
      success.focus({ preventScroll: true });
    });
    bridge.addEventListener('wpcf7invalid', () => submissionFailed('Форма не приняла заявку. Проверьте телефон или позвоните: 8-951-936-94-90.'));
    bridge.addEventListener('wpcf7spam', () => submissionFailed('Не удалось пройти проверку формы. Попробуйте позже или позвоните: 8-951-936-94-90.'));
    bridge.addEventListener('wpcf7mailfailed', () => submissionFailed('Не удалось отправить заявку. Попробуйте позже или позвоните: 8-951-936-94-90.'));
    bridge.addEventListener('wpcf7submit', event => {
      if (pending && !['mail_sent', 'validation_failed', 'spam', 'mail_failed'].includes(event.detail?.status)) submissionFailed('Не удалось подтвердить отправку заявки. Позвоните: 8-951-936-94-90.');
    });
    // CF7 emits an in-flight status, then a terminal status on network errors.
    bridge.addEventListener('wpcf7statuschanged', event => {
      if (pending && ['aborted', 'failed'].includes(event.detail?.status)) submissionFailed('Связь с сервером прервана. Попробуйте позже или позвоните: 8-951-936-94-90.');
    });
  }
  if (ready) {
    quiz.hidden = false;
    root.querySelector('[data-asny-fallback]').hidden = true;
    showStep(0, false);
    if (typeof ideasDialog.showModal === 'function') ideasTrigger.hidden = false;
  }
  // One continuous parallax scene behind the catalog and portfolio.
  const parallaxSections = Array.from(root.querySelectorAll('[data-asny-parallax]'));
  const staticMotion = window.matchMedia('(prefers-reduced-motion: reduce), (max-width: 767px)');
  let frame = 0;
  const active = new Set();
  const motion = new WeakMap();
  const observer = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
    entries.forEach(entry => entry.isIntersecting ? active.add(entry.target) : active.delete(entry.target));
    scheduleParallax();
  }, { rootMargin: '100px' }) : null;
  parallaxSections.forEach(section => { if (observer) observer.observe(section); else active.add(section); });
  function renderParallax() {
    frame = 0;
    if (staticMotion.matches) return;
    let settling = false;
    active.forEach(section => {
      const rect = section.getBoundingClientRect();
      const progress = Math.max(-1, Math.min(1, (window.innerHeight / 2 - rect.top - rect.height / 2) / ((window.innerHeight + rect.height) / 2)));
      section.querySelector('.asny-v2-backdrop').style.transform = 'translate3d(0,' + (progress * 32).toFixed(1) + 'px,0)';
      section.querySelectorAll('[data-asny-depth]').forEach((ornament, index) => {
        const speed = Number(ornament.dataset.asnyDepth);
        const target = { x: progress * (index % 2 ? -55 : 55) * (Math.abs(speed) * 4 + .4), y: progress * speed * 480, turn: progress * (index % 2 ? 9 : -9) };
        const current = motion.get(ornament) || { x: 0, y: 0, turn: 0 };
        ['x', 'y', 'turn'].forEach(axis => {
          const difference = target[axis] - current[axis];
          current[axis] += difference * .085;
          if (Math.abs(difference) > .08) settling = true;
        });
        motion.set(ornament, current);
        ornament.style.setProperty('--ornament-drift-x', current.x.toFixed(2) + 'px');
        ornament.style.setProperty('--ornament-drift', current.y.toFixed(2) + 'px');
        ornament.style.setProperty('--ornament-sway', current.turn.toFixed(2) + 'deg');
      });
    });
    if (settling) scheduleParallax();
  }
  function scheduleParallax() { if (!frame && !staticMotion.matches) frame = requestAnimationFrame(renderParallax); }
  function motionChanged() {
    parallaxSections.forEach(section => {
      section.querySelector('.asny-v2-backdrop').style.transform = '';
      section.querySelectorAll('[data-asny-depth]').forEach(ornament => {
        motion.delete(ornament);
        ornament.style.setProperty('--ornament-drift', '0px');
        ornament.style.setProperty('--ornament-drift-x', '0px');
        ornament.style.setProperty('--ornament-sway', '0deg');
      });
    });
    scheduleParallax();
  }
  window.addEventListener('scroll', scheduleParallax, { passive: true });
  window.addEventListener('resize', scheduleParallax, { passive: true });
  staticMotion.addEventListener('change', motionChanged);
  scheduleParallax();
})();
