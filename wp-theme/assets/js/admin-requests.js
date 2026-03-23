jQuery(document).ready(function ($) {
    const listContainer = $('#bis-requests-list');
    const expandedRequestIds = new Set();
    const config = window.bisRequestsData || {};
    const strings = config.strings || {};
    let lastRequestCount = 0;
    let isMutating = false;

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getAjaxData(extra) {
        return Object.assign({
            nonce: config.nonce || ''
        }, extra);
    }

    function getNewRequestsCount(requests) {
        return requests.filter((req) => req.status === 'new').length;
    }

    function getMessengerIcon(messenger) {
        const icons = config.icons || {};
        const normalized = String(messenger || '').toLowerCase();
        let iconUrl = '';
        let alt = messenger || 'Контакт';

        if (normalized === 'telegram') {
            iconUrl = icons.telegram || '';
            alt = 'Telegram';
        } else if (normalized === 'max') {
            iconUrl = icons.max || '';
            alt = 'MAX';
        } else if (normalized === 'whatsapp') {
            iconUrl = icons.whatsapp || '';
            alt = 'WhatsApp';
        }

        if (!iconUrl) {
            return '<span class="dashicons dashicons-phone"></span>';
        }

        return `<img class="bis-messenger-icon" src="${escapeHtml(iconUrl)}" alt="${escapeHtml(alt)}" width="18" height="18">`;
    }

    function renderRequests(requests) {
        if (requests.length === 0) {
            expandedRequestIds.clear();
            listContainer.html(`<div class="bis-empty">${escapeHtml(strings.empty || 'Нет заявок')}</div>`);
            return;
        }

        const currentRequestIds = new Set(requests.map((req) => String(req.id)));
        Array.from(expandedRequestIds).forEach((id) => {
            if (!currentRequestIds.has(id)) {
                expandedRequestIds.delete(id);
            }
        });

        let html = `
            <table class="bis-requests-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column column-status">Статус</th>
                        <th class="manage-column column-name">Имя</th>
                        <th class="manage-column column-phone">Телефон</th>
                        <th class="manage-column column-date">Дата</th>
                        <th class="manage-column column-actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
        `;

        requests.forEach(function (req) {
            const requestId = String(req.id);
            const isExpanded = expandedRequestIds.has(requestId);
            const isNew = req.status === 'new';
            const statusClass = isNew ? 'status-new' : 'status-read';
            const statusLabel = isNew ? '<span class="bis-badge new">Новая</span>' : '<span class="bis-badge read">Просмотрено</span>';
            const messengerIcon = getMessengerIcon(req.messenger);
            const messengerValue = req.messenger ? `${messengerIcon}<span>${escapeHtml(req.messenger)}</span>` : '-';
            const extraItems = [];

            const pushDetail = (label, value) => {
                if (!value) return;
                extraItems.push(`
                    <div class="bis-detail-item">
                        <span class="label">${escapeHtml(label)}:</span>
                        <span class="value">${escapeHtml(value)}</span>
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
                <tr class="bis-request-row ${statusClass}${isExpanded ? ' is-expanded' : ''}" data-id="${escapeHtml(req.id)}">
                    <td class="column-status">${statusLabel}</td>
                    <td class="column-name"><strong>${escapeHtml(req.name)}</strong></td>
                    <td class="column-phone"><a href="tel:${escapeHtml(req.phone)}">${escapeHtml(req.phone)}</a></td>
                    <td class="column-date">${escapeHtml(req.time_ago)}</td>
                    <td class="column-actions">
                        <div class="bis-request-actions">
                            <button type="button" class="button-link toggle-row" aria-label="Показать детали">
                                <span class="dashicons ${isExpanded ? 'dashicons-arrow-up-alt2' : 'dashicons-arrow-down-alt2'}"></span>
                            </button>
                            <button type="button" class="button-link delete-request" aria-label="Удалить заявку">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="bis-request-details${isExpanded ? '' : ' hidden'}" id="details-${escapeHtml(req.id)}">
                    <td colspan="5">
                        <div class="bis-details-content">
                            <div class="bis-detail-grid">
                                <div class="bis-detail-item">
                                    <span class="label">Email:</span>
                                    <span class="value">${req.email ? `<a href="mailto:${escapeHtml(req.email)}">${escapeHtml(req.email)}</a>` : '-'}</span>
                                </div>
                                <div class="bis-detail-item">
                                    <span class="label">Контакт:</span>
                                    <span class="value bis-messenger-value">${messengerValue}</span>
                                </div>
                                <div class="bis-detail-item">
                                    <span class="label">Файл:</span>
                                    <span class="value">${req.file_url ? `<a href="${escapeHtml(req.file_url)}" download target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-media-document"></span> ${escapeHtml(req.file_name || 'Скачать')}</a>` : '-'}</span>
                                </div>
                                ${extraItems.join('')}
                            </div>
                            ${req.comment ? `
                                <div class="bis-detail-comment">
                                    <span class="label">Комментарий:</span>
                                    <div class="comment-box">${escapeHtml(req.comment)}</div>
                                </div>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        listContainer.html(html);
        attachHandlers();
    }

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

    function markRequestAsRead(row) {
        if (!row.hasClass('status-new')) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: getAjaxData({
                action: 'bis_mark_read',
                id: row.data('id')
            }),
            success: function (response) {
                if (response.success) {
                    row.removeClass('status-new').addClass('status-read');
                    row.find('.bis-badge').removeClass('new').addClass('read').text('Просмотрено');
                    updateMenuCount(response.data.count);
                }
            }
        });
    }

    function toggleRow(row, expand) {
        const detailsRow = row.next('.bis-request-details');
        const icon = row.find('.toggle-row .dashicons');
        const requestId = String(row.data('id'));

        detailsRow.toggleClass('hidden', !expand);
        row.toggleClass('is-expanded', expand);

        if (expand) {
            expandedRequestIds.add(requestId);
            icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
            markRequestAsRead(row);
        } else {
            expandedRequestIds.delete(requestId);
            icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
        }
    }

    function deleteRequest(requestId) {
        if (!window.confirm(strings.delete_confirm || 'Удалить заявку без возможности восстановления?')) {
            return;
        }

        isMutating = true;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: getAjaxData({
                action: 'bis_delete_request',
                id: requestId
            }),
            success: function (response) {
                if (response.success) {
                    expandedRequestIds.delete(String(requestId));
                    updateMenuCount(response.data.count);
                    fetchRequests({ forceRender: true });
                    return;
                }

                window.alert(strings.delete_error || 'Не удалось удалить заявку. Попробуйте ещё раз.');
            },
            error: function () {
                window.alert(strings.delete_error || 'Не удалось удалить заявку. Попробуйте ещё раз.');
            },
            complete: function () {
                isMutating = false;
            }
        });
    }

    function attachHandlers() {
        $('.toggle-row').off('click').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const row = $(this).closest('.bis-request-row');
            const shouldExpand = row.next('.bis-request-details').hasClass('hidden');
            toggleRow(row, shouldExpand);
        });

        $('.delete-request').off('click').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            deleteRequest($(this).closest('.bis-request-row').data('id'));
        });

        $('.bis-request-row').off('click').on('click', function (event) {
            if ($(event.target).closest('button,a').length) {
                return;
            }

            const row = $(this);
            const shouldExpand = row.next('.bis-request-details').hasClass('hidden');
            toggleRow(row, shouldExpand);
        });
    }

    function playNotificationSound() {
        console.log('New request received!');
    }

    function fetchRequests(options) {
        const fetchOptions = Object.assign({ forceRender: false }, options);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: getAjaxData({
                action: 'bis_get_requests'
            }),
            success: function (response) {
                if (!response.success) {
                    return;
                }

                const requests = Array.isArray(response.data) ? response.data : [];
                const nextCount = getNewRequestsCount(requests);
                updateMenuCount(nextCount);

                if (requests.length > lastRequestCount && lastRequestCount !== 0) {
                    playNotificationSound();
                }
                lastRequestCount = requests.length;

                if (expandedRequestIds.size > 0 && !fetchOptions.forceRender) {
                    return;
                }

                renderRequests(requests);
            }
        });
    }

    fetchRequests({ forceRender: true });
    setInterval(function () {
        if (!isMutating) {
            fetchRequests();
        }
    }, 5000);
});
