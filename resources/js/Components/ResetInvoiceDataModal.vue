<template>
    <div v-if="show" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title font-weight-bold d-flex align-items-center gap-2">
                        <i class="fa fa-exclamation-triangle"></i> Reset Session Invoice Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form @submit.prevent="submitReset">
                        <!-- Invoice Type -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Invoice Type</label>
                            <select v-model="form.invoice_type" class="form-select" required>
                                <option value="gst">GST Invoice</option>
                                <option value="export">Export Invoice</option>
                                <option value="proforma">Proforma Invoice</option>
                            </select>
                        </div>

                        <!-- Firm / Company -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Select Firm / Company</label>
                            <select v-model="form.company_id" class="form-select" required>
                                <option value="" disabled>Select Company</option>
                                <option v-for="company in companyList" :key="company.id" :value="company.id">
                                    {{ company.company_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Session / Financial Year -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Select Financial Year (Session)</label>
                            <select v-model="form.session" class="form-select" required>
                                <option value="" disabled>Select Session</option>
                                <option v-for="session in sessionList" :key="session" :value="session">
                                    {{ session }}
                                </option>
                            </select>
                        </div>

                        <!-- Warning Alert Box -->
                        <div class="alert alert-danger border-2 border-danger rounded-3 my-4 p-3 bg-danger-subtle">
                            <div class="d-flex items-start gap-2 text-danger">
                                <i class="fa fa-exclamation-triangle fa-2x me-2"></i>
                                <div>
                                    <h6 class="font-weight-bold text-uppercase mb-1">Critical Warning - Irreversible Action</h6>
                                    <p class="mb-1 text-dark small">
                                        You are about to <strong>permanently delete all {{ getInvoiceTypeName(form.invoice_type) }}s</strong> for the selected firm in Session <strong>{{ form.session || 'N/A' }}</strong>.
                                    </p>
                                    <ul class="mb-0 text-dark small ps-3">
                                        <li>All invoice items and payment histories for this session will be permanently deleted.</li>
                                        <li><strong>This action CANNOT be undone or reverted.</strong></li>
                                        <li>Subsequent invoice numbers generated for this session will <strong>restart from 00001</strong>.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Input -->
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-danger">
                                To confirm, please type "<span class="user-select-all">RESET</span>" below:
                            </label>
                            <input
                                type="text"
                                v-model="form.confirmation"
                                class="form-control form-control-lg border-danger text-uppercase font-weight-bold"
                                placeholder="Type RESET to confirm"
                                required
                            />
                            <div v-if="form.errors.confirmation" class="text-danger small mt-1">
                                {{ form.errors.confirmation }}
                            </div>
                        </div>

                        <div class="modal-footer px-0 pb-0 border-top-0 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary px-4" @click="closeModal" :disabled="form.processing">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="btn btn-danger px-4 font-weight-bold"
                                :disabled="!isConfirmed || form.processing"
                            >
                                <span v-if="form.processing" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                <i v-else class="fa fa-trash me-1"></i>
                                Reset Data Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    defaultType: {
        type: String,
        default: 'gst'
    },
    companies: Array,
    sessions: Array
});

const emit = defineEmits(['close']);

const companyList = ref(props.companies || []);
const sessionList = ref(props.sessions || []);

const form = useForm({
    invoice_type: props.defaultType || 'gst',
    company_id: '',
    session: '',
    confirmation: ''
});

const isConfirmed = computed(() => {
    return form.confirmation.trim().toUpperCase() === 'RESET';
});

const fetchOptions = () => {
    axios.get(route('reset.data.options'))
        .then(response => {
            companyList.value = response.data.companies || [];
            sessionList.value = response.data.sessions || [];

            if (companyList.value.length > 0 && !form.company_id) {
                form.company_id = companyList.value[0].id;
            }
            if (sessionList.value.length > 0 && !form.session) {
                form.session = sessionList.value[0];
            }
        })
        .catch(err => {
            console.error('Failed to fetch reset options:', err);
        });
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        form.invoice_type = props.defaultType || 'gst';
        form.confirmation = '';
        fetchOptions();
    }
});

watch(() => props.defaultType, (newType) => {
    if (newType) {
        form.invoice_type = newType;
    }
});

onMounted(() => {
    if (props.show) {
        fetchOptions();
    }
});

const closeModal = () => {
    form.confirmation = '';
    emit('close');
};

const getInvoiceTypeName = (type) => {
    switch (type) {
        case 'gst': return 'GST Invoice';
        case 'export': return 'Export Invoice';
        case 'proforma': return 'Proforma Invoice';
        default: return 'Invoice';
    }
};

const submitReset = () => {
    if (!isConfirmed.value) return;

    form.post(route('reset.invoice.data'), {
        onSuccess: () => {
            closeModal();
        }
    });
};
</script>
