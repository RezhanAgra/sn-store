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

window.regionSelect = (initial) => ({
    province: initial.province,
    city: initial.city,
    district: initial.district,
    village: initial.village,
    cities: [],
    districts: [],
    villages: [],
    postalCode: initial.postalCode,
    subtotal: initial.subtotal,
    shippingOptions: [],
    shippingOption: '',
    loadingShipping: false,
    shippingError: '',

    get selectedShipping() {
        return this.shippingOptions.find((option) => option.key === this.shippingOption) ?? null;
    },

    get grandTotal() {
        return this.subtotal + (this.selectedShipping?.cost ?? 0);
    },

    formatRupiah(amount) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
    },

    async loadShipping() {
        this.shippingOptions = [];
        this.shippingOption = '';
        this.shippingError = '';

        if (!this.village || this.postalCode.length !== 5) {
            return;
        }

        const requestKey = `${this.village}:${this.postalCode}`;
        this.loadingShipping = true;

        try {
            const response = await fetch(initial.urls.shipping, {
                method: 'POST',
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': initial.csrf },
                body: JSON.stringify({ village_code: this.village, postal_code: this.postalCode }),
            });
            const body = await response.json();

            if (requestKey !== `${this.village}:${this.postalCode}`) {
                return;
            }

            if (response.ok) {
                this.shippingOptions = body;
                this.shippingOption = body[0]?.key ?? '';
            } else {
                this.shippingError = Object.values(body.errors ?? {})[0]?.[0] ?? 'Ongkos kirim tidak dapat dihitung.';
            }
        } catch {
            this.shippingError = 'Ongkos kirim tidak dapat dihitung.';
        } finally {
            this.loadingShipping = false;
        }
    },

    async init() {
        if (this.province) {
            this.cities = await this.fetchOptions('cities', this.province);
        }
        if (this.city) {
            this.districts = await this.fetchOptions('districts', this.city);
        }
        if (this.district) {
            this.villages = await this.fetchOptions('villages', this.district);
        }
        await this.loadShipping();
    },

    async fetchOptions(type, code) {
        const response = await fetch(initial.urls[type].replace('__code__', code), { headers: { Accept: 'application/json' } });

        return response.ok ? response.json() : [];
    },

    async loadCities() {
        this.city = this.district = this.village = '';
        this.loadShipping();
        this.cities = this.districts = this.villages = [];
        if (this.province) {
            this.cities = await this.fetchOptions('cities', this.province);
        }
    },

    async loadDistricts() {
        this.district = this.village = '';
        this.loadShipping();
        this.districts = this.villages = [];
        if (this.city) {
            this.districts = await this.fetchOptions('districts', this.city);
        }
    },

    async loadVillages() {
        this.village = '';
        this.loadShipping();
        this.villages = [];
        if (this.district) {
            this.villages = await this.fetchOptions('villages', this.district);
        }
    },
});

Alpine.start();
