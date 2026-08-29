<template>
    <Head title="Quotations List" />
    <AuthenticatedLayout>
        <div class="py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Quotations &amp; Proposals</h2>
                        <p class="text-sm text-gray-600">Manage, preview, print and export commercial quotations.</p>
                    </div>
                    <Link :href="route('quotation.create')" class="btn btn-purple text-white font-semibold">
                        <i class="fa fa-plus me-1"></i> Create New Quotation
                    </Link>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" v-model="search" @input="handleFilter" class="form-control" placeholder="Search by Quotation No, Title, Customer..." />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select v-model="selectedCompany" @change="handleFilter" class="form-select">
                                    <option value="">All Firms / Companies</option>
                                    <option v-for="comp in companies" :key="comp.id" :value="comp.id">
                                        {{ comp.company_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 text-end">
                                <button v-if="search || selectedCompany" @click="clearFilters" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-times me-1"></i> Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotation Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Ref No.</th>
                                        <th>Date</th>
                                        <th>Customer / Client</th>
                                        <th>Quotation Title</th>
                                        <th class="text-end">Total Costing</th>
                                        <th class="text-center">Items</th>
                                        <th class="text-center pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!quotations.data || quotations.data.length === 0">
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa fa-file-lines fa-3x mb-3 text-gray-300"></i>
                                            <p class="mb-2">No quotations found.</p>
                                            <Link :href="route('quotation.create')" class="btn btn-sm btn-purple text-white">
                                                Create Your First Quotation
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-for="item in quotations.data" :key="item.id">
                                        <td class="ps-3 fw-bold text-purple">
                                            {{ item.quotation_number }}
                                        </td>
                                        <td>{{ formatDate(item.quotation_date) }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ getCustomerName(item) }}</div>
                                            <small class="text-muted">{{ item.company?.company_name }}</small>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                                {{ item.title }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">
                                            ₹ {{ formatCurrency(item.total_amount) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">
                                                {{ item.items?.length || 0 }} items
                                            </span>
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-group btn-group-sm">
                                                <Link :href="route('quotation.view', item.id)" class="btn btn-outline-primary" title="Preview / Print / PDF">
                                                    <i class="fa fa-eye"></i>
                                                </Link>
                                                <Link :href="route('quotation.edit', item.id)" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </Link>
                                                <button type="button" @click="deleteQuotation(item)" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="quotations.links && quotations.links.length > 3" class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="text-muted small">
                            Showing {{ quotations.from || 0 }} to {{ quotations.to || 0 }} of {{ quotations.total }} quotations
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li v-for="(link, lidx) in quotations.links" :key="lidx" class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                    <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
                                    <span v-else class="page-link" v-html="link.label"></span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    quotations: Object,
    companies: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const selectedCompany = ref(props.filters?.company_id || '');

const handleFilter = () => {
    router.get(route('quotation.list'), {
        search: search.value,
        company_id: selectedCompany.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    selectedCompany.value = '';
    handleFilter();
};

const getCustomerName = (item) => {
    if (item.customer_data) {
        const data = typeof item.customer_data === 'string' ? JSON.parse(item.customer_data) : item.customer_data;
        return data.company_name || data.name || 'N/A';
    }
    if (item.customer?.customer_detail) {
        return item.customer.customer_detail.name || 'N/A';
    }
    return 'N/A';
};

const formatCurrency = (val) => {
    if (!val && val !== 0) return '0';
    return Number(val).toLocaleString('en-IN');
};

const formatDate = (val) => {
    if (!val) return '';
    const d = new Date(val);
    if (isNaN(d)) return val;
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
};

const deleteQuotation = (item) => {
    Swal.fire({
        title: 'Delete Quotation?',
        text: `Are you sure you want to delete ${item.quotation_number}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('quotation.destroy', item.id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'Quotation has been deleted.', 'success');
                }
            });
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
