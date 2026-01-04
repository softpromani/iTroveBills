<template>
    <Head title="Proforma Invoice" />
    <div class="no-print p-3 bg-light border-bottom sticky-top d-flex align-items-center justify-content-center">
        <button @click="printInvoice" class="btn btn-dark mx-2">
            <i class="fa fa-print mr-2"></i> Print Invoice
        </button>
        <button @click="exportToPDF" class="btn btn-outline-dark mx-2">
            <i class="fa fa-download mr-2"></i> Download PDF
        </button>
        <Link :href="route('performa.invoice.list')" class="btn btn-outline-secondary mx-2">
            <i class="fa fa-arrow-left mr-2"></i> Back to List
        </Link>
    </div>

    <div class="invoice-wrapper py-4 px-2">
        <div class="invoice-container shadow-sm mx-auto bg-white border-2 border-dark">
            <!-- Header Title -->
            <div class="text-center font-weight-bold border-bottom border-dark py-2 bg-light invoice-title">
                PROFORMA INVOICE
            </div>

            <!-- Top Section: Seller and Metadata -->
            <div class="row no-gutters border-bottom border-dark">
                <!-- Seller Details (Left) -->
                <div class="col-6 border-right border-dark p-3 min-vh-15">
                    <div class="d-flex align-items-start mb-2">
                        <div v-if="props.invoice.company.logo" class="logo-container mr-3">
                            <img :src="`/${props.invoice.company.logo}`" class="seller-logo" alt="Logo" />
                        </div>
                        <div>
                            <h5 class="company-name mb-1 font-weight-bold">{{ props.invoice.company.company_name }}</h5>
                            <div class="address-box details-text">
                                {{ props.invoice.company.address }}<br>
                                GSTIN/UIN: <strong>{{ props.invoice.company.gstin }}</strong><br>
                                State Name: {{ getStateName(props.invoice.company.gstin) }}, Code: {{ props.invoice.company.gstin?.substring(0, 2) }}<br>
                                E-Mail: {{ props.invoice.company.email }}<br>
                                Phone: {{ props.invoice.company.mobile }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Metadata (Right) -->
                <div class="col-6 no-gutters">
                    <div class="row no-gutters border-bottom border-dark h-50">
                        <div class="col-6 border-right border-dark p-2">
                            <label class="meta-label">Invoice No.</label>
                            <div class="meta-value font-weight-bold">{{ props.invoice.invoice_number }}</div>
                        </div>
                        <div class="col-6 p-2">
                            <label class="meta-label">Dated</label>
                            <div class="meta-value font-weight-bold">{{ formatDate(props.invoice.invoice_date) }}</div>
                        </div>
                    </div>
                    <div class="row no-gutters h-50">
                        <div class="col-6 border-right border-dark p-2">
                            <label class="meta-label">LUT</label>
                            <div class="meta-value">{{ props.invoice.lut?.lut_no || "" }}</div>
                        </div>
                        <div class="col-6 p-2">
                            <label class="meta-label">LUT Date</label>
                            <div class="meta-value">{{ props.invoice.lut?.formatted_expiry_date || "" }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Section: Buyer / Consignee / Dispatch -->
            <div class="row no-gutters border-bottom border-dark">
                <!-- Consignee / Buyer Details (Left) -->
                <div class="col-6 border-right border-dark">
                    <div class="p-2 border-bottom border-dark min-vh-12 border-print">
                        <label class="meta-label">Consignee (Ship to)</label>
                        <div class="font-weight-bold details-title">{{ props.invoice.customer.company_name }}</div>
                        <div class="address-box details-text">
                            {{ props.invoice.customer.address }}<br>
                            ID/TPIN/TIN NO: <strong>{{ props.invoice.customer.gstin ?? "No ID/TPIN/TIN NO" }}</strong><br>
                            Phone: <strong>{{ props.invoice.customer.mobile }}</strong><br>
                            State Name: {{ getStateName(props.invoice.customer.gstin) }}, Code: {{ props.invoice.customer.gstin?.substring(0, 2) }}
                        </div>
                    </div>
                    <div class="p-2 min-vh-12">
                        <label class="meta-label">Buyer (Bill to)</label>
                        <div class="font-weight-bold details-title">{{ props.invoice.customer.company_name }}</div>
                        <div class="address-box details-text">
                            {{ props.invoice.customer.address }}<br>
                            ID/TPIN/TIN NO: <strong>{{ props.invoice.customer.gstin ?? "No ID/TPIN/TIN NO" }}</strong><br>
                            Phone: <strong>{{ props.invoice.customer.mobile }}</strong><br>
                            State Name: {{ getStateName(props.invoice.customer.gstin) }}, Code: {{ props.invoice.customer.gstin?.substring(0, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Dispatch Details (Right) -->
                <div class="col-6 no-gutters h-100">
                    <div class="row no-gutters border-bottom border-dark h-25">
                        <div class="col-6 border-right border-dark p-2">
                            <label class="meta-label">IEC Code</label>
                            <div class="meta-value">{{ props.invoice.company.iec }}</div>
                        </div>
                        <div class="col-6 p-2">
                            <label class="meta-label">AD Code</label>
                            <div class="meta-value">{{ props.invoice.company.ad_code }}</div>
                        </div>
                    </div>
                    <div class="row no-gutters border-bottom border-dark h-25">
                        <div class="col-6 border-right border-dark p-2">
                            <label class="meta-label">Total Weight</label>
                            <div class="meta-value">{{ props.invoice.total_weight ?? "" }}</div>
                        </div>
                        <div class="col-6 p-2">
                            <label class="meta-label">No. OF PACKAGES</label>
                            <div class="meta-value">{{ props.invoice.no_packets ?? "No Packs" }}</div>
                        </div>
                    </div>
                    <div class="row no-gutters border-dark h-25">
                        <div class="col-6 border-right border-dark p-2">
                            <label class="meta-label">Dispatched through</label>
                            <div class="meta-value">Yes</div>
                        </div>
                        <div class="col-6 p-2">
                            <label class="meta-label">Vehicle No.</label>
                            <div class="meta-value">{{ props.invoice.vehicle_no }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Table -->
            <div class="item-table-container">
                <table class="table border-0 mb-0 item-table">
                    <thead>
                        <tr class="bg-light text-center small font-weight-bold row-border-bottom">
                            <th width="40" class="border-right border-dark">Sl No.</th>
                            <th class="border-right border-dark text-left">Description of Goods</th>
                            <th width="90" class="border-right border-dark">HSN/SAC</th>
                            <th width="70" class="border-right border-dark">Quantity</th>
                            <th width="90" class="border-right border-dark">Rate</th>
                            <th width="60" class="border-right border-dark">Unit</th>
                            <th width="80" class="border-right border-dark">Weight</th>
                            <th width="110">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.invoice.invoiceitems" :key="item.id" class="item-row">
                            <td class="text-center border-right border-dark">{{ index + 1 }}</td>
                            <td class="border-right border-dark">
                                <strong>{{ item.desc_product }}</strong>
                            </td>
                            <td class="text-center border-right border-dark">{{ item.hsn_code }}</td>
                            <td class="text-right border-right border-dark">
                                <strong>{{ item.quantity }}</strong> {{ item.unit || 'PCS' }}
                            </td>
                            <td class="text-right border-right border-dark">{{ formatCurrency(item.rate) }}</td>
                            <td class="text-center border-right border-dark">{{ item.unit || 'PCS' }}</td>
                            <td class="text-center border-right border-dark">{{ item.weight }}</td>
                            <td class="text-right font-weight-bold">
                                {{ formatCurrency(item.rate * item.quantity) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="row-border-top">
                        <tr class="font-weight-bold total-bold">
                            <td class="text-right border-right border-dark" colspan="3">Total</td>
                            <td class="text-right border-right border-dark">{{ calculateTotalQty() }}</td>
                            <td class="border-right border-dark"></td>
                            <td class="border-right border-dark"></td>
                            <td class="text-right border-right border-dark">{{ props.invoice.total_weight ?? "" }}</td>
                            <td class="text-right">₹ {{ formatAmount(props.invoice.total_ammount) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Amount in Words Section -->
            <div class="border-top border-dark p-2 text-uppercase">
                <div class="row no-gutters">
                    <div class="col-8">
                        <div class="x-small font-weight-bold">Amount Chargeable (in words):</div>
                        <div class="font-weight-bold amount-words mb-0">INR {{ amountInWords }} Only</div>
                    </div>
                    <div class="col-4 text-right small italic font-weight-bold">
                        E. & O.E
                    </div>
                </div>
            </div>


            <!-- Declaration and Signature -->
            <div class="row no-gutters border-top border-dark">
                <!-- Declaration (Left) -->
                <div class="col-7 border-right border-dark p-3 x-small">
                    <div class="row no-gutters mb-3">
                         <div class="col-12 font-weight-bold mb-1 border-bottom border-dark border-dotted pb-1">Company's Bank Details</div>
                            <div class="col-4">Bank Name</div><div class="col-8">: <strong>{{ props.invoice.company.bank_name || '-' }}</strong></div>
                            <div class="col-4">A/c No.</div><div class="col-8">: <strong>{{ props.invoice.company.bank_account_no || '-' }}</strong></div>
                            <div class="col-4">Branch & IFSC Code</div><div class="col-8">: {{ props.invoice.company.branch_name || 'Main' }} & <strong>{{ props.invoice.company.bank_ifsc || '-' }}</strong></div>
                    </div>
                    <div class="mb-1 text-danger">Note: We will deliver the specific items in <strong>{{ props.invoice.delivery_in_days || "..." }}</strong> days.</div>
                    <div class="mb-1"><u>Declaration:</u></div>
                    <div class="text-justify line-height-tight text-muted">
                        We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
                        Interest @ 18% p.a. will be charged if payment is not made within the due date.
                        Subject to {{ props.invoice.company.city || "Local" }} Jurisdiction.
                    </div>
                </div>

                <!-- Company Signature (Right) -->
                <div class="col-5 p-2 text-right d-flex flex-column justify-content-between min-vh-15">
                    <div class="small font-weight-bold text-center">for {{ props.invoice.company.company_name }}</div>
                    <div class="signature-space">
                        <img v-if="props.invoice.company.sign" :src="`/${props.invoice.company.sign}`" class="sign-img" alt="Authorized Signatory" />
                    </div>
                    <div class="small font-weight-bold text-center border-top border-dark pt-1">Authorized Signatory</div>
                </div>
            </div>
        </div>

        <div class="text-center small text-muted mt-2 no-print font-weight-bold">
            This is a Computer Generated Proforma Invoice.
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const props = defineProps({
    invoice: Object,
});

const printInvoice = () => { window.print(); };
const exportToPDF = () => { window.print(); };

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatAmount = (amount) => {
    return parseFloat(amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatCurrency = (val) => { return parseFloat(val || 0).toFixed(2); };

const calculateTotalQty = () => {
    return props.invoice.invoiceitems?.reduce((sum, item) => sum + parseFloat(item.quantity || 0), 0) || 0;
};

// HSN Summary logic for display
const getHsnSummary = () => {
    const summary = {};
    props.invoice.invoiceitems?.forEach(item => {
        const hsn = item.hsn_code || 'N/A';
        if (!summary[hsn]) {
            summary[hsn] = { hsn, taxable: 0 };
        }
        summary[hsn].taxable += (parseFloat(item.rate) * parseFloat(item.quantity));
    });
    return Object.values(summary);
};

const states = {
    "01": "Jammu & Kashmir", "02": "Himachal Pradesh", "03": "Punjab", "04": "Chandigarh", "05": "Uttarakhand",
    "06": "Haryana", "07": "Delhi", "08": "Rajasthan", "09": "Uttar Pradesh", "10": "Bihar",
    "11": "Sikkim", "12": "Arunachal Pradesh", "13": "Nagaland", "14": "Manipur", "15": "Mizoram",
    "16": "Tripura", "17": "Meghalaya", "18": "Assam", "19": "West Bengal", "20": "Jharkhand",
    "21": "Odisha", "22": "Chhattisgarh", "23": "Madhya Pradesh", "24": "Gujarat", "25": "Daman & Diu",
    "26": "Dadra & Nagar Haveli", "27": "Maharashtra", "28": "Andhra Pradesh (Old)", "29": "Karnataka", "30": "Goa",
    "31": "Lakshadweep", "32": "Kerala", "33": "Tamil Nadu", "34": "Puducherry", "35": "Andaman & Nicobar Islands",
    "36": "Telangana", "37": "Andhra Pradesh (New)", "38": "Ladakh"
};

const getStateName = (gstin) => {
    if (!gstin || gstin.length < 2) return "N/A";
    return states[gstin.substring(0, 2)] || "Other State";
};

// Words logic
const amountInWords = ref("");
const rupeeWords = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
const tensWords = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
const teensWords = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];

const convertToWordsRecursive = (amount) => {
    if (amount === 0) return "Zero";
    if (amount < 0) return "";
    if (amount < 10) return rupeeWords[amount];
    else if (amount < 20) return teensWords[amount - 10];
    else if (amount < 100) return tensWords[Math.floor(amount / 10)] + (amount % 10 !== 0 ? " " + rupeeWords[amount % 10] : "");
    else if (amount < 1000) return rupeeWords[Math.floor(amount / 100)] + " Hundred" + (amount % 100 !== 0 ? " " + convertToWordsRecursive(amount % 100) : "");
    else if (amount < 100000) return convertToWordsRecursive(Math.floor(amount / 1000)) + " Thousand" + (amount % 1000 !== 0 ? " " + convertToWordsRecursive(amount % 1000) : "");
    else if (amount < 10000000) return convertToWordsRecursive(Math.floor(amount / 100000)) + " Lakh" + (amount % 100000 !== 0 ? " " + convertToWordsRecursive(amount % 100000) : "");
    else return convertToWordsRecursive(Math.floor(amount / 10000000)) + " Crore" + (amount % 10000000 !== 0 ? " " + convertToWordsRecursive(amount % 10000000) : "");
};

const getWords = (total) => {
    const amountVal = parseFloat(total || 0);
    if (amountVal === 0) return "Zero Rupees";
    const amountValue = Math.floor(amountVal);
    const decimalPart = Math.round((amountVal - amountValue) * 100);
    let words = amountValue > 0 ? convertToWordsRecursive(amountValue) + " Rupees" : "";
    let pWords = decimalPart > 0 ? convertToWordsRecursive(decimalPart) + " Paise" : "";
    if (words && pWords) return words + " and " + pWords;
    return words || pWords || "Zero Rupees";
};

onMounted(() => {
    amountInWords.value = getWords(props.invoice.total_ammount);
});
</script>

<style scoped>
.invoice-wrapper { background-color: #f0f2f5; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.invoice-container { max-width: 850px; color: #000; font-size: 13px; margin-bottom: 20px; }
.border-2 { border-width: 2px !important; }
.company-name { font-size: 1.5rem; text-transform: uppercase; color: #1a1a1a; line-height: 1.2; }
.amount-words { font-size: 12px; font-weight: 800; }
.total-bold { font-weight: 800 !important; }
.invoice-title { font-size: 1.8rem; letter-spacing: 2px; font-weight: 900 !important; }
.details-title { font-size: 1.1rem; margin-top: 2px; }
.details-text { font-size: 14px; line-height: 1.4; }
.logo-container { width: 70px; flex-shrink: 0; display: flex; align-items: start; justify-content: center; padding-left: 5px; }
.seller-logo { max-width: 100%; max-height: 70px; height: auto; object-fit: contain; }
.meta-label { font-size: 0.75rem; color: #444; text-transform: uppercase; margin-bottom: 2px; font-weight: 700; }
.meta-value { font-size: 0.95rem; }
.min-vh-12 { min-height: 110px; overflow: hidden; }
.min-vh-15 { min-height: 140px; }
.address-box { line-height: 1.4; word-break: break-word; overflow-wrap: break-word; }
.item-table { table-layout: fixed; width: 100%; border-collapse: collapse; border-bottom: 2px solid #000; }
.item-table th, .item-table td { padding: 4px 6px; font-size: 13px; line-height: 1.2; }
.item-row { height: 30px; vertical-align: top; }
.row-border-bottom { border-bottom: 2px solid #000; }
.row-border-top { border-top: 2px solid #000; }
.tax-summary-table th, .tax-summary-table td { padding: 2px 5px; font-size: 11px; }
.x-small { font-size: 11px !important; line-height: 1.3; }
.italic { font-style: italic; }
.sign-img { max-height: 50px; width: auto; }
.signature-space { flex-grow: 1; display: flex; align-items: center; justify-content: center; }
.border-dotted { border-style: dotted !important; }

@media print {
    .no-print { display: none !important; }
    body, .invoice-wrapper { background: white !important; padding: 0 !important; }
    .invoice-container { width: 100% !important; max-width: none !important; border: 2px solid black !important; box-shadow: none !important; margin: 0 !important; font-size: 11px; }
    .border-dark { border-color: black !important; }
    .row-border-bottom, .row-border-top, .border-bottom, .border-top, .border-right, .border-left { border-color: black !important; border-style: solid !important; }
    .border-print { border-bottom: 1px solid black !important; }
}
</style>
