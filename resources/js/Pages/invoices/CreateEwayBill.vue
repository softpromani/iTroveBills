<template>
    <Head title="Generate E-Way Bill" />
    <AuthenticatedLayout>
        <div class="container-fluid p-4">
            <div class="card shadow-sm border-0 rounded-lg">
            <div class="bg-purple-700 text-white py-2 px-3 rounded-t-lg position-relative">
                <h4 class="text-sm mb-0 text-center uppercase font-weight-bold"><i class="fa fa-truck mr-2"></i> Generate E-Way Bill</h4>
            </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Invoice Summary -->
                        <div class="col-md-4 border-right">
                            <h5 class="text-secondary mb-4 border-bottom pb-2">Invoice Details</h5>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Invoice Number</label>
                                <div class="font-weight-bold d-flex align-items-center">
                                    {{ invoice.invoice_number }}
                                    <span v-if="invoice.invoice_number.length > 16" class="ml-2 badge badge-warning" style="font-size: 10px;" title="NIC portal allows max 16 chars. This will be automatically shortened.">
                                        <i class="fa fa-exclamation-triangle"></i> >16 Chars
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Invoice Date</label>
                                <div class="font-weight-bold">{{ formatDate(invoice.invoice_date) }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Seller</label>
                                <div class="font-weight-bold">{{ invoice.company?.company_name }}</div>
                                <div class="small text-muted">{{ invoice.company?.gstin }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Buyer</label>
                                <div class="font-weight-bold">{{ invoice.customer?.company_name }}</div>
                                <div class="small text-muted">{{ invoice.customer?.gstin }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Total Amount</label>
                                <div class="font-weight-bold text-success">₹ {{ formatAmount(invoice.subtotal_amount || invoice.total_ammount) }}</div>
                            </div>

                            <div class="mt-4">
                                <h6 class="text-muted mb-2">Items ({{ invoice.items?.length }})</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm small">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in invoice.items" :key="item.id">
                                                <td class="text-truncate" style="max-width: 100px;">{{ item.desc_product }}</td>
                                                <td>{{ item.quantity }}</td>
                                                <td>{{ formatAmount(item.subtotal_amount || (item.rate * item.quantity)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Transport Details Form -->
                        <div class="col-md-8">
                            <form @submit.prevent="submit">
                                <h5 class="text-secondary mb-4 border-bottom pb-2">Transport & Supply Details</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">From Pincode (Seller)</label>
                                        <input type="text" v-model="form.fromPincode" class="form-control" placeholder="6-digit Pincode" maxlength="6" required />
                                        <InputError :message="form.errors.fromPincode" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">To Pincode (Buyer)</label>
                                        <input type="text" v-model="form.toPincode" class="form-control" placeholder="6-digit Pincode" maxlength="6" required />
                                        <InputError :message="form.errors.toPincode" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Supply Type</label>
                                        <select v-model="form.supplyType" class="form-control">
                                            <option value="O">Outward</option>
                                            <option value="I">Inward</option>
                                        </select>
                                        <InputError :message="form.errors.supplyType" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Sub Supply Type</label>
                                        <select v-model="form.subSupplyType" class="form-control">
                                            <option value="1">Supply</option>
                                            <option value="2">Import</option>
                                            <option value="3">Export</option>
                                            <option value="4">Job Work</option>
                                            <option value="5">For Own Use</option>
                                            <option value="6">SKD/CKD</option>
                                            <option value="7">Recipient Not Known</option>
                                            <option value="8">Exhibition or Fairs</option>
                                            <option value="9">Line Sales</option>
                                            <option value="10">Others</option>
                                        </select>
                                        <InputError :message="form.errors.subSupplyType" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Transport Mode</label>
                                        <select v-model="form.transMode" class="form-control">
                                            <option value="1">Road</option>
                                            <option value="2">Rail</option>
                                            <option value="3">Air</option>
                                            <option value="4">Ship</option>
                                        </select>
                                        <InputError :message="form.errors.transMode" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Distance (approx. KM)</label>
                                        <input type="number" v-model="form.transDistance" class="form-control" placeholder="Distance in Km" required />
                                        <InputError :message="form.errors.transDistance" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Vehicle Number</label>
                                        <input type="text" v-model="form.vehicleNo" class="form-control" placeholder="e.g. MH01AB1234" :required="form.transMode == '1'" />
                                        <small class="text-muted">Mandatory for Road transport</small>
                                        <InputError :message="form.errors.vehicleNo" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">Vehicle Type</label>
                                        <select v-model="form.vehicleType" class="form-control">
                                            <option value="R">Regular</option>
                                            <option value="O">Over Dimensional Cargo</option>
                                        </select>
                                        <InputError :message="form.errors.vehicleType" />
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-3 border-bottom pb-1">Optional Transporter Info</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Transporter Name</label>
                                        <input type="text" v-model="form.transporterName" class="form-control" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Transporter ID</label>
                                        <input type="text" v-model="form.transporterId" class="form-control" />
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                                    <Link :href="route('invoice.list')" class="btn btn-outline-secondary">
                                        <i class="fa fa-arrow-left"></i> Back to List
                                    </Link>
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fa fa-spinner fa-spin mr-1"></i> Generating...</span>
                                        <span v-else><i class="fa fa-paper-plane mr-1"></i> Generate E-Way Bill</span>
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import Swal from 'sweetalert2';
import axios from 'axios';

const props = defineProps({
    invoice: Object,
    invoice_type: String
});

const form = useForm({
    invoice_id: props.invoice.id,
    supplyType: 'O',
    subSupplyType: '1',
    subSupplyDesc: '',
    fromPincode: props.invoice.company?.pin || '',
    toPincode: props.invoice.customer?.pin || '',
    transMode: '1',
    transDistance: '',
    vehicleNo: props.invoice.vehicle_no || '',
    vehicleType: 'R',
    transporterName: '',
    transporterId: '',
    transDocNo: '',
    transDocDate: '',
    transactionType: 1,
    invoice_type: props.invoice_type
});

const submit = () => {
    if (form.transDistance > 4000) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Distance',
            text: 'Distance cannot exceed 4000 KM for a single E-Way Bill.'
        });
        return;
    }

    Swal.fire({
        title: 'Generating E-Way Bill...',
        text: 'Please wait while we connect to the portal',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(route('generate.eway.bill'), form.data())
        .then(response => {
            Swal.close();
            if (response.data.status === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.data.message,
                    confirmButtonText: 'View List'
                }).then(() => {
                    router.get(route('invoice.list'));
                });
            }
        })
        .catch(error => {
            Swal.close();
            const message = error.response?.data?.message || 'Something went wrong while generating the E-Way Bill';
            const details = error.response?.data?.details?.error?.message || '';
            
            Swal.fire({
                icon: 'error',
                title: 'Generation Failed',
                html: `<div>${message}</div>${details ? `<div class="mt-2 small text-danger"><b>API Detail:</b> ${details}</div>` : ''}`
            });
            
            if (error.response?.data?.errors) {
                form.setErrors(error.response.data.errors);
            }
        });
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-IN');
};

const formatAmount = (amount) => {
    return parseFloat(amount || 0).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};
</script>

<style scoped>
.font-weight-bold { font-weight: 600; }
.card { border-radius: 12px; overflow: hidden; }
.form-control:focus { box-shadow: none; border-color: #007bff; }
</style>
