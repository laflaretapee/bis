  <!-- Footer -->
  <footer class="footer">
    <?php
    $documents = array(
      array(
        'label' => 'Выписка из протокола СРО МРП №45-01-ПП/25 от 05.11.2025',
        'file' => '1130000018015543.191121509186564585.1.pdf',
      ),
      array(
        'label' => 'Выписка из реестра СРО ЦСО',
        'file' => 'Выписка из реестра ЦСО БИС.pdf',
      ),
      array(
        'label' => 'Сертификат соответствия ГОСТ Р ИСО 9001-2015 (ISO 9001:2015',
        'file' => 'Сертификат ИСО 14 644-1 Чистые помещения.pdf',
      ),
      array(
        'label' => 'Реестр лицензий МЧС (03.09.2025)',
        'file' => 'Реестр лицензий МЧС БИС 2025г..pdf',
      ),
      array(
        'label' => 'Уведомление о приеме в ЦСО (05.07.2024)',
        'file' => 'Уведомление о приеме ЦСО.pdf',
      ),
      array(
        'label' => 'Уведомление о подтверждении лицензии МЧС (01.09.2025 №1352)',
        'file' => 'Уведомление_о_соответствии_ЛИЦЕНЗИИ_МЧС.pdf',
      ),
    );
    ?>
    <div class="footer-content">
      <div class="footer-section">
        <h3>ООО «БИС — Баланс Инженерных Систем»</h3>
        <p>ИНН 7722323589</p>
      </div>
      <div class="footer-section">
        <h3>Навигация</h3>
        <p><a href="#services">Специализация</a></p>
        <p><a href="#equipment">Оборудование</a></p>
        <p><a href="#experience">Опыт</a></p>
        <p><a href="<?php echo esc_url(home_url('/about/')); ?>">О нас</a></p>
        <p><a href="<?php echo esc_url(home_url('/projects/')); ?>">Наши проекты</a></p>
        <p><a href="<?php echo esc_url(home_url('/news/')); ?>">Новости</a></p>
        <p><a href="#contact">Контакты</a></p>
        <p><a href="#faq">F.A.Q</a></p>
      </div>
      <div class="footer-section">
        <h3>Контакты</h3>
        <p style="display: flex; align-items: center; gap: 12px;">
          <a href="tel:+79264380770">+7 (926) 438-07-70</a>
        </p>
        <p><a href="tel:+79169861187">+7 (916) 986-11-87</a></p>
        
        <p><a href="mailto:office@bis-rf.ru">office@bis-rf.ru</a></p>
        <p style="display: flex; align-items: center; gap: 12px;margin-top: 20px;">
          <a href="https://t.me/+79264380770" target="_blank" rel="noopener" aria-label="Telegram" style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; transition: opacity 0.3s ease;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/telegram-white-32x32.png" alt="Telegram" style="width: 100%; height: 100%; object-fit: contain;">
          </a>
          <a href="https://max.ru/u/f9LHodD0cOIYdHZd-s9_nqTN9t76kGjdQxmIoxXSFGhqRnW3d4TLAMEFfVs" target="_blank" rel="noopener" aria-label="Max" style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; transition: opacity 0.3s ease;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/MAX-white-32x32.png" alt="Max" style="width: 100%; height: 100%; object-fit: contain;">
          </a>
        </p>
      </div>
      <div class="footer-section">
        <h3>Документы</h3>
        <ul class="document-links">
          <?php foreach ($documents as $document) : ?>
            <li>
              <a href="<?php echo esc_url(get_template_directory_uri() . '/assets/documents/' . rawurlencode($document['file'])); ?>" target="_blank" rel="noopener">
                <span class="document-icon" aria-hidden="true">
                  <svg width="22" height="26" viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.5 1H4C2.89543 1 2 1.89543 2 3V23C2 24.1046 2.89543 25 4 25H18C19.1046 25 20 24.1046 20 23V7.5L13.5 1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M13 1V7H20" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M7 14H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M7 18H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                </span>
                <span class="document-text"><?php echo esc_html($document['label']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> Баланс Инженерных Систем. Все права защищены. | <a href="#privacy" class="privacy-link">Политика конфиденциальности</a></p>
    </div>
  </footer>

  <?php wp_footer(); ?>
  
  <button class="btn btn-primary open-estimate-modal floating-estimate-btn">Рассчитать смету и сроки</button>
</body>
</html>
