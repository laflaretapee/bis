jQuery(document).ready(function ($) {
    const listContainer = $('#bis-requests-list');
    let lastRequestCount = 0;

    function fetchRequests() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'bis_get_requests'
            },
            success: function (response) {
                if (response.success) {
                    renderRequests(response.data);

                    // Play sound if new request
                    if (response.data.length > lastRequestCount && lastRequestCount !== 0) {
                        playNotificationSound();
                    }
                    lastRequestCount = response.data.length;
                }
            }
        });
    }

    function renderRequests(requests) {
        if (requests.length === 0) {
            listContainer.html('<div class="bis-empty">Нет заявок</div>');
            return;
        }

        let html = `
            <table class="bis-requests-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column column-status">Статус</th>
                        <th class="manage-column column-name">Имя</th>
                        <th class="manage-column column-phone">Телефон</th>
                        <th class="manage-column column-date">Дата</th>
                        <th class="manage-column column-actions" style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
        `;

        requests.forEach(function (req) {
            const statusClass = req.status === 'new' ? 'status-new' : 'status-read';
            const statusLabel = req.status === 'new' ? '<span class="bis-badge new">Новая</span>' : '<span class="bis-badge read">Просмотрено</span>';
            const messengerIcon = getMessengerIcon(req.messenger);
            const messengerValue = req.messenger ? `${messengerIcon} ${req.messenger}` : '-';
            const extraItems = [];

            const pushDetail = (label, value) => {
                if (!value) return;
                extraItems.push(`
                    <div class="bis-detail-item">
                        <span class="label">${label}:</span>
                        <span class="value">${value}</span>
                    </div>
                `);
            };

            const typeLabel = req.type === 'consultation' ? 'Консультация' : (req.type === 'estimate' ? 'Смета' : req.type);
            pushDetail('Тип', typeLabel);
            pushDetail('Проект', req.project);
            pushDetail('Компания', req.company);
            pushDetail('Должность', req.position);
            pushDetail('Тема', req.topic);

            html += `
                <tr class="bis-request-row ${statusClass}" data-id="${req.id}">
                    <td class="column-status">${statusLabel}</td>
                    <td class="column-name"><strong>${req.name}</strong></td>
                    <td class="column-phone"><a href="tel:${req.phone}">${req.phone}</a></td>
                    <td class="column-date">${req.time_ago}</td>
                    <td class="column-actions">
                        <button type="button" class="button-link toggle-row">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                    </td>
                </tr>
                <tr class="bis-request-details hidden" id="details-${req.id}">
                    <td colspan="5">
                        <div class="bis-details-content">
                            <div class="bis-detail-grid">
                                <div class="bis-detail-item">
                                    <span class="label">Email:</span>
                                    <span class="value">${req.email ? `<a href="mailto:${req.email}">${req.email}</a>` : '-'}</span>
                                </div>
                                <div class="bis-detail-item">
                                    <span class="label">Мессенджер:</span>
                                    <span class="value">${messengerValue}</span>
                                </div>
                                <div class="bis-detail-item">
                                    <span class="label">Файл:</span>
                                    <span class="value">${req.file_url ? `<a href="${req.file_url}" download target="_blank"><span class="dashicons dashicons-media-document"></span> ${req.file_name || 'Скачать'}</a>` : '-'}</span>
                                </div>
                                ${extraItems.join('')}
                            </div>
                            
                            ${req.comment ? `
                                <div class="bis-detail-comment">
                                    <span class="label">Комментарий:</span>
                                    <div class="comment-box">"${req.comment}"</div>
                                </div>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';

        // Preserve scroll position or selection if possible, but for auto-update replace is simplest
        // Ideally we would diff, but full replace is requested "auto refresh"
        listContainer.html(html);

        // Re-attach click handlers for toggle
        attachToggleHandlers();
    }

    function attachToggleHandlers() {
        $('.toggle-row').off('click').on('click', function (e) {
            e.stopPropagation();
            const btn = $(this);
            const row = btn.closest('tr');
            const detailsRow = row.next('.bis-request-details');
            const icon = btn.find('.dashicons');

            detailsRow.toggleClass('hidden');
            if (detailsRow.hasClass('hidden')) {
                icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
            } else {
                icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
            }
        });

        // Also toggle on row click
        $('.bis-request-row').off('click').on('click', function () {
            $(this).find('.toggle-row').trigger('click');
        });
    }

    function getMessengerIcon(messenger) {
        if (messenger === 'WhatsApp') return '<span class="dashicons dashicons-whatsapp" style="color:#25D366"></span>';
        if (messenger === 'Telegram') return '<span class="dashicons dashicons-telegram" style="color:#0088cc"></span>';
        return '<span class="dashicons dashicons-phone"></span>';
    }

    function playNotificationSound() {
        // Simple beep or notification sound logic could go here
        // For now, we'll just log it
        console.log('New request received!');
    }

    // Initial fetch
    fetchRequests();

    // Poll every 5 seconds
    const interval = setInterval(fetchRequests, 5000);

    // Handle click to mark as read
    listContainer.on('click', '.bis-request-card.status-new', function () {
        const card = $(this);
        const id = card.data('id');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'bis_mark_read',
                id: id
            },
            success: function (response) {
                if (response.success) {
                    card.removeClass('status-new').addClass('status-read');
                    updateMenuCount(response.data.count);
                }
            }
        });
    });

    function updateMenuCount(count) {
        const menuLink = $('a.toplevel_page_bis-requests .wp-menu-name');
        const countSpan = menuLink.find('.awaiting-mod');

        if (count > 0) {
            if (countSpan.length) {
                countSpan.find('.pending-count').text(count);
                countSpan.attr('class', 'awaiting-mod count-' + count);
            } else {
                menuLink.append(` <span class="awaiting-mod count-${count}"><span class="pending-count" aria-hidden="true">${count}</span></span>`);
            }
        } else {
            countSpan.remove();
        }
    }
});
