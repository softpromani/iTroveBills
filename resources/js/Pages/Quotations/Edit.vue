<template>
    <Head :title="`Edit Quotation - ${form.quotation_number}`" />
    <AuthenticatedLayout>
        <div class="py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Edit Quotation</h2>
                        <p class="text-sm text-gray-600">Editing {{ form.quotation_number }}</p>
                    </div>
                    <div>
                        <Link :href="route('quotation.view', quotation.id)" class="btn btn-outline-primary me-2">
                            <i class="fa fa-eye me-1"></i> Preview / Print
                        </Link>
                        <Link :href="route('quotation.list')" class="btn btn-outline-secondary">
                            <i class="fa fa-list me-1"></i> Back to List
                        </Link>
                    </div>
                </div>

                <form @submit.prevent="submitForm">
                    <!-- SECTION 1: BASIC DETAILS -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-purple text-white py-3">
                            <h5 class="card-title mb-0 fs-6 font-semibold">
                                <i class="fa fa-building me-2"></i> 1. Firm &amp; Recipient Details
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Company / Firm Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Select Firm / Company <span class="text-danger">*</span></label>
                                    <select v-model="form.company_id" class="form-select" required>
                                        <option value="" disabled>Select Firm</option>
                                        <option v-for="comp in company_list" :key="comp.id" :value="comp.id">
                                            {{ comp.name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Customer Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Select Existing Customer (Optional)</label>
                                    <select v-model="selectedCustomer" @change="onCustomerSelect" class="form-select">
                                        <option :value="null">-- Select Customer to Autofill or Type Below --</option>
                                        <option v-for="cust in customer_list" :key="cust.id" :value="cust">
                                            {{ cust.name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Customer Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Customer / Business Name <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.customer_data.company_name" class="form-control" placeholder="e.g. Lalit Narayan Mithila University" required />
                                </div>

                                <!-- Recipient Designation -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Recipient Designation / Attention To</label>
                                    <input type="text" v-model="form.recipient_designation" class="form-control" placeholder="e.g. The Registrar" />
                                </div>

                                <!-- Customer Address -->
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Customer Address</label>
                                    <input type="text" v-model="form.customer_data.address" class="form-control" placeholder="e.g. Kameshwaranagar, Darbhanga, Bihar - 846008" />
                                </div>

                                <!-- Customer Mobile -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Customer Phone / Mobile</label>
                                    <input type="text" v-model="form.customer_data.mobile" class="form-control" placeholder="e.g. 9876543210" />
                                </div>

                                <div class="col-12"><hr class="my-2" /></div>

                                <!-- Quotation Number / Reference No -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Quotation Reference No. <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.quotation_number" class="form-control" required />
                                </div>

                                <!-- Quotation Date -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Quotation Date <span class="text-danger">*</span></label>
                                    <input type="date" v-model="form.quotation_date" class="form-control" required />
                                </div>

                                <!-- Quotation Subject / Title -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Quotation / Project Title <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.title" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: PROPOSAL COVER LETTER -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-purple text-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fs-6 font-semibold">
                                <i class="fa fa-envelope-open-text me-2"></i> 2. Proposal &amp; Cover Letter Paragraphs (Page 2)
                            </h5>
                            <button type="button" @click="addParagraph" class="btn btn-sm btn-light">
                                <i class="fa fa-plus me-1"></i> Add Paragraph
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div v-for="(p, pidx) in form.cover_letter_content" :key="pidx" class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label text-muted small fw-bold mb-0">Paragraph {{ pidx + 1 }}</label>
                                    <button v-if="form.cover_letter_content.length > 1" type="button" @click="removeParagraph(pidx)" class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="fa fa-times"></i> Remove
                                    </button>
                                </div>
                                <textarea v-model="form.cover_letter_content[pidx]" class="form-control" rows="3" placeholder="Enter paragraph content..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: COMMERCIALS & SCOPE OF WORK ITEMS -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-purple text-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fs-6 font-semibold">
                                <i class="fa fa-list-check me-2"></i> 3. Commercial Scope of Work &amp; Pricing (Page 3)
                            </h5>
                            <button type="button" @click="addItem" class="btn btn-sm btn-light">
                                <i class="fa fa-plus me-1"></i> Add Scope Item
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div v-for="(item, idx) in form.items" :key="idx" class="border rounded p-3 mb-4 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-purple mb-0">Item #{{ idx + 1 }}</h6>
                                    <button v-if="form.items.length > 1" type="button" @click="removeItem(idx)" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash me-1"></i> Delete Item
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Scope / Product Title <span class="text-danger">*</span></label>
                                        <input type="text" v-model="item.title" class="form-control" placeholder="e.g. Server Infrastructure Setup" required />
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Unit</label>
                                        <input type="text" v-model="item.unit" class="form-control" placeholder="e.g. 01 Lab or 100 Nos." />
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Unit Rate (₹) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" v-model.number="item.unit_rate" @input="calcTotal(item)" class="form-control" required />
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Total Rate (₹)</label>
                                        <input type="number" step="0.01" v-model.number="item.total_rate" class="form-control font-weight-bold" />
                                    </div>

                                    <!-- Deliverables / Bullet points -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-muted small fw-bold mb-0">
                                                Scope Deliverables / Feature Points (Bullet points under this item)
                                            </label>
                                            <button type="button" @click="addPoint(item)" class="btn btn-sm btn-outline-secondary py-0">
                                                <i class="fa fa-plus me-1"></i> Add Bullet Point
                                            </button>
                                        </div>

                                        <div v-for="(point, ptIdx) in item.description_points" :key="ptIdx" class="input-group mb-2">
                                            <span class="input-group-text bg-white">•</span>
                                            <input type="text" v-model="item.description_points[ptIdx]" class="form-control" placeholder="Enter deliverable point..." />
                                            <button type="button" @click="removePoint(item, ptIdx)" class="btn btn-outline-danger">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Costing Summary -->
                            <div class="row align-items-center bg-white p-3 rounded border">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tax / GST Note</label>
                                    <input type="text" v-model="form.tax_note" class="form-control" />
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="text-muted small">Total Costing</div>
                                    <h3 class="text-purple font-bold mb-0">₹ {{ formatCurrency(grandTotal) }} /-</h3>
                                    <div class="text-muted small italic">({{ amountInWords }} Only)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: PAYMENT TERMS & CONDITIONS -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-purple text-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fs-6 font-semibold">
                                <i class="fa fa-gavel me-2"></i> 4. Payment Terms &amp; Terms &amp; Conditions (Page 4)
                            </h5>
                            <button type="button" @click="addTerm" class="btn btn-sm btn-light">
                                <i class="fa fa-plus me-1"></i> Add Term Clause
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div v-for="(term, tidx) in form.terms_and_conditions" :key="tidx" class="row g-2 mb-3 align-items-center">
                                <div class="col-md-3">
                                    <input type="text" v-model="term.title" class="form-control fw-bold" placeholder="Clause Title" />
                                </div>
                                <div class="col-md-8">
                                    <input type="text" v-model="term.description" class="form-control" placeholder="Clause Details" />
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" @click="removeTerm(tidx)" class="btn btn-outline-danger w-100">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mb-5">
                        <Link :href="route('quotation.list')" class="btn btn-secondary px-4 py-2">
                            Cancel
                        </Link>
                        <button type="submit" class="btn btn-purple text-white px-5 py-2 fw-bold" :disabled="processing">
                            <i class="fa fa-check me-2"></i> {{ processing ? 'Updating...' : 'Update Quotation' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    quotation: Object,
    company_list: Array,
    customer_list: Array,
    companies: Array,
});

const selectedCustomer = ref(null);
const processing = ref(false);

// Parse initial customer data
let initialCustomerData = {
    company_name: '',
    address: '',
    mobile: '',
};

if (props.quotation.customer_data) {
    initialCustomerData = typeof props.quotation.customer_data === 'string'
        ? JSON.parse(props.quotation.customer_data)
        : props.quotation.customer_data;
}

// Format items with fallback
const initialItems = (props.quotation.items && props.quotation.items.length) 
    ? props.quotation.items.map(item => ({
        title: item.title,
        unit: item.unit || '',
        quantity: item.quantity || 1,
        unit_rate: item.unit_rate || 0,
        total_rate: item.total_rate || 0,
        description_points: Array.isArray(item.description_points) ? item.description_points : []
    }))
    : [{ title: '', unit: '', quantity: 1, unit_rate: 0, total_rate: 0, description_points: [''] }];

const form = ref({
    company_id: props.quotation.company_id,
    customer_id: props.quotation.customer_company_id,
    customer_data: initialCustomerData,
    quotation_number: props.quotation.quotation_number,
    quotation_date: props.quotation.quotation_date ? props.quotation.quotation_date.slice(0, 10) : '',
    title: props.quotation.title,
    recipient_designation: props.quotation.recipient_designation,
    subject: props.quotation.subject || props.quotation.title,
    cover_letter_content: Array.isArray(props.quotation.cover_letter_content) ? props.quotation.cover_letter_content : [],
    tax_note: props.quotation.tax_note || '',
    terms_and_conditions: Array.isArray(props.quotation.terms_and_conditions) ? props.quotation.terms_and_conditions : [],
    items: initialItems,
    status: props.quotation.status || 'draft',
});

// Customer select autofill
const onCustomerSelect = () => {
    if (selectedCustomer.value) {
        form.value.customer_id = selectedCustomer.value.id;
        form.value.customer_data = {
            company_name: selectedCustomer.value.details?.name || '',
            address: selectedCustomer.value.details?.address || '',
            mobile: selectedCustomer.value.details?.mobile || '',
        };
    }
};

// Paragraph management
const addParagraph = () => {
    form.value.cover_letter_content.push('');
};
const removeParagraph = (idx) => {
    form.value.cover_letter_content.splice(idx, 1);
};

// Item management
const addItem = () => {
    form.value.items.push({
        title: '',
        unit: '',
        quantity: 1,
        unit_rate: 0,
        total_rate: 0,
        description_points: ['']
    });
};
const removeItem = (idx) => {
    form.value.items.splice(idx, 1);
};
const calcTotal = (item) => {
    item.total_rate = (Number(item.quantity) || 1) * (Number(item.unit_rate) || 0);
};

// Bullet point management
const addPoint = (item) => {
    if (!item.description_points) item.description_points = [];
    item.description_points.push('');
};
const removePoint = (item, idx) => {
    item.description_points.splice(idx, 1);
};

// Terms management
const addTerm = () => {
    form.value.terms_and_conditions.push({ title: '', description: '' });
};
const removeTerm = (idx) => {
    form.value.terms_and_conditions.splice(idx, 1);
};

// Calculations
const grandTotal = computed(() => {
    return form.value.items.reduce((sum, item) => sum + (Number(item.total_rate) || 0), 0);
});

const formatCurrency = (val) => {
    if (!val && val !== 0) return '0';
    return Number(val).toLocaleString('en-IN');
};

const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
const tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

const convertNumberToWords = (num) => {
    if (num < 1) return '';
    if (num < 10) return ones[num];
    if (num < 20) return teens[num - 10];
    if (num < 100) return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
    if (num < 1000) return ones[Math.floor(num / 100)] + ' Hundred' + (num % 100 !== 0 ? ' ' + convertNumberToWords(num % 100) : '');
    if (num < 100000) return convertNumberToWords(Math.floor(num / 1000)) + ' Thousand' + (num % 1000 !== 0 ? ' ' + convertNumberToWords(num % 1000) : '');
    if (num < 10000000) return convertNumberToWords(Math.floor(num / 100000)) + ' Lakh' + (num % 100000 !== 0 ? ' ' + convertNumberToWords(num % 100000) : '');
    return convertNumberToWords(Math.floor(num / 10000000)) + ' Crore' + (num % 10000000 !== 0 ? ' ' + convertNumberToWords(num % 10000000) : '');
};

const amountInWords = computed(() => {
    const total = Math.floor(grandTotal.value || 0);
    if (!total) return 'Zero Rupees';
    return convertNumberToWords(total) + ' INR';
});

// Submit
const submitForm = () => {
    processing.value = true;
    router.post(route('quotation.update', props.quotation.id), form.value, {
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>

<style scoped>
.bg-purple {
    background-color: #6c2b87 !important;
}
.text-purple {
    color: #6c2b87 !important;
}
.btn-purple {
    background-color: #6c2b87;
    border-color: #6c2b87;
}
.btn-purple:hover {
    background-color: #551d6c;
    border-color: #551d6c;
}
</style>
