import $ from 'jquery';

window.$ = window.jQuery = $;

// jquery.repeater 1.2.1 memakai global `jQuery` (IIFE tanpa UMD),
// jadi harus di-load setelah window.jQuery diset.
import('jquery.repeater').then(() => {
    const MAX_TICKETS = 10;

    function esc(s) {
        return String(s ?? '').replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    // Bebaskan object URL preview agar tidak bocor saat file diganti / baris dihapus.
    function revokePreview($item) {
        const $img = $item.find('.attachment-preview');
        const oldUrl = $img.data('objectUrl');
        if (oldUrl) {
            URL.revokeObjectURL(oldUrl);
            $img.removeData('objectUrl');
        }
    }

    function resetAttachment($item) {
        revokePreview($item);
        $item.find('.attachment-input').val('');
        $item.find('.attachment-preview').attr('src', '').addClass('hidden');
        $item.find('.attachment-name').text('Format: PNG, JPG, JPEG (Maks. 2MB)');
    }

    // Nomor urut kartu + ringkasan sidebar mengikuti isi repeater
    function refreshRepeater() {
        const $items = $('#ticket-repeater [data-repeater-item]');
        $items.each(function (i) {
            $(this).find('.repeater-number').text('#' + (i + 1));
        });
        $('#summary-count').text($items.length + ' tiket');

        const titles = [];
        $items.each(function () {
            const v = $.trim($(this).find('.ticket-title').val());
            if (v) titles.push(v);
        });
        $('#summary-list').html(titles.length
            ? titles.map(function (t, i) {
                const short = t.length > 42 ? t.substring(0, 42) + '…' : t;
                return '<li class="flex items-start gap-1.5"><span class="font-bold text-sky-600 shrink-0">' + (i + 1) + '.</span><span class="min-w-0 break-words">' + esc(short) + '</span></li>';
            }).join('')
            : '<li class="italic text-gray-400">Belum ada judul tiket.</li>');
    }

    $('#ticket-repeater').repeater({
        initEmpty: false,
        isFirstItemUndeletable: true,
        show: function () {
            // Batasi maksimal tiket per pengajuan (server juga enforce max:10)
            if ($('#ticket-repeater [data-repeater-item]').length > MAX_TICKETS) {
                $(this).remove();
                alert('Maksimal ' + MAX_TICKETS + ' tiket dalam satu pengajuan.');
                return;
            }
            // Baris baru selalu kosong (hasil clone di-reset)
            const $item = $(this);
            $item.find('input[type=text], textarea').val('');
            $item.find('select.ticket-category').val('');
            $item.find('select.ticket-priority').val('medium');
            resetAttachment($item);
            $item.slideDown(function () { refreshRepeater(); });
        },
        hide: function (deleteElement) {
            if (confirm('Hapus baris tiket ini dari pengajuan?')) {
                const $item = $(this);
                $item.slideUp(function () {
                    revokePreview($item);
                    deleteElement();
                    refreshRepeater();
                });
            }
        }
    });

    // Ringkasan ikut berubah saat judul diketik
    $(document).on('input', '#ticket-repeater .ticket-title', refreshRepeater);

    // Preview lampiran per baris tiket
    $(document).on('change', '#ticket-repeater .attachment-input', function () {
        const file = this.files[0];
        const $item = $(this).closest('[data-repeater-item]');
        const $img = $item.find('.attachment-preview');
        const $name = $item.find('.attachment-name');
        revokePreview($item);
        if (!file) {
            $img.attr('src', '').addClass('hidden');
            $name.text('Format: PNG, JPG, JPEG (Maks. 2MB)');
            return;
        }
        $name.text('Terpilih: ' + file.name);
        if (file.type.indexOf('image/') === 0) {
            const url = URL.createObjectURL(file);
            $img.data('objectUrl', url);
            $img.attr('src', url).removeClass('hidden');
        } else {
            $img.attr('src', '').addClass('hidden');
        }
    });

    refreshRepeater();
});
