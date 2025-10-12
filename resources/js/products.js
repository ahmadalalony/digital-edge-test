window.productsDataTable = null;

$(function () {
    // Wait for translations to be loaded
    if (typeof window.translations === 'undefined') {
        setTimeout(arguments.callee, 100);
        return;
    }
    let token = window.authToken || localStorage.getItem('auth_token');

    window.productsDataTable = $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/products/data',
            type: 'GET',
            beforeSend: function (xhr) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrf) xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
            },
            dataSrc: function (json) {
                if (Array.isArray(json?.data)) return json.data;
                if (Array.isArray(json?.data?.data)) return json.data.data;
                return [];
            }
        },
        columns: [
            { data: 'id' },
            { data: 'title_en', defaultContent: '-' },
            { data: 'title_ar', defaultContent: '-' },
            { data: 'price', defaultContent: '-' },
            {
                data: 'creator',
                render: function (data) {
                    return data && data.full_name ? data.full_name : '-';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (data) {
                    return `
                        <a href="/admin/products/${data.id}/edit" class="btn btn-sm btn-primary">${window.translations.edit}</a>
                        <button class="btn btn-sm btn-danger delete-product" data-id="${data.id}">${window.translations.delete}</button>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.delete-product', function () {
        let id = $(this).data('id');
        if (confirm(window.translations.confirmDelete)) {
            fetch(`/admin/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert(window.translations.deleteSuccess);
                        $('#products-table').DataTable().ajax.reload();
                    } else {
                        alert(data.message || window.translations.error);
                    }
                });
        }
    });

    $('#export-products').on('click', function () {
        // collect current filters from DataTables
        const dt = window.productsDataTable;
        const params = dt && dt.ajax && dt.ajax.params ? dt.ajax.params() : {};
        const query = new URLSearchParams(params).toString();

        const url = `/admin/products/export?${query}`;

        // Simple redirect for web routes
        window.open(url, '_blank');
    });
});


