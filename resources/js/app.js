import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;

window.confirmDelete = (form, label) => {
    Swal.fire({
        title: 'Hapus data?',
        html: `<b>${label.replace(/[&<>"']/g, (c) => `&#${c.charCodeAt(0)};`)}</b> akan dihapus dan tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-2xl',
            title: 'text-lg font-extrabold text-gray-900',
            confirmButton: 'rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700 mx-1',
            cancelButton: 'rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 mx-1',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

Alpine.start();
