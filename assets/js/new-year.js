/* New Year page only. Submission goes through the existing Contact Form 7 form. */
(() => {
  'use strict';
  const root = document.querySelector('[data-asny-root]');
  if (!root) return;
  const quiz = root.querySelector('[data-asny-form="quiz"]');
  const bridge = root.querySelector('[data-asny-bridge] .wpcf7');
  const contact = bridge?.querySelector('form');
  const contactPhone = contact?.querySelector('[name="text-944"]');
  const contactMessage = contact?.querySelector('[name="textarea-144"]');
  const contactSubmit = contact?.querySelector('[type="submit"]');
  const ready = !!(contactPhone && contactMessage && contactSubmit && window.wpcf7);
  const steps = Array.from(quiz.querySelectorAll('[data-asny-step]'));
  const dialogs = Array.from(root.querySelectorAll('[data-asny-dialog]'));
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
      if (!ready) return; // The normal anchor still leads to the phone fallback.
      event.preventDefault();
      if (pending) {
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
      root.querySelector('#asny-quiz').scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
      steps[step].querySelector('legend').focus({ preventScroll: true });
    });
  });

  function closeDialog(dialog) { if (dialog.open) dialog.close(); }
  function openDialog(dialog) {
    if (!dialog || typeof dialog.showModal !== 'function') return false;
    dialogs.forEach(other => { if (other !== dialog) closeDialog(other); });
    dialog.showModal();
    document.body.classList.add('asny-modal-open');
    return true;
  }
  dialogs.forEach(dialog => {
    dialog.querySelector('[data-asny-close]').addEventListener('click', () => closeDialog(dialog));
    dialog.addEventListener('close', () => {
      if (!dialogs.some(item => item.open)) document.body.classList.remove('asny-modal-open');
    });
    dialog.addEventListener('click', event => {
      const bounds = dialog.getBoundingClientRect();
      if (event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) closeDialog(dialog);
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
      if (!/^\+?[\d\s()\-]+$/.test(phone) || digits.length < 10 || digits.length > 15) {
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
    // One gentle invitation per tab session, only after the visitor explores the page.
    setTimeout(() => {
      let shown = false;
      try { shown = sessionStorage.getItem('asny-ideas-seen') === '1'; } catch (_) { /* Storage is optional. */ }
      const pageHeight = document.documentElement.scrollHeight - window.innerHeight;
      if (shown || engaged || document.hidden || dialogs.some(dialog => dialog.open) || pageHeight <= 0 || window.scrollY / pageHeight < .3 || root.querySelector('#asny-quiz').getBoundingClientRect().top < window.innerHeight) return;
      if (openDialog(ideasDialog)) { try { sessionStorage.setItem('asny-ideas-seen', '1'); } catch (_) { /* Storage is optional. */ } }
    }, 45000);
  }
})();
