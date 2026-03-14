document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('em-load-more');
    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', function() {
        const offset = this.dataset.offset;
        const nonce = this.dataset.nonce;

        this.disabled = true;
        this.textContent = 'Загрузка...';

        const formData = new FormData();
        formData.append('action', 'em_load_more');
        formData.append('nonce', nonce);
        formData.append('offset', offset);

        fetch(em_ajax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const container = document.querySelector('.em-events-list');
                container.insertAdjacentHTML('beforeend', data.data);

                loadMoreBtn.dataset.offset = parseInt(offset) + 3;

            } else {
                loadMoreBtn.textContent = 'Событий больше нет';
                loadMoreBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            loadMoreBtn.textContent = 'Ошибка загрузки';
        })
        .finally(() => {
            if (loadMoreBtn.disabled && loadMoreBtn.textContent !== 'Событий больше нет') {
                loadMoreBtn.disabled = false;
                loadMoreBtn.textContent = 'Показать больше';
            }
        });
    });
});