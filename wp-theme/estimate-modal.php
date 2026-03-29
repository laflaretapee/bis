<!-- Estimate Modal -->
<div class="popup-overlay" id="estimateOverlay">
  <div class="estimate-modal">
    <button type="button" class="estimate-close" id="estimateClose" aria-label="Закрыть форму">
      <span></span>
      <span></span>
    </button>
    <div class="estimate-modal-content">
      <div class="estimate-image">
        <div class="estimate-image__media" style="background-image: linear-gradient(135deg, rgba(15,23,42,0.35), rgba(15,23,42,0.15)), url('<?php echo get_template_directory_uri(); ?>/assets/img/1.webp');"></div>
      </div>
      <div class="estimate-form-container">
        <h2>Вы получите исчерпывающую смету с точностью до 95% в течение 2 дней</h2>
        <form class="contact-form" id="estimateForm">
          <div class="form-group">
            <label for="estimateName">Имя *</label>
            <input type="text" id="estimateName" name="name" required placeholder="Ваше имя" autocomplete="name">
          </div>
          <div class="form-group">
            <label for="estimatePhone">Телефон *</label>
            <input type="tel" id="estimatePhone" name="phone" required placeholder="+7 (___) ___-__-__" autocomplete="tel">
          </div>
          <div class="form-group">
            <label for="estimateEmail">Email *</label>
            <input type="email" id="estimateEmail" name="email" required placeholder="example@mail.ru" autocomplete="email">
          </div>
          <div class="form-group">
            <label for="estimateFile">Проектная документация</label>
            <input type="file" id="estimateFile" name="project_doc">
          </div>
          <div class="form-group">
            <label for="estimateComment">Комментарий</label>
            <textarea id="estimateComment" name="comment" rows="2" placeholder="Дополнительная информация..."></textarea>
          </div>
          <div class="form-group">
            <label for="estimateMessenger">Куда прислать расчет?</label>
            <div class="select-wrapper">
              <select id="estimateMessenger" name="messenger">
                <option value="MAX">MAX</option>
                <option value="Telegram">Telegram</option>
                <option value="По телефону">По телефону</option>
              </select>
            </div>
          </div>
          <?php echo do_shortcode('[hcaptcha auto="true" force="true"]'); ?>

          <button type="submit" class="btn btn-primary btn-block">Рассчитать смету и сроки</button>
          <p class="form-consent">Нажимая на кнопку, вы даете согласие на обработку своих персональных данных и соглашаетесь с Политикой конфиденциальности сайта</p>
        </form>
      </div>
    </div>
  </div>
</div>
